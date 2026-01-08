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

            <x-page-title>Detail Non PO/Kontrak</x-page-title>
        </div>

        <div>
            <form action="/nonpo/{{ $kontrak->id }}" method="POST" onsubmit="return konfirmasiUpdate(event)">
                @csrf
                @method('PATCH')

                <div class="w-full flex flex-col gap-2">
                    <div class="rounded-lg shadow-md p-4">
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            <div>
                                <label for="suplier"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier</label>
                                <input disabled id="suplier" name="suplier"
                                    value="{{ $kontrak->suplier->nama . ' - ' . $kontrak->suplier->telepon }}"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                <input type="text" name="suplier_id" value="{{ $kontrak->suplier->id }}" hidden />
                            </div>

                            <div>
                                <label for="jenis_id"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis</label>
                                <input type="text" id="jenis_id" name="jenis_id" disabled
                                    value="{{ $kontrak->jenis->kode . ' - ' . $kontrak->jenis->nama }}"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="gudang_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Gudang
                                    - Telepon</label>
                                <input type="text" id="gudang_id" name="gudang_id" disabled
                                    value="{{ $kontrak->gudang->nama . ' - ' . $kontrak->gudang->telepon }}"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="tanggal_mulai"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Tanggal
                                    Mulai</label>
                                <input type="text" id="tanggal_mulai" name="tanggal_mulai" disabled
                                    value="{{ Carbon\Carbon::parse($kontrak->tanggal_mulai)->format('d-m-Y') }}"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="target_kirim"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Target
                                    Kirim</label>
                                <input type="text" id="target_kirim" name="target_kirim" disabled
                                    value="{{ Carbon\Carbon::parse($kontrak->target_kirim)->format('d-m-Y') }}"
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg shadow-md p-4">
                        <div class="mt-4 flex flex-col w-full gap-2" id="input-area">
                            @php
                                $jumlahItem = 0;
                            @endphp

                            @foreach ($kontrak->itemPenawarans as $index => $itemPenawaran)
                                <div class="w-full flex justify-center items-center gap-2">
                                    <div class="w-full border border-[#099AA7]"></div>
                                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                        {{ $loop->iteration }}</p>
                                    <div class="w-full border border-t border-[#099AA7]"></div>
                                </div>

                                <div
                                    class="justify-center items-end grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 item-group">
                                    <div class="w-full space-y-2">
                                        <div>
                                            <label for="item_id-{{ $loop->index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Item</label>
                                            <select name="item[{{ $loop->index }}][item_id]"
                                                id="item_id-{{ $loop->index }}" style="width: 100%;" disabled
                                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                                <option selected value="{{ $itemPenawaran->item->id }}">
                                                    {{ $itemPenawaran->item->nama }}</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="w-full space-y-2">
                                        <div>
                                            <label for="satuan_id-{{ $loop->index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan</label>
                                            <select name="item[{{ $loop->index }}][satuan_id]" disabled
                                                style="width: 100%;" id="satuan_id-{{ $loop->index }}"
                                                class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                                <option selected value="{{ $itemPenawaran->satuan->id }}">
                                                    {{ $itemPenawaran->satuan->nama }}</option>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="w-full space-y-2">
                                        <div>
                                            <label for="jumlah-{{ $loop->index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Jumlah
                                            </label>
                                            <input name="item[{{ $loop->index }}][jumlah]"
                                                id="jumlah-{{ $loop->index }}" type="text"
                                                value="{{ $itemPenawaran->jumlah }}" disabled
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                        </div>

                                    </div>

                                    <div class="w-full space-y-2">
                                        <div>
                                            <label for="harga-{{ $loop->index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Harga/Satuan
                                            </label>
                                            <input name="item[{{ $loop->index }}][harga]"
                                                id="harga-{{ $loop->index }}" type="text"
                                                value="{{ $itemPenawaran->harga }}" disabled
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                        </div>

                                    </div>

                                    <div class="w-full space-y-2">
                                        <div>
                                            <label for="total_harga-{{ $loop->index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Total
                                                Harga
                                            </label>
                                            <input name="item[{{ $loop->index }}][total_harga]"
                                                id="total_harga-{{ $loop->index }}" type="text"
                                                value="{{ $itemPenawaran->total_harga }}" disabled
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                        </div>

                                    </div>


                                </div>
                                <div class="mt-2 flex gap-2 justify-end items-end">
                                    @if ($kontrak->status == 'kontrak' || $kontrak->status == 'selesai' || $kontrak->status == 'ditolak')
                                        @if ($kontrak->user_id == Auth::user()->id)
                                            <button type="submit" form="item-delete-form"
                                                onclick="hapusItemForm(event, {{ $itemPenawaran->id }})"
                                                class="rounded-md bg-red-500 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                                                <svg class="w-6 h-6 text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                @php
                                    $jumlahItem++;
                                @endphp
                            @endforeach

                            <p class="text-center font-bold text-lg text-green-500">
                                {{ $kontrak->status == 'selesai' ? 'Pesanan sudah selesai' : '' }}</p>
                            <p class="text-center font-bold text-lg text-red-500">
                                {{ $kontrak->status == 'ditolak' ? 'Kontrak sudah ditolak' : '' }}</p>
                            <p class="text-center font-bold text-lg text-yellow-500">
                                {{ $kontrak->status == 'preorder' ? 'Kontrak sudah preorder' : '' }}</p>
                            <p class="text-center font-bold text-lg text-yellow-500">
                                {{ $jumlahItem <= 0 ? 'Item kosong!' : '' }}</p>

                            @if (Auth::user()->level != 'admin' && Auth::user()->id == $kontrak->user_id)
                                <div class="mb-4 flex items-center justify-between gap-2 md:gap-6">
                                    <a href="/nonpo"
                                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                                    @if ($kontrak->status == 'preorder')
                                        <a href="/order/{{ $kontrak->order_id }}"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Ke
                                            Pre Order</a>
                                    @endif


                                    @if ($kontrak->status == 'kontrak' || $kontrak->status == 'selesai' || $kontrak->status == 'ditolak')
                                        <div
                                            class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                            <button type="submit" id="btn-delete" form="delete-form"
                                                class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus</button>

                                            @if ($jumlahItem > 0)
                                                <button form="buat-order" type="submit"
                                                    class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                                    Order
                                                    {{ $kontrak->status == 'selesai' ? 'Lagi' : '' }}</button>
                                            @endif
                                        </div>
                                    @elseif ($kontrak->status == 'ditolak')
                                        <a href="/order/{{ $kontrak->order_id }}"
                                            onclick="return confirm('Buat Pre Order lagi?')"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                            Pre Order Lagi</a>
                                </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if (Auth::user()->level != 'admin' && Auth::user()->id == $kontrak->user_id)
            <form id="item-delete-form" method="POST">
                @csrf
                @method('DELETE')
            </form>

            <form method="POST" action="/nonpo/{{ $kontrak->id }}" id="delete-form">
                @csrf
                @method('DELETE')
            </form>

            <form method="POST" action="/order" id="buat-order" class="hidden"
                onsubmit="return buatOrderForm(event)">
                @csrf
                @method('POST')

                <input type="number" name="kontrak_id" value="{{ $kontrak->id }}">
            </form>
        @endif
    </div>

    <script>
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

        function buatOrderForm(event) {
            event.preventDefault();

            konfirmasiBuatOrder = confirm('Buat data order lagi?');

            if (konfirmasiBuatOrder) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiBuatOrder;
        }
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
                var confirmed = confirm('Apakah Anda yakin ingin menghapus kontrak ini?');

                // Cegah submit form jika pengguna membatalkan konfirmasi
                if (!confirmed) {
                    event.preventDefault(); // Mencegah pengiriman form jika dibatalkan
                } else {
                    showLoadingModal();

                    form.submit(); // Jika user menekan "OK", submit form
                }
            });
        });

        function konfirmasiUpdate(event) {
            event.preventDefault();

            konfirmasi = confirm('Update data kontrak?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }

        // Ketika value dari select berubah, form akan di-submit
        $('#satuan-select').on('change', function() {
            $('#satuan-update-form').submit();
        });

        // Fungsi untuk menghitung total biaya per baris
        function hitungTotal(index) {
            var harga = document.getElementById('harga-' + index).value;
            var jumlah = document.getElementById('jumlah-' + index).value;
            var total = harga * jumlah;
            document.getElementById('total-' + index).value = total;

            // Panggil fungsi untuk menghitung total keseluruhan
            hitungTotalKeseluruhan();
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

        // Fungsi untuk menghitung total biaya untuk semua item saat halaman dimuat
        function hitungSemuaTotal() {
            var totalItems = document.querySelectorAll('.item').length;
            for (var i = 0; i < totalItems; i++) {
                hitungTotal(i);
            }
        }

        // Panggil hitungSemuaTotal saat halaman dimuat
        window.onload = function() {
            hitungSemuaTotal();
        };
    </script>
</x-layout>
