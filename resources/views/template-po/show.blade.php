<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    <div>
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Detail Template PO</x-page-title>
        </div>

        <div">
            <form action="/templateorder/{{ $templateorder->id }}" method="POST"
                onsubmit="return konfirmasiUpdate(event)">
                @csrf
                @method('PATCH')

                <div class="rounded-lg shadow-md p-4">
                    <div class="w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>

                    <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                        <div>
                            <label for="suplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier
                                <span class="text-green-500 text-xs italic">*updateable</span></label>
                            <select id="suplier_id" name="suplier_id" style="width: 100%;" form="update-meta-form"
                                onchange="submitMetaForm()"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">


                                <option selected hidden value={{ $templateorder->suplier->id }}>
                                    {{ $templateorder->suplier->nama . ' - ' . $templateorder->suplier->telepon }}
                                </option>

                                @foreach ($supliers as $suplier)
                                    <option value={{ $suplier->id }}>
                                        {{ $suplier->nama . ' - ' . $suplier->telepon }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jenis" class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis
                                <span class="text-green-500 text-xs italic">*updateable</span></label>
                            <select id="jenis_id" name="jenis_id" style="width: 100%;" form="update-meta-form"
                                onchange="submitMetaForm()"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                <option selected value={{ $templateorder->jenis->id }}>
                                    {{ $templateorder->jenis->nama }}-{{ $templateorder->jenis->kode }}</option>

                                @foreach ($jenises as $jenis)
                                    <option value={{ $jenis->id }}>{{ $jenis->nama }}-{{ $jenis->kode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="gudang" class="block text-xs font-medium leading-6 text-[#099AA7]">Gudang -
                                Telepon <span class="text-green-500 text-xs italic">*updateable</span></label>
                            <select id="gudang_id" name="gudang_id" style="width: 100%;" form="update-meta-form"
                                onchange="submitMetaForm()"
                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                <option selected value={{ $templateorder->gudang->id }}>
                                    {{ $templateorder->gudang->nama . ' - ' . ($templateorder->gudang->cabang ? $templateorder->gudang->cabang->nama : '--') }}
                                </option>

                                @foreach ($gudangs as $gudang)
                                    <option value={{ $gudang->id }}>
                                        {{ $gudang->nama . ' - ' . ($gudang->cabang ? $gudang->cabang->nama : '--') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="gudang" class="block text-xs font-medium leading-6 text-[#099AA7]">Status
                                <span class="text-green-500 text-xs italic">*updateable</span></label>
                            <select id="status" name="status" form="update-meta-form" onchange="submitMetaForm()"
                                class="block w-full rounded-md border-0 px-2 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                @if ($templateorder->status == 'tidakaktif')
                                    <option selected value={{ $templateorder->status }}>
                                        Tidak Aktif
                                    </option>
                                @else
                                    <option selected value={{ $templateorder->status }}>
                                        {{ Str::ucfirst($templateorder->status) }}
                                    </option>
                                @endif

                                <option value=aktif>Aktif</option>
                                <option value=tidakaktif>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg shadow-md p-4"">

                    <div class="flex flex-col w-full gap-2">
                        <div class="mt-4 flex items-center gap-2">
                            <div class="flex items-center bg-black/10 p-1 rounded-md">
                                <input id="check-all-checkbox" type="checkbox"
                                    onchange="checkAll(this, {{ count($templateorder->itemPenawarans) }})"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            </div>

                            <label class="font-semibold" for="check-all-checkbox">Pilih Semua</label>
                        </div>

                        @php
                            $jumlahItem = 0;
                        @endphp
                        @foreach ($templateorder->itemPenawarans as $index => $itemPenawaran)
                            <div class="mt-4 w-full flex justify-center items-center gap-2">
                                <div class="w-full border border-[#099AA7]"></div>
                                <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                    {{ $loop->iteration }}</p>
                                <div class="w-full border border-t border-[#099AA7]"></div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="flex items-center bg-black/10 p-1 rounded-md">
                                    <input id="penawaran-checkbox-{{ $index }}" type="checkbox"
                                        onchange="checkBoxCheck(this, {{ $index }})"
                                        value="{{ $itemPenawaran->id }}" name="check_items[]"
                                        form="buat-order-penawaran"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">

                                </div>

                                <input id="kontrak-checkbox-{{ $index }}" type="checkbox"
                                    value="{{ $itemPenawaran->id }}" name="check_items[]" form="buat-order-kontrak"
                                    class="hidden w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">

                                <label class="font-semibold" for="penawaran-checkbox-{{ $index }}">Pilih
                                    Item
                                    {{ $loop->iteration }} untuk Pre Order</label>
                            </div>

                            <div class="flex w-full flex-col md:flex-row gap-2 item-group">
                                <div class="flex-1">
                                    <label for="item_id-{{ $loop->index }}"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Item <span
                                            class="text-green-500 text-xs italic">{{ $templateorder->status != 'preorder' ? '*updateable' : '' }}</span></label>
                                    <select name="item[{{ $loop->index }}][item_id]" id="item_id-{{ $loop->index }}"
                                        style="width: 100%;"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        <option selected value="{{ $itemPenawaran->item->id }}">
                                            {{ $itemPenawaran->item->nama }}</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex-1">
                                    <label for="satuan_id-{{ $loop->index }}"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan
                                        <span
                                            class="text-green-500 text-xs italic">{{ $templateorder->status != 'preorder' ? '*updateable' : '' }}</span></label>
                                    <select name="item[{{ $loop->index }}][satuan_id]" style="width: 100%;"
                                        id="satuan_id-{{ $loop->index }}"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        <option selected value="{{ $itemPenawaran->satuan->id }}">
                                            {{ $itemPenawaran->satuan->nama }}</option>
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex md:items-end ms-auto">
                                    <button type="submit" form="item-delete-form"
                                        onclick="hapusItemForm(event, {{ $itemPenawaran->id }})"
                                        class="rounded-md bg-red-500 p-2 text-xs font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                                        <svg class="w-6 h-6 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </button>
                                </div>

                            </div>

                            @php
                                $jumlahItem++;
                            @endphp
                        @endforeach

                        <p class="text-center font-bold text-lg text-yellow-500">

                        <div class="flex flex-col w-full gap-2 sm:col-span-3" id="input-area">
                            <div class="hidden text-[#099AA7] text-center text-xl font-bold mt-5"
                                id="txt-tambahan-item">
                                <p class="flex-1">Tambahan Item</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end mt-2">
                        <button type="submit" form="tambah-item-form" id="btn-simpan"
                            class="min-w-[120px] hidden text-center rounded-md bg-green-600 p-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Simpan</button>

                        <button type="button" id="add-input"
                            class="rounded-md bg-yellow-600 p-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>
                        </button>
                    </div>

                    @if (Auth::user()->level == 'admin' || Auth::user()->id == $templateorder->user_id)
                        <div class="mt-6 mb-4 flex items-center justify-between gap-2 md:gap-6">
                            <a href="/templateorder"
                                class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                <button type="submit" id="btn-delete" form="delete-form"
                                    class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus</button>

                                @if ($jumlahItem > 0)
                                    <button type="submit"
                                        class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Update</button>

                                    @if ($templateorder->status == 'aktif')
                                        <button form="buat-order-penawaran" type="submit"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                            Pre Order</button>
                                        <button form="buat-order-kontrak" type="submit"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                            Non Pre Order</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </form>
            @if (Auth::user()->level == 'admin' || Auth::user()->id == $templateorder->user_id)
                <form id="item-delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                </form>

                <form method="POST" action="/templateorder/{{ $templateorder->id }}" id="delete-form">
                    @csrf
                    @method('DELETE')
                </form>

                <form method="POST" action="/order/fromTemplate/{{ $templateorder->id }}" id="buat-order-kontrak"
                    class="hidden" onsubmit="return buatOrderDariTemplate(event)">
                    @csrf
                    @method('POST')

                </form>

                <form method="POST" action="/preorder/template/{{ $templateorder->id }}" id="buat-order-penawaran"
                    class="hidden" onsubmit="return buatPreOrderDariTemplate(event)">
                    @csrf
                    @method('POST')

                </form>

                <form action="/item-penawaran/create-from-template/{{ $templateorder->id }}" method="POST"
                    id="tambah-item-form" onsubmit="return buatItemTemplate(event)" class="mt-10">
                    @csrf
                    @method('POST')
                </form>

                <form action="/templateorder/metaupdate/{{ $templateorder->id }}" method="POST"
                    id="update-meta-form">
                    @csrf
                    @method('PATCH')

                </form>
            @endif
    </div>

    <script>
        function buatOrderDariTemplate(event) {
            event.preventDefault();

            konfirmasiOrderDariTemplate = confirm('Buat data order dengan template?');

            if (konfirmasiOrderDariTemplate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiOrderDariTemplate;
        }

        function buatPreOrderDariTemplate(event) {
            event.preventDefault();

            konfirmasiPreOrderDariTemplate = confirm('Buat data pre order dengan template?');

            if (konfirmasiPreOrderDariTemplate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiPreOrderDariTemplate;
        }

        function buatItemTemplate(event) {
            event.preventDefault();

            konfirmasiItemTemplate = confirm('Tambah item ke template?');

            if (konfirmasiItemTemplate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiItemTemplate;
        }

        function hapusItemForm(event, itemId) {
            event.preventDefault();

            const form = document.getElementById('item-delete-form');
            const newAction = '/item-penawaran/delete/' + itemId;
            form.setAttribute('action', newAction);

            let konfirmasiHapusItem = confirm('Hapus item?');

            if (konfirmasiHapusItem) {
                showLoadingModal();

                form.submit();
            }

            return konfirmasiHapusItem;
        }
    </script>

    <script>
        function checkAll(checkBox, itemCount) {
            for (let i = 0; i < itemCount; i++) {
                let penawaranCheckbox = document.getElementById(`penawaran-checkbox-${i}`);
                let kontrakCheckbox = document.getElementById(`kontrak-checkbox-${i}`);

                if (penawaranCheckbox) penawaranCheckbox.checked = checkBox.checked;
                if (kontrakCheckbox) kontrakCheckbox.checked = checkBox.checked;
            }
        }
    </script>

    <script>
        function checkBoxCheck(penawaranCheckbox, index) {
            let kontrakCheckbox = document.getElementById(`kontrak-checkbox-${ index }`);

            kontrakCheckbox.checked = penawaranCheckbox.checked;
        }
    </script>

    <script>
        function submitMetaForm() {
            showLoadingModal()
            document.getElementById('update-meta-form').submit();
        }
    </script>

    <script>
        $(document).ready(function() {
            let inputCount = 0; // Mulai dari input pertama

            // meghapus input
            $(document).on('click', `.delete-input`, function() {
                inputCount--;
                if (inputCount == 0) {
                    $(`#btn-simpan`).hide();
                    $(`#txt-tambahan-item`).hide();
                }
                // show previous delete button
                $(`#delete-input-${inputCount}`).show();
                $(this).closest('#input-group').remove();

                updateTotalSemua();
            });

            $('.select2').select2({
                placeholder: "Select an item"
            });

            // Ketika tombol "Tambah Input" diklik
            $('#add-input').click(function() {
                // hide previous delete button
                $(`#delete-input-${inputCount}`).hide();


                inputCount++; // Tambah jumlah input

                if (inputCount == 1) {
                    $(`#btn-simpan`).show();
                    $(`#txt-tambahan-item`).show();
                }

                // Buat input baru
                let newInput = `
                <div class="mt-4" id="input-group">
                            <div class="mt-4 w-full flex justify-center items-center gap-2">
                                <div class="w-full border border-[#099AA7]"></div>
                                <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                    ${inputCount}</p>
                                <div class="w-full border border-t border-[#099AA7]"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label for="item"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Item <span class="text-green-500 text-xs italic">*updateable</span></label>
                                    <select name="item[${inputCount}][item_id]" style="width: 100%;" form="tambah-item-form"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="satuan_id"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan <span class="text-green-500 text-xs italic">*updateable</span></label>
                                    <select name="item[${inputCount}][satuan_id]" style="width: 100%;" form="tambah-item-form"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                                <div class="flex w-full mt-4 justify-end items-end">
                                    <button type="button" id="delete-input-${inputCount}"
                                        class="delete-input rounded-md bg-red-500 p-2 text-xs font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                                        <svg class="w-6 h-6 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                    </button>
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
        $(document).ready(function() {
            // select2
            $('.select2').select2({
                placeholder: "Select an item"
            });

            // Event listener untuk tombol hapus
            $('#btn-delete').click(function() {
                var id = $(this).data('id'); // Dapatkan id data
                var form = $('#delete-form'); // Dapatkan form berdasarkan id

                // Menampilkan konfirmasi sebelum menghapus
                var confirmed = confirm('Apakah Anda yakin ingin menghapus templateorder ini?');

                // Cegah submit form jika pengguna membatalkan konfirmasi
                if (!confirmed) {
                    event.preventDefault(); // Mencegah pengiriman form jika dibatalkan
                } else {
                    showLoadingModal()

                    form.submit(); // Jika user menekan "OK", submit form
                }
            });



        });

        function konfirmasiUpdate(event) {
            event.preventDefault();

            konfirmasiUpdateTemplate = confirm('Update data tamplate?');

            if (konfirmasiUpdateTemplate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiUpdateTemplate;
        }
    </script>
</x-layout>
