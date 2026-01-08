<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>List Harga Item Kontrak/Non PO</x-page-title>

        <form action="/harga" method="GET" class="my-6 flex flex-col md:flex-row gap-4 md:items-end"
            onchange="showLoadingModal()">
            @csrf
            @method('GET')

            <div>
                <label for="filterId" class="block text-xs font-medium leading-6 text-[#099AA7]">Search By ID</label>
                <input id="filterId" name="filterId" onchange="this.form.submit()" type="number" placeholder="ID"
                    value="{{ $filterId }}"
                    class=" block w-full md:min-w-[200px] rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
            </div>

            <div class="w-full md:w-[200px]">
                <label for="filterStatus" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Status</label>
                <select id="filterStatus" name="filterStatus" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterStatus == 'tidakaktif')
                        <option selected hidden value="{{ $filterStatus }}">Tidak Aktif</option>
                    @else
                        <option selected hidden value="{{ $filterStatus }}">{{ Str::ucfirst($filterStatus) }}</option>
                    @endif

                    <option value="all">All</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidakaktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="w-full md:w-[200px]">
                <label for="filterSuplier" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Suplier</label>
                <select id="filterSuplier" name="filterSuplier" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterSuplier != 'all')
                        <option selected hidden value="{{ $filteredSuplier->id }}">{{ $filteredSuplier->nama }} -
                            {{ $filteredSuplier->cabang ? $filteredSuplier->cabang->nama : $filteredSuplier->wilayah }}
                        </option>
                    @endif

                    <option value="all">All</option>
                    @foreach ($supliers as $suplier)
                        <option value="{{ $suplier->id }}">{{ $suplier->nama }} -
                            {{ $suplier->cabang ? $suplier->cabang->nama : $suplier->wilayah }}</option>
                    @endforeach

                </select>
            </div>

            <div class="w-full md:w-[200px]">
                <label for="filterJenis" class="block text-xs font-medium leading-6 text-[#099AA7]">Filter
                    Jenis</label>
                <select id="filterJenis" name="filterJenis" onchange="this.form.submit()" style="width: 100%"
                    class="form-select select2 block rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                    @if ($filterJenis != 'all')
                        <option selected value="{{ $filteredJenis->id }}">
                            {{ $filteredJenis->nama }}</option>
                    @endif

                    <option value="all">All</option>
                    @foreach ($jenises as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama }} - {{ $jenis->kode }}</option>
                    @endforeach

                </select>
            </div>

            <a href="/harga/create"
                class="rounded-md bg-[#099AA7] ms-auto p-3 text-center text-xs font-semibold text-white shadow-sm hover:bg-[#099AA7]/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#099AA7]">Tambah
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
                    @foreach ($hargas as $index => $harga)
                        @php
                            if ($harga->status == 'selesai') {
                                $warnaStatus = 'text-green-500';
                            } elseif ($harga->status == 'ditolak') {
                                $warnaStatus = 'text-red-500';
                            } elseif ($harga->status == 'order') {
                                $warnaStatus = 'text-yellow-500';
                            } else {
                                $warnaStatus = 'text-black';
                            }
                        @endphp

                        @if ($index == count($hargas) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $harga->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $harga->user->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $harga->suplier->nama . ' - ' . $harga->suplier->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $harga->jenis->nama }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $harga->status }}
                                </td>
                                <td class="px-6 py-4 flex gap-2 justify-center">
                                    <a href="/harga/{{ $harga->id }}"
                                        class="px-2 py-1 rounded-md bg-blue-500 hover:bg-blue-700 text-white ease-out transition-colors duration-300 font-semibold"><svg
                                            class="w-4 h-4 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                        </svg>
                                    </a>

                                    <form action="/harga/duplicate/{{ $harga->id }}" method="POST"
                                        onsubmit="return duplikatHargaForm(event)">
                                        @csrf
                                        @method('POST')

                                        <button type="submit"
                                            class="flex px-2 py-1 rounded-md bg-green-500 hover:bg-green-700 text-white ease-out transition-colors duration-300 font-semibold"><svg
                                                class="w-4 h-4 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                                                    clip-rule="evenodd" />
                                                <path fill-rule="evenodd"
                                                    d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="/harga/{{ $harga->id }}" method="POST"
                                        onsubmit="return hapusHargaForm(event)">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="flex px-2 py-1 rounded-md bg-red-500 hover:bg-red-700 text-white ease-out transition-colors duration-300 font-semibold"><svg
                                                class="w-4 h-4 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $harga->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $harga->user->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $harga->suplier->nama . ' - ' . $harga->suplier->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $harga->jenis->nama }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $harga->status }}
                                </td>
                                <td class="px-6 py-4 flex gap-2 justify-center">
                                    <a href="/harga/{{ $harga->id }}"
                                        class="px-2 py-1 rounded-md bg-blue-500 hover:bg-blue-700 text-white ease-out transition-colors duration-300 font-semibold"><svg
                                            class="w-4 h-4 text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                        </svg>
                                    </a>

                                    <form action="/harga/duplicate/{{ $harga->id }}" method="POST"
                                        onsubmit="return duplikatHargaForm(event)">
                                        @csrf
                                        @method('POST')

                                        <button type="submit"
                                            class="flex px-2 py-1 rounded-md bg-green-500 hover:bg-green-700 text-white ease-out transition-colors duration-300 font-semibold"><svg
                                                class="w-4 h-4 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M18 3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1V9a4 4 0 0 0-4-4h-3a1.99 1.99 0 0 0-1 .267V5a2 2 0 0 1 2-2h7Z"
                                                    clip-rule="evenodd" />
                                                <path fill-rule="evenodd"
                                                    d="M8 7.054V11H4.2a2 2 0 0 1 .281-.432l2.46-2.87A2 2 0 0 1 8 7.054ZM10 7v4a2 2 0 0 1-2 2H4v6a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="/harga/{{ $harga->id }}" method="POST"
                                        onsubmit="return hapusHargaForm(event)">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="flex px-2 py-1 rounded-md bg-red-500 hover:bg-red-700 text-white ease-out transition-colors duration-300 font-semibold"><svg
                                                class="w-4 h-4 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($hargas) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        <div class="my-2">
            {{ $hargas->appends(request()->query())->links() }}
        </div>

    </div>

    <script>
        function hapusHargaForm(event) {
            event.preventDefault();
            let konfirmasiHapus = confirm('Hapus data harga?');
            if (konfirmasiHapus) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiHapus;
        }

        function duplikatHargaForm(event) {
            event.preventDefault();
            let konfirmasiDuplikat = confirm('Duplikat data harga?');
            if (konfirmasiDuplikat) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiDuplikat;
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
