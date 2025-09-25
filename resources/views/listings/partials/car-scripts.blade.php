<script>
    function imageUpload() {
        return {
            files: [],
            dragover: false,
            fileBuffer: new DataTransfer(),
            handleDrop(event) {
                const droppedFiles = Array.from(event.dataTransfer.files);
                this.addFiles(droppedFiles);
                this.dragover = false;
            },
            handleFiles(event) {
                const selectedFiles = Array.from(event.target.files);
                this.addFiles(selectedFiles);
                event.target.value = '';
            },
            addFiles(newFiles) {
                newFiles.forEach(file => {
                    if (! file.type.startsWith('image/')) {
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
                    this.fileBuffer.items.add(file);
                });

                this.updateFileInput();
            },
            remove(index) {
                const [removed] = this.files.splice(index, 1);

                if (removed) {
                    URL.revokeObjectURL(removed.url);
                }

                const dataTransfer = new DataTransfer();

                this.files.forEach(({ file }) => dataTransfer.items.add(file));

                this.fileBuffer = dataTransfer;
                this.updateFileInput();
            },
            updateFileInput() {
                if (this.$refs?.fileInput) {
                    this.$refs.fileInput.files = this.fileBuffer.files;
                }
            }
        }
    }

    function carSelection(carData, initialBrand = '', initialModel = '') {
        const normalized = JSON.parse(JSON.stringify(carData || {}));

        return {
            carData: normalized,
            selectedBrand: initialBrand ?? '',
            selectedModel: initialModel ?? '',
            init() {
                this.normalizeSelections();
                this.$watch('selectedBrand', () => this.normalizeSelections());
            },
            get availableBrands() {
                return Object.keys(this.carData);
            },
            get availableModels() {
                return this.selectedBrand && Array.isArray(this.carData[this.selectedBrand])
                    ? this.carData[this.selectedBrand]
                    : [];
            },
            normalizeSelections() {
                if (typeof this.selectedBrand === 'string') {
                    this.selectedBrand = this.selectedBrand.trim();
                }

                if (typeof this.selectedModel === 'string') {
                    this.selectedModel = this.selectedModel.trim();
                }

                if (this.selectedBrand && ! Object.prototype.hasOwnProperty.call(this.carData, this.selectedBrand)) {
                    this.carData = {
                        ...this.carData,
                        [this.selectedBrand]: this.selectedModel ? [this.selectedModel] : [],
                    };
                    this.sortCarData();
                }

                if (this.selectedBrand) {
                    const current = Array.isArray(this.carData[this.selectedBrand]) ? [...this.carData[this.selectedBrand]] : [];

                    if (this.selectedModel && ! current.includes(this.selectedModel)) {
                        current.push(this.selectedModel);
                    }

                    current.sort((a, b) => a.localeCompare(b, 'lv', { sensitivity: 'base', numeric: true }));

                    this.carData = {
                        ...this.carData,
                        [this.selectedBrand]: current,
                    };

                    if (! current.includes(this.selectedModel)) {
                        this.selectedModel = '';
                    }
                } else {
                    this.selectedModel = '';
                }
            },
            sortCarData() {
                const sorted = Object.entries(this.carData)
                    .sort((a, b) => a[0].localeCompare(b[0], 'lv', { sensitivity: 'base', numeric: true }));

                this.carData = Object.fromEntries(sorted);
            },
        };
    }

    function listingForm(carData, initialBrand = '', initialModel = '') {
        return {
            ...imageUpload(),
            ...carSelection(carData, initialBrand, initialModel),
        };
    }

    function listingsPage(carData, initialBrand = '', initialModel = '') {
        return {
            compare: [],
            showFilters: false,
            ...carSelection(carData, initialBrand, initialModel),
        };
    }
</script>
