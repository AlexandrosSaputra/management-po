<x-layout>
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    @session('errorMessage')
        <script type="text/javascript">
            toastr.error("{{ session('errorMessage') }}");
        </script>
    @endsession

    <div>
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Buat Data Harga</x-page-title>
        </div>
    </div>

    <div>
        <form onsubmit="return createHargaForm(event)" method="POST" action="/harga">
            @csrf
            @method('POST')

            <div class="rounded-lg shadow-md p-4">
                <div class="w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label for="suplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier
                            <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                        <select id="suplier_id" name="suplier_id" style="width: 100%;" required
                            class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                            <option value=""></option>
                            @foreach ($supliers as $suplier)
                                <option value={{ $suplier->id }}>
                                    {{ $suplier->nama . ' - ' . ($suplier->telepon) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="jenis" class="block text-xs font-medium leading-6 text-[#099AA7]">Jenis <span
                                class="text-yellow-500 text-xs italic">*harap update</span></label>
                        <select id="jenis_id" name="jenis_id" style="width: 100%;" required
                            class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                            <option value=""></option>
                            @foreach ($jenises as $jenis)
                                <option value={{ $jenis->id }}>{{ $jenis->nama }}-{{ $jenis->kode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow-md p-4">
                <div class="mt-4 flex flex-col w-full gap-y-2 my-2 sm:col-span-3" id="input-area">
                    <div class="w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item 1</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>

                    <div class="grid w-full gird-cols-1 md:grid-cols-3 gap-2">
                        <div>
                            <label for="item" class="block text-xs font-medium leading-6 text-[#099AA7]">Item
                                <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <select name="item[1][item_id]" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="satuan_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan
                                <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <select name="item[1][satuan_id]" style="width: 100%;"
                                class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="harga-1"
                                class="block text-xs font-medium leading-6 text-[#099AA7]">Harga/Satuan
                                <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                            <input type="number" name="item[1][harga]" id="harga-1" required
                                class="block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                        </div>

                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" id="add-input"
                        class="rounded-md bg-yellow-600 p-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14m-7 7V5" />
                        </svg>
                    </button>
                </div>

                <div class="mt-6 mb-4 flex items-center justify-between gap-x-6">
                    <a href="/harga"
                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>
                    <button type="submit"
                        class="min-w-[120px] rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Simpan</button>
                </div>
            </div>

        </form>
    </div>

    <script>
        function createHargaForm(event) {
            event.preventDefault();
            let konfirmasi = confirm('Buat data harga?');
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
                                <div class="w-full border border-[#099AA7]"></div>
                                <p class="w-[200px] text-center font-bold text-[#099AA7] uppercase">Item ${inputCount}</p>
                                <div class="w-full border border-t border-[#099AA7]"></div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div>
                                    <label for="item"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Item <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <select name="item[${inputCount}][item_id]" style="width: 100%;"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="satuan_id"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Satuan <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <select name="item[${inputCount}][satuan_id]" style="width: 100%;"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="harga-${inputCount}" class="block text-xs font-medium leading-6 text-[#099AA7]">Harga/Satuan
                                        <span class="text-yellow-500 text-xs italic">*harap update</span></label>
                                    <input type="number" name="item[${inputCount}][harga]" id="harga-${inputCount}" required
                                        class="block w-full rounded-md border-0 p-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6"/>
                                </div>

                            </div>

                                <div class="flex w-full mt-2 justify-start items-end">
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
</x-layout>
