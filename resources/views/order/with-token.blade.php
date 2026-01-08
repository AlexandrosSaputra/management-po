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

    <div class="flex flex-wrap items-center justify-center w-full">
        <div class="my-10 w-full bg-white rounded-lg shadow-md p-4 md:p-10">

            <div class="mx-4 my-4">
                <div class="border-b border-gray-900/10 pb-4">
                    <div class="relative">
                        <h2
                            class="flex text-center justify-center items-center text-2xl font-bold leading-7 text-gray-900">
                            Detail Order {{ $order->kode }}
                            {{ $order->isKontrak ? '(Kontrak/Non PO)' : '(Pre Order)' }}</h2>
                    </div>

                    <div class="mt-20">

                        <div class="w-full flex flex-col gap-x-6 gap-y-4 ">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label for="suplier"
                                        class="block text-sm font-medium leading-6 text-gray-900">Suplier</label>
                                    <input disabled id="suplier" name="suplier" value={{ $order->suplier->nama }}
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <div>
                                    <label for="suplier"
                                        class="block text-sm font-medium leading-6 text-gray-900">Telepon</label>
                                    <input type="text" id="suplierid" name="suplierid" disabled
                                        value="{{ $order->suplier->telepon }}"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <div>
                                    <label for="pemesan"
                                        class="block text-sm font-medium leading-6 text-gray-900">Pemesan</label>
                                    <input id="pemesan" name="pemesan" value="{{ $order->user->nama }}"
                                        @disabled(true)
                                        class=" block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                @if ($order->pre_order_id)
                                    <div>
                                        <label for="penawaran_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">ID
                                            Pre Order</label>
                                        <input type="text" id="penawaran_id" name="penawaran_id" disabled
                                            value="{{ $order->pre_order_id }}"
                                            class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    </div>
                                @else
                                    <div>
                                        <label for="kontrak_id"
                                            class="block text-sm font-medium leading-6 text-gray-900">ID
                                            Kontrak</label>
                                        <input type="text" id="kontrak_id" name="kontrak_id" disabled
                                            value="{{ $order->kontrak_id }}"
                                            class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    </div>
                                @endif

                                <div>
                                    <label for="tanggal_po"
                                        class="block text-sm font-medium leading-6 text-gray-900">Tanggal PO</label>
                                    <input type="text" id="tanggal_po" name="tanggal_po" disabled
                                        value="{{ explode(' ', $order->created_at)[0] }}"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <div>
                                    <label for="target_kirim"
                                        class="block text-sm font-medium leading-6 text-gray-900">Target Kirim</label>
                                    <input type="date" id="target_kirim" name="target_kirim" disabled
                                        value="{{ $order->target_kirim }}"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>

                                <div>
                                    <label for="gudang_id"
                                        class="block text-sm font-medium leading-6 text-gray-900">Gudang</label>
                                    <input name="gudang_id" disabled
                                        value="{{ $order->gudang->nama . ' - ' . $order->gudang->telepon }}"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>

                                <div>
                                    <label for="jenis_id"
                                        class="block text-sm font-medium leading-6 text-gray-900">Jenis</label>
                                    <input id="jenis_id" name="jenis_id" disabled value="{{ $order->jenis->nama }}"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>

                                @if ($order->foto)
                                    <div class="flex w-full items-end gap-2">
                                        <img id="preview" src="{{ asset('folder-image-truenas/' . $order->foto) }}"
                                            alt="Preview Gambar"
                                            class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                                        <a id="image-link" href="{{ asset('folder-image-truenas/' . $order->foto) }}"
                                            target="_blank"
                                            class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                            Bukti Suplier</a>
                                    </div>
                                @else
                                    @if ($order->status == 'terkirim')
                                        <div>
                                            <label for="gambar"
                                                class="block text-sm font-medium leading-6 text-gray-900">Foto
                                                Barang <span
                                                    class="text-xs text-black/40 italic">*opsional</span></label>
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
                                        <a href="{{ asset('folder-image-truenas/' . $order->invoice_suplier) }}"
                                            target="_blank"
                                            class="block w-full md:w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                            Lihat
                                            Invoice Suplier</a>
                                    </div>
                                @else
                                    @if ($order->status == 'terkirim')
                                        <div>
                                            <label for="uploadInvoice"
                                                class="block text-sm font-medium leading-6 text-gray-900">Invoice Pre
                                                Order
                                                <span class="text-xs text-black/40 italic">*opsional</span></label>
                                            <input form="suplier-terima-form"
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                                id="uploadInvoice" name="uploadInvoice" type="file"
                                                accept="application/pdf" onchange="previewPDF(this)">
                                        </div>

                                        <div class="flex w-full items-center gap-2">
                                            <div id="pdfPreview" style="display: none;" class="md:pt-6">
                                                <a href="#" target="_blank" id="pdfPreviewLink"
                                                    class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Preview
                                                    PDF</a>
                                            </div>
                                        </div>
                                    @endif

                                @endif

                                @if ($order->catatan_gudang)
                                    <div class="flex w-full items-end gap-2">
                                        <!-- Modal toggle -->
                                        <button data-modal-target="modal-isi-gudang"
                                            data-modal-toggle="modal-isi-gudang"
                                            class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="button">
                                            Catatan Ketidaksesuaian
                                        </button>


                                    </div>
                                @endif

                                @if ($order->catatan_suplier)
                                    <div class="flex w-full items-end gap-2">
                                        <!-- Modal toggle -->
                                        <button data-modal-target="modal-isi-suplier"
                                            data-modal-toggle="modal-isi-suplier"
                                            class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="button">
                                            Catatan Penolakan Suplier
                                        </button>

                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col w-full gap-y-2 my-2 sm:col-span-3 space-y-2" id="input-area">
                                <div class="flex text-center text-2xl font-bold">
                                    <p class="flex-1">Items</p>
                                </div>

                                @php
                                    $index = 0;
                                    $jumlahItem = 0;
                                @endphp
                                @foreach ($itemPenawarans as $index => $itemPenawaran)
                                    <div class="my-4 w-full flex justify-center items-center ">
                                        <div class="w-full border border-black/20"></div>
                                        <div class="w-[200px]">
                                            <p class="text-center font-bold">Item {{ $loop->iteration }} </p>
                                            <p class="text-center text-red-500 font-bold">
                                                {{ $itemPenawaran->isRevisi ? 'Revisi' : '' }}</p>
                                        </div>

                                        <div class="w-full border border-t border-black/20"></div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 item-group">
                                        <div>
                                            <label for="item"
                                                class="block text-sm font-medium leading-6 text-gray-900">Item</label>
                                            <input id="item" name="item[]"
                                                value="{{ $itemPenawaran->item->nama }}" disabled
                                                class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>
                                        <div>
                                            <label for="satuan"
                                                class="block text-sm font-medium leading-6 text-gray-900">Satuan</label>
                                            <input id="satuan" value="{{ $itemPenawaran->satuan->nama }}" disabled
                                                class=" block w-full rounded-md border-0 py-3 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>


                                        <div>
                                            <label for="jumlah"
                                                class="block text-sm font-medium leading-6 text-gray-900">Jumlah</label>
                                            <input type="text" id="jumlah-{{ $index }}" name="jumlah[]"
                                                placeholder="0"
                                                value="{{ str_replace('.', ',', $itemPenawaran->jumlah) }}" disabled
                                                class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>

                                        @if ($itemPenawaran->isRevisi)
                                            <div>
                                                <label for="jumlah_revisi-{{ $index }}"
                                                    class="block text-sm font-medium leading-6 text-red-500">Revisi
                                                    Jumlah
                                                    @if ($order->status == 'preorder' || $order->status == 'ditolak')
                                                        <span class="text-yellow-500 text-xs italic">*harap
                                                            update</span>
                                                    @endif
                                                </label>
                                                <input type="text" id="jumlah_revisi-{{ $index }}"
                                                    form="revisi-form" name="jumlah_revisi[]" placeholder="0"
                                                    value="{{ str_replace('.', ',', $itemPenawaran->jumlah_revisi) }}"
                                                    value="{{ $order->target_kirim }}" disabled
                                                    class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6" />
                                            </div>
                                        @endif

                                        <div>
                                            <label for="harga"
                                                class="block text-sm font-medium leading-6 text-gray-900">Harga/satuan</label>
                                            <input type="text" id="harga-{{ $index }}" name="harga[]"
                                                placeholder="10000"
                                                value="{{ str_replace('.', ',', $itemPenawaran->harga) }}" disabled
                                                class="format-rupiah block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>

                                        @if ($itemPenawaran->isRevisi && $itemPenawaran->harga_revisi != null)
                                            <div>
                                                <label for="harga_revisi-{{ $index }}"
                                                    class="block text-sm font-medium leading-6 text-red-500">Revisi
                                                    Harga
                                                    @if ($order->status == 'preorder' || $order->status == 'ditolak')
                                                        <span class="text-yellow-500 text-xs italic">*harap
                                                            update</span>
                                                    @endif
                                                </label>
                                                <input type="text" id="harga_revisi-{{ $index }}"
                                                    form="revisi-form" name="harga_revisi[]" placeholder="0"
                                                    value="{{ number_format($itemPenawaran->harga_revisi, 2, ',', '.') }}"
                                                    disabled
                                                    class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6" />
                                            </div>
                                        @endif

                                        <div>
                                            <label for="total_harga"
                                                class="block text-sm font-medium leading-6 text-gray-900">Total
                                                Harga</label>
                                            <input type="text" id="total-{{ $index }}"
                                                name="total_harga[]" placeholder="10000"
                                                value="{{ str_replace('.', ',', $itemPenawaran->total_harga) }}"
                                                disabled
                                                class="format-rupiah block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>

                                        @if ($order->status == 'onprocess')
                                            <div>
                                                <label for="total_harga"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Kesesuaian</label>

                                                <div class="flex w-full pt-2 gap-6">
                                                    <div>
                                                        <input id="default-radio-1-{{ $index }}"
                                                            type="radio" value="sesuai" form="gudang-form"
                                                            onclick="checkRadios()"
                                                            name="kesesuaian_item[{{ $index }}]"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="default-radio-1-{{ $index }}"
                                                            class="ms-2 text-sm font-medium text-gray-900">Sesuai</label>
                                                    </div>
                                                    <div>
                                                        <input id="default-radio-2-{{ $index }}"
                                                            type="radio" value="salah" form="gudang-form"
                                                            onclick="checkRadios()"
                                                            name="kesesuaian_item[{{ $index }}]" checked
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 ">
                                                        <label for="default-radio-2-{{ $index }}"
                                                            class="ms-2 text-sm font-medium text-gray-900">Tidak
                                                            Sesuai</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="gambar[{{ $index }}]"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Upload
                                                    Bukti Barang <span class="text-yellow-500 text-xs italic">*harap
                                                        update</span>
                                                </label>

                                                <div class="flex w-full pt-2 gap-6">
                                                    <div class="w-full">
                                                        <input form="gudang-form" required
                                                            class="upload block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                                            id="gambar[{{ $index }}]" type="file"
                                                            accept="image/*" name="gambar[{{ $index }}]"
                                                            onchange="imageInput(event, 'preview[{{ $index }}]', 'image-link[{{ $index }}]')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex w-full items-end gap-2">
                                                <img id="preview[{{ $index }}]" src="path/to/your/image.jpg"
                                                    alt="Preview Gambar"
                                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                                <a id="image-link[{{ $index }}]" href="#"
                                                    target="_blank"
                                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                                            </div>
                                        @endif

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
                                    </div>
                                    @php
                                        $index++;
                                        $jumlahItem += $itemPenawaran->jumlah;
                                    @endphp
                                @endforeach
                            </div>

                            <p class="flex items-center mt-2 text-lg font-bold">Total: <input
                                    value="{{ number_format($order->total_biaya, 2, ',', '.') }}"
                                    id="total-keseluruhan" type="text" name="total_biaya"
                                    class=" w-full bg-white border border-transparent" readonly></p>


                        </div>

                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $order->status == 'revisiditolak' ? 'Revisi ditolak dan diajukan ke pemesan' : '' }}
                        </p>

                        @if ($order->status != 'ditolak')
                            <div
                                class="flex flex-col justify-end mt-4 md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                @if ($order->status == 'terkirim')

                                    <button type="button" data-modal-target="modal-tolak-suplier"
                                        data-modal-toggle="modal-tolak-suplier"
                                        class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Tolak</button>


                                    <button type="submit" form="suplier-terima-form"
                                        class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima
                                        Order</button>
                                @elseif ($order->status == 'onprocess')
                                    <div class="flex w-full justify-between">

                                        <a href="/gudang/list-order?telepon={{ $order->gudang->telepon }}"
                                            class="block min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                                        <div>
                                            <button type="button" id="btn-salah" data-modal-target="modal-gudang"
                                                data-modal-toggle="modal-gudang"
                                                class="hidden min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Terima
                                                Order Tidak Sesuai</button>

                                            <button value="sesuai" name="sesuai" form="gudang-form" id="btn-accept"
                                                onclick="submitGudangForm(event)" hidden
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima
                                                Order Sesuai</button>
                                        </div>

                                    </div>
                                @elseif ($order->status == 'revisiterkirim')
                                    <a href="https://wa.me/{{ $order->user->telepon }}" target="_blank"
                                        class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Chat
                                        WA Admin</a>

                                    <button type="button" data-modal-target="modal-suplier"
                                        data-modal-toggle="modal-suplier"
                                        class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Tolak
                                        Revisi</button>

                                    <button onclick="submitSuplierRevisiForm(event)" value="diterima" name="diterima"
                                        form="suplier-revisi-form"
                                        class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima</button>
                                @elseif ($order->status == 'revisiditolak')
                                    <a href="https://wa.me/{{ $order->user->telepon }}" target="_blank"
                                        class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Chat
                                        WA Admin</a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- form modal tolak suplier --}}
                    <!-- Main modal -->
                    <div id="modal-tolak-suplier" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Catatan Menolak Order
                                    </h3>
                                    <button type="button"
                                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                        data-modal-hide="modal-tolak-suplier">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-4 md:p-5">
                                    <div class="mb-4">
                                        <textarea rows="4" name="catatan_suplier" form="tolak-form"
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                            required placeholder="Masukkan catatan tolak order">{{ $order->catatan_suplier }}</textarea>
                                    </div>
                                    <button type="submit" form="tolak-form" value="tolak" name="tolak"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Kirim</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- form modal revisi gudang --}}
                    <!-- Main modal -->
                    <div id="modal-gudang" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Catatan Ketidaksesuaian
                                    </h3>
                                    <button type="button"
                                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                        data-modal-hide="modal-gudang">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-4 md:p-5">
                                    <div class="mb-4">
                                        <textarea id="message" rows="4" name="catatan_gudang" form="gudang-form"
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Masukkan catatan tidak sesuai"></textarea>
                                    </div>
                                    <button type="submit" form="gudang-form" value="salah" name="salah"
                                        onclick="return confirm('Terima order tidak sesuai?')"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Kirim</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- form modal revisi suplier --}}
                    <!-- Main modal -->
                    <div id="modal-suplier" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Catatan Menolak Revisi
                                    </h3>
                                    <button type="button"
                                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                        data-modal-hide="modal-suplier">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-4 md:p-5">
                                    <div class="mb-4">
                                        <textarea rows="4" name="catatan_suplier" form="suplier-revisi-form" required
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Masukkan catatan tolak revisi">{{ $order->catatan_suplier }}</textarea>
                                    </div>
                                    <button type="submit" form="suplier-revisi-form" value="tolak" name="tolak"
                                        onclick="return confirm('Tolak pengajuan revisi?')"
                                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Kirim</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="/order/{{ $order->id }}?token={{ $order->token }}"
                        id="gudang-form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                    </form>

                    <form method="POST" action="/order/revisi/{{ $order->id }}?token={{ $order->token }}"
                        id="suplier-revisi-form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                    </form>

                    <form method="POST" action="/order/{{ $order->id }}?token={{ $order->token }}"
                        id="tolak-form" class="hidden"
                        onsubmit="return confirm('Apakah Anda yakin ingin monolak pesanan ini?')">
                        @csrf
                        @method('PATCH')

                        <input type="text" hidden name="status" id="status" value="ditolak">
                    </form>

                    <form method="POST" action="/order/{{ $order->id }}?token={{ $order->token }}"
                        id="suplier-terima-form" class="hidden" enctype="multipart/form-data"
                        onsubmit="return confirm('Terima order?')">
                        @csrf
                        @method('PATCH')

                    </form>
                </div>
            </div>
        </div>


        {{-- Modal Catatan Gudang --}}
        <!-- Main modal -->
        <div id="modal-isi-gudang" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Catatan Ketidaksesuaian
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                            data-modal-hide="modal-isi-gudang">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <textarea id="message" rows="10"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Write your thoughts here..." disabled>{{ $order->catatan_gudang }}</textarea>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button data-modal-hide="modal-isi-gudang" type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Oke</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Catatan Suplier --}}
        <!-- Main modal -->
        <div id="modal-isi-suplier" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Catatan Penolakan Suplier
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                            data-modal-hide="modal-isi-suplier">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <textarea id="message" rows="10"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Write your thoughts here..." disabled>{{ $order->catatan_suplier }}</textarea>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button data-modal-hide="modal-isi-suplier" type="button"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Oke</button>
                    </div>
                </div>
            </div>
        </div>
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
        function submitGudangForm(event) {
            event.preventDefault()
            if (confirm('Terima order?')) document.getElementById('gudang-form').submit();
        }

        function submitSuplierRevisiForm(event) {
            event.preventDefault()
            if (confirm('Terima order?')) document.getElementById('suplier-revisi-form').submit();
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
