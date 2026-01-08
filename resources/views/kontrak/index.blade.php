<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>Non PO/Kontrak</x-page-title>

        <form action="/nonpo" method="GET" class="my-6 flex flex-col lg:flex-row flex-wrap gap-4 lg:items-end"
            onchange="showLoadingModal()">
            @csrf
            @method('GET')

            <div class="lg:w-[200px]">
                <label for="filterId" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By ID</label>
                <input id="filterId" name="filterId" onchange="this.form.submit()" type="number" placeholder="ID"
                    value="{{ $filterId }}"
                    class=" block w-full lg:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6" />
            </div>

            <div class="lg:w-[200px]">
                <label for="filterStatus" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Status</label>
                <select id="filterStatus" name="filterStatus" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6">
                    @if ($filterStatus)
                        <option selected hidden value="{{ $filterStatus }}">{{ Str::ucfirst($filterStatus) }}</option>
                    @else
                        <option value=""></option>
                    @endif

                    <option value="kontrak">Kontrak</option>
                    <option value="order">Order</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div class="lg:w-[200px]">
                <label for="filterSuplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Suplier</label>
                <select id="filterSuplier" name="filterSuplier" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6">

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

            <div class="lg:w-[200px]">
                <label for="filterGudang" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Gudang</label>
                <select id="filterGudang" name="filterGudang" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6">

                    @if ($filterGudang)
                        <option selected hidden value="{{ $filteredGudang[0]->id }}">{{ $filteredGudang[0]->nama }} -
                            {{ $filteredGudang[0]->cabang ? $filteredGudang[0]->cabang->nama : $filteredGudang[0]->wilayah }}
                        </option>
                    @else
                        <option value=""></option>
                    @endif

                    @foreach ($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama }} -
                            {{ $gudang->cabang ? $gudang->cabang->nama : $gudang->wilayah }}</option>
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
                <div class="lg:w-[200px]">
                    <label for="filterCabang" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                        Cabang Gudang </label>
                    <select id="filterCabang" name="filterCabang" onchange="this.form.submit()" style="width: 100%"
                        class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6">

                        @if ($filterCabang)
                            <option selected hidden value="{{ $filteredCabang->id }}">{{ $filteredCabang->nama }}
                            </option>
                        @else
                            <option value=""></option>
                        @endif

                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }} </option>
                        @endforeach

                    </select>
                </div>
            @endif

            <div class="lg:w-[200px]">
                <label for="periode-awal" class="block text-xs font-medium leading-6 text-[#099AA7]">Data Mulai
                    Tanggal (M/D/Y)</label>

                <input id="periode-awal" name="periode_awal" onchange="this.form.submit()" type="date"
                    value="{{ $periode_awal }}"
                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6">
                </input>
            </div>

            <div class="lg:w-[200px]">
                <label for="periode-akhir" class="block text-xs font-medium leading-6 text-[#099AA7]">Hingga
                    Tanggal (M/D/Y)</label>
                <input id="periode-akhir" name="periode_akhir" onchange="this.form.submit()" type="date"
                    value="{{ $periode_akhir }}"
                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:text-xs sm:leading-6">
                </input>
            </div>

            <a href="/harga"
                class="rounded-md bg-[#099AA7] ms-auto p-3 text-center text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]">Master
                Harga
            </a>
        </form>

        <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
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
                            Jenis
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
                    @foreach ($kontraks as $index => $kontrak)
                        @php
                            if ($kontrak->status == 'selesai') {
                                $warnaStatus = 'text-green-500';
                            } elseif ($kontrak->status == 'ditolak') {
                                $warnaStatus = 'text-red-500';
                            } elseif ($kontrak->status == 'order') {
                                $warnaStatus = 'text-yellow-500';
                            } else {
                                $warnaStatus = 'text-black';
                            }
                        @endphp

                        @if ($index == count($kontraks) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $kontrak->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $kontrak->user->nama . ' - ' . ($kontrak->user->cabang ? $kontrak->user->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kontrak->suplier->nama . ' - ' . ($kontrak->suplier->cabang ? $kontrak->suplier->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kontrak->gudang->nama . ' - ' . ($kontrak->gudang->cabang ? $kontrak->gudang->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kontrak->jenis->nama }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $kontrak->status }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/nonpo/{{ $kontrak->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $kontrak->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $kontrak->user->nama . ' - ' . ($kontrak->user->cabang ? $kontrak->user->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kontrak->suplier->nama . ' - ' . ($kontrak->suplier->cabang ? $kontrak->suplier->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kontrak->gudang->nama . ' - ' . ($kontrak->gudang->cabang ? $kontrak->gudang->cabang->nama : '') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kontrak->jenis->nama }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $kontrak->status }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/nonpo/{{ $kontrak->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($kontraks) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        <div class="my-2">
            {{ $kontraks->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- select2 --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an item",
                allowClear: true
            });
        });
    </script>
</x-layout>
