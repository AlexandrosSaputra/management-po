<x-layout>
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Buat Data Pre Order/Penawaran</x-page-title>
        </div>

        <div>
            <form id="uploadForm" method="POST" action="/preorder" enctype="multipart/form-data"
                onsubmit="return buatPreorderForm(event)">
                @csrf
                @method('POST')

                <input type="number" value="{{ Auth::user()->id }}" name="user_id" id="user_id" class="hidden" />

                <div class="rounded-lg shadow-md p-4">
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-2">
                            <div class="flex-1">
                                <label for="suplier_id"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier</label>
                                <select id="suplier_id" name="suplier_id" style="width: 100%;" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    <option value=""></option>
                                    @foreach ($supliers as $suplier)
                                        <option value={{ $suplier->id }}>
                                            {{ $suplier->nama . ' - ' . $suplier->telepon }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex-1">
                                <label for="jenis_id"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis</label>
                                <select id="jenis_id" name="jenis_id" style="width: 100%;" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    <option value=""></option>
                                    @foreach ($jenises as $jenis)
                                        <option value={{ $jenis->id }}>{{ $jenis->nama . ' - ' . $jenis->kode }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="rounded-lg shadow-md p-4">
                    <div class="flex flex-col w-full gap-2 my-2 sm:col-span-3" id="input-area">
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item 1</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            <div>
                                <label for="item"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Item</label>
                                <select name="item_id[]" style="width: 100%;"
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="satuan_id"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan</label>
                                <select name="satuan_id[]" style="width: 100%;"
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    @foreach ($satuans as $satuan)
                                        <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full">
                                <label for="gambar-1" class="block text-xs font-medium leading-6 text-[#099AA7]">Gambar
                                    <span class="text-xs text-black/40 italic">*opsional</span></label>
                                <input
                                    class="upload block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    id="gambar-1" type="file" accept="image/*" name="gambar-1"
                                    onchange="imageInput(event, 'preview-1', 'image-link-1')">
                            </div>

                            <div class="flex w-full items-end gap-2">
                                <img id="preview-1" src="path/to/your/image.jpg" alt="Preview Gambar"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                <a id="image-link-1" href="#" target="_blank"
                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                            </div>

                        </div>
                    </div>

                    <div class="flex w-full gap-2 justify-end">
                        <button type="button" id="delete-input" hidden
                            class="delete-input rounded-md bg-red-500 p-2 text-xs font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                            </svg>
                        </button>

                        <button type="button" id="add-input"
                            class="rounded-md bg-yellow-600 p-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>

                        </button>
                    </div>

                    <div class="mt-10 flex items-center justify-between gap-2">
                        <a href="/preorder"
                            class="min-w-[120px] text-center rounded-md bg-blue-600 p-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>
                        <button type="submit"
                            class="min-w-[120px] text-center rounded-md bg-green-600 p-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function buatPreorderForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Buat data penawaran pre order?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>

    <script>
        $(document).ready(function() {
            let inputCount = 1; // Mulai dari input pertama

            // meghapus input
            $(document).on('click', `.delete-input`, function() {

                inputCount--;
                // $(this).closest('#input-group').remove();
                $('#input-area').children().last().remove();

                if (inputCount <= 1) {
                    $(`#delete-input`).hide();
                }

            });

            $('.select2').select2({
                placeholder: "Select an item"
            });

            // Ketika tombol "Tambah Input" diklik
            $('#add-input').click(function() {
                // hide previous delete button
                $(`#delete-input`).show();

                inputCount++; // Tambah jumlah input

                // Buat input baru
                let newInput = `
                 <div class="flex flex-col w-full gap-y-4 my-2 sm:col-span-3" id="input-group">
                    <div class="w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item ${inputCount}</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                        <div>
                            <label for="item"
                                class="block text-xs font-medium leading-6 text-[#099AA7]">Item</label>
                            <select name="item_id[]" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="satuan_id"
                                class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan</label>
                            <select name="satuan_id[]" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full">
                            <label for="gambar-${inputCount}" class="block text-xs font-medium leading-6 text-[#099AA7]">Gambar
                                <span class="text-xs text-black/40 italic">*opsional</span></label>
                            <input
                                class="upload block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                id="gambar-${inputCount}" type="file" accept="image/*" name="gambar-${inputCount}"
                                onchange="imageInput(event, 'preview-${inputCount}', 'image-link-${inputCount}')">
                        </div>

                        <div class="flex w-full items-end gap-2">
                            <img id="preview-${inputCount}" src="path/to/your/image.jpg" alt="Preview Gambar"
                                class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                            <a id="image-link-${inputCount}" href="#" target="_blank"
                                class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                        </div>

                    </div>
                </div>
                `;

                // Tambahkan input baru ke dalam area input
                $('#input-area').append(newInput);

                $('.select2').select2({
                    placeholder: "Select an item"
                });
            });
        });
    </script>

    <script>
        function imageInput(event, previewId, linkId) {
            const file = event.target.files[0];
            if (file) {
                var output = document.getElementById(previewId);
                var link = document.getElementById(linkId);
                // Membuat URL gambar yang diunggah
                const imageUrl = URL.createObjectURL(file);

                // Menampilkan gambar di halaman
                output.src = imageUrl;
                output.style.display = 'block';

                // Mengatur link untuk membuka gambar
                link.href = imageUrl;
                link.textContent = file.name;
                link.style.display = 'block';

                // compress gambar
                compressImage(event, previewId);
            }
        }

        function compressImage(event, itemId) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = (e) => {
                const img = new Image();
                img.src = e.target.result;

                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Resize settings
                    const MAX_WIDTH = 800;
                    const MAX_HEIGHT = 800;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    // Set canvas dimensions and compress image
                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);

                    // Compress to 70% quality
                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: "image/jpeg",
                            lastModified: Date.now()
                        });

                        // Update the file input with compressed file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        event.target.files = dataTransfer.files;

                        // Display compressed image preview
                        const imgPreview = document.getElementById(`compressedImage_${itemId}`);
                        imgPreview.src = URL.createObjectURL(compressedFile);
                        imgPreview.style.display = "block";
                    }, 'image/jpeg', 0.7);
                };
            };
        }
    </script>
</x-layout>
