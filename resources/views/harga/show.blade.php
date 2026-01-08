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

            <x-page-title>Detail Data Harga</x-page-title>
        </div>

        <div>
            <div class="rounded-lg shadow-md p-4">
                <div class="w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    <div>
                        <label for="suplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier
                            <span class="text-green-500 text-xs italic">*updateable</span></label>
                        <select id="suplier_id" name="suplier_id" style="width: 100%;" form="update-meta-form"
                            onchange="submitMetaForm()"
                            class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">


                            <option selected hidden value={{ $harga->suplier->id }}>
                                {{ $harga->suplier->nama . ' - ' . $harga->suplier->telepon }}
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

                            <option selected value={{ $harga->jenis->id }}>
                                {{ $harga->jenis->nama }}-{{ $harga->jenis->kode }}</option>

                            @foreach ($jenises as $jenis)
                                <option value={{ $jenis->id }}>{{ $jenis->nama }}-{{ $jenis->kode }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-medium leading-6 text-[#099AA7]">Status
                            <span class="text-green-500 text-xs italic">*updateable</span></label>
                        <select id="status" name="status" form="update-meta-form" onchange="submitMetaForm()"
                            class="block w-full rounded-md border-0 px-2 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                            @if ($harga->status == 'tidakaktif')
                                <option selected value={{ $harga->status }}>
                                    Tidak Aktif
                                </option>
                            @else
                                <option selected value={{ $harga->status }}>
                                    {{ Str::ucfirst($harga->status) }}
                                </option>
                            @endif

                            <option value=aktif>Aktif</option>
                            <option value=tidakaktif>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow-md p-4">
                <div class="mt-4 flex flex-col w-full gap-2">
                    @php
                        $jumlahItem = 0;
                    @endphp
                    @foreach ($harga->itemPenawarans as $index => $itemPenawaran)
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                {{ $loop->iteration }}</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="grid w-full gird-cols-1 md:grid-cols-3 gap-2 item-group">
                            <div class="flex-1">
                                <label for="item_id-{{ $loop->index }}"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Item <span
                                        class="text-green-500 text-xs italic">{{ $harga->status != 'preorder' ? '*updateable' : '' }}</span></label>
                                <select name="item[{{ $loop->index }}][item_id]" id="item_id-{{ $loop->index }}"
                                    style="width: 100%;" form="update-item-form"
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
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
                                        class="text-green-500 text-xs italic">{{ $harga->status != 'preorder' ? '*updateable' : '' }}</span></label>
                                <select name="item[{{ $loop->index }}][satuan_id]" style="width: 100%;"
                                    id="satuan_id-{{ $loop->index }}" form="update-item-form"
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    <option selected value="{{ $itemPenawaran->satuan->id }}">
                                        {{ $itemPenawaran->satuan->nama }}</option>
                                    @foreach ($satuans as $satuan)
                                        <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="harga-{{ $loop->index }}"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Harga/Satuan
                                    <span class="text-green-500 text-xs italic">*updateable</span></label>
                                <input type="number" name="item[{{ $loop->index }}][harga]"
                                    id="harga-{{ $loop->index }}" required
                                    value="{{ number_format($itemPenawaran->harga, 0, '', '') }}"
                                    form="update-item-form"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div class="flex md:items-end">
                                <button type="submit" form="item-delete-form"
                                    onclick="hapusItemForm(event, {{ $itemPenawaran->id }})"
                                    class="rounded-md bg-red-500 p-2 text-xs font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                                    <svg class="w-6 h-6 text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
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
                        {{ count($harga->itemPenawarans) > 0 ? '' : 'Item Kosong!' }}</p>


                    <div class="flex flex-col w-full gap-2 sm:col-span-3" id="input-area">
                        <div class="hidden text-[#099AA7] text-center text-xl font-bold mt-5" id="txt-tambahan-item">
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

                @if (Auth::user()->level == 'admin' || Auth::user()->id == $harga->user_id)
                    <div class="mt-6 mb-4 flex items-center justify-between gap-2 md:gap-6">
                        <a href="/harga"
                            class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                            <button type="submit" id="btn-delete" form="delete-form"
                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus</button>

                            @if ($jumlahItem > 0)
                                <button type="submit" form="update-item-form"
                                    class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Update</button>

                                @if ($harga->status == 'aktif')
                                    <a href="/order/create"
                                        class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                        Data Order</a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (Auth::user()->level == 'admin' || Auth::user()->id == $harga->user_id)
            <form id="item-delete-form" method="POST">
                @csrf
                @method('DELETE')
            </form>

            <form action="/harga/{{ $harga->id }}" method="POST" id="update-item-form"
                onsubmit="return updateItemForm(event)">
                @csrf
                @method('PATCH')

            </form>

            <form method="POST" action="/harga/{{ $harga->id }}" id="delete-form"
                onsubmit="return hargaDeleteForm()">
                @csrf
                @method('DELETE')
            </form>

            <form action="/item-penawaran/create-from-harga/{{ $harga->id }}" method="POST"
                id="tambah-item-form" onsubmit="return tambahItemForm(event)" class="mt-10">
                @csrf
                @method('POST')
            </form>

            <form action="/harga/metaupdate/{{ $harga->id }}" method="POST" id="update-meta-form"
                onsubmit="showLoadingModal()">
                @csrf
                @method('PATCH')

            </form>
        @endif
    </div>

    <script>
        function tambahItemForm() {
            event.preventDefault();

            let konfirmasiTambahItem = confirm('Tambahkan item item ini?');

            if (konfirmasiTambahItem) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiTambahItem;
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

        function updateItemForm(event) {
            event.preventDefault();
            let konfirmasiUpdate = confirm('Update data templateorder?');
            if (konfirmasiUpdate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiUpdate;
        }
    </script>

    <script>
        function submitMetaForm() {
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
                    <div class="w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item ${inputCount}</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div>
                                    <label for="item"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Item <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <select name="item[${inputCount}][item_id]" style="width: 100%;" form="tambah-item-form"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="satuan_id"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <select name="item[${inputCount}][satuan_id]" style="width: 100%;" form="tambah-item-form"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="harga-${inputCount}"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Harga/Satuan
                                        <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <input type="number" name="item[${inputCount}][harga]" id="harga-${inputCount}" required  form="tambah-item-form"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                </div>


                            </div>

                            <div class="flex mt-2  w-fulljustify-start items-end">
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
                    showLoadingModal();
                    form.submit(); // Jika user menekan "OK", submit form
                }
            });



        });
    </script>
</x-layout>
