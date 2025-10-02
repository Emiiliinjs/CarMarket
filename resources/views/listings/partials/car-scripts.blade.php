<script id="car-models-data" type="application/json">
    {!! $carModels->toJson() !!}
</script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('listingForm', (initialBrand = '', initialModel = '') => ({
            carData: {},
            availableBrands: [],
            availableModels: [],
            selectedBrand: initialBrand || '',
            selectedModel: initialModel || '',
            files: [],
            dragover: false,

            init() {
                try {
                    const raw = document.getElementById('car-models-data').textContent;
                    this.carData = JSON.parse(raw);

                    // pieejamās markas
                    this.availableBrands = Object.keys(this.carData)
                        .map(b => b.trim())
                        .sort();

                    // ja jau ir izvēlēta marka (piem. edit skatā)
                    if (this.selectedBrand) {
                        this.updateModels();
                    }
                } catch (e) {
                    console.error('Nevar nolasīt carData:', e);
                }
            },

            updateModels() {
                let brand = (this.selectedBrand || '').trim().toLowerCase();

                let foundKey = Object.keys(this.carData).find(
                    key => key.trim().toLowerCase() === brand
                );

                if (foundKey) {
                    this.availableModels = this.carData[foundKey].map(m => m.trim());
                } else {
                    this.availableModels = [];
                }

                // ja izvēlētais modelis nepieder izvēlētajai markai → notīrīt
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
