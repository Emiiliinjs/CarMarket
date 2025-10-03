<script id="car-models-data" type="application/json">
    {!! json_encode($carModels, JSON_UNESCAPED_UNICODE) !!}
</script>

<script>
    document.addEventListener('alpine:init', () => {

        /**
         * Filtru panelis (index.blade.php)
         */
        Alpine.data('listingsPage', (carModels, initialBrand = '', initialModel = '', initialSearch = '') => ({
            carModels: carModels,
            availableBrands: [],
            selectedBrand: (initialBrand || '').trim(),
            selectedModel: (initialModel || '').trim(),
            searchQuery: initialSearch || '',
            searchOptions: [],

            init() {
                // Ielādē visas markas (tikai no DB esošajiem sludinājumiem)
                this.availableBrands = Object.keys(this.carModels).map(b => b.trim()).sort();

                // Ja jau izvēlēta marka, ielādē atbilstošos modeļus
                if (this.selectedBrand) {
                    this.updateModels();
                }

                // Izveido meklēšanas opciju sarakstu
                this.updateSearchOptions();
            },

            get availableModels() {
                if (this.selectedBrand && this.carModels[this.selectedBrand]) {
                    return this.carModels[this.selectedBrand].map(m => m.trim()).sort();
                }
                return [];
            },

            updateModels() {
                if (!this.carModels[this.selectedBrand]) {
                    this.selectedModel = '';
                } else if (!this.availableModels.includes(this.selectedModel)) {
                    this.selectedModel = '';
                }
            },

            updateSearchOptions() {
                let options = [];
                for (const [brand, models] of Object.entries(this.carModels)) {
                    options.push(brand);
                    models.forEach(m => options.push(`${brand} ${m}`));
                }
                this.searchOptions = options;
            }
        }));


        /**
         * Sludinājuma forma (create/edit.blade.php)
         */
        Alpine.data('listingForm', (initialBrand = '', initialModel = '') => ({
            carData: {},
            availableBrands: [],
            availableModels: [],
            selectedBrand: (initialBrand || '').trim(),
            selectedModel: (initialModel || '').trim(),
            files: [],
            dragover: false,

            init() {
                try {
                    const raw = document.getElementById('car-models-data').textContent;
                    this.carData = JSON.parse(raw);

                    this.availableBrands = Object.keys(this.carData).map(b => b.trim()).sort();

                    if (this.selectedBrand) {
                        this.updateModels();
                    }
                } catch (e) {
                    console.error('Nevar nolasīt carData:', e);
                }
            },

            updateModels() {
                const brand = (this.selectedBrand || '').trim().toLowerCase();

                // Atrodam DB esošo marku
                const foundKey = Object.keys(this.carData).find(
                    key => key.trim().toLowerCase() === brand
                );

                if (foundKey) {
                    this.availableModels = this.carData[foundKey].map(m => m.trim()).sort();
                } else {
                    this.availableModels = [];
                }

                if (!this.availableModels.includes(this.selectedModel)) {
                    this.selectedModel = '';
                }
            },

            handleFiles(event) {
                [...event.target.files].forEach(file => {
                    this.files.push({ file, url: URL.createObjectURL(file) });
                });
            },

            handleDrop(event) {
                this.dragover = false;
                [...event.dataTransfer.files].forEach(file => {
                    this.files.push({ file, url: URL.createObjectURL(file) });
                });
            },

            remove(index) {
                this.files.splice(index, 1);
            }
        }));
    });
</script>
