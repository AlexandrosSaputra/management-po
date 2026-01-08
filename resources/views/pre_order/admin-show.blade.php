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

            <x-page-title>Detail Pre Order/Penawaran</x-page-title>
        </div>

        <div>
            <form action="/preorder/{{ $preorder->id }}" method="POST" onsubmit="return updateDataForm(event)">
                @csrf
                @method('PATCH')

                <div class="w-full flex flex-col gap-2">
                    <div class="rounded-lg shadow-md p-4">
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            <div>
                                <label for="suplier_id"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier
                                    <span class="text-green-500 text-xs italic">*updateable</span></label>
                                <select style="width: 100%" id="suplier_id" name="suplier_id" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">

                                    <option value="{{ $preorder->suplier->id }}">{{ $preorder->suplier->nama }} -
                                        {{ $preorder->suplier->telepon }}</option>

                                    @foreach ($supliers as $suplier)
                                        <option value="{{ $suplier->id }}">{{ $suplier->nama }} -
                                            {{ $suplier->telepon }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="user_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Pemesan
                                    <span class="text-green-500 text-xs italic">*updateable</span></label>
                                <select id="user_id" name="user_id" required style="width: 100%"
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">

                                    <option selected value="{{ $preorder->user->id }}">{{ $preorder->user->nama }}
                                    </option>

                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="jenis_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis
                                    <span class="text-green-500 text-xs italic">*updateable</span>
                                </label>
                                <select id="jenis_id" name="jenis_id" style="width: 100%;" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">
                                    @if ($preorder->jenis_id != null)
                                        <option selected hidden value="{{ $preorder->jenis->id }}">
                                            {{ $preorder->jenis->kode }} -
                                            {{ $preorder->jenis->nama }}</option>
                                    @endif

                                    @foreach ($jenises as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->kode }} -
                                            {{ $jenis->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-xs font-medium leading-6 text-[#099AA7]">Status
                                    <span class="text-green-500 text-xs italic">*updateable</span>
                                </label>
                                <select type="date" id="status" name="status" required
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">

                                    @if ($preorder->status == 'penawaran')
                                        <option value="{{ $preorder->status }}" selected>Penawaran</option>
                                    @elseif ($preorder->status == 'dikirim')
                                        <option value="{{ $preorder->status }}" selected>dikirim</option>
                                    @elseif ($preorder->status == 'ditolak')
                                        <option value="{{ $preorder->status }}" selected>Ditolak</option>
                                    @elseif ($preorder->status == 'invalid')
                                        <option value="{{ $preorder->status }}" selected>Invalid</option>
                                    @elseif ($preorder->status == 'diterima')
                                        <option value="{{ $preorder->status }}" selected>Diterima</option>
                                    @endif

                                    <option value="penawaran">Penawaran</option>
                                    <option value="dikirim">Dikirim</option>
                                    <option value="invalid">Invalid</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="diterima">Diterima</option>
                                </select>
                            </div>

                            @if ($preorder->catatan_suplier)
                                <div class="mt-2 flex w-full items-end gap-2">
                                    <!-- Modal toggle -->
                                    <button data-modal-target="modal-isi-suplier" data-modal-toggle="modal-isi-suplier"
                                        class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-2 text-center"
                                        type="button">
                                        Catatan Penolakan Suplier
                                    </button>

                                    <x-modal-catatan-suplier :preorder="$preorder" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-lg shadow-md p-4">
                        <div class="flex flex-col w-full gap-2" id="input-area" class="rounded-lg shadow-md p-4">
                            @if ($preorder->status == 'diterima' || $preorder->status == 'invalid')
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center bg-black/10 p-1 rounded-md">
                                        <input id="check-all-checkbox" type="checkbox"
                                            onchange="checkAll(this, {{ count($preorder->itemPenawarans) }})"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </div>

                                    <label class="font-semibold text-[#099AA7]" for="check-all-checkbox">Pilih Semua</label>
                                </div>
                            @endif

                            @php
                                $jumlahItem = 0;
                            @endphp

                            @foreach ($preorder->itemPenawarans as $index => $itemPenawaran)
                                <div class="mt-2 w-full flex justify-center items-center gap-2">
                                    <div class="w-full border border-[#099AA7]"></div>
                                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                        {{ $loop->iteration }}</p>
                                    <div class="w-full border border-t border-[#099AA7]"></div>
                                </div>

                                @if ($preorder->status == 'diterima' || $preorder->status == 'invalid')
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center bg-black/10 p-1 rounded-md">
                                            <input id="default-checkbox-{{ $index }}" type="checkbox"
                                                value="{{ $itemPenawaran->id }}" name="check_items[]" form="buat-order"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">

                                        </div>
                                        <label class="font-semibold text-[#099AA7]"
                                            for="default-checkbox-{{ $index }}">Pilih Item
                                            {{ $loop->iteration }} untuk diorder</label>
                                    </div>
                                @endif

                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 {{$preorder->status == 'diterima' ? 'lg:grid-cols-3' : ''}} gap-2 item-group">
                                    <div>
                                        <label for="item_id-{{ $loop->index }}"
                                            class="block text-xs font-medium leading-6 text-[#099AA7]">Item <span
                                                class="text-xs text-green-500 italic">*updateable</span></label>
                                        <select name="item[{{ $loop->index }}][item_id]" style="width: 100%;"
                                            id="item_id-{{ $loop->index }}" required
                                            class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">

                                            <option selected hidden value="{{ $itemPenawaran->item->id }}">
                                                {{ $itemPenawaran->item->nama }}</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($preorder->status == 'diterima' || $preorder->status == 'invalid')
                                        <div>
                                            <label for="harga-{{ $loop->index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Harga/satuan
                                                <span class="text-xs text-green-500 italic">*updateable</span></label>
                                            <input type="text" id="harga-{{ $loop->index }}"
                                                name="item[{{ $loop->index }}][harga]" placeholder="10000"
                                                value="{{ str_replace('.', ',', $itemPenawaran->harga) }}" required
                                                class="format-rupiah block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6" />
                                        </div>
                                    @endif

                                    <div>
                                        <label for="satuan_id-{{ $loop->index }}"
                                            class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan <span
                                                class="text-xs text-green-500 italic">*updateable</span></label>
                                        <select name="item[{{ $loop->index }}][satuan_id]" style="width: 100%;"
                                            id="satuan_id-{{ $loop->index }}" required
                                            class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">

                                            <option selected hidden value="{{ $itemPenawaran->satuan->id }}">
                                                {{ $itemPenawaran->satuan->nama }}</option>
                                            @foreach ($satuans as $satuan)
                                                <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="flex w-full justify-between items-end mt-2">


                                    </div>
                                </div>

                                <div class="flex items-end justify-between gap-2">
                                    <div class="flex gap-2">
                                        @if (count($compareItemPenawarans) > 0)
                                            <div class="flex items-end">
                                                <button data-modal-target="modal-comparasi-{{ $loop->index }}"
                                                    data-modal-toggle="modal-comparasi-{{ $loop->index }}"
                                                    class="block mt-2 w-full md:w-fit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-5 py-3 text-center"
                                                    type="button">
                                                    Lihat Komparasi Item
                                                </button>
                                            </div>

                                            <x-modal-komparasi-item :compareItemPenawarans="$compareItemPenawarans" :itemPenawaran="$itemPenawaran"
                                                index="{{ $loop->index }}" />
                                        @endif

                                        @if ($itemPenawaran->gambar)
                                            <div class="flex items-end gap-2">
                                                <img id="preview-1"
                                                    src="{{ asset('folder-image-truenas/' . $itemPenawaran->gambar) }}"
                                                    alt="Preview Gambar"
                                                    class="h-[45px] max-w-[45px] rounded-md border border-black/20">

                                                <a id="image-link-1"
                                                    href="{{ asset('folder-image-truenas/' . $itemPenawaran->gambar) }}"
                                                    target="_blank"
                                                    class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                                    Gambar</a>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($preorder->status == 'penawaran' ||
                                    $preorder->status == 'diterima' ||
                                    $preorder->status == 'invalid' ||
                                    $preorder->status == 'ditolak')
                                        <div class="flex">
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
                                    @endif
                                </div>

                                @php
                                    $jumlahItem++;
                                @endphp
                            @endforeach

                            <div class="mt-10">
                                <p class="text-center font-bold text-lg text-green-500">
                                    {{ $preorder->status == 'diterima' || $preorder->status == 'invalid' ? 'Pre Order diterima suplier' : '' }}
                                </p>

                                <p class="text-center font-bold text-lg text-red-500">
                                    {{ $preorder->status == 'ditolak' ? 'Pre Order sudah ditolak' : '' }}</p>

                                @foreach ($errors->all() as $error)
                                    <p class="text-center font-bold text-lg text-red-500">{{ $error }}</p>
                                @endforeach

                                <div class="mt-6 mb-4 flex items-center justify-between gap-2 md:gap-6">
                                    <a href="/preorder"
                                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>


                                    <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                        <button type="submit"
                                            class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Update
                                            Pre Order</button>

                                        @if ($preorder->status == 'penawaran')
                                            <button type="submit" id="btn-delete" form="delete-form"
                                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus</button>
                                            @if ($jumlahItem > 0)
                                                <button form="kirim-wa"
                                                    class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                                    ke Suplier</button>
                                            @endif
                                        @elseif ($preorder->status == 'diterima' || $preorder->status == 'invalid')
                                            <button type="submit" id="btn-delete" form="delete-form"
                                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus</button>

                                            <button form="buat-order" type="submit"
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                                Order</button>
                                        @elseif ($preorder->status == 'ditolak')
                                            <button type="submit" id="btn-delete" form="delete-form"
                                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus</button>
                                            @if ($jumlahItem > 0)
                                                <button form="kirim-wa"
                                                    class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                                    ke Suplier</button>
                                            @endif
                                        @elseif ($preorder->status == 'dikirim')
                                            <button type="submit" id="btn-cancel" form="cancel-form"
                                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cancel
                                                Penawaran</button>

                                            <button form="kirim-wa"
                                                class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                                ke Suplier Lagi</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <form id="item-delete-form" method="POST">
            @csrf
            @method('DELETE')
        </form>

        <form method="POST" action="/preorder/{{ $preorder->id }}" id="delete-form">
            @csrf
            @method('DELETE')
        </form>

        <form method="POST" action="/preorder/cancel/{{ $preorder->id }}" id="cancel-form"
            onsubmit="return cancelPreorderForm(event)">
            @csrf
            @method('DELETE')

        </form>

        <form method="POST" action="/preorder/kirimWA/{{ $preorder->id }}" id="kirim-wa" class="hidden"
            onsubmit="return kirimFormConfirm(event)">
            @csrf
            @method('POST')

            <input type="number" name="preorder_id" value="{{ $preorder->id }}">
        </form>

        <form method="POST" action="/order" id="buat-order" class="hidden" onsubmit="return buatOrderForm(event)">
            @csrf
            @method('POST')

            <input type="number" name="preorder_id" value="{{ $preorder->id }}">
        </form>
    </div>

    <script>
        function updateDataForm(event) {
            event.preventDefault();

            konfirmasiUpdate = confirm('Update data pre order?');

            if (konfirmasiUpdate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiUpdate;
        }

        function cancelPreorderForm(event) {
            event.preventDefault();

            konfirmasiCancel = confirm('Cancel penawaran pre order?');

            if (konfirmasiCancel) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiCancel;
        }

        function buatOrderForm(event) {
            event.preventDefault();

            konfirmasiCancel = confirm('Cancel penawaran pre order?');

            if (konfirmasiCancel) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiCancel;
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
                let itemCheckbox = document.getElementById(`default-checkbox-${i}`);

                if (itemCheckbox) itemCheckbox.checked = checkBox.checked;
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

            // select2
            $('.select2').select2({
                placeholder: "Select an item"
            });

        });
    </script>

    <script>
        function kirimFormConfirm(event) {
            event.preventDefault();

            konfirmasiKirimSuplier = confirm('Kirim Penawaran Pre Order ke Suplier?');

            if (konfirmasiKirimSuplier) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiKirimSuplier;
        }
    </script>
</x-layout>
