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

        <form action="/preorder/{{ $preorder->id }}?token={{ $preorder->token }}" method="POST"
            onsubmit="return terimaPreorderForm(event)">
            @csrf
            @method('PATCH')

            <div class="rounded-lg shadow-md p-4">
                <div class="w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div>
                        <label for="suplier" class="block text-sm font-medium leading-6 text-[#099AA7]">Suplier</label>
                        <input disabled id="suplier" name="suplier"
                            value="{{ $preorder->suplier->nama . ' (' . $preorder->suplier->wilayah . ')' }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                        <input type="text" name="suplier_id" value="{{ $preorder->suplier->id }}" hidden />
                    </div>

                    <div>
                        <label for="jenis_id" class="block text-sm font-medium leading-6 text-[#099AA7]">Jenis</label>
                        <input type="text" id="jenis_id" name="jenis_id" disabled
                            value="{{ $preorder->jenis->nama . ' - ' . $preorder->jenis->kode }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium leading-6 text-[#099AA7]">Pemesan</label>
                        <input type="text" id="user_id" name="user_id" disabled
                            value="{{ $preorder->user->nama . ' (' . $preorder->user->telepon . ')' }}"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                    </div>

                </div>
                @if ($preorder->catatan_suplier)
                    <div class="mt-2 flex w-full items-end gap-2">
                        <!-- Modal toggle -->
                        <button data-modal-target="modal-isi-suplier" data-modal-toggle="modal-isi-suplier"
                            class="block w-full md:w-fit text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            type="button">
                            Catatan Penolakan Suplier
                        </button>
                    </div>

                    <x-modal-catatan-suplier :preorder="$preorder" />
                @endif
            </div>

            <div class="rounded-lg shadow-md p-4">
                <div class="flex flex-col w-full gap-4" id="input-area">
                    @php
                        $jumlahItem = 0;
                        $jumlahHarga = 1;
                    @endphp

                    @foreach ($preorder->itemPenawarans as $index => $itemPenawaran)
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Item
                                {{ $loop->iteration }}</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 item-group">
                            <div>
                                <label for="item_id"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Item</label>
                                <input name="item[{{ $loop->index }}][item_id]" id="item_id"
                                    value="{{ $itemPenawaran->item->nama }}" disabled
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                            </div>

                            <div>
                                <label for="harga-{{ $loop->index }}"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Harga/satuan
                                    <span
                                        class="text-yellow-500 text-sm italic">{{ $preorder->status == 'dikirim' ? '*harap update' : '' }}</span></label>
                                <input placeholder="cth: 1234,56" id="harga-{{ $loop->index }}"
                                    name="item[{{ $loop->index }}][harga]" placeholder="10000"
                                    value="{{ str_replace('.', ',', $itemPenawaran->harga) }}"
                                    onkeyup="hitungTotal({{ $loop->index }})" onchange="hitungTotal({{ $loop->index }})"
                                    required
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6" />
                                @error("item.$index.harga")
                                    <div class="text-red-500 text-sm font-semibold">
                                        Format input harus angka bulat atau koma
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="satuan_id-{{ $loop->index }}"
                                    class="block text-sm font-medium leading-6 text-[#099AA7]">Satuan <span
                                        class="text-green-500 text-sm italic">{{ $preorder->status == 'dikirim' ? '*updateble' : '' }}</span></label>
                                <select name="item[{{ $loop->index }}][satuan_id]" style="width: 100%;"
                                    id="satuan_id-{{ $loop->index }}" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-sm leading-6">
                                    <option selected value="{{ $itemPenawaran->satuan->id }}">
                                        {{ $itemPenawaran->satuan->nama }}</option>
                                    @foreach ($satuans as $satuan)
                                        <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex w-full justify-between items-end mt-2">
                            @if ($itemPenawaran->gambar)
                                <div class="flex items-end gap-2">
                                    <img id="preview-1" src="{{ asset('folder-image-truenas/' . $itemPenawaran->gambar) }}"
                                        alt="Preview Gambar"
                                        class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                                    <a id="image-link-1"
                                        href="{{ asset('folder-image-truenas/' . $itemPenawaran->gambar) }}"
                                        target="_blank"
                                        class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                        Gambar</a>
                                </div>
                            @endif

                            {{-- hapus per item --}}
                            @if ($preorder->status == 'dikirim')
                                <button type="submit" form="item-delete-form"
                                    onclick="hapusItemForm(event, {{ $itemPenawaran->id }})"
                                    class="rounded-md bg-red-500 p-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">
                                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        @php
                            if ($itemPenawaran->harga == 0) {
                                $jumlahHarga *= 0;
                            }

                            $jumlahHarga *= $itemPenawaran->harga;

                            $jumlahItem++;
                        @endphp
                    @endforeach

                    <div class="mt-10">
                        <p class="text-center font-bold text-lg text-green-500">
                            {{ $preorder->status == 'preorder' ? 'Pre Order sudah diterima' : '' }}</p>
                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $jumlahHarga <= 0 ? 'Harap update harga/satuan!' : '' }}</p>
                        <p class="text-center font-bold text-lg text-yellow-500">
                            {{ $jumlahItem <= 0 ? 'Satuan kosong' : '' }}</p>
                        <p class="text-center font-bold text-lg text-red-500">
                            {{ $preorder->status == 'ditolak' ? 'Pre Order sudah ditolak' : '' }}</p>

                        <div class="mt-6 mb-4 flex w-full items-center justify-center md:justify-between gap-2 md:gap-6">
                            <a href="javascript:history.back()"
                                class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>
                            @if ($preorder->status == 'dikirim')
                                <div
                                    class="flex w-full justify-end flex-col md:flex-row space-y-2 md:space-y-0 space-x-0 md:space-x-6">
                                    <button type="button" data-modal-target="modal-tolak-suplier"
                                        data-modal-toggle="modal-tolak-suplier"
                                        class="min-w-[120px] text-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Tolak</button>

                                    @if ($jumlahItem > 0)
                                        <button type="submit"
                                            class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Terima</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <x-modal-tolak-suplier :preorder="$preorder" />

        <form id="item-delete-form" method="POST">
            @csrf
            @method('DELETE')

            <input type="text" value="{{ $preorder->token }}" name="token" hidden>
        </form>
    </div>

    <script>
        function terimaPreorderForm(event) {
            event.preventDefault();

            konfirmasiTerimaPreorder = confirm('Terima penawaran pre order?');

            if (konfirmasiTerimaPreorder) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiTerimaPreorder;
        }

        function tolakPreorderForm(event) {
            event.preventDefault();

            konfirmasiTolakPreorder = confirm('Tolak penawaran pre order?');

            if (konfirmasiTolakPreorder) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiTolakPreorder;
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
        $(document).ready(function() {
            // select2
            $('.select2').select2({
                placeholder: "Select an item"
            });

        });
    </script>
</x-layout>
