<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Pievienot jaunu sludinājumu
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Auto info -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium">Marka</label>
                        <input type="text" name="marka" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Modelis</label>
                        <input type="text" name="modelis" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Gads</label>
                        <input type="number" name="gads" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nobraukums (km)</label>
                        <input type="number" name="nobraukums" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Cena (€)</label>
                        <input type="number" step="0.01" name="cena" class="w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Degviela</label>
                        <select name="degviela" class="w-full rounded border-gray-300" required>
                            <option>Benzīns</option>
                            <option>Dīzelis</option>
                            <option>Elektriska</option>
                            <option>Hibrīds</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Pārnesumkārba</label>
                        <select name="parnesumkarba" class="w-full rounded border-gray-300" required>
                            <option>Manuālā</option>
                            <option>Automātiskā</option>
                        </select>
                    </div>
                </div>

                <!-- Apraksts -->
                <div class="mt-4">
                    <label class="block text-sm font-medium">Apraksts</label>
                    <textarea name="apraksts" rows="4" class="w-full rounded border-gray-300"></textarea>
                </div>

                <!-- Drag & Drop bildes -->
                <div class="mt-4" x-data="imageUpload()">
                    <label class="block text-sm font-medium mb-2">Auto bildes</label>
                    <div 
                        @dragover.prevent="dragover=true" 
                        @dragleave.prevent="dragover=false" 
                        @drop.prevent="handleDrop($event)"
                        :class="{'border-blue-400 bg-blue-50': dragover}"
                        class="w-full border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition-colors"
                    >
                        <template x-if="files.length === 0">
                            <p class="text-gray-500">Velc šeit bildes vai klikšķini, lai izvēlētos</p>
                        </template>

                        <template x-if="files.length > 0">
                            <div class="flex flex-wrap justify-center gap-2">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="relative w-20 h-20 overflow-hidden rounded-lg shadow">
                                        <img :src="file.url" class="w-full h-full object-cover">
                                        <button type="button" @click="remove(index)" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">×</button>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <input type="file" name="images[]" multiple class="hidden" x-ref="fileInput" @change="handleFiles($event)" accept="image/*">
                    </div>
                    <button type="button" @click="$refs.fileInput.click()" class="mt-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Izvēlēties failus</button>
                    <p class="text-xs text-gray-500 mt-1">Varat izvēlēties vairākas bildes (maks. 2MB katra).</p>
                </div>

                <!-- Submit -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 text-gray-800 text-base font-semibold bg-gray-200 rounded-lg shadow hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition">
                        Ievietot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js script -->
    <script>
        function imageUpload() {
            return {
                files: [],
                dragover: false,
                handleDrop(event) {
                    const droppedFiles = Array.from(event.dataTransfer.files);
                    this.addFiles(droppedFiles);
                    this.dragover = false;
                },
                handleFiles(event) {
                    const selectedFiles = Array.from(event.target.files);
                    this.addFiles(selectedFiles);
                },
                addFiles(newFiles) {
                    newFiles.forEach(file => {
                        file.url = URL.createObjectURL(file);
                        this.files.push(file);
                    });
                },
                remove(index) {
                    this.files.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>
