<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    @session('errorMessage')
        <script type="text/javascript">
            toastr.error("{{ session('errorMessage') }}");
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
            <div class="w-full flex flex-col gap-2">
                <div class="rounded-md shadow-md p-4">
                    <div class="w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>

                    <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                        <div>
                            <label for="suplier_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier
                                <span class="text-green-500 text-xs italic">*updateable</span></label>
                            <select style="width: 100%" id="suplier_id" name="suplier_id" required form="data-form"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                <option value="{{ $order->suplier->id }}">{{ $order->suplier->nama }} -
                                    {{ $order->suplier->telepon }}</option>

                                @foreach ($supliers as $suplier)
                                    <option value="{{ $suplier->id }}">{{ $suplier->nama }} -
                                        {{ $suplier->telepon }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="user_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Pemesan
                                <span class="text-green-500 text-xs italic">*updateable</span></label>
                            <select id="user_id" name="user_id" required form="data-form" style="width: 100%"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                <option selected value="{{ $order->user->id }}">{{ $order->user->nama }}
                                </option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($order->pre_order_id)
                            <div>
                                <label for="pre_order_id" class="block text-xs font-medium leading-6 text-[#099AA7]">ID
                                    Pre Order</label>
                                <input type="text" id="pre_order_id" name="pre_order_id" disabled
                                    value="{{ $order->pre_order_id }}"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>
                        @else
                            <div>
                                <label for="kontrak_id" class="block text-xs font-medium leading-6 text-[#099AA7]">ID
                                    Kontrak</label>
                                <input type="text" id="kontrak_id" name="kontrak_id" disabled
                                    value="{{ $order->kontrak_id }}"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>
                        @endif

                        <div>
                            <label for="jenis_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis
                                <span class="text-green-500 text-xs italic">*updateable</span>
                            </label>
                            <select id="jenis_id" name="jenis_id" style="width: 100%;" required form="data-form"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                @if ($order->jenis_id != null)
                                    <option selected hidden value="{{ $order->jenis->id }}">
                                        {{ $order->jenis->kode }} -
                                        {{ $order->jenis->nama }}</option>
                                @endif

                                @foreach ($jenises as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->kode }} -
                                        {{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="gudang_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Gudang
                                <span class="text-green-500 text-xs italic">*updateable</span>
                            </label>
                            <select name="gudang_id" style="width: 100%;" required form="data-form"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                @if ($order->gudang_id != null)
                                    <option selected hidden value="{{ $order->gudang->id }}">
                                        {{ $order->gudang->nama }} - {{ $order->gudang->telepon }}</option>
                                @endif

                                @foreach ($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }} -
                                        {{ $gudang->telepon }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_po" class="block text-xs font-medium leading-6 text-[#099AA7]">Tanggal
                                PO
                                <span class="text-green-500 text-xs italic">*updateable</span>
                            </label>

                            <input type="date" id="tanggal_po" name="tanggal_po"
                                value="{{ Carbon\Carbon::parse($order->created_at)->toDateString() }}" required
                                form="data-form"
                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                        </div>

                        <div>
                            <label for="target_kirim" class="block text-xs font-medium leading-6 text-[#099AA7]">Target
                                Kirim <span class="text-green-500 text-xs italic">*updateable</span>
                            </label>
                            <input type="date" id="target_kirim" name="target_kirim" required form="data-form"
                                value="{{ $order->target_kirim }}"
                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                        </div>

                        @if ($order->tgl_selesai)
                            <div>
                                <label for="tgl_selesai"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Tanggal Selesai
                                    <span class="text-green-500 text-xs italic">*updateable</span>
                                </label>
                                <input type="date" id="tgl_selesai" name="tgl_selesai"
                                    value="{{ $order->tgl_selesai }}"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>
                        @endif

                        <div>
                            <label for="status" class="block text-xs font-medium leading-6 text-[#099AA7]">Status
                                <span class="text-green-500 text-xs italic">*updateable</span>
                            </label>
                            <select type="date" id="status" name="status" required form="data-form"
                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                @if ($order->status == 'preorder')
                                    <option value="{{ $order->status }}" selected>Pre Order</option>
                                @elseif ($order->status == 'terkirim')
                                    <option value="{{ $order->status }}" selected>Terkirim</option>
                                @elseif ($order->status == 'ditolak')
                                    <option value="{{ $order->status }}" selected>Ditolak</option>
                                @elseif ($order->status == 'onprocess')
                                    <option value="{{ $order->status }}" selected>On Process</option>
                                @elseif ($order->status == 'diterima')
                                    <option value="{{ $order->status }}" selected>Diterima</option>
                                @elseif ($order->status == 'revisi')
                                    <option value="{{ $order->status }}" selected>Revisi</option>
                                @elseif ($order->status == 'revisiditolak')
                                    <option value="{{ $order->status }}" selected>Revisi Ditolak</option>
                                @elseif ($order->status == 'revisiditerima')
                                    <option value="{{ $order->status }}" selected>Revisi Diterima</option>
                                @elseif ($order->status == 'invalid')
                                    <option value="{{ $order->status }}" selected>Invalid</option>
                                @endif

                                <option value="preorder">Pre Order</option>
                                <option value="terkirim">Terkirim</option>
                                <option value="onprocess">On Process</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="diterima">Diterima</option>
                                <option value="revisi">Revisi</option>
                                <option value="revisiditerima">Revisi Diterima</option>
                                <option value="revisiditolak">Revisi Ditolak</option>
                                <option value="invalid">Invalid</option>
                            </select>
                        </div>

                        @if ($order->foto)
                            <div class="flex w-full items-end gap-2">
                                <img id="preview-1" src="{{ asset('folder-image-truenas/' . $order->foto) }}"
                                    alt="Preview Gambar"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                                <a id="image-link-1" href="{{ asset('folder-image-truenas/' . $order->foto) }}"
                                    target="_blank"
                                    class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                    Bukti Barang</a>
                            </div>
                        @endif

                        @if ($order->invoice_suplier)
                            <div class="flex w-full items-end gap-2">
                                <a href="{{ asset('folder-image-truenas/' . $order->invoice_suplier) }}"
                                    target="_blank"
                                    class="block w-full md:w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-5 py-2.5 text-center">
                                    Lihat
                                    Invoice Suplier</a>
                            </div>
                        @endif

                        <div>
                            <label for="uploadInvoice" class="block text-sm font-medium leading-6 text-[#099AA7]">Foto
                                Invoice Suplier <span class="text-sm text-yellow-500 italic">*wajib
                                    isi</span></label>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                id="uploadInvoice" type="file" accept="image/*" name="uploadInvoice" form="data-form"
                                onchange="previewImage(event, 'preview-invoice', 'image-link-invoice')">
                        </div>

                        <div class="flex w-full items-end gap-2">
                            <img id="preview-invoice" src="path/to/your/image.jpg" alt="Preview Gambar Invoice"
                                class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                            <a id="image-link-invoice" href="#" target="_blank"
                                class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                        </div>

                        @if ($order->catatan_gudang)
                            <div class="flex w-full items-end gap-2">
                                <!-- Modal toggle -->
                                <button data-modal-target="modal-gudang" data-modal-toggle="modal-gudang"
                                    class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-2.5 text-center"
                                    type="button">
                                    Catatan Ketidaksesuaian
                                </button>
                            </div>
                        @endif

                        @if ($order->catatan_suplier)
                            <div class="flex w-full items-end gap-2">
                                <!-- Modal toggle -->
                                <button data-modal-target="modal-suplier" data-modal-toggle="modal-suplier"
                                    class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-2.5 text-center"
                                    type="button">
                                    Catatan Penolakan Suplier
                                </button>
                            </div>
                        @endif

                        @if ($order->dp_1)
                            <div class="w-full">
                                <label for="nominaldp1"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Nominal DP 1</label>
                                <input id="nominaldp1" name="nominaldp1" placeholder="Masukkan nominal DP" disabled
                                    type="text" value="{{ number_format(floatval($order->dp_1), 2, ',', '.') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            </div>
                        @endif

                        @if ($order->dp_2)
                            <div class="w-full">
                                <label for="nominaldp2"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Nominal DP 2</label>
                                <input id="nominaldp2" name="nominaldp2" placeholder="Masukkan nominal DP" disabled
                                    type="text" value="{{ number_format(floatval($order->dp_2), 2, ',', '.') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            </div>
                        @endif

                        @if (($order->status == 'diterima' || $order->status == 'revisiditerima') && $order->pembayaran_id == null)
                            @if (!$order->dp_1)
                                <div class="flex w-full items-end gap-2">
                                    <button data-modal-target="modal-pendanaan" data-modal-toggle="modal-pendanaan"
                                        class="flex gap-2 items-center w-full md:w-fit text-white bg-[#099AA7] hover:bg-[#099AA7]/80 focus:ring-4 focus:outline-none focus:ring-[#099AA7]/30 font-medium rounded-lg text-xs px-5 py-2.5 text-center">
                                        <p>Tambah DP 1</p>
                                        <svg class="w-4 h-4 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            @elseif (!$order->dp_2)
                                <div class="flex w-full items-end gap-2">
                                    <button data-modal-target="modal-pendanaan" data-modal-toggle="modal-pendanaan"
                                        class="flex gap-2 items-center w-full md:w-fit text-white bg-[#099AA7] hover:bg-[#099AA7]/80 focus:ring-4 focus:outline-none focus:ring-[#099AA7]/30 font-medium rounded-lg text-xs px-5 py-2.5 text-center">
                                        <p>Tambah DP 2</p>
                                        <svg class="w-4 h-4 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z"
                                                clip-rule="evenodd" />
                                        </svg>

                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="rounded-md shadow-md p-4">
                    <div class="flex flex-col w-full gap-2" id="input-area">
                        @php
                            $index = 0;
                            $jumlahItem = 1;
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
                                    <label for="item_id-{{ $index }}"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Item
                                        <span class="text-green-500 text-xs italic">*updateable</span></label>
                                    <select id="item_id-{{ $index }}" name="item_id[{{ $index }}]"
                                        required form="data-form" style="width: 100%"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                        <option selected value="{{ $itemPenawaran->item->id }}">
                                            {{ $itemPenawaran->item->nama }}</option>

                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="satuan_id-{{ $index }}"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan
                                        <span class="text-green-500 text-xs italic">*updateable</span></label>
                                    <select id="satuan_id-{{ $index }}" name="satuan_id[{{ $index }}]"
                                        required form="data-form"
                                        class=" block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                        <option selected value="{{ $itemPenawaran->satuan->id }}">
                                            {{ $itemPenawaran->satuan->nama }}</option>

                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="jumlah"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Jumlah
                                        <span
                                            class="text-green-500 text-xs italic">{{ $itemPenawaran->isRevisi ? '' : '*updateable' }}</span>
                                    </label>
                                    <input type="text" id="jumlah-{{ $index }}"
                                        name="jumlah[{{ $index }}]" placeholder="0"
                                        value="{{ str_replace(',', '.', $itemPenawaran->jumlah) }}"
                                        onkeyup="hitungTotal({{ $index }})"
                                        onchange="hitungTotal({{ $index }})"
                                        {{ $itemPenawaran->isRevisi ? 'readonly' : 'required' }} form="data-form"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                </div>

                                @if ($itemPenawaran->isRevisi)
                                    <div>
                                        <label for="jumlah_revisi_{{ $index }}"
                                            class="block text-xs font-medium leading-6 text-red-500">Revisi
                                            Jumlah
                                            <span class="text-green-500 text-xs italic">*updateable</span>
                                        </label>
                                        <input type="text" id="jumlah_revisi_{{ $index }}"
                                            form="data-form" name="jumlah_revisi[{{ $index }}]"
                                            placeholder="0"
                                            value="{{ str_replace(',', '.', $itemPenawaran->jumlah_revisi) }}"
                                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-600 text-xs leading-6" />

                                    </div>
                                @endif

                                <div>
                                    <label for="harga" class="block text-xs font-medium leading-6 text-[#099AA7]">
                                        Harga/satuan
                                        <span
                                            class="text-green-500 text-xs italic">{{ $itemPenawaran->isRevisi ? '' : '*updateable' }}</span>
                                    </label>

                                    <input type="text" id="harga-{{ $index }}"
                                        name="harga[{{ $index }}]" placeholder="10000"
                                        value="{{ str_replace(',', '.', $itemPenawaran->harga) }}"
                                        onkeyup="hitungTotal({{ $index }})"
                                        onchange="hitungTotal({{ $index }})"
                                        {{ $itemPenawaran->isRevisi ? 'readonly' : 'required' }} form="data-form"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                </div>

                                {{-- <div>
                                        <label for="potongan-harga-{{ $index }}"
                                            class="block text-xs font-medium leading-6 text-[#099AA7]">
                                            Potongan Harga

                                            <span class="text-green-500 italic text-xs"> *updateable</span>
                                        </label>

                                        <input type="text" id="potongan-harga-{{ $index }}"
                                            name="potongan_harga[]" placeholder="10000"
                                            value="{{ str_replace(',', '.', $itemPenawaran->potongan_harga) }}"
                                            onkeyup="hitungTotalPotonganHarga({{ $index }})"
                                            onchange="hitungTotalPotonganHarga({{ $index }})"
                                            class="{{ $order->status == 'revisiditerima' || $order->status == 'diterima' ? 'format-rupiah' : '' }} block w-full rounded-lg md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                    </div> --}}

                                @if ($itemPenawaran->isRevisi)
                                    <div>
                                        <label for="harga_revisi_{{ $index }}"
                                            class="block text-xs font-medium leading-6 text-red-500">Revisi
                                            Harga
                                            <span class="text-green-500 text-xs italic">*updateable</span>
                                        </label>

                                        <input type="text" id="harga_revisi_{{ $index }}" form="data-form"
                                            name="harga_revisi[{{ $index }}]" placeholder="0"
                                            value="{{ str_replace(',', '.', $itemPenawaran->harga_revisi) }}"
                                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-red-300 focus:ring-2 focus:ring-inset focus:ring-red-600 text-xs leading-6" />
                                    </div>

                                    <input type="text" value="{{ $itemPenawaran->id }}" hidden
                                        name="itempenawaran_id[{{ $index }}]">
                                @endif

                                <div>
                                    <label for="total_harga"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Total
                                        Harga
                                    </label>

                                    <input type="text" id="total-{{ $index }}"
                                        name="total_harga[{{ $index }}]" placeholder="10000" disabled
                                        value="{{ str_replace(',', '.', $itemPenawaran->total_harga) }}"
                                        class="{{ $order->status == 'revisiditerima' || $order->status == 'diterima' ? 'format-rupiah' : '' }} block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />

                                </div>

                                {{-- <div id="div-total-potongan-harga-{{ $index }}">
                                        <label for="total_potongan"
                                            class="block text-xs font-medium leading-6 text-[#099AA7]">Total
                                            Harga Potongan
                                        </label>

                                        <input type="text" id="total-potongan-harga-{{ $index }}"
                                            name="total_harga_potongan[]" placeholder="10000" disabled
                                            value="{{ str_replace(',', '.', $itemPenawaran->total_harga_potongan) }}"
                                            class="{{ $order->status == 'revisiditerima' || $order->status == 'diterima' ? 'format-rupiah' : '' }} block w-full rounded-lg md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />

                                    </div> --}}

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
                                        class="block w-fit text-center rounded-md bg-[#099AA7] p-2.5 text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]/80">Bukti
                                        Gudang</button>

                                    <x-modal-bukti-gudang :itemPenawaran="$itemPenawaran" index="{{ $index }}" />
                                @endif

                                @if (
                                    $order->status == 'onprocess' ||
                                        $order->status == 'revisi' ||
                                        $order->status == 'revisiditolak' ||
                                        $order->status == 'revisiditerima' ||
                                        $order->status == 'diterima')
                                    <div>
                                        <form method="POST"
                                            action="/item-penawaran/bukti-gudang/{{ $itemPenawaran->id }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')

                                            <input type="text" name="index_item" value="{{ $index }}"
                                                hidden />

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
                                                        id="gambar-{{ $index }}" type="file"
                                                        accept="image/*" {{-- name="gambar[{{ $index }}]" --}} name="gambar[]"
                                                        multiple onchange="imageInput(event, {{ $index }})">
                                                    {{-- onchange="this.form.submit()"> --}}
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="flex w-full items-end gap-2">
                                        <img id="preview[{{ $index }}]" src="path/to/your/image.jpg"
                                            alt="Preview Gambar"
                                            class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                        <a id="image-link[{{ $index }}]" href="#" target="_blank"
                                            class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                                    </div>
                                @endif

                                {{-- <div class="flex flex-col">
                                        <label for="harga_revisi_{{ $index }}"
                                            class="block text-xs font-medium leading-6 text-[#099AA7]">Keterangan
                                            Admin
                                            <span class="text-green-500 text-xs italic">*opsional</span>
                                            <span class="text-red-500 text-xs italic">/ wajib (Potongan
                                                Harga)</span>
                                        </label>

                                        <button type="button"
                                            data-modal-target="modal-keterangan-admin-{{ $index }}"
                                            data-modal-toggle="modal-keterangan-admin-{{ $index }}"
                                            class="block w-fit text-center rounded-lg md bg-[#099AA7] p-3 text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]/80">Keterangan
                                            Admin</button>

                                    </div>

                                    <x-modal-keterangan-admin index="{{ $index }}"
                                        value="{{ $itemPenawaran->keterangan }}" /> --}}
                            </div>
                            @php
                                if ($itemPenawaran->jumlah == 0) {
                                    $jumlahItem *= 0;
                                }

                                $jumlahItem *= $itemPenawaran->jumlah;

                                $index++;
                            @endphp
                        @endforeach
                    </div>

                    <div class="mt-2">
                        @if ($order->status == 'preorder' || $order->status == 'ditolak')
                            <a target="_blank"
                                href="https://www.smartick.com/blog/other-contents/curiosities/decimal-separators/"
                                class="font-bold text-xs text-red-500 underline hover:text-red-700 italic">*Perhitungan
                                menggunakan format US</a>
                        @endif
                        <div class="flex items-center text-sm font-bold">
                            <p>Total:</p>
                            <input value="{{ number_format(floatval($order->total_biaya), 2, ',', '.') }}"
                                id="total-keseluruhan" type="text" name="total_biaya"
                                class="{{ $order->status == 'revisiditerima' || $order->status == 'diterima' ? 'format-rupiah' : '' }} w-fit text-sm bg-white border border-transparent"
                                {{ $order->status == 'preorder' || $order->status == 'ditolak' ? 'readonly' : 'disabled' }} />
                        </div>
                    </div>

                    {{-- <div id="div-total-keseluruhan-potongan-harga" class="mt-2 w-fit">
                            <div class="flex items-center text-sm font-bold">
                                <p>Total Terpotong: </p>

                                <input value="{{ number_format($order->total_biaya_potongan, 2, ',', '.') }}"
                                    id="total-keseluruhan-potongan-harga" type="text" name="total_biaya"
                                    class="{{ $order->status == 'revisiditerima' || $order->status == 'diterima' ? 'format-rupiah' : '' }} w-fit text-sm bg-white border border-transparent"
                                    {{ $order->status == 'preorder' || $order->status == 'ditolak' ? 'readonly' : 'disabled' }} />
                            </div>
                        </div> --}}

                    <div class="mt-10">
                        @if ($jumlahItem == 0 || $order->target_kirim == null)
                            <p class="text-center font-bold text-lg text-yellow-500">
                                Data masih belum lengkap, harap update</p>
                        @endif

                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $order->status == 'terkirim' ? 'Pesanan dikirim ke suplier' : '' }}</p>
                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $order->status == 'onprocess' ? 'Pesanan diterima suplier dan sedang dilaporkan ke gudang' : '' }}
                        </p>
                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $order->status == 'revisi' ? 'Pesanan dalam tahap revisi' : '' }}
                        </p>
                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $order->status == 'revisiterkirim' ? 'Pesanan revisi sedang dikirim ke suplier' : '' }}
                        </p>
                        <p class="text-center font-bold text-lg text-green-500">
                            {{ $order->status == 'diterima' || $order->status == 'revisiditerima' ? 'Pesanan sudah diterima' : '' }}
                        </p>
                        <p class="text-center font-bold text-lg text-red-500">
                            {{ $order->status == 'ditolak' ? 'Pesanan sudah ditolak' : '' }}
                        </p>
                        <p class="text-center font-bold text-lg text-red-500">
                            {{ $order->status == 'revisiditolak' ? 'Revisi ditolak suplier' : '' }}
                        </p>

                        <div class="mt-6 mb-4 flex items-center justify-between gap-2 md:gap-6">
                            <a href="/order"
                                class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                <button type="submit" form="data-form"
                                    class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Update</button>
                                @if ($order->status == 'preorder' || $order->status == 'ditolak')
                                    <button type="submit" id="btn-delete" form="delete-form"
                                        class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cancel
                                        PO</button>
                                @endif

                                @if ($order->status != 'ditolak')
                                    @if ($order->status == 'preorder')
                                        @if ($jumlahItem > 0 && $order->target_kirim != null)
                                            <button form="kirim-form"
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                                ke Suplier</button>
                                        @endif
                                    @elseif ($order->status == 'terkirim')
                                        <button type="submit" form="delete-form-terkirim" onclick="cancelPOkirim()"
                                            class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cancel
                                            PO Terkirim</button>
                                        <a href="/order/kirimWASuplier/{{ $order->id }}"
                                            onclick="return confirm('Ajukan ke WA ke suplier?')"
                                            class="min-w-[120px] text-center rounded-lg md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                            WA ke Suplier</a>
                                    @elseif ($order->status == 'onprocess')
                                        <a href="/order/kirimWAGudang/{{ $order->id }}"
                                            onclick="return confirm('Ajukan ke WA ke gudang?')"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                            WA ke Gudang</a>
                                    @elseif($order->status == 'diterima' || $order->status == 'revisiditerima')
                                        @if (!$order->pembayaran_id)
                                            <a href="/pembayaran"
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Pembayaran</a>
                                        @endif
                                        <a href="/order-pdf/{{ $order->id }}" target="_blank"
                                            class="ms-auto min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Cetak
                                            PDF</a>
                                    @elseif ($order->status == 'revisi')
                                        <a href="https://wa.me/{{ $order->suplier->telepon }}" target="_blank"
                                            class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Chat
                                            WA Suplier</a>

                                        @if (Auth::user()->level == 'admin')
                                            <button form="revisi-form"
                                                {{ $order->target_kirim == null ? 'disabled' : '' }}
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Update
                                                Harga</button>
                                        @else
                                            <button form="revisi-form"
                                                {{ $order->target_kirim == null ? 'disabled' : '' }}
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Ajukan
                                                Revisi Ke Suplier</button>
                                        @endif
                                    @elseif ($order->status == 'revisiterkirim')
                                        <a href="https://wa.me/{{ $order->suplier->telepon }}" target="_blank"
                                            class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Chat
                                            WA Suplier</a>
                                    @elseif ($order->status == 'revisiditolak')
                                        <a href="https://wa.me/{{ $order->suplier->telepon }}" target="_blank"
                                            class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Chat
                                            WA Suplier</a>
                                        <button form="revisi-form"
                                            {{ $order->target_kirim == null ? 'disabled' : '' }}
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Ajukan
                                            Ulang
                                            Revisi Ke Suplier</button>
                                    @endif
                                @else
                                    <button form="kirim-form" {{ $order->target_kirim == null ? 'disabled' : '' }}
                                        class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Ajukan
                                        Ulang
                                        ke Suplier</button>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- Main modal -->
            <div id="modal-pendanaan" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                            <h3 class="text-lg font-semibold text-[#099AA7]">
                                Data Pendanaan Untuk DP
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                data-modal-toggle="modal-pendanaan">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form action="/order/dp/{{ $order->id }}" method="POST" id="form-pendanaan"
                            class="p-4 md:p-5" onsubmit="return kirimPendanaanForm(event)">
                            @csrf
                            @method('POST')

                            <div class="flex items-center mb-4">
                                <input checked id="pendanaan-checkbox" type="checkbox" value="pendanaan"
                                    onclick="checkDana(this)" name="dana_check"
                                    class="w-4 h-4 text-[#099AA7] bg-gray-100 border-gray-300 rounded-md focus:ring-[#099AA7]/80 focus:ring-2">
                                <label for="pendanaan-checkbox" class="ms-2 text-sm font-medium text-[#099AA7]">Masuk
                                    Pendanaan</label>
                            </div>

                            <div class="grid gap-4 mb-4">
                                <div class="w-full">
                                    <label for="nominaldp"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Nominal DP <span
                                            class="italic text-xs text-green-500">*Koma tulis dengan "."</span></label>
                                    <input id="nominaldp" name="nominaldp" placeholder="Masukkan nominal DP" required
                                        type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                </div>
                            </div>

                            <button type="submit"
                                class="text-white inline-flex items-center bg-[#099AA7] hover:bg-[#099AA7]/80 focus:ring-4 focus:outline-none focus:ring-[#099AA7]/30 font-medium rounded-lg text-xs px-5 py-2 text-center">
                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Kirim Ke Pendanaan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="/order/{{ $order->id }}" enctype="multipart/form-data"
            onsubmit="return updateOrderForm(event)" id="data-form">
            @csrf
            @method('PATCH')
        </form>

        <form method="POST" action="/order/kirimSuplier/{{ $order->id }}" id="kirim-form"
            onsubmit="return kirimSuplierForm(event)" class="hidden">
            @csrf
            @method('POST')
        </form>

        <form method="POST" action="/order/{{ $order->id }}" id="delete-form" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <form method="POST" action="/order/cancelOrderTerkirirm/{{ $order->id }}" id="delete-form-terkirim"
            onsubmit="return cancelOrderTerkirimForm(event)" class="hidden">
            @csrf
            @method('PATCH')
        </form>

        <form method="POST" action="/order/revisi/{{ $order->id }}" id="revisi-form"
            onsubmit="return revisiForm(event)" class="hidden">
            @csrf
            @method('PATCH')
        </form>


    </div>

    <script>
        function kirimPendanaanForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Kirim DP order ke aplikasi pendanaan?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>

    <script>
        function updateOrderForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Update data order?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }

        function kirimSuplierForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Kirim data order ke suplier?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }

        function cancelOrderTerkirimForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Cancel order yang terkirim ke suplier?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }

        function revisiForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Kirim revisi ke suplier?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
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
                compressImageMeta(event, previewId);
            }
        }

        function compressImageMeta(event, itemId) {
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
        $(document).ready(function() {
            // Event listener untuk tombol hapus
            $('#btn-delete').click(function() {
                var id = $(this).data('id'); // Dapatkan id data
                var form = $('#delete-form'); // Dapatkan form berdasarkan id

                // Menampilkan konfirmasi sebelum menghapus
                var confirmed = confirm('Apakah Anda yakin ingin menghapus data ini?');

                // Cegah submit form jika pengguna membatalkan konfirmasi
                if (!confirmed) {
                    event.preventDefault(); // Mencegah pengiriman form jika dibatalkan
                } else {
                    showLoadingModal();

                    form.submit(); // Jika user menekan "OK", submit form
                }
            });

            $('.select2').select2({
                placeholder: "Select an item"
            });

        });
    </script>

    <script>
        // Fungsi untuk menghitung total biaya per baris
        function hitungTotal(index) {
            var harga = document.getElementById('harga-' + index).value.replace(',', '.');
            var jumlah = document.getElementById('jumlah-' + index).value.replace(',', '.');

            var total = harga * jumlah;
            document.getElementById('total-' + index).value = total;

            // Panggil fungsi untuk menghitung total keseluruhan
            hitungTotalKeseluruhan();
            hitungTotalPotonganHarga(index)
        }

        function hitungTotalKeseluruhan() {
            var totalSemua = 0;
            var totalItems = document.querySelectorAll('.item-group').length;
            for (var i = 0; i < totalItems; i++) {
                var totalBaris = parseFloat(document.getElementById('total-' + i).value);
                totalSemua += isNaN(totalBaris) ? 0 : totalBaris;
            }
            document.getElementById('total-keseluruhan').value = totalSemua;
        }
    </script>

    <script>
        // Fungsi untuk menghitung total biaya per baris
        function hitungTotalPotonganHarga(index) {

            var harga = document.getElementById('potongan-harga-' + index).value.replace(',', '.');
            var jumlah = document.getElementById('jumlah-' + index).value.replace(',', '.');

            var total = harga * jumlah;
            document.getElementById('total-potongan-harga-' + index).value = total;

            // Panggil fungsi untuk menghitung total keseluruhan
            hitungTotalKeseluruhanPotonganHarga();
        }

        function hitungTotalKeseluruhanPotonganHarga() {
            var totalSemuaPotonganHarga = 0;
            var totalItems = document.querySelectorAll('.item-group').length;
            for (var i = 0; i < totalItems; i++) {
                var totalBarisPotonganharga = parseFloat(document.getElementById('total-potongan-harga-' + i).value);

                totalSemuaPotonganHarga += isNaN(totalBarisPotonganharga) ? 0 : totalBarisPotonganharga;
            }

            document.getElementById('total-keseluruhan-potongan-harga').value = document.getElementById('total-keseluruhan')
                .value - totalSemuaPotonganHarga;
        }
    </script>

    <script>
        // Fungsi untuk menghitung total biaya untuk semua item saat halaman dimuat
        function hitungSemuaTotal() {
            var totalItems = document.querySelectorAll('.item').length;
            for (var i = 0; i < totalItems; i++) {
                hitungTotal(i);
            }
        }

        // Fungsi untuk menghitung total biaya untuk semua item saat halaman dimuat
        function hitungSemuaTotalPotonganHarga() {
            var totalItems = document.querySelectorAll('.item').length;
            for (var i = 0; i < totalItems; i++) {
                hitungTotalPotonganHarga(i);
            }
        }

        // Panggil hitungSemuaTotal saat halaman dimuat
        window.onload = function() {
            hitungSemuaTotal();
            hitungSemuaTotalPotonganHarga();
        };
    </script>

    <script>
        // JavaScript to validate the input
        document.getElementById('nominaldp').addEventListener('input', function(event) {
            // Allow only numbers and decimal point
            const value = event.target.value;
            const regex = /^[0-9]*\.?[0-9]*$/;
            if (!regex.test(value)) {
                event.target.value = value.slice(0, -
                    1); // Remove the last character if it's not a number or decimal point
            }
        });
    </script>

    <script>
        function checkDana(check) {
            // Get the input elements
            var divCabangDana = document.getElementById('input-cabang-dana');
            var divDivisiDana = document.getElementById('input-divisi-dana');
            var divJudulDana = document.getElementById('input-judul-dana');
            var inputCabangDana = document.getElementById('cabangDana');
            var inputDivisiDana = document.getElementById('divisiDana');
            var inputJudulDana = document.getElementById('judulDana');

            // Check if the checkbox is checked
            if (check.checked) {
                // Show the input fields
                divCabangDana.style.display = 'block'; // or 'inline' depending on your layout
                divDivisiDana.style.display = 'block'; // or 'inline'
                divJudulDana.style.display = 'block'; // or 'inline'

                // Add the required attribute
                inputCabangDana.setAttribute('required', 'required');
                inputDivisiDana.setAttribute('required', 'required');
                inputJudulDana.setAttribute('required', 'required');
            } else {
                // Hide the input fields
                divCabangDana.style.display = 'none';
                divDivisiDana.style.display = 'none';
                divJudulDana.style.display = 'none';

                // Remove the required attribute
                inputCabangDana.removeAttribute('required');
                inputDivisiDana.removeAttribute('required');
                inputJudulDana.removeAttribute('required');
            }
        }
    </script>
</x-layout>
