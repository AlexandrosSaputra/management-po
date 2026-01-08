<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    @session('erroruploadfile')
        <script type="text/javascript">
            toastr.error("{{ session('erroruploadfile') }}");
        </script>
    @endsession

    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    <x-modal-catatan-order-suplier :order="$order" />
    <x-modal-revisi-gudang :order="$order" />
    <x-modal-catatan-order-gudang :order="$order" />

    <div>
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Detail Order {{ $order->kode }}
                {{ $order->isKontrak ? '(Kontrak/Non PO)' : '(Pre Order)' }}</x-page-title>
        </div>

        <div>
            <div class="rounded-lg shadow-md p-4">
                <div class="w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                    <div>
                        <label for="suplier" class="block text-sm font-medium leading-6 text-[#099AA7]">Suplier</label>
                        <input disabled id="suplier" name="suplier" value={{ $order->suplier->nama }}
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>
                    <div>
                        <label for="suplier" class="block text-sm font-medium leading-6 text-[#099AA7]">Telepon</label>
                        <input type="text" id="suplierid" name="suplierid" disabled
                            value="{{ $order->suplier->telepon }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>
                    <div>
                        <label for="pemesan" class="block text-sm font-medium leading-6 text-[#099AA7]">Pemesan</label>
                        <input id="pemesan" name="pemesan" value="{{ $order->user->nama }}"
                            @disabled(true)
                            class=" block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>
                    @if ($order->pre_order_id)
                        <div>
                            <label for="penawaran_id" class="block text-sm font-medium leading-6 text-[#099AA7]">ID
                                Pre Order</label>
                            <input type="text" id="penawaran_id" name="penawaran_id" disabled
                                value="{{ $order->pre_order_id }}"
                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                        </div>
                    @else
                        <div>
                            <label for="kontrak_id" class="block text-sm font-medium leading-6 text-[#099AA7]">ID
                                Kontrak</label>
                            <input type="text" id="kontrak_id" name="kontrak_id" disabled
                                value="{{ $order->kontrak_id }}"
                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                        </div>
                    @endif

                    <div>
                        <label for="tanggal_po" class="block text-sm font-medium leading-6 text-[#099AA7]">Tanggal
                            PO</label>
                        <input type="text" id="tanggal_po" name="tanggal_po" disabled
                            value="{{ explode(' ', $order->created_at)[0] }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>
                    <div>
                        <label for="target_kirim" class="block text-sm font-medium leading-6 text-[#099AA7]">Target
                            Kirim</label>
                        <input type="date" id="target_kirim" name="target_kirim" disabled
                            value="{{ $order->target_kirim }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>

                    <div>
                        <label for="gudang_id" class="block text-sm font-medium leading-6 text-[#099AA7]">Gudang</label>
                        <input name="gudang_id" disabled
                            value="{{ $order->gudang->nama . ' - ' . $order->gudang->telepon }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6">
                    </div>

                    <div>
                        <label for="jenis_id" class="block text-sm font-medium leading-6 text-[#099AA7]">Jenis</label>
                        <input id="jenis_id" name="jenis_id" disabled value="{{ $order->jenis->nama }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6">
                    </div>

                    @if ($order->foto)
                        <div class="flex w-full items-end gap-2">
                            <img id="preview" src="{{ asset('folder-image-truenas/' . $order->foto) }}"
                                alt="Preview Gambar" class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                            <a id="image-link" href="{{ asset('folder-image-truenas/' . $order->foto) }}"
                                target="_blank"
                                class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                Bukti Suplier</a>
                        </div>
                    @endif

                    @if ($order->invoice_suplier)
                        <div class="flex w-full items-end gap-2">
                            <a href="{{ asset('folder-image-truenas/' . $order->invoice_suplier) }}" target="_blank"
                                class="block w-full md:w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Lihat
                                Invoice Suplier</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg shadow-md p-4">
                <div class="flex flex-col w-full gap-2" id="input-area">
                    @php
                        $jumlahItem = 0;
                    @endphp
                    @foreach ($itemPenawarans as $index => $itemPenawaran)
                        <div id="itempenawaran-{{ $index + 1 }}"
                            class="mt-4 w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item 
                                {{ $loop->iteration }}</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 items-end item-group">
                            <div>
                                <label for="item"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Item</label>
                                <input id="item" name="item[]" value="{{ $itemPenawaran->item->nama }}"
                                    disabled
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                            </div>
                            <div>
                                <label for="satuan"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Satuan</label>
                                <input id="satuan" value="{{ $itemPenawaran->satuan->nama }}" disabled
                                    class=" block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6">
                            </div>


                            <div>
                                <label for="jumlah"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Jumlah</label>
                                <input type="text" id="jumlah-{{ $index }}" name="jumlah[]"
                                    placeholder="0" value="{{ str_replace('.', ',', $itemPenawaran->jumlah) }}"
                                    disabled
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                            </div>

                            <div>
                                <label for="harga"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Harga/satuan</label>
                                <input type="text" id="harga-{{ $index }}" name="harga[]"
                                    placeholder="10000" value="{{ str_replace('.', ',', $itemPenawaran->harga) }}"
                                    disabled
                                    class="format-rupiah block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                            </div>

                            <div>
                                <label for="total_harga"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Total
                                    Harga</label>
                                <input type="text" id="total-{{ $index }}" name="total_harga[]"
                                    placeholder="10000"
                                    value="{{ str_replace('.', ',', $itemPenawaran->total_harga) }}" disabled
                                    class="format-rupiah block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                            </div>

                            <div>
                                <form method="POST" action="/item-penawaran/bukti-gudang/{{ $itemPenawaran->id }}"
                                    id="bukti-gudang-form" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <input type="text" name="index_item" value="{{ $index }}" hidden />

                                    <button type="submit" hidden id="submit-gambar-{{ $index }}">
                                    </button>

                                    <label for="gambar[{{ $index }}]"
                                        class="block text-sm font-medium leading-6 text-[#099AA7]">Upload
                                        Bukti Barang <span class="text-yellow-500 text-sm italic">*harap
                                            update</span>
                                    </label>

                                    <div class="flex w-full gap-6">
                                        <div class="w-full">
                                            <input required
                                                class="upload block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                                id="gambar-{{ $index }}" type="file" accept="image/*"
                                                {{-- name="gambar[{{ $index }}]" --}} name="gambar[]" multiple
                                                onchange="imageInput(event, {{ $index }})">
                                            {{-- onchange="this.form.submit()"> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>

                            @if ($itemPenawaran->gambar_bukti_gudang)
                                <div class="flex w-full items-end gap-2">
                                    <img id="preview-1"
                                        src="{{ asset('folder-image-truenas/' . $itemPenawaran->gambar_bukti_gudang) }}"
                                        alt="Preview Gambar"
                                        class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                                    <a id="image-link-1"
                                        href="{{ asset('folder-image-truenas/' . $itemPenawaran->gambar_bukti_gudang) }}"
                                        target="_blank"
                                        class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                        Bukti Gudang</a>
                                </div>
                            @endif

                            @if (!$itemPenawaran->bukti_gudangs->isEmpty())
                                <button type="button" data-modal-target="modal-bukti-gudang-{{ $index }}"
                                    data-modal-toggle="modal-bukti-gudang-{{ $index }}"
                                    class="block w-fit text-center rounded-md bg-[#099AA7] p-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]/80">Bukti
                                    Gudang</button>

                                <x-modal-bukti-gudang :itemPenawaran="$itemPenawaran" index="{{ $index }}" />
                            @endif

                            <div class="flex
                                    w-full items-end gap-2">
                                <img id="preview[{{ $index }}]" src="path/to/your/image.jpg"
                                    alt="Preview Gambar"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                <a id="image-link[{{ $index }}]" href="#" target="_blank"
                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                            </div>

                            <div>
                                <label for="total_harga"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Kesesuaian</label>

                                <div class="flex w-full pt-2 gap-6">
                                    <div>
                                        <input id="default-radio-1-{{ $index }}" type="radio"
                                            value="sesuai" form="gudang-form" onclick="checkRadios()"
                                            name="kesesuaian_item[{{ $index }}]" checked
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                        <label for="default-radio-1-{{ $index }}"
                                            class="ms-2 text-sm font-medium text-[#099AA7]">Sesuai</label>
                                    </div>
                                    <div>
                                        <input id="default-radio-2-{{ $index }}" type="radio"
                                            value="salah" form="gudang-form" onclick="checkRadios()"
                                            name="kesesuaian_item[{{ $index }}]"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                        <label for="default-radio-2-{{ $index }}"
                                            class="ms-2 text-sm font-medium text-[#099AA7]">Tidak
                                            Sesuai</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $jumlahItem += $itemPenawaran->jumlah;
                        @endphp
                    @endforeach
                </div>

                <div>
                    <p class="flex items-center mt-2 text-lg font-bold">Total: <input
                            value="{{ number_format($order->total_biaya, 2, ',', '.') }}" id="total-keseluruhan"
                            type="text" name="total_biaya" class=" w-full bg-white border border-transparent"
                            readonly>
                    </p>

                    <p class="text-center font-bold text-lg text-yellow-500">
                        {{ $order->status == 'revisiditolak' ? 'Revisi ditolak dan diajukan ke pemesan' : '' }}
                    </p>
                </div>

                <div
                    class="mb-4 flex flex-col justify-end mt-4 md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                    <div class="flex w-full justify-between">

                        <a href="/gudang/list-order?telepon={{ $order->gudang->telepon }}"
                            class="block min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                        <div>
                            <button type="button" id="btn-salah" data-modal-target="modal-gudang"
                                data-modal-toggle="modal-gudang"
                                class="hidden min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Terima
                                Order Tidak Sesuai</button>

                            <button form="gudang-form" id="btn-accept" value="sesuai" name="sesuai"
                                onclick="submitGudangSesuaiForm(event)" hidden
                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima
                                Order Sesuai</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="/order/{{ $order->id }}?token={{ $order->token }}" id="gudang-form"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
        </form>
    </div>

    <script>
        function checkRadios() {
            // Get all radio groups
            const items = document.querySelectorAll('[name^="kesesuaian_item"]');

            // Check if all "default" radios are selected
            let allDefault = false;
            items.forEach((item, index) => {
                if (index <= 0) {
                    allDefault = true;
                }

                if (item.checked && item.value === "sesuai") {
                    if (allDefault !== false) {
                        allDefault = true;
                    }
                }

                if (item.checked && item.value === "salah") {
                    allDefault = false;
                }

                if (!item.checked && item.value === "sesuai") {
                    allDefault = false;
                }
            });

            // Show "Submit" button if all are default, otherwise show "Denied" button
            document.getElementById('btn-accept').style.display = allDefault ? 'inline-block' : 'none';
            document.getElementById('btn-salah').style.display = allDefault ? 'none' : 'inline-block';
        }

        // Initial check on page load
        checkRadios();
    </script>

    <script>
        function submitGudangSesuaiForm(event) {
            event.preventDefault()
            if (confirm('Terima order sesuai?')) {
                showLoadingModal();

                document.getElementById('gudang-form').submit();
            }
        }

        function submitGudangTidakSesuaiForm(event) {
            event.preventDefault()
            if (confirm('Order tidak sesuai?')) {
                showLoadingModal();

                document.getElementById('gudang-form').submit();
            }
        }

        function buktiGudangForm(event, previewId, linkId) {
            event.preventDefault()
            showLoadingModal();

            imageInput(event, previewId, linkId);

            document.getElementById('bukti-gudang-form').submit();
        }
    </script>

    <script>
        function previewPDF(input) {

            const file = input.files[0];
            const pdfPreview = document.getElementById('pdfPreview');
            const pdfPreviewLink = document.getElementById('pdfPreviewLink');

            // Check if a file is selected and it's a valid PDF file
            if (file && file.type === 'application/pdf') {
                const fileSizeMB = file.size / (1024 * 1024);

                if (fileSizeMB <= 2) {
                    const fileURL = URL.createObjectURL(file);
                    pdfPreviewLink.href = fileURL;
                    pdfPreview.style.display = 'block';
                } else {
                    alert('File size exceeds 2MB. Please select a smaller file.');
                    input.value = ''; // Clear the input
                    pdfPreview.style.display = 'none';
                }
            } else {
                alert('Please select a valid PDF file.');
                input.value = ''; // Clear the input
                pdfPreview.style.display = 'none';
            }
        }
    </script>

    <script>
        function imageInput(event, itemIndex) {
            const file = event.target.files[0];
            if (file) {
                // compress gambar
                compressImage(event, itemIndex);
            }
        }

        function compressImage(event, itemIndex) {
            const files = event.target.files;
            const dataTransfer = new DataTransfer();
            const MAX_WIDTH = 800;
            const MAX_HEIGHT = 800;

            function processFile(file, index) {
                if (!file) return;
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
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
                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);
                        canvas.toBlob((blob) => {
                            const compressedFile = new File([blob], file.name, {
                                type: "image/jpeg",
                                lastModified: Date.now()
                            });
                            dataTransfer.items.add(compressedFile);
                            if (index === files.length - 1) {
                                event.target.files = dataTransfer.files;

                                console.log(document.getElementById(`gambar-${itemIndex }`).files);
                                document.getElementById(`submit-gambar-${itemIndex}`).click();
                            } else {
                                setTimeout(() => processFile(files[index + 1], index + 1),
                                    100); // Process next file with delay
                            }
                        }, 'image/jpeg', 0.7);
                    };
                };
            }

            if (files.length > 0) {
                processFile(files[0], 0); // Start processing the first file
            }
        }
    </script>

    <script>
        // Fungsi untuk memformat angka menjadi format dengan titik
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Ambil semua elemen dengan kelas 'format-rupiah'
        const inputs = document.querySelectorAll(".format-rupiah");

        // Loop setiap elemen dan format value-nya
        inputs.forEach(input => {
            const valueAsli = input.value; // Ambil value asli
            input.value = formatRupiah(valueAsli); // Format angka
        });
    </script>
</x-layout>
