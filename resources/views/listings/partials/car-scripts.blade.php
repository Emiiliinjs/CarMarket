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
                    this.availableBrands = Object.keys(this.carData).sort();

                    // ja jau izvēlēta marka
                    if (this.selectedBrand) {
                        this.updateModels();
                    }
                } catch (e) {
                    console.error('Nevar nolasīt carData:', e);
                }
            },

            updateModels() {
                let brand = this.selectedBrand;
                let foundKey = Object.keys(this.carData).find(
                    key => key.toLowerCase() === (brand || '').toLowerCase()
                );

                if (foundKey) {
                    this.availableModels = this.carData[foundKey];
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
