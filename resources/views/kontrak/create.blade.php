<x-layout>
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    <div class="flex flex-wrap items-center justify-center w-full">
        <form class="my-10 w-full bg-white rounded-lg shadow-md p-4 md:p-10" onsubmit="return buatDataKontrak(event)"
            method="POST" action="/kontrak">
            @csrf
            @method('POST')

            <input type="number" value="{{ Auth::user()->id }}" name="user_id" id="user_id" class="hidden" />

            <div class="mx-4 my-4">
                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="flex justify-center items-center text-2xl font-bold leading-7 text-gray-900">Buat
                        Kontrak</h2>

                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-2 mt-10">
                        <div>
                            <label for="suplier" class="block text-sm font-medium leading-6 text-gray-900">Suplier
                                <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <select id="suplier_id" name="suplier_id" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @foreach ($supliers as $suplier)
                                    <option value={{ $suplier->id }}>{{ $suplier->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="jenis" class="block text-sm font-medium leading-6 text-gray-900">Jenis <span
                                    class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <select id="jenis_id" name="jenis_id" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @foreach ($jenises as $jenis)
                                    <option value={{ $jenis->id }}>{{ $jenis->nama }}-{{ $jenis->kode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                                Mulai <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <input id="tanggal_mulai" name="tanggal_mulai" required placeholder="y-m-d" type="date"
                                class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>

                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                                Akhir <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <input id="tanggal_akhir" name="tanggal_akhir" required placeholder="y-m-d" type="date"
                                class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>

                        <div>
                            <label for="gudang" class="block text-sm font-medium leading-6 text-gray-900">Gudang -
                                Telepon <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <select id="gudang_id" name="gudang_id" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @foreach ($gudangs as $gudang)
                                    <option value={{ $gudang->id }}>{{ $gudang->nama }} - {{ $gudang->telepon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col w-full gap-y-2 my-2 sm:col-span-3" id="input-area">
                        <div class="mt-4 w-full flex justify-center items-center gap-x-4">
                            <div class="w-full border border-black/20"></div>
                            <p class="w-[200px] text-center font-bold">Item 1</p>
                            <div class="w-full border border-t border-black/20"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div>
                                <label for="item" class="block text-sm font-medium leading-6 text-gray-900">Item
                                    <span class="text-green-500 text-xs italic">*updateable</span></label>
                                <select name="item[1][item_id]" style="width: 100%;"
                                    class="form-select select2 block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="satuan_id" class="block text-sm font-medium leading-6 text-gray-900">Satuan
                                    <span class="text-green-500 text-xs italic">*updateable</span></label>
                                <select name="item[1][satuan_id]" style="width: 100%;"
                                    class="form-select select2 block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    @foreach ($satuans as $satuan)
                                        <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div>
                                <label for="harga" class="block text-sm font-medium leading-6 text-gray-900">Harga
                                    <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                <input name="item[1][harga]" id="harga" required type="number" placeholder="100000"
                                    onchange="updateTotal(1)"
                                    class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>

                            <div>
                                <label for="jumlah" class="block text-sm font-medium leading-6 text-gray-900">Jumlah
                                    <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                <input name="item[1][jumlah]" id="jumlah" required type="number" placeholder="3"
                                    onchange="updateTotal(1)"
                                    class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>

                            <div>
                                <label for="total_harga" class="block text-sm font-medium leading-6 text-gray-900">Total
                                    Harga</label>
                                <input name="item[1][total_harga]" id="total_harga" readonly type="number"
                                    :value="0"
                                    class="item-total block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                    </div>

                    <div class="flex  justify-end mt-10">
                        <button type="button" id="add-input"
                            class="rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 12h14m-7 7V5" />
                            </svg>

                        </button>
                    </div>

                    <div>
                        <p class="flex items-center mt-2 text-lg font-bold">Total: <input :value="0"
                                id="total_biaya" type="number" name="total_biaya"
                                class="bg-white border border-transparent" readonly /></p>
                    </div>
                    <div class="mt-6 mb-4 flex items-center justify-between gap-x-6">
                        <a href="/kontrak"
                            class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>
                        <button type="submit"
                            class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function buatDataKontrak(event) {
            event.preventDefault();

            konfirmasi = confirm('Buat data kontrak?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>

    <script>
        $(document).ready(function() {
            let inputCount = 1; // Mulai dari input pertama

            // meghapus input
            $(document).on('click', `.delete-input`, function() {
                inputCount--;
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

                // Buat input baru
                let newInput = `
                <div class="mt-4" id="input-group">
                    <div class="mt-4 w-full flex justify-center items-center gap-x-4">
                                <div class="w-full border border-black/20"></div>
                                <p class="w-[200px] text-center font-bold">Item ${inputCount}</p>
                                <div class="w-full border border-t border-black/20"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label for="item"
                                        class="block text-sm font-medium leading-6 text-gray-900">Item <span class="text-green-500 text-xs italic">*updateable</span></label>
                                    <select name="item[${inputCount}][item_id]" style="width: 100%;"
                                        class="form-select select2 block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="satuan_id"
                                        class="block text-sm font-medium leading-6 text-gray-900">Satuan <span class="text-green-500 text-xs italic">*updateable</span></label>
                                    <select name="item[${inputCount}][satuan_id]" style="width: 100%;"
                                        class="form-select select2 block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div>
                                    <label for="harga"
                                        class="block text-sm font-medium leading-6 text-gray-900">Harga <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <input name="item[${inputCount}][harga]" id="harga" required type="number" placeholder="100000" onchange="updateTotal(${inputCount})"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>

                                <div>
                                    <label for="jumlah"
                                        class="block text-sm font-medium leading-6 text-gray-900">Jumlah <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <input name="item[${inputCount}][jumlah]" id="jumlah" required type="number" placeholder="3" onchange="updateTotal(${inputCount})"
                                        class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>

                                <div>
                                    <label for="total_harga"
                                        class="block text-sm font-medium leading-6 text-gray-900">Total Harga</label>
                                    <input name="item[${inputCount}][total_harga]" id="total_harga" readonly type="number" :value="0"
                                        class="item-total block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <div class="flex items-end">
                                    <button type="button" id="delete-input-${inputCount}"
                                        class="delete-input rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
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
        function updateTotal(index) {
            const harga = document.querySelector(`input[name="item[${index}][harga]"]`).value;
            const jumlah = document.querySelector(`input[name="item[${index}][jumlah]"]`).value;
            const totalHarga = document.querySelector(`input[name="item[${index}][total_harga]"]`);

            // Menghitung total harga untuk item tersebut
            totalHarga.value = harga * jumlah;

            // Update total semua item
            updateTotalSemua();
        }

        function updateTotalSemua() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_biaya').value = total;
        }
    </script>
</x-layout>
