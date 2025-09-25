import './bootstrap';

import Alpine from 'alpinejs';

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

const carSelection = (carData, initialBrand = '', initialModel = '') => {
    const normalized = (() => {
        if (carData && Object.keys(carData).length > 0) {
            try {
                return JSON.parse(JSON.stringify(carData));
            } catch (error) {
                console.error('Neizdevās nokopēt sākotnējos auto datus:', error);
            }
        }

        return carModelsData();
    })();

    return {
        carData: normalized,
        selectedBrand: initialBrand ?? '',
        selectedModel: initialModel ?? '',
        availableBrands: [],
        availableModels: [],
        init() {
            this.normalizeSelections();
            this.$watch('selectedBrand', () => this.normalizeSelections());
            this.$watch('selectedModel', () => {
                if (typeof this.selectedModel === 'string') {
                    this.selectedModel = this.selectedModel.trim();
                }
            });
        },
        normalizeSelections() {
            if (typeof this.selectedBrand === 'string') {
                this.selectedBrand = this.selectedBrand.trim();
            }

            if (typeof this.selectedModel === 'string') {
                this.selectedModel = this.selectedModel.trim();
            }

            if (this.selectedBrand && !Object.prototype.hasOwnProperty.call(this.carData, this.selectedBrand)) {
                this.carData = {
                    ...this.carData,
                    [this.selectedBrand]: this.selectedModel ? [this.selectedModel] : [],
                };
                this.sortCarData();
            }

            if (this.selectedBrand) {
                const current = Array.isArray(this.carData[this.selectedBrand]) ? [...this.carData[this.selectedBrand]] : [];

                if (this.selectedModel && !current.includes(this.selectedModel)) {
                    current.push(this.selectedModel);
                }

                current.sort((a, b) => a.localeCompare(b, 'lv', { sensitivity: 'base', numeric: true }));

                this.carData = {
                    ...this.carData,
                    [this.selectedBrand]: current,
                };

                this.availableModels = [...current];
                if (!current.includes(this.selectedModel)) {
                    this.selectedModel = '';
                }
            } else {
                this.selectedModel = '';
                this.availableModels = [];
            }

            this.availableBrands = Object.keys(this.carData);
        },
        sortCarData() {
            const sorted = Object.entries(this.carData)
                .sort((a, b) => a[0].localeCompare(b[0], 'lv', { sensitivity: 'base', numeric: true }));

            this.carData = Object.fromEntries(sorted);
        },
    };
};

const listingForm = (carData, initialBrand = '', initialModel = '') => ({
    ...imageUpload(),
    ...carSelection(carData, initialBrand, initialModel),
});

const listingsPage = (carData, initialBrand = '', initialModel = '') => ({
    compare: [],
    ...carSelection(carData, initialBrand, initialModel),
});

window.__carModels = window.__carModels ?? {};

Object.assign(window, {
    parseCarModelDataset,
    carModelsData,
    imageUpload,
    carSelection,
    listingForm,
    listingsPage,
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
