<x-layout>
    @if (session('message'))
        <script>
            alert('{{ session('message') }}');
        </script>
    @endif

    <div>
        <x-page-title>Komparasi Item {{ $filteredItem->nama }} dalam Sepekan</x-page-title>

        <form action="/item-penawaran" method="POST" class="my-6 flex flex-col md:flex-row gap-4 md:items-end"
            onsubmit="showLoadingModal()">
            @csrf
            @method('GET')

            <div class="min-w-[200px]">
                <label for="filterItem" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter Item</label>
                <select id="filterItem" name="filterItem" onchange="this.form.submit()" style="width: 100%;"
                    class="form-select select2 block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    <option value="{{ $filteredItem->id }}">{{ $filteredItem->nama }}</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </form>

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
                    @foreach ($itemPenawarans as $index => $itemPenawaran)
                        @if ($index == count($itemPenawarans) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $itemPenawaran[0]->item->nama }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $itemPenawaran[0]->suplier->nama . ' - ' . ($itemPenawaran[0]->suplier->cabang ? $itemPenawaran[0]->suplier->cabang->nama : $itemPenawaran[0]->suplier->wilayah) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $itemPenawaran[0]->satuan->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($itemPenawaran[0]->harga, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $itemPenawaran[0]->updated_at)[0])->format('d-m-Y') }}
                                </td>
                                @if ($loop->index == 0)
                                    <td class="px-6 py-4 font-bold uppercase text-orange-500">
                                        Termurah
                                    </td>
                                @else
                                    <td class="px-6 py-4">
                                        --
                                    </td>
                                @endif
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $itemPenawaran[0]->item->nama }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $itemPenawaran[0]->suplier->nama . ' - ' . ($itemPenawaran[0]->suplier->cabang ? $itemPenawaran[0]->suplier->cabang->nama : $itemPenawaran[0]->suplier->wilayah) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $itemPenawaran[0]->satuan->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    Rp {{ number_format($itemPenawaran[0]->harga, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse(explode(' ', $itemPenawaran[0]->updated_at)[0])->format('d-m-Y') }}
                                </td>
                                @if ($loop->index == 0)
                                    <td class="px-6 py-4 font-bold uppercase text-orange-500">
                                        Termurah
                                    </td>
                                @else
                                    <td class="px-6 py-4">
                                        --
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($itemPenawarans) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
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
