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
        <x-page-title>List Pembayaran</x-page-title>

        <form action="/pembayaran" method="GET" onchange="showLoadingModal()"
            class="my-6 flex flex-col md:flex-row flex-wrap items-end gap-2">
            @csrf
            @method('GET')

            <div class="w-full md:w-[200px]">
                <label for="filterKode" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By
                    Kode</label>
                <input id="filterKode" name="filterKode" onchange="this.form.submit()" type="text" placeholder="Kode"
                    value="{{ $filterKode }}"
                    class=" block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
            </div>

            <div class="w-full md:w-[200px]">
                <label for="filterSuplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Suplier</label>
                <select id="filterSuplier" name="filterSuplier" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterSuplier)
                        <option selected hidden value="{{ $filteredSuplier->id }}">{{ $filteredSuplier->nama }} -
                            {{ $filteredSuplier->cabang ? $filteredSuplier->cabang->nama : $filteredSuplier->wilayah }}
                        </option>
                    @else
                        <option value=""></option>
                    @endif

                    @foreach ($supliers as $suplier)
                        <option value="{{ $suplier->id }}">{{ $suplier->nama }} -
                            {{ $suplier->cabang ? $suplier->cabang->nama : $suplier->wilayah }}</option>
                    @endforeach

                </select>
            </div>

            <div class="w-full md:w-[200px]">
                <label for="filterUser" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Pemesan/Admin</label>
                <select id="filterUser" name="filterUser" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterUser && $filteredUser)
                        <option selected hidden value="{{ $filteredUser[0]->id }}">
                            {{ $filteredUser[0]->nama }}</option>
                    @else
                        <option value=""></option>
                    @endif

                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                    @endforeach

                </select>
            </div>

            <div class="w-full md:w-[200px]">
                <label for="filterKasir" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Kasir</label>
                <select id="filterKasir" name="filterKasir" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterKasir && $filteredKasir)
                        <option selected hidden value="{{ $filteredKasir[0]->id }}">
                            {{ $filteredKasir[0]->nama }}</option>
                    @else
                        <option value=""></option>
                    @endif

                    @foreach ($kasirs as $kasir)
                        <option value="{{ $kasir->id }}">{{ $kasir->nama }}</option>
                    @endforeach

                </select>
            </div>

            @if (Auth::user()->level == 'admin' || Auth::user()->level == 'qc' || Auth::user()->level == 'pembayaran')
                <div class="w-full md:w-[200px]">
                    <label for="filterCabang" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                        Cabang Gudang</label>
                    <select id="filterCabang" name="filterCabang" onchange="this.form.submit()" style="width: 100%"
                        class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                        @if ($filterCabang)
                            <option selected hidden value="{{ $filteredCabang->id }}">
                                {{ $filteredCabang->nama }}</option>
                        @else
                            <option value=""></option>
                        @endif

                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                        @endforeach

                    </select>
                </div>
            @endif

            <div class="w-full md:w-[200px]">
                <label for="filterCabang" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Gudang</label>
                <select id="filterGudang" name="filterGudang" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterGudang)
                        <option selected hidden value="{{ $filteredGudang[0]->id }}">{{ $filteredGudang[0]->nama }} -
                            {{ $filteredGudang[0]->cabang->nama }}</option>
                    @else
                        <option value=""></option>
                    @endif

                    @foreach ($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama }} -
                            {{ $gudang->cabang->nama }}</option>
                    @endforeach

                </select>
            </div>

            <div class="w-full md:max-w-[200px]">
                <label for="periode-awal" class="block text-xs font-medium leading-6 text-[#099AA7]">Data Mulai
                    Tanggal (M/D/Y)</label>

                <input id="periode-awal" name="periode_awal" onchange="this.form.submit()" type="date"
                    value="{{ $periode_awal }}"
                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                </input>
            </div>

            <div class="w-full md:max-w-[200px]">
                <label for="periode-akhir" class="block text-xs font-medium leading-6 text-[#099AA7]">Hingga
                    Tanggal (M/D/Y)</label>
                <input id="periode-akhir" name="periode_akhir" onchange="this.form.submit()" type="date"
                    value="{{ $periode_akhir }}"
                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                </input>
            </div>

            <a href="/pembayaran/create"
                class="rounded-md bg-[#099AA7] ms-auto p-3 text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]/80">Buat
                Pembayaran
            </a>
        </form>


        @if (Auth::user()->level == 'pembayaran' || Auth::user()->level == 'admin')
            <div class="my-4 flex flex-col-reverse md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex gap-1">
                    <div class="flex items-center bg-black/10 p-1 rounded-md">
                        <input id="check-all-checkbox" type="checkbox"
                            onchange="checkAll(this, {{ json_encode($pembayarans) }})"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">

                    </div>
                    <label class="font-semibold text-sm" for="check-all-checkbox">Pilih Semua Pembayaran Ke App
                        Dana</label>

                </div>


                <div class="flex flex-col md:flex-row items-center justify-between w-full md:w-[50%] gap-2">
                    <div class="font-semibold text-sm">Total Semua Tagihan Terpilih: Rp <input id="total-keseluruhan"
                            type="text" value="0,00" form="form-pendanaan"
                            class="w-[100px] px-0 bg-transparent border border-none" />
                    </div>

                    <div class="w-full md:w-fit flex flex-row md:flex-col">
                        <button data-modal-target="modal-pendanaan" data-modal-toggle="modal-pendanaan"
                            class="rounded-md bg-[#099AA7] ms-auto p-3 text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]/80">Kirim
                            Pendanaan
                        </button>

                    </div>
                </div>
            </div>
        @endif

        <div class="mt-4 relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
                        @if (Auth::user()->level == 'pembayaran' || Auth::user()->level == 'admin')
                            <th scope="col" class="px-6 py-3">Pilih<br>App Dana</th>
                        @endif
                        <th scope="col" class="px-6 py-3">
                            Kode
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pemesan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Suplier
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Gudang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kasir
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Periode PO
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tgl. Invoice
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total Tagihan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pembayarans as $index => $pembayaran)
                        @php
                            if ($pembayaran->status == 'dibayar') {
                                $warnaStatus = 'text-green-500';
                                $textStatus = 'Dibayar';
                            } elseif ($pembayaran->status == 'proses' || $pembayaran->status == 'diterima') {
                                $warnaStatus = 'text-yellow-500';
                                $textStatus = 'Proses';
                            } elseif ($pembayaran->status == 'invalid') {
                                $warnaStatus = 'text-red-500';
                                $textStatus = 'Invalid';
                            } else {
                                $warnaStatus = 'text-black';
                            }
                        @endphp

                        @if ($index == count($pembayarans) - 1)
                            <tr>
                                @if (Auth::user()->level == 'pembayaran' || Auth::user()->level == 'admin')
                                    @if (!$pembayaran->is_pendanaan)
                                        @if (
                                            (Auth::user()->level == 'pembayaran' && $pembayaran->kasir_id == Auth::user()->id) ||
                                                Auth::user()->level == 'admin')
                                            <td class="py-4 px-6"><input id="default-checkbox-{{ $index }}"
                                                    type="checkbox" value="{{ $pembayaran->id }}"
                                                    name="check_pembayaran[]" form="form-pendanaan"
                                                    onchange="hitungTotal(this, {{ json_encode($pembayaran->total_tagihan) }})"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                        @else
                                            <td class="py-4 px-6">Unauthorizedly</td>
                                        @endif
                                    @else
                                        <td class="py-4 px-6">Sudah Masuk Aplikasi Dana</td>
                                    @endif
                                @endif
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $pembayaran->kode }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $pembayaran->user->nama . ' - ' . ($pembayaran->user->cabang ? $pembayaran->user->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $pembayaran->suplier->nama . ' - ' . ($pembayaran->suplier->cabang ? $pembayaran->suplier->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $pembayaran->gudang ? ($pembayaran->gudang->nama . ' - ' . ($pembayaran->gudang->cabang ? $pembayaran->gudang->cabang->nama : '')) : '--' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $kasir = App\Models\User::where('id', $pembayaran->kasir_id)->first();
                                    @endphp
                                    {{ ($kasir->nama ?? '--') . ' - ' . ($kasir->cabang ? $kasir->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $pembayaran->periode_tgl)[0])->format('d-m-Y') . ' - ' . Carbon\Carbon::parse(explode(' ', $pembayaran->sampai_tgl)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $pembayaran->created_at)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $textStatus }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp. {{ $pembayaran->total_tagihan }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/pembayaran/{{ $pembayaran->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                @if (Auth::user()->level == 'pembayaran' || Auth::user()->level == 'admin')
                                    @if (!$pembayaran->is_pendanaan)
                                        @if (
                                            (Auth::user()->level == 'pembayaran' && $pembayaran->kasir_id == Auth::user()->id) ||
                                                Auth::user()->level == 'admin')
                                            <td class="py-4 px-6"><input id="default-checkbox-{{ $index }}"
                                                    type="checkbox" value="{{ $pembayaran->id }}"
                                                    name="check_pembayaran[]" form="form-pendanaan"
                                                    onchange="hitungTotal(this, {{ json_encode($pembayaran->total_tagihan) }})"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                        @else
                                            <td class="py-4 px-6">Unauthorizedly</td>
                                        @endif
                                    @else
                                        <td class="py-4 px-6">Sudah Masuk Aplikasi Dana</td>
                                    @endif
                                @endif
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $pembayaran->kode }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $pembayaran->user->nama . ' - ' . ($pembayaran->user->cabang ? $pembayaran->user->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $pembayaran->suplier->nama . ' - ' . ($pembayaran->suplier->cabang ? $pembayaran->suplier->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $pembayaran->gudang ? ($pembayaran->gudang->nama . ' - ' . ($pembayaran->gudang->cabang ? $pembayaran->gudang->cabang->nama : '')) : '--' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $kasir = App\Models\User::where('id', $pembayaran->kasir_id)->first();
                                    @endphp
                                    {{ ($kasir->nama ?? '--') . ' - ' . ($kasir->cabang ? $kasir->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $pembayaran->periode_tgl)[0])->format('d-m-Y') . ' - ' . Carbon\Carbon::parse(explode(' ', $pembayaran->sampai_tgl)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $pembayaran->created_at)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $textStatus }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp. {{ $pembayaran->total_tagihan }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/pembayaran/{{ $pembayaran->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($pembayarans) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        {{-- <div class="my-2">
            {{ $pembayarans->appends(request()->query())->links() }}
        </div> --}}

        <div class="mt-8 w-full flex flex-wrap gap-1">
            @foreach ($countItemPenawaran as $item)
                <div class="px-2 py-1 rounded-xl bg-[#099AA7] text-white text-xs font-semibold">
                    <p>{{ $item['nama'] }}: <span>{{ $item['jumlah'] }} {{ $item['satuan'] }}</span></p>
                </div>
            @endforeach
        </div>

        <form action="/pembayaran/pendanaan" method="POST" id="form-pendanaan"
            onsubmit="return kirimPendanaanForm(event)">
            @csrf
            @method('POST')
        </form>

        <!-- Main modal -->
        <div id="modal-pendanaan" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                        <h3 class="text-lg font-semibold text-[#099AA7]">
                            Data Pendanaan
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
                    <form class="p-4 md:p-5">
                        <div class="grid gap-4 mb-4">
                            <div>
                                <label for="cabangDana"
                                    class="block mb-2 text-sm font-medium text-[#099AA7]">Cabang</label>
                                <select id="cabangDana" name="cabang" style="width:100%" form="form-pendanaan"
                                    required
                                    class="form-select select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value=""></option>
                                    @foreach ($cabangsDana as $cabangDana)
                                        <option value="{{ $cabangDana->id_cabang }}">{{ $cabangDana->cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="divisiDana"
                                    class="block mb-2 text-sm font-medium text-[#099AA7]">Divisi</label>
                                <select id="divisiDana" name="project" style="width:100%" form="form-pendanaan"
                                    required
                                    class="form-select select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value=""></option>
                                    @foreach ($divisisDana as $divisiDana)
                                        <option value="{{ $divisiDana->program_pusat_id }}">{{ $divisiDana->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div>
                                <label for="judulDana"
                                    class="block mb-2 text-sm font-medium text-[#099AA7]">Judul</label>
                                <select id="judulDana" name="judul" style="width:100%" form="form-pendanaan"
                                    required
                                    class="form-select select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value=""></option>
                                    @foreach ($judulsDana as $judulDana)
                                        <option value="{{ $judulDana->id_keg }}">{{ $judulDana->kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" form="form-pendanaan"
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

    <script>
        function kirimPendanaanForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Kirim ke aplikasi pendanaan?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>

    <script>
        function indonesianFormat(number) {
            // Ensure the number is a string and split the integer and decimal parts
            let parts = number.toFixed(2).toString().split(".");

            // Format the integer part with periods for thousands
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            // Join the integer and decimal parts with a comma
            return parts.join(",");
        }
    </script>

    <script>
        let total_semua_tagihan = 0.00;

        function checkAll(checkBox, pembayarans) {
            total_semua_tagihan = 0.00;
            for (let i = 0; i < pembayarans.length; i++) {

                let itemCheckbox = document.getElementById(`default-checkbox-${i}`);

                if (itemCheckbox) itemCheckbox.checked = checkBox.checked;

                if (checkBox.checked) {
                    if (!pembayarans[i].is_pendanaan) {
                        total_semua_tagihan += parseFloat(pembayarans[i].total_tagihan.replace(/\./g, '').replace(/\,/g,
                            '.'));
                    }
                }
            }


            document.getElementById('total-keseluruhan').value = indonesianFormat(total_semua_tagihan);
        }
    </script>

    <script>
        function hitungTotal(checkbox, total_tagihan) {


            if (checkbox.checked) {
                total_semua_tagihan += parseFloat(total_tagihan.replace(/\./g, '').replace(/\,/g, '.'));

            } else {
                total_semua_tagihan -= parseFloat(total_tagihan.replace(/\./g, '').replace(/\,/g, '.'));
            }


            document.getElementById('total-keseluruhan').value = indonesianFormat(total_semua_tagihan);
        }

        function selectChange() {
            document.getElementById("form-change-select").submit();
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an item",
                allowClear: true
            });
        });
    </script>
</x-layout>
