<x-layout>
    <div>
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Detail Pembayaran {{ $pembayaran->kode }}</x-page-title>
        </div>

        <div class="rounded-lg shadow-lg p-4">
            <div class="w-full flex flex-col gap-2">
                <div>
                    <div class="w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>

                    <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2" id="input-group">
                        <div>
                            <label for="suplier"
                                class="block text-xs font-medium leading-6 text-gray-900">Suplier</label>
                            <input id="suplier" name="suplier" disabled
                                value="{{ $pembayaran->suplier->nama . ' - ' . $pembayaran->suplier->telepon }}"
                                class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">
                            </input>
                        </div>

                        <div>
                            <label for="gudang"
                                class="block text-xs font-medium leading-6 text-gray-900">Gudang</label>
                            <input id="gudang" name="gudang" disabled
                                    value="{{ $pembayaran->gudang ? ($pembayaran->gudang->nama . ' - ' . $pembayaran->gudang->telepon) : '--' }}"
                                class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">

                            </input>
                        </div>

                        <div>
                            <label for="pemesan"
                                class="block text-xs font-medium leading-6 text-gray-900">Pemesan</label>
                            <input id="pemesan" name="pemesan" value="{{ $pembayaran->user->nama }}" disabled
                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">
                            </input>

                        </div>

                        <div>

                            <label for="periode"
                                class="block text-xs font-medium leading-6 text-gray-900">Periode</label>
                            <input id="periode" name="periode"
                                value="{{ $pembayaran->periode_tgl }} s/d {{ $pembayaran->sampai_tgl }}" disabled
                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">
                            </input>
                        </div>

                        <div>

                            <label for="tipe_pembayaran_id"
                                class="block text-xs font-medium leading-6 text-gray-900">Tipe
                                Pembayaran</label>
                            <input id="tipe_pembayaran_id" name="tipe_pembayaran_id"
                                value="{{ $pembayaran->arsipPembayaran->tipePembayaran->nama . ' (' . $pembayaran->arsipPembayaran->tipePembayaran->norek . ')' }}"
                                disabled
                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">
                            </input>
                        </div>

                        <div>

                            <label for="tgl_bayar" class="block text-xs font-medium leading-6 text-gray-900">Tanggal
                                Bayar</label>
                            <input id="tgl_bayar" name="tgl_bayar"
                                value="{{ Carbon\Carbon::parse($pembayaran->arsipPembayaran->tgl_bayar)->format('d-m-Y') }}"
                                disabled
                                class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6">
                            </input>
                        </div>

                        <div class="flex flex-nowrap w-full gap-2 overflow-x-auto">
                            <div class="flex w-full items-end gap-2">
                                <img id="preview" src="path/to/your/image.jpg" alt="Preview Gambar"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                <a id="image-link" href="#" target="_blank"
                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                                @if ($pembayaran->foto)
                                    <img id="gambar-upload"
                                        src="{{ asset('folder-image-truenas/' . $pembayaran->foto) }}"
                                        alt="Preview Gambar"
                                        class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                                    <a id="image-link-upload"
                                        href="{{ asset('folder-image-truenas/' . $pembayaran->foto) }}" target="_blank"
                                        class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                        Gambar Terupload</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">List PO</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
                    <table class="mt-4 w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                        <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Kode PO
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal PO
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal Selesai
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Target Kirim
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jenis PO
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Suplier
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Gudang
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nilai
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $total = 0; // Inisialisasi variabel total
                                $index = 0;
                            @endphp
                            @foreach ($pembayaran->orders as $index => $order)
                                @if ($index == count($pembayaran->orders) - 1)
                                    <tr>
                                        <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                            {{ $order->kode }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ Carbon\Carbon::parse(explode(' ', $order->created_at)[0])->format('d-m-Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->tgl_selesai ? Carbon\Carbon::parse($order->tgl_selesai)->format('d-m-Y') : explode(' ', Carbon\Carbon::parse($order->target_kirim)->format('d-m-Y'))[0] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ Carbon\Carbon::parse($order->target_kirim)->format('d-m-Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->isKontrak ? 'Kontrak' : 'Penawaran' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->suplier->nama }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->gudang->nama }}
                                        </td>
                                        <td class="px-6 py-4">
                                            Rp
                                            {{ number_format(floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0), 2, ',', '.') }}
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
                                            {{ Carbon\Carbon::parse(explode(' ', $order->created_at)[0])->format('d-m-Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->tgl_selesai ? Carbon\Carbon::parse($order->tgl_selesai)->format('d-m-Y') : explode(' ', Carbon\Carbon::parse($order->target_kirim)->format('d-m-Y'))[0] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ Carbon\Carbon::parse($order->target_kirim)->format('d-m-Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->isKontrak ? 'Kontrak' : 'Penawaran' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->suplier->nama }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->gudang->nama }}
                                        </td>
                                        <td class="px-6 py-4">
                                            Rp
                                            {{ number_format(floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0), 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="/order/{{ $order->id }}"
                                                class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                        </td>
                                    </tr>
                                @endif
                                @php
                                    $total += (floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0)); // Menambahkan setiap nilai ke total
                                    $index += 1;
                                @endphp
                            @endforeach
                            <input type="number" name="index" id="index" hidden
                                value="{{ $index }}" />
                        </tbody>
                    </table>
                    @if (count($pembayaran->orders) <= 0)
                        <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                            <p>Data Kosong!</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mx-4">
                <p class="flex items-center mt-2 text-lg font-bold">Total: <input
                        value="{{ number_format($total, 2, ',', '.') }}" id="total-keseluruhan" type="text"
                        name="total_biaya" class="format-rupiah w-full bg-white border border-transparent" disabled>
                </p>
            </div>

            <div class="mx-4 w-full flex flex-wrap gap-1">
                @foreach ($countItemPenawaran as $item)
                    <div class="px-2 py-1 rounded-xl bg-[#099AA7] text-white text-xs font-semibold">
                        <p>{{$item['nama']}}: <span>{{$item['jumlah']}} {{$item['satuan']}}</span></p>
                    </div>
                @endforeach
            </div>

            <div>
                @if ($pembayaran->status == 'dibayar')
                    <p class="text-center text-green-500 text-lg font-bold">Pembayaran sudah dibayar</p>
                @endif
                @if ($pembayaran->status == 'proses')
                    <p class="text-center text-yellow-500 text-lg font-bold">Pembayaran sedang diproses</p>
                @endif
                @if ($pembayaran->status == 'ditolak')
                    <p class="text-center text-red-500 text-lg font-bold">Pembayaran ditolak</p>
                @endif
            </div>

            <div class="w-full mt-6 mb-4 px-4 flex gap-y-4 items-center justify-between gap-x-6">
                <a href="/arsip"
                    class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                <div class="flex flex-col md:flex-row gap-2">
                    @if ($index == 0)
                        <p class="text-red-500 text-xs text-center mb-2 font-semibold">PO Kosong!</p>
                    @endif

                    <a href="/pembayaran-pdf/{{ $pembayaran->id }}" target="_blank"
                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Cetak
                        PDF</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
