<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div>
        <x-page-title>List Pre Order Suplier {{ $suplier->nama }}</x-page-title>

        <div class="mt-4 relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
            <table class="w-full text-sm text-center rtl:text-right text-[#099AA7] font-semibold ">
                <thead class="text-xs text-white uppercase bg-[#099AA7] ">
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
                    @foreach ($preorders as $index => $preorder)
                        @php
                            if ($preorder->status == 'dikirim' || $preorder->status == 'preorder') {
                                $warnaStatus = 'text-yellow-500';
                            } elseif ($preorder->status == 'diterima') {
                                $warnaStatus = 'text-green-500';
                            } elseif ($preorder->status == 'ditolak') {
                                $warnaStatus = 'text-red-500';
                            } elseif ($preorder->status == 'invalid') {
                                $warnaStatus = 'text-red-500';
                            } else {
                                $warnaStatus = 'text-black';
                            }
                        @endphp

                        @if ($index == count($preorders) - 1)
                            <tr>
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $preorder->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $preorder->user->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $preorder->suplier->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $preorder->jenis->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse($preorder->created_at)->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $preorder->status }}
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2">
                                    <a href="/preorder/{{ $preorder->id }}?token={{ $preorder->token }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @else
                            <tr class="odd:bg-white  even:bg-gray-50 border-b">
                                <th scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                    {{ $preorder->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $preorder->user->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $preorder->suplier->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $preorder->jenis->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ Carbon\Carbon::parse($preorder->created_at)->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 {{ $warnaStatus }} uppercase">
                                    {{ $preorder->status }}
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2">
                                    <a href="/preorder/{{ $preorder->id }}?token={{ $preorder->token }}"
                                        class="px-2 py-1 rounded-md hover:bg-[#099AA7] hover:text-white ease-out transition-colors duration-300 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if (count($preorders) <= 0)
                <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                    <p>Data Kosong!</p>
                </div>
            @endif
        </div>

        <div class="my-2">
            {{ $preorders->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</x-layout>
