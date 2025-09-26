import './bootstrap';

import Alpine from 'alpinejs';

const ensureArray = (value) => (Array.isArray(value) ? value : []);

const normalizeString = (value) => (typeof value === 'string' ? value.trim() : '');

const sortLatvianStrings = (a, b) => a.localeCompare(b, 'lv', { sensitivity: 'base', numeric: true });

const uniqueSortedStrings = (values) => {
    const unique = new Map();

    ensureArray(values).forEach((item) => {
        const normalized = normalizeString(item);

        if (normalized === '') {
            return;
        }

        const key = normalized.toLocaleLowerCase('lv');

        if (!unique.has(key)) {
            unique.set(key, normalized);
        }
    });

    return Array.from(unique.values()).sort(sortLatvianStrings);
};

const sanitizeModelList = (models, ...additional) => {
    const extras = additional.flatMap((item) => (Array.isArray(item) ? item : [item]));

    return uniqueSortedStrings([
        ...ensureArray(models),
        ...extras,
    ]);
};

const toNumber = (value, fallback = 0) => {
    const numeric = Number(value);

    return Number.isFinite(numeric) ? numeric : fallback;
};

const createCurrencyFormatter = (locale = 'lv-LV') => {
    try {
        return new Intl.NumberFormat(locale || 'lv-LV', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 2,
        });
    } catch (error) {
        console.warn('Neizdevās izveidot valūtas formātētāju:', error);

        return new Intl.NumberFormat('lv-LV', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 2,
        });
    }
};

const normalizeBidHistory = (items) => ensureArray(items).map((item) => {
    const amount = toNumber(item?.amount, 0);
    const userName = typeof item?.user === 'string' && item.user.trim() !== ''
        ? item.user.trim()
        : 'Anonīms solītājs';

    return {
        id: item?.id ?? `${userName}-${item?.created_at ?? amount}`,
        amount,
        user: userName,
        created_at: item?.created_at ?? null,
        created_at_human: item?.created_at_human ?? '',
    };
});

const createDataTransfer = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    if (typeof window.DataTransfer === 'function') {
        try {
            return new window.DataTransfer();
        } catch (error) {
            console.warn('DataTransfer nav pieejams:', error);
        }
    }

    if (typeof window.ClipboardEvent === 'function') {
        try {
            const clipboardEvent = new window.ClipboardEvent('copy');

            if (clipboardEvent.clipboardData) {
                return clipboardEvent.clipboardData;
            }
        } catch (error) {
            console.warn('ClipboardData nav pieejams:', error);
        }
    }

    return null;
};

const parseCarModelDataset = () => {
    if (typeof document === 'undefined') {
        return {};
    }

    const dataElement = document.getElementById('car-models-data');

    if (!dataElement) {
        return {};
    }

    try {
        const parsed = JSON.parse(dataElement.textContent || '{}') || {};

        return parsed && typeof parsed === 'object' ? parsed : {};
    } catch (error) {
        console.error('Neizdevās nolasīt auto marku datus:', error);

        return {};
    }
};

const carModelsData = () => {
    if (!window.__carModels || Object.keys(window.__carModels).length === 0) {
        window.__carModels = parseCarModelDataset();
    }

    try {
        return JSON.parse(JSON.stringify(window.__carModels || {}));
    } catch (error) {
        console.error('Neizdevās nokopēt auto marku datus:', error);

        return {};
    }
};

const imageUpload = () => ({
    files: [],
    dragover: false,
    fileBuffer: createDataTransfer(),
    handleDrop(event) {
        const droppedFiles = Array.from(event.dataTransfer.files);
        this.addFiles(droppedFiles);
        this.dragover = false;
    },
    handleFiles(event) {
        const selectedFiles = Array.from(event.target.files);
        this.addFiles(selectedFiles);

        if (this.fileBuffer) {
            event.target.value = '';
        }
    },
    addFiles(newFiles) {
        newFiles.forEach(file => {
            if (!file.type.startsWith('image/')) {
                return;
            }

            const exists = this.files.some(({ file: existing }) => existing.name === file.name && existing.size === file.size);

            if (exists) {
                return;
            }

            const preview = {
                file,
                url: URL.createObjectURL(file),
            };

            this.files.push(preview);

            if (!this.fileBuffer) {
                this.fileBuffer = createDataTransfer();
            }

            if (this.fileBuffer?.items?.add) {
                this.fileBuffer.items.add(file);
            }
        });

        this.updateFileInput();
    },
    remove(index) {
        const [removed] = this.files.splice(index, 1);

        if (removed) {
            URL.revokeObjectURL(removed.url);
        }

        if (!this.fileBuffer) {
            if (this.$refs?.fileInput) {
                this.$refs.fileInput.value = '';
            }

            return;
        }

        const dataTransfer = createDataTransfer();

        if (dataTransfer?.items?.add) {
            this.files.forEach(({ file }) => dataTransfer.items.add(file));

            this.fileBuffer = dataTransfer;
        } else {
            this.fileBuffer = null;
        }

        this.updateFileInput();
    },
    updateFileInput() {
        if (this.$refs?.fileInput && this.fileBuffer?.files) {
            this.$refs.fileInput.files = this.fileBuffer.files;
        }
    },
});

const carSelection = (carData, initialBrand = '', initialModel = '', initialSearch = '') => {
    const initialData = (() => {
        if (carData && Object.keys(carData).length > 0) {
            try {
                return JSON.parse(JSON.stringify(carData));
            } catch (error) {
                console.error('Neizdevās nokopēt sākotnējos auto datus:', error);
            }
        }

        return carModelsData();
    })();

    const normalizedData = Object.entries(initialData || {}).reduce((accumulator, [brand, models]) => {
        const normalizedBrand = normalizeString(brand);

        if (normalizedBrand === '') {
            return accumulator;
        }

        accumulator[normalizedBrand] = sanitizeModelList(models);

        return accumulator;
    }, {});

    return {
        carData: normalizedData,
        selectedBrand: normalizeString(initialBrand),
        selectedModel: normalizeString(initialModel),
        searchQuery: typeof initialSearch === 'string' ? initialSearch : '',
        availableBrands: [],
        availableModels: [],
        searchOptions: [],
        isInitialized: false,
        init() {
            if (this.isInitialized) {
                return;
            }

            this.isInitialized = true;

            this.availableBrands = uniqueSortedStrings(Object.keys(this.carData));
            this.syncBrandData(this.selectedBrand);

            this.$watch('selectedBrand', (value) => {
                const normalizedBrand = normalizeString(value);

                if (normalizedBrand !== value) {
                    this.selectedBrand = normalizedBrand;

                    return;
                }

                this.syncBrandData(normalizedBrand);
            });

            this.$watch('selectedModel', (value) => {
                const normalizedModel = normalizeString(value);

                if (normalizedModel !== value) {
                    this.selectedModel = normalizedModel;

                    return;
                }

                if (!this.selectedBrand) {
                    return;
                }

                const models = sanitizeModelList(this.carData[this.selectedBrand], normalizedModel);

                this.carData = {
                    ...this.carData,
                    [this.selectedBrand]: models,
                };

                this.availableModels = [...models];

                this.updateSearchOptions();
            });
        },
        syncBrandData(brand) {
            const normalizedBrand = normalizeString(brand);

            if (normalizedBrand === '') {
                this.availableModels = [];
                this.selectedModel = '';
                this.availableBrands = uniqueSortedStrings(Object.keys(this.carData));
                this.updateSearchOptions();

                return;
            }

            const models = sanitizeModelList(this.carData[normalizedBrand] ?? [], this.selectedModel);

            this.carData = {
                ...this.carData,
                [normalizedBrand]: models,
            };

            this.availableModels = [...models];

            if (this.selectedModel && !this.availableModels.includes(this.selectedModel)) {
                this.selectedModel = '';
            }

            this.availableBrands = uniqueSortedStrings(Object.keys(this.carData));
            this.updateSearchOptions();
        },
        updateSearchOptions() {
            const combined = [];

            Object.entries(this.carData).forEach(([brand, models]) => {
                const normalizedBrand = normalizeString(brand);

                if (normalizedBrand !== '') {
                    combined.push(normalizedBrand);
                }

                ensureArray(models).forEach((model) => {
                    const normalizedModel = normalizeString(model);

                    if (normalizedModel === '') {
                        return;
                    }

                    combined.push(normalizedModel);

                    if (normalizedBrand !== '') {
                        combined.push(`${normalizedBrand} ${normalizedModel}`);
                    }
                });
            });

            this.searchOptions = uniqueSortedStrings(combined);
        },
    };
};

const listingForm = (carData, initialBrand = '', initialModel = '') => ({
    ...imageUpload(),
    ...carSelection(carData, initialBrand, initialModel),
});

const listingsPage = (carData, initialBrand = '', initialModel = '', initialSearch = '') => ({
    compare: [],
    ...carSelection(carData, initialBrand, initialModel, initialSearch),
});

const liveBid = (config = {}) => {
    const locale = typeof config.locale === 'string' && config.locale.trim() !== ''
        ? config.locale
        : 'lv-LV';

    return {
        pollUrl: config.pollUrl ?? null,
        storeUrl: config.storeUrl ?? null,
        minIncrement: toNumber(config.minIncrement, 100),
        currentBid: toNumber(config.currentBid, 0),
        nextBidAmount: toNumber(config.nextBidAmount, 0),
        amount: toNumber(config.nextBidAmount, 0),
        bids: normalizeBidHistory(config.bids),
        loading: false,
        error: '',
        success: '',
        formatter: createCurrencyFormatter(locale),
        pollingInterval: null,
        init() {
            this.amount = this.normalizeAmount(this.amount || this.nextBidAmount);
            this.startPolling();

            if (typeof this.$watch === 'function') {
                this.$watch('nextBidAmount', () => {
                    this.amount = this.normalizeAmount(this.amount);
                });
            }
        },
        normalizeAmount(value) {
            const step = this.minIncrement > 0 ? this.minIncrement : 100;
            const base = this.currentBid;
            const minimum = Math.max(this.nextBidAmount || 0, base + step);
            const numeric = toNumber(value, minimum);

            if (numeric <= base) {
                return minimum;
            }

            const steps = Math.round((numeric - base) / step);
            const candidate = base + steps * step;

            return candidate < minimum ? minimum : candidate;
        },
        increase() {
            this.amount = this.normalizeAmount(this.amount + this.minIncrement);
        },
        decrease() {
            const minimum = Math.max(this.nextBidAmount, this.currentBid + this.minIncrement);
            const candidate = this.amount - this.minIncrement;

            this.amount = candidate < minimum ? minimum : this.normalizeAmount(candidate);
        },
        format(value) {
            return this.formatter.format(toNumber(value, 0));
        },
        applyPayload(payload) {
            if (!payload || typeof payload !== 'object') {
                return;
            }

            this.minIncrement = toNumber(payload.minIncrement, this.minIncrement);
            this.currentBid = toNumber(payload.currentBid, this.currentBid);

            const next = toNumber(payload.nextBidAmount, this.currentBid + this.minIncrement);
            this.nextBidAmount = next > this.currentBid ? next : this.currentBid + this.minIncrement;

            this.bids = normalizeBidHistory(payload.bids);
            this.amount = this.normalizeAmount(Math.max(this.amount, this.nextBidAmount));
        },
        async fetchState() {
            if (!this.pollUrl) {
                return;
            }

            try {
                const response = await fetch(this.pollUrl, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`Status ${response.status}`);
                }

                const payload = await response.json();
                this.applyPayload(payload);
            } catch (error) {
                console.warn('Neizdevās atjaunināt izsoles datus:', error);
            }
        },
        async placeBid(event) {
            if (event?.preventDefault) {
                event.preventDefault();
            }

            if (!this.storeUrl) {
                return;
            }

            this.loading = true;
            this.error = '';
            this.success = '';

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

                const response = await fetch(this.storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({
                        amount: this.amount,
                    }),
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const message = payload?.errors?.amount?.[0]
                        ?? payload?.message
                        ?? 'Neizdevās pievienot soli. Lūdzu, mēģini vēlreiz.';

                    this.error = message;
                    this.applyPayload(payload);

                    return;
                }

                this.success = payload?.message ?? 'Tavs solis ir pieņemts!';
                this.applyPayload(payload);
            } catch (error) {
                console.error('Neizdevās nosūtīt soli:', error);
                this.error = 'Neizdevās pievienot soli. Lūdzu, mēģini vēlreiz.';
            } finally {
                this.loading = false;
            }
        },
        startPolling() {
            if (!this.pollUrl) {
                return;
            }

            this.stopPolling();
            this.fetchState();

            this.pollingInterval = window.setInterval(() => {
                this.fetchState();
            }, 5000);
        },
        stopPolling() {
            if (this.pollingInterval) {
                window.clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        },
    };
};

window.__carModels = window.__carModels ?? {};

Object.assign(window, {
    parseCarModelDataset,
    carModelsData,
    imageUpload,
    carSelection,
    listingForm,
    listingsPage,
    liveBid,
});

const storedTheme = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

if (storedTheme === 'dark' || (!storedTheme && prefersDark.matches)) {
    document.documentElement.classList.add('dark');
}

const handlePrefersChange = (event) => {
    if (!localStorage.getItem('theme')) {
        document.documentElement.classList.toggle('dark', event.matches);
        if (window.Alpine) {
            Alpine.store('theme').isDark = event.matches;
        }
    }
};

if (typeof prefersDark.addEventListener === 'function') {
    prefersDark.addEventListener('change', handlePrefersChange);
} else if (typeof prefersDark.addListener === 'function') {
    prefersDark.addListener(handlePrefersChange);
}

document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        isDark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.isDark = !this.isDark;
            this.persist();
            this.apply();
        },
        apply() {
            document.documentElement.classList.toggle('dark', this.isDark);
        },
        persist() {
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
        init() {
            this.apply();
        },
    });

    Alpine.store('theme').init();
});

window.Alpine = Alpine;

Alpine.start();
