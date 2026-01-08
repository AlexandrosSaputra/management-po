<div id="modal-comparasi-{{ $index }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900 ">
                    Komparasi Item {{ $itemPenawaran->item->nama }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                    data-modal-hide="modal-comparasi-{{ $index }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
                    <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                        <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Item
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Suplier
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Satuan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Harga/Satuan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Ket.
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $itemPenawaran->item->nama }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $itemPenawaran->suplier->nama . ' - ' . $itemPenawaran->suplier->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $itemPenawaran->satuan->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($itemPenawaran->harga, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $itemPenawaran->updated_at)[0])->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 uppercase">
                                    Item ini
                                </td>
                            </tr>
                            @foreach ($compareItemPenawarans[$index] as $indexCompare => $compareItemPenawaran)
                                <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                    <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                        {{ $compareItemPenawaran[0]->item->nama }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $compareItemPenawaran[0]->suplier->nama . ' - ' . $compareItemPenawaran[0]->suplier->telepon }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $compareItemPenawaran[0]->satuan->nama }}
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp {{ number_format($compareItemPenawaran[0]->harga, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::parse(explode(' ', $compareItemPenawaran[0]->updated_at)[0])->format('d-m-Y') }}
                                    </td>
                                    @if ($loop->index == 0)
                                        @if ($compareItemPenawaran[0]->id == $itemPenawaran->id)
                                            <td class="px-6 py-4 font-bold uppercase text-orange-500">
                                                Item ini termurah
                                            </td>
                                        @else
                                            <td class="px-6 py-4 font-bold uppercase text-orange-500">
                                                Termurah
                                            </td>
                                        @endif
                                    @else
                                        @if ($compareItemPenawaran[0]->id == $itemPenawaran->id)
                                            <td class="px-6 py-4 uppercase">
                                                Item ini
                                            </td>
                                        @else
                                            <td class="px-6 py-4 uppercase">
                                                --
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($compareItemPenawarans) <= 0)
                        <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                            <p>Data Kosong!</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                <button data-modal-hide="modal-comparasi-{{ $index }}" type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Oke</button>
            </div>
        </div>
    </div>
</div>
