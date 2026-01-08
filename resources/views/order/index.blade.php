<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>Order</x-page-title>

        <form action="/order" method="GET" onchange="showLoadingModal()" class="my-6 w-full flex flex-col gap-4">
            @csrf
            @method('GET')

            <div class="flex flex-col md:flex-row flex-wrap items-end gap-2">
                <div class="w-full md:w-[200px]">
                    <label for="filterKode" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By
                        Kode</label>
                    <input id="filterKode" name="filterKode" onchange="this.form.submit()" type="text"
                        placeholder="Kode" value="{{ $filterKode }}"
                        class=" block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                </div>

                <div class="w-full md:w-[200px]">
                    <label for="filterJenis" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter Jenis
                        PO</label>
                    <select id="filterJenis" name="filterJenis" onchange="this.form.submit()" style="width: 100%"
                        class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                        @if ($filterJenis)
                            <option selected hidden value="{{ $filterJenis }}">{{ Str::ucfirst($filterJenis) }}
                            </option>
                        @else
                            <option value=""></option>
                        @endif

                        <option value="kontrak">Kontrak</option>
                        <option value="preorder">Preorder</option>
                    </select>
                </div>

                <div class="w-full md:w-[200px]">
                    <label for="filterStatus" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                        Status</label>
                    <select id="filterStatus" name="filterStatus" onchange="this.form.submit()" style="width: 100%"
                        class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                        @if ($filterStatus)
                            <option selected hidden value="{{ $filterStatus }}">{{ Str::ucfirst($filterStatus) }}
                            </option>
                        @else
                            <option value=""></option>
                        @endif

                        <option value="preorder">Pre Order</option>
                        <option value="terkirim">Terkirim</option>
                        <option value="onprocess">On Process</option>
                        <option value="diterima">Diterima</option>
                        <option value="revisi">Revisi</option>
                        <option value="revisiditerima">Revisi Diterima</option>
                        <option value="revisiditolak">Revisi Ditolak</option>
                    </select>
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

                @if (Auth::user()->level == 'admin' || Auth::user()->level == 'qc')
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
                            <option selected hidden value="{{ $filteredGudang[0]->id }}">
                                {{ $filteredGudang[0]->nama }} -
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

                <a href="/order/create"
                    class="rounded-md bg-[#099AA7] ms-auto p-3 text-center text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]">Tambah
                    Order Kontrak
                </a>
            </div>
        </form>

        @if ($jumlahOrder > 0)
            <div class="w-full flex flex-wrap gap-2">
                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Admin Terlambat:
                        {{ $orderPreorderTerlambat . ' (' . round(($orderPreorderTerlambat / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Suplier Terlambat:
                        {{ $orderTerkirimTerlambat . ' (' . round(($orderTerkirimTerlambat / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Proses Terlambat:
                        {{ $orderOnprocessTerlambat . ' (' . round(($orderOnprocessTerlambat / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Gudang Terlambat:
                        {{ $orderDiterimaTerlambat . ' (' . round(($orderDiterimaTerlambat / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Total Terlambat:
                        {{ $orderTerlambat . ' (' . round(($orderTerlambat / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Belum Selesai:
                        {{ $orderBelumSelesai . ' (' . round(($orderBelumSelesai / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Selesai:
                        {{ $orderSelesai . ' (' . round(($orderSelesai / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Tepat Waktu:
                        {{ $orderTepat . ' (' . round(($orderTepat / $jumlahOrder) * 100, 2) . '%)' }}
                    </p>
                </div>

                <div class="bg-[#099AA7] text-white text-sm px-2 py-1 rounded-lg">
                    <p>Total Order:
                        {{ $jumlahOrder }}
                    </p>
                </div>
            </div>
        @endif

        <div class="mt-4 relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Kode
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jenis-Kode ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Pemesan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Suplier
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Gudang - Cabang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Target Kirim
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $index => $order)
                        @php
                            if ($order->status == 'ditolak') {
                                $warnaStatus = 'text-red-500';
                                $status = 'Ditolak';
                            } elseif ($order->status == 'revisiditolak') {
                                $warnaStatus = 'text-red-500';
                                $status = 'Revisi Ditolak';
                            } elseif ($order->status == 'invalid') {
                                $warnaStatus = 'text-red-500';
                                $status = 'Invalid';
                            } elseif ($order->status == 'diterima') {
                                $warnaStatus = 'text-green-500';
                                $status = 'Diterima';
                            } elseif ($order->status == 'onprocess') {
                                $warnaStatus = 'text-yellow-500';
                                $status = 'On Process';
                            } elseif ($order->status == 'revisi') {
                                $warnaStatus = 'text-yellow-500';
                                $status = 'Revisi';
                            } elseif ($order->status == 'revisiditerima') {
                                $warnaStatus = 'text-green-500';
                                $status = 'Revisi Diterima';
                            } elseif ($order->status == 'revisiterkirim') {
                                $warnaStatus = 'text-yellow-500';
                                $status = 'Revisi Terkirim';
                            } elseif ($order->status == 'preorder') {
                                $warnaStatus = 'text-black';
                                $status = 'Pre Order';
                            } elseif ($order->status == 'terkirim') {
                                $warnaStatus = 'text-yellow-500';
                                $status = 'Terkirim';
                            }
                        @endphp

                        @if ($index == count($orders) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $order->kode }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->isKontrak ? 'Kontrak-' . $order->kontrak_id : 'Pre Order-' . $order->pre_order_id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->user->nama . ' - ' . ($order->user->cabang ? $order->user->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->suplier->nama . ' - ' . ($order->suplier->cabang ? $order->suplier->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->gudang_id ? $order->gudang->nama : '--' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $order->created_at)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $order->target_kirim)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $order->status }}
                                    @if ($order->status == 'diterima')
                                        @if (Carbon\Carbon::parse($order->tgl_selesai)->diff(Carbon\Carbon::parse($order->target_kirim))->invert > 0)
                                            <span class="text-red-500 uppercase font-bold">Terlambat!</span>
                                        @endif
                                    @else
                                        @if (Carbon\Carbon::today()->diff(Carbon\Carbon::parse($order->target_kirim))->invert > 0)
                                            <span class="text-red-500 uppercase font-bold">Terlambat!</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/order/{{ $order->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $order->kode }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->isKontrak ? 'Kontrak-' . $order->kontrak_id : 'Pre Order-' . $order->pre_order_id }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->user->nama . ' - ' . ($order->user->cabang ? $order->user->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->suplier->nama . ' - ' . ($order->suplier->cabang ? $order->suplier->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->gudang_id ? $order->gudang->nama : '--' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $order->created_at)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $order->target_kirim)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $order->status }}
                                    @if ($order->status == 'diterima')
                                        @if (Carbon\Carbon::parse($order->tgl_selesai)->diff(Carbon\Carbon::parse($order->target_kirim))->invert > 0)
                                            <span class="text-red-500 uppercase font-bold">Terlambat!</span>
                                        @endif
                                    @else
                                        @if (Carbon\Carbon::today()->diff(Carbon\Carbon::parse($order->target_kirim))->invert > 0)
                                            <span class="text-red-500 uppercase font-bold">Terlambat!</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/order/{{ $order->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($orders) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>
        
        <div class="my-2">{{ $orders->appends(request()->query())->links() }}</div>
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
