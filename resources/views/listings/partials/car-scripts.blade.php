{{-- Ieliekam auto marku un modeļu JSON no PHP --}}
<script id="car-models-data" type="application/json">
    {!! json_encode($carModels, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

<script>
    function listingForm(carData, initialBrand = '', initialModel = '') {
        return {
            carData,
            availableBrands: Object.keys(carData),
            availableModels: [],
            selectedBrand: initialBrand || '',
            selectedModel: initialModel || '',

            init() {
                if (this.selectedBrand) {
                    this.updateModels();
                }
            },

            updateModels() {
                this.availableModels = this.carData[this.selectedBrand] || [];
                if (!this.availableModels.includes(this.selectedModel)) {
                    this.selectedModel = '';
                }
            }
        }
    }
</script>
