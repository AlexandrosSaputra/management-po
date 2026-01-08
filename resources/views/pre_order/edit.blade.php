<x-layout>
    <div class="flex flex-wrap items-center justify-center w-full">
        <div class="w-full">

            <div class="mx-4 my-4">
                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="flex justify-center items-center text-2xl font-bold leading-7 text-gray-900">Detail
                        Pre Order</h2>

                    <form method="POST" action="/preorder/{{ $preorder->id }}" class="mt-10">
                        @csrf
                        @method('PATCH')

                        <input type="number" value="1" name="admin_id" id="admin_id" class="hidden" />
                        <div class="w-full flex flex-col gap-x-6 gap-y-2 ">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label for="suplier"
                                        class="block text-sm font-medium leading-6 text-gray-900">Suplier</label>
                                    <input readonly id="suplier" name="suplier" value={{ $preorder->suplier->nama }}
                                        class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <input type="text" name="suplier_id" value="{{ $preorder->suplier->id }}"
                                        hidden />
                                </div>
                                <div>
                                    <label for="suplier"
                                        class="block text-sm font-medium leading-6 text-gray-900">Telepon</label>
                                    <input type="text" id="suplierid" name="suplierid" readonly
                                        value="{{ $preorder->suplier->telepon }}"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="flex flex-col w-full gap-y-2 my-2 sm:col-span-3 space-y-2" id="input-area">
                                <div class="flex text-center text-2xl font-bold mt-5">
                                    <p class="flex-1">Items</p>
                                </div>

                                @php
                                    $index = 0;
                                @endphp
                                @foreach ($preorder->itempreorders as $itempreorder)
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-2 item-group">
                                        <div>
                                            <label for="item"
                                                class="block text-sm font-medium leading-6 text-gray-900">Item</label>
                                            <input id="item" name="item[]"
                                                value="{{ $itempreorder->item->nama }}" readonly
                                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>
                                        <div>
                                            <label for="harga"
                                                class="block text-sm font-medium leading-6 text-gray-900">Harga/satuan</label>
                                            <input type="number" id="harga-{{ $index }}" name="harga[]"
                                                placeholder="10000" value="{{ $itempreorder->harga }}"
                                                onkeyup="hitungTotal({{ $index }})"
                                                onchange="hitungTotal({{ $index }})" required
                                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>
                                        <div>
                                            <label for="satuan"
                                                class="block text-sm font-medium leading-6 text-gray-900">Satuan</label>
                                            <select id="satuan" name="satuan[]"
                                                class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="{{ $itempreorder->satuan }}" selected="selected">
                                                    {{ $itempreorder->satuan }}</option>
                                                <option value="kg">kg</option>
                                                <option value="gr">gr</option>
                                                <option value="dus">dus</option>
                                                <option value="kw">kw</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="jumlah"
                                                class="block text-sm font-medium leading-6 text-gray-900">Jumlah</label>
                                            <input type="number" id="jumlah-{{ $index }}" name="jumlah[]"
                                                placeholder="0" value="{{ $itempreorder->jumlah }}"
                                                onkeyup="hitungTotal({{ $index }})"
                                                onchange="hitungTotal({{ $index }})" readonly
                                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>
                                        <div>
                                            <label for="total_biaya"
                                                class="block text-sm font-medium leading-6 text-gray-900">Total
                                                Biaya</label>
                                            <input type="text" id="total-{{ $index }}" name="total_biaya[]"
                                                placeholder="10000" value="{{ $itempreorder->total_biaya }}" readonly
                                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </div>
                                    </div>
                                    @php
                                        $index++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>

                        <p class="mt-2 text-lg font-bold">Total: <input value="0" id="total-keseluruhan"
                                type="number" name="total_biaya" class="bg-white border border-transparent" readonly>
                        </p>

                        <div class="mt-6 mb-4 flex items-center justify-between gap-2 md:gap-6">
                            <a href="/preorder"
                                class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Kembali</a>

                            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                <button type="submit" id="btn-delete" form="delete-form"
                                    class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Hapus
                                    Pesanan</button>

                                <button type="submit"
                                    class="min-w-[120px] text-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Edit</button>

                                <button form="store-preorder"
                                    class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Kirim
                                    ke Dapur</button>
                            </div>

                        </div>
                    </form>

                    <form method="POST" action="/preorder/{{ $preorder->id }}" id="delete-form">
                        @csrf
                        @method('DELETE')
                    </form>

                    <form method="POST" action="/preorder" id="store-preorder" class="hidden">
                        @csrf
                        @method('POST')

                        <input type="number" name="preorder_id" value="{{ $preorder->id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                    form.submit(); // Jika user menekan "OK", submit form
                }
            });
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
