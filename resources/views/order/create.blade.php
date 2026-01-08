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

            <x-page-title>Detail Order Kontrak</x-page-title>
        </div>

        <div>
            <form method="POST" action="/order/kontrakstore/{{ $harga->isEmpty() ? '#' : $harga[0]->id }}"
                onsubmit="return buatDataOrder(event)">
                @csrf
                @method('POST')

                <div class="w-full flex flex-col gap-x-6 gap-y-4 ">
                    <div class="rounded-lg shadow-md p-4">
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            <div>
                                <label for="suplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier
                                    <span class="text-xs text-green-500 italic">*sesuaikan</span></label>

                                <select id="suplier" name="suplier" onchange="submitSuplierChange()"
                                    form="suplier-change" style="width: 100%"
                                    class="forms-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                    <option selected value="{{ $filteredSuplier->id }}">
                                        {{ $filteredSuplier->nama . ' - ' . $filteredSuplier->telepon }}</option>

                                    @foreach ($supliers as $suplier)
                                        <option value="{{ $suplier->id }}">
                                            {{ $suplier->nama . ' - ' . $suplier->telepon }}</option>
                                    @endforeach
                                </select>

                                <input type="text" name="suplier_id" hidden
                                    value="{{ !$harga->isEmpty() ? $filteredSuplier->id : null }}">
                            </div>

                            <div>
                                <label for="pemesan"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Pemesan</label>
                                <input id="pemesan" name="pemesan"
                                    value="{{ Auth::user()->nama . ' - ' . Auth::user()->telepon }}" disabled
                                    class=" block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="jenis_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis
                                    <span class="text-yellow-500 text-xs italic">*harap update</span>
                                </label>

                                <select id="jenis_id" name="jenis_id" style="width: 100%" required
                                    class="forms-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                    @if ($jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama }} -
                                            {{ $jenis->kode }}</option>
                                    @else
                                        <option value=""></option>
                                    @endif

                                    @foreach ($jenises as $itemJenises)
                                        <option value="{{ $itemJenises->id }}">{{ $itemJenises->nama }} -
                                            {{ $itemJenises->kode }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="gudang_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Gudang
                                    <span class="text-yellow-500 text-xs italic">*harap update</span>
                                </label>

                                <select name="gudang_id" style="width: 100%;" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                    <option value=""></option>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}">{{ $gudang->nama }} -
                                            {{ $gudang->telepon }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tanggal_po"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Tanggal PO
                                    <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                <input type="date" id="tanggal_po" name="tanggal_po" required
                                    value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="target_kirim"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Target
                                    Kirim
                                    <span class="text-yellow-500 text-xs italic">*harap update</span>
                                </label>
                                <input type="date" id="target_kirim" name="target_kirim" required
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>
                        </div>
                    </div>

                    @if (!$harga->isEmpty())
                        <div class="rounded-lg shadow-md p-4">
                            <div class="flex flex-col w-full gap-2" id="input-area">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center bg-black/10 p-1 rounded-md">
                                        <input id="check-all-checkbox" type="checkbox"
                                            onchange="checkAll(this, {{ $itemPenawarans }})"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </div>

                                    <label class="font-semibold" for="check-all-checkbox">Pilih Semua Item</label>
                                </div>

                                @php
                                    $jumlahItem = 1;
                                @endphp
                                @foreach ($itemPenawarans as $index => $itemPenawaran)
                                    <div class="mt-2 w-full flex justify-center items-center gap-2">
                                        <div class="w-full border border-[#099AA7]"></div>
                                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                            {{ $loop->iteration }}</p>
                                        <div class="w-full border border-t border-[#099AA7]"></div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center bg-black/10 p-1 rounded-md">
                                            <input id="default-checkbox-{{ $index }}" type="checkbox"
                                                value="{{ $itemPenawaran->id }}"
                                                name="check_items[{{ $index }}]"
                                                onchange="checkAnItem(this, {{ $itemPenawarans }}, {{ $index }})"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        </div>

                                        <label class="font-semibold" for="default-checkbox-{{ $index }}">Pilih
                                            Item
                                            {{ $loop->iteration }}</label>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 item-group">
                                        <div>
                                            <label for="item-{{ $index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Item</label>
                                            <input id="item-{{ $index }}"
                                                name="items[{{ $index }}][item_id]"
                                                value="{{ $itemPenawaran->item->nama }}" disabled
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                        </div>

                                        <div>
                                            <label for="satuan-{{ $index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan</label>
                                            <input id="satuan-{{ $index }}" names="item[]['satuan_id']"
                                                value="{{ $itemPenawaran->satuan->nama }}" disabled
                                                class=" block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        </div>

                                        <div>
                                            <label for="jumlah-{{ $index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Jumlah
                                                <span class="text-yellow-500 text-xs italic">*harap update</span>
                                            </label>
                                            <input type="text" id="jumlah-{{ $index }}"
                                                name="items[{{ $index }}][jumlah]" placeholder="0"
                                                onkeyup="hitungTotal({{ $index }}, {{ $itemPenawarans }})"
                                                onchange="hitungTotal({{ $index }}, {{ $itemPenawarans }})"
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                        </div>

                                        <div>
                                            <label for="harga-{{ $index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">
                                                Harga/satuan
                                            </label>

                                            <input type="text" id="harga-{{ $index }}"
                                                name="items[{{ $index }}][harga]" placeholder="10000"
                                                value="{{ str_replace('.', ',', $itemPenawaran->harga) }}"
                                                onkeyup="hitungTotal({{ $index }}, {{ $itemPenawarans }})"
                                                onchange="hitungTotal({{ $index }}, {{ $itemPenawarans }})"
                                                readonly
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                        </div>

                                        <div>
                                            <label for="total_harga-{{ $index }}"
                                                class="block text-xs font-medium leading-6 text-[#099AA7]">Total
                                                Harga
                                                <a target="_blank"
                                                    href="https://www.smartick.com/blog/other-contents/curiosities/decimal-separators/"
                                                    class="font-bold text-2xs text-red-500 underline hover:text-red-700 italic">*Perhitungan
                                                    menggunakan format US</a>
                                            </label>


                                            <input type="text" id="total_harga-{{ $index }}"
                                                name="items[{{ $index }}][total_harga]" placeholder="0"
                                                disabled
                                                class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />

                                        </div>
                                    </div>
                                    @php
                                        if ($itemPenawaran->jumlah == 0) {
                                            $jumlahItem *= 0;
                                        }

                                        $jumlahItem *= $itemPenawaran->jumlah;
                                    @endphp
                                @endforeach
                            </div>

                            <div class="mt-2">
                                <a target="_blank"
                                    href="https://www.smartick.com/blog/other-contents/curiosities/decimal-separators/"
                                    class="font-bold text-xs text-red-500 underline hover:text-red-700 italic">*Perhitungan
                                    menggunakan format US</a>

                                <p class="flex items-center text-lg font-bold">Total:
                                    <input id="total-keseluruhan" type="text" name="total_biaya"
                                        class="w-full bg-white border border-transparent" />
                                </p>
                            </div>

                            <div class="mt-10">
                                <div class="mt-6 mb-4 flex items-center justify-between gap-2 md:gap-6">
                                    <a href="/order"
                                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                                    <div
                                        class="flex flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                        <button type="submit"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                            Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                            <p>Data Kosong!</p>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <form action="/order/create" method="POST" id="suplier-change" class="hidden"
            onsubmit="showLoadingModal()">
            @csrf
            @method('GET')

        </form>
    </div>

    <script>
        function buatDataOrder(event) {
            event.preventDefault();

            konfirmasi = confirm('Buat data order?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>

    <script>
        let total_biaya = 0.00;
        let checkedCheckbox = [];

        function checkAll(checkBox, itemPenawarans) {
            checkedCheckbox = [];
            1

            for (let i = 0; i < itemPenawarans.length; i++) {
                let itemCheckbox = document.getElementById(`default-checkbox-${i}`);
                let inputJumlah = document.getElementById(`jumlah-${i}`);

                if (itemCheckbox) itemCheckbox.checked = checkBox.checked;

                if (checkBox.checked) {
                    inputJumlah.setAttribute('required', 'required');
                    checkedCheckbox.push(itemCheckbox.value);
                } else {
                    inputJumlah.removeAttribute('required');
                }

            }

            hitungTotalBiaya(itemPenawarans);
        }

        function checkAnItem(checkBox, itemPenawarans, index) {
            let inputJumlah = document.getElementById(`jumlah-${index}`);
            if (checkBox.checked) {
                inputJumlah.setAttribute('required', 'required');
                checkedCheckbox.push(checkBox.value);
            }

            if (!checkBox.checked) {
                inputJumlah.removeAttribute('required');
                checkedCheckbox = checkedCheckbox.filter(item => item !== checkBox.value.toString());
            }

            hitungTotalBiaya(itemPenawarans);
        }

        function hitungTotalBiaya(itemPenawarans) {

            total_biaya = 0.00;
            itemPenawarans.forEach((element, index) => {
                let total_harga = document.getElementById(`total_harga-${index}`).value;

                checkedCheckbox.forEach((elementCheckBox, indexCheckBox) => {
                    if (elementCheckBox == element.id) {
                        if (total_harga) total_biaya += parseFloat(total_harga);
                    }
                });
            });

            document.getElementById('total-keseluruhan').value = total_biaya.toFixed(2);
        }
    </script>

    <script>
        function submitSuplierChange() {
            let suplierChangeForm = document.getElementById('suplier-change');

            suplierChangeForm.submit();
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
                    form.submit(); // Jika user menekan "OK", submit form
                }
            });

            $('.select2').select2({
                placeholder: "Select an item"
            });

        });

        // Fungsi untuk menghitung total biaya per baris
        function hitungTotal(index, itemPenawarans) {
            var harga = document.getElementById('harga-' + index).value.replace(',', '.');
            var jumlah = document.getElementById('jumlah-' + index).value.replace(',', '.');

            var total = harga * jumlah;
            document.getElementById('total_harga-' + index).value = total;


            hitungTotalBiaya(itemPenawarans);
            // Panggil fungsi untuk menghitung total keseluruhan
            // hitungTotalKeseluruhan();
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
        function hitungSemuaTotal(checkBox, total_harga) {
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
