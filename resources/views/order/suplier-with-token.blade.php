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

    @session('waerror')
        <script type="text/javascript">
            toastr.error("{{ session('waerror') }}");
        </script>
    @endsession

    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    <x-modal-catatan-order-suplier :order="$order" />
    <x-modal-revisi-suplier :order="$order" />
    <x-modal-tolak-order-suplier :order="$order" />
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
                    @else
                        @if ($order->status == 'terkirim')
                            <div>
                                <label for="gambar" class="block text-sm font-medium leading-6 text-[#099AA7]">Foto
                                    Barang <span class="text-sm text-black/40 italic">*opsional</span></label>
                                <input form="suplier-terima-form"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    id="gambar" type="file" accept="image/*" name="foto"
                                    onchange="previewImage(event, 'preview', 'image-link')">
                            </div>

                            <div class="flex w-full items-end gap-2">
                                <img id="preview" src="path/to/your/image.jpg" alt="Preview Gambar"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                <a id="image-link" href="#" target="_blank"
                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                            </div>
                        @endif
                    @endif

                    @if ($order->invoice_suplier)
                        <div class="flex w-full items-end gap-2">
                            <img id="preview-invoice"
                                src="{{ asset('folder-image-truenas/' . $order->invoice_suplier) }}"
                                alt="Preview Invoice" class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                            <a id="image-link-invoice"
                                href="{{ asset('folder-image-truenas/' . $order->invoice_suplier) }}" target="_blank"
                                class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                Invoice Suplier</a>
                        </div>
                    @else
                        @if ($order->status == 'terkirim')
                            <div>
                                <label for="uploadInvoice"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Foto
                                    Invoice Suplier <span class="text-sm text-yellow-500 italic">*wajib
                                        isi</span></label>
                                <input form="suplier-terima-form"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    id="uploadInvoice" type="file" accept="image/*" name="uploadInvoice"
                                    onchange="previewImage(event, 'preview-invoice', 'image-link-invoice')" required>
                            </div>

                            <div class="flex w-full items-end gap-2">
                                <img id="preview-invoice" src="path/to/your/image.jpg" alt="Preview Gambar Invoice"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                <a id="image-link-invoice" href="#" target="_blank"
                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                            </div>
                        @endif

                    @endif

                    @if ($order->catatan_gudang)
                        <div class="flex w-full items-end gap-2">
                            <!-- Modal toggle -->
                            <button data-modal-target="modal-gudang" data-modal-toggle="modal-gudang"
                                class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                type="button">
                                Catatan Ketidaksesuaian
                            </button>
                        </div>
                    @endif

                    @if ($order->catatan_suplier)
                        <div class="flex w-full items-end gap-2">
                            <!-- Modal toggle -->
                            <button data-modal-target="modal-suplier" data-modal-toggle="modal-suplier"
                                class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                type="button">
                                Catatan Penolakan Suplier
                            </button>
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
                        <div class="mt-4 w-full flex justify-center items-center gap-2">
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

                            @if ($itemPenawaran->isRevisi)
                                <div>
                                    <label for="jumlah_revisi-{{ $index }}"
                                        class="block text-sm font-medium leading-6 text-red-500">Revisi
                                        Jumlah
                                        @if ($order->status == 'preorder' || $order->status == 'ditolak')
                                            <span class="text-yellow-500 text-sm italic">*harap
                                                update</span>
                                        @endif
                                    </label>
                                    <input type="text" id="jumlah_revisi-{{ $index }}" form="revisi-form"
                                        name="jumlah_revisi[]" placeholder="0"
                                        value="{{ str_replace('.', ',', $itemPenawaran->jumlah_revisi) }}"
                                        value="{{ $order->target_kirim }}" disabled
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-600 text-sm leading-6" />
                                </div>
                            @endif

                            <div>
                                <label for="harga"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Harga/satuan</label>
                                <input type="text" id="harga-{{ $index }}" name="harga[]"
                                    placeholder="10000" value="{{ str_replace('.', ',', $itemPenawaran->harga) }}"
                                    disabled
                                    class="format-rupiah block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                            </div>

                            @if ($itemPenawaran->isRevisi && $itemPenawaran->harga_revisi != null)
                                <div>
                                    <label for="harga_revisi-{{ $index }}"
                                        class="block text-sm font-medium leading-6 text-red-500">Revisi
                                        Harga
                                        @if ($order->status == 'preorder' || $order->status == 'ditolak')
                                            <span class="text-yellow-500 text-sm italic">*harap
                                                update</span>
                                        @endif
                                    </label>
                                    <input type="text" id="harga_revisi-{{ $index }}" form="revisi-form"
                                        name="harga_revisi[]" placeholder="0"
                                        value="{{ number_format($itemPenawaran->harga_revisi, 2, ',', '.') }}"
                                        disabled
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-600 text-sm leading-6" />
                                </div>
                            @endif

                            <div>
                                <label for="total_harga"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Total
                                    Harga</label>
                                <input type="text" id="total-{{ $index }}" name="total_harga[]"
                                    placeholder="10000"
                                    value="{{ str_replace('.', ',', $itemPenawaran->total_harga) }}" disabled
                                    class="format-rupiah block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
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
                    class="mb-4 flex flex-col justify-between mt-4 md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                    <a href="/suplier/list-order?telepon={{ $order->suplier->telepon }}"
                        class="block min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                    @if ($order->status == 'terkirim')
                        <div class="flex flex-col md:flex-row gap-2">
                            <button type="button" data-modal-target="modal-tolak-order-suplier"
                                data-modal-toggle="modal-tolak-order-suplier"
                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Tolak</button>

                            <button type="submit" form="suplier-terima-form"
                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima
                                Order</button>
                        </div>
                    @elseif ($order->status == 'onprocess')
                        <a href="/order/kirimWAGudang/{{ $order->id }}" onclick="showLoadingModal()"
                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                            WA Gudang</a>
                    @elseif ($order->status == 'revisiterkirim')
                        <div class="flex flex-col md:flex-row gap-2">

                            <a href="https://wa.me/{{ $order->user->telepon }}" target="_blank"
                                class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Chat
                                WA Admin</a>

                            <button type="button" data-modal-target="modal-revisi-suplier"
                                data-modal-toggle="modal-revisi-suplier"
                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Tolak
                                Revisi</button>

                            <button onclick="submitSuplierRevisiForm(event)" value="diterima" name="diterima"
                                form="suplier-revisi-form"
                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima</button>
                        </div>
                    @elseif ($order->status == 'revisiditolak')
                        <a href="https://wa.me/{{ $order->user->telepon }}" target="_blank"
                            class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Chat
                            WA Admin</a>
                    @endif
                </div>
            </div>
        </div>

        <form method="POST" action="/order/revisi/{{ $order->id }}?token={{ $order->token }}"
            id="suplier-revisi-form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

        </form>

        <form method="POST" action="/order/{{ $order->id }}?token={{ $order->token }}" id="suplier-terima-form"
            class="hidden" enctype="multipart/form-data" onsubmit="return suplierTerimaOrderForm(event)">
            @csrf
            @method('PATCH')

        </form>
    </div>

    <script>
        function submitSuplierRevisiForm(event) {
            event.preventDefault()
            if (confirm('Terima order?')) {
                showLoadingModal();

                var input = document.createElement("input");
                input.type = "text";
                input.value = "diterima";
                input.name = "diterima";
                input.hidden = true;
                input.setAttribute("form", "suplier-revisi-form");

                // get the form element
                var form = document.getElementById('suplier-revisi-form')

                // Append the new input element to the body
                form.appendChild(input);

                // submit the form
                form.submit();
            }
        }

        function submitSuplierTolakRevisiForm(event) {
            event.preventDefault()
            if (confirm('Tolak revisi order?')) {
                showLoadingModal();

                var input = document.createElement("input");
                input.type = "text";
                input.value = "tolak";
                input.name = "tolak";
                input.hidden = true;
                input.setAttribute("form", "suplier-revisi-form");

                // get the form element
                var form = document.getElementById('suplier-revisi-form')

                // Append the new input element to the body
                form.appendChild(input);

                // submit the form
                form.submit();
            }
        }

        function suplierTolakOrderForm(event) {
            event.preventDefault();

            konfirmasiTolakOrder = confirm('Tolak order ini?');

            if (konfirmasiTolakOrder) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiTolakOrder;
        }

        function suplierTerimaOrderForm(event) {
            event.preventDefault();

            konfirmasiTerimaOrder = confirm('Terima order ini?');

            if (konfirmasiTerimaOrder) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiTerimaOrder;
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
        function previewImage(event, previewId, linkId) {
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
