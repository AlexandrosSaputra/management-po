<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>List Jenis</x-page-title>

        <form action="/jenis" method="GET" class="my-6 flex flex-col md:flex-row gap-4 md:items-end"
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

            @if (Auth::user()->level == 'admin')
                <a href="/jenis/create"
                    class="min-w-[120px] text-center rounded-md bg-[#099AA7] ms-auto px-3 py-3 text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]">Tambah
                    Data Jenis</a>
            @endif
        </form>

        <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        <th scope="col" class="px-6 py-3">
                            Kode
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jenis Stok
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($jenises as $index => $jenis)
                        @if ($index == count($jenises) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $jenis->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $jenis->kode }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $jenis->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $jenis->isStokable ? 'Ya' : 'Tidak' }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/jenis/{{ $jenis->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $jenis->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $jenis->kode }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $jenis->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $jenis->isStokable ? 'Ya' : 'Tidak' }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/jenis/{{ $jenis->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($jenises) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        <div class="my-2">
            {{ $jenises->appends(request()->query())->links() }}
        </div>

    </div>
</x-layout>
