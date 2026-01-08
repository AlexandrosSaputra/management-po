<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>List User</x-page-title>

        <form action="/master-user" method="GET" class="my-6 flex flex-col md:flex-row gap-4"
            onsubmit="showLoadingModal()">
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
        </form>

        <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Telepon
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cabang
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Level
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $index => $user)
                        @php
                            if ($user->level == 'admin') {
                                $levelText = 'Super Admin';
                                $warnaText = 'text-red-500';
                            } elseif ($user->level == 'user') {
                                $levelText = 'Admin';
                                $warnaText = 'text-green-500';
                            } elseif ($user->level == 'pembayaran') {
                                $levelText = 'Pembayaran/Kasir';
                                $warnaText = 'text-orange-500';
                            } else {
                                $levelText = $user->level;
                                $warnaText = 'text-orange-500';
                            }
                        @endphp
                        @if ($index == count($users) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $user->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ Str::ucfirst($user->nama) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->cabang_id ? $user->cabang->nama : '--' }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaText }} uppercase">
                                    {{ $levelText }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/master-user/{{ $user->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $user->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ Str::ucfirst($user->nama) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->telepon }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->cabang_id ? $user->cabang->nama : '--' }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaText }} uppercase">
                                    {{ $levelText }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/master-user/{{ $user->id }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($users) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        <div class="my-2">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</x-layout>
