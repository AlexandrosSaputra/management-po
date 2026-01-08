<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>List Suplier</x-page-title>

        <form action="/suplier" method="GET" class="my-6 flex flex-col md:flex-row gap-4 items-end"
            onchange="showLoadingModal()">
            @csrf
            @method('GET')

            <div>
                <label for="filterId" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By ID</label>
                <input id="filterId" name="filterId" onchange="this.form.submit()" type="number" placeholder="ID"
                    value="{{ $filterId }}"
                    class=" block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
            </div>

            <div>
                <label for="filterNama" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By
                    Nama</label>
                <input id="filterNama" name="filterNama" onchange="this.form.submit()" type="text" placeholder="Nama"
                    value="{{ $filterNama }}"
                    class=" block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
            </div>

            <div>
                <label for="filterTelepon" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By
                    Telepon</label>
                <input id="filterTelepon" name="filterTelepon" onchange="this.form.submit()" type="text"
                    placeholder="Telepon" value="{{ $filterTelepon }}"
                    class=" block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
            </div>

            <div class="w-[200px]">
                <label for="filterCabang" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By
                    Cabang</label>

                <select id="filterCabang" name="filterCabang" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterCabang)
                        <option value="{{ $filteredCabang->id }}">{{ $filteredCabang->nama }}</option>
                    @else
                        <option value=""></option>
                    @endif

                    @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                    @endforeach
                </select>

            </div>

            @if (Auth::user()->level == 'admin')
                <a href="/suplier/create"
                    class="min-w-[120px] text-center rounded-md bg-[#099AA7] ms-auto px-3 py-3 text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]">Tambah
                    Data Suplier</a>
            @endif
        </form>

        <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cabang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Telepon
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($supliers as $index => $suplier)
                        @if ($index == count($supliers) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $suplier->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $suplier->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $suplier->cabang ? $suplier->cabang->nama : $suplier->wilayah }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $suplier->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/suplier/{{ $suplier->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $suplier->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $suplier->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $suplier->cabang ? $suplier->cabang->nama : $suplier->wilayah }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $suplier->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/suplier/{{ $suplier->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($supliers) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        <div class="my-2">
            {{ $supliers->appends(request()->query())->links() }}
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
