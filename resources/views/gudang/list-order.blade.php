<x-layout>
    <x-page-title>List Order Gudang {{$gudang->nama}}</x-page-title>

    <div class="mt-4 relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
        <table class="w-full text-sm text-center rtl:text-right text-[#099AA7] font-semibold ">
            <thead class="text-xs text-white uppercase bg-[#099AA7] ">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Kode
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Jenis-Kode ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Suplier
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Gudang
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal
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
                                {{ $order->suplier->nama . ' (' . $order->suplier->telepon . ')' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->gudang_id ? $order->gudang->nama . ' (' . $order->gudang->telepon . ')' : '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ Carbon\Carbon::parse(explode(' ', $order->created_at)[0])->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                {{ $status }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="/order/gudang/{{$order->id}}?token={{ $order->token }}"
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
                                {{ $order->suplier->nama . ' (' . $order->suplier->telepon . ')' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->gudang_id ? $order->gudang->nama . ' (' . $order->gudang->telepon . ')' : '' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ Carbon\Carbon::parse(explode(' ', $order->created_at)[0])->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                {{ $status }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="/order/gudang/{{$order->id}}?token={{ $order->token }}"
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
</x-layout>
