<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>List Arsip Pembayaran</x-page-title>

        <form action="/arsip" method="GET" onchange="showLoadingModal()"
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
        </form>

        <div class="mt-4 relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
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
                            Tgl. Dibayar
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
                                <td class="px-6 py-4">
                                    {{ $pembayaran->arsipPembayaran->tgl_bayar ? Carbon\Carbon::parse($pembayaran->arsipPembayaran->tgl_bayar)->format('d-m-Y') : Carbon\Carbon::parse(explode(' ', $pembayaran->created_at)[0])->format('d-m-Y') }}
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
                                <td class="px-6 py-4">
                                    {{ $pembayaran->arsipPembayaran->tgl_bayar ? Carbon\Carbon::parse($pembayaran->arsipPembayaran->tgl_bayar)->format('d-m-Y') : Carbon\Carbon::parse(explode(' ', $pembayaran->created_at)[0])->format('d-m-Y') }}
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
{{--
        <div class="my-2">
            {{ $pembayarans->appends(request()->query())->links() }}
        </div> --}}


        <div class="mt-8 w-full flex flex-wrap gap-1">
            @foreach ($countItemPenawaran as $item)
                <div class="px-2 py-1 rounded-xl bg-[#099AA7] text-white text-xs font-semibold">
                    <p>{{ $item['nama'] }}: <span>{{ $item['jumlah'] }} {{ $item['satuan'] }}</span></p>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an item",
                allowClear: true
            });
        });
    </script>
</x-layout>
