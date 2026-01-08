<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    <div class="flex flex-wrap items-center justify-center w-full">
        <form class="my-10 w-full bg-white rounded-lg shadow-md p-2 md:p-10" method="POST" action="/pembayaran" onsubmit="return confirmUpdate(event)">
            @csrf
            @method('POST')

            <input type="number" value="{{ $pembayaran->user->id }}" name="user_id" id="user_id" class="hidden" />

            <div class="mx-4 my-4">
                <div class="border-b border-gray-900/10 pb-4">
                    <h2 class="flex justify-center items-center text-2xl font-bold leading-7 text-gray-900">Detail
                        Pembayaran {{ $pembayaran->kode }}</h2>

                    <div class="w-full flex flex-col gap-x-6 gap-y-2 mt-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2" id="input-group">
                            <div>
                                <label for="suplier"
                                    class="block text-sm font-medium leading-6 text-gray-900">Suplier</label>
                                <input id="suplier_id" name="suplier_id" disabled
                                    value="{{ $pembayaran->suplier->nama }}"
                                    class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </input>
                            </div>

                            <div>
                                <label for="gudang"
                                    class="block text-sm font-medium leading-6 text-gray-900">Gudang</label>
                                <input id="gudang_id" name="gudang_id" disabled
                                    value="{{ $pembayaran->gudang->nama . ' - ' . $pembayaran->gudang->telepon }}"
                                    class="block w-full rounded-md border-0 py-2.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                </input>
                            </div>
                            <div>
                                <label for="pemesan"
                                    class="block text-sm font-medium leading-6 text-gray-900">Pemesan</label>
                                <input id="pemesan" name="pemesan" value="{{ $pembayaran->user->nama }}" disabled
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </input>

                            </div>

                            <div>

                                <label for="periode"
                                    class="block text-sm font-medium leading-6 text-gray-900">Periode</label>
                                <input id="periode" name="periode" value="{{ $pembayaran->periode_tgl }} s/d {{ $pembayaran->sampai_tgl }}"
                                    disabled
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </input>
                            </div>

                            <div class="flex w-full items-end gap-2">
                                @if ($pembayaran->foto)
                                    <img id="gambar-upload"
                                        src="{{ asset('folder-image-truenas/' . $pembayaran->foto) }}"
                                        alt="Preview Gambar"
                                        class="h-[40px] max-w-[40px] rounded-md border border-black/20">

                                    <a id="image-link-upload"
                                        href="{{ asset('folder-image-truenas/' . $pembayaran->foto) }}" target="_blank"
                                        class="hover:underline text-blue-400 font-semibold py-3 whitespace-nowrap truncate">Lihat
                                        Gambar Bukti</a>
                                @endif
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full mt-6 bg-gray-200 shadow-md rounded-lg">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="py-3 px-6 text-left">Kode PO</th>
                                        <th class="py-3 px-6 text-left">Tanggal PO</th>
                                        <th class="py-3 px-6 text-left">Tanggal Selesai</th>
                                        <th class="py-3 px-6 text-left">Target Kirim</th>
                                        <th class="py-3 px-6 text-left">Jenis PO</th>
                                        <th class="py-3 px-6 text-left">Suplier</th>
                                        <th class="py-3 px-6 text-left">Gudang</th>
                                        <th class="py-3 px-6 text-left">Nilai</th>
                                        <th class="py-3 px-6 text-left">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @php
                                        $total = 0; // Inisialisasi variabel total
                                        $index = 0;
                                    @endphp
                                    @foreach ($pembayaran->orders as $order)
                                        <tr class="border-b border-gray-400">
                                            <td class="py-4 px-6">{{ $order->kode }}</td>
                                            <td class="py-4 px-6">{{ explode(' ', $order->created_at)[0] }}</td>
                                            <td class="py-4 px-6">{{ explode(' ', $order->updated_at)[0] }}
                                            </td>
                                            <td class="py-4 px-6">{{ $order->target_kirim }}</td>
                                            <td class="py-4 px-6">{{ $order->isKontrak ? 'Kontrak' : 'Penawaran' }}
                                            </td>
                                            <td class="py-4 px-6">{{ $order->suplier->nama }}</td>
                                            <td class="py-4 px-6">{{ $order->gudang->nama }}</td>
                                            <td class="py-4 px-6">Rp
                                                {{ number_format($order->total_biaya, 2, ',', '.') }} </td>
                                            <td class="py-4 px-6 space-x-2">
                                                <a href="{{ $order->link_token }}"
                                                    class="px-2 py-1 rounded-md hover:bg-blue-500 hover:text-white ease-out transition-colors duration-300 font-semibold">Lihat</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <input type="text" name="index" id="index" hidden
                                        value="{{ $pembayaran->total_tagihan }}" />
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-4">
                <p class="flex items-center mt-2 text-lg font-bold">Total: <input
                        value="{{ $pembayaran->total_tagihan }}" id="total-keseluruhan" type="text"
                        name="total_biaya" class="bg-white border border-transparent" disabled></p>
            </div>

            @if ($pembayaran->status == 'pendanaan')
                <p class="text-center text-green-500 text-lg font-bold">Terima kasih atas konfirmasinya</p>
            @endif

            <div
                class="w-full mt-6 mb-4 px-4 flex flex-col gap-y-4 sm:gap-y-0 sm:flex-row items-center justify-end gap-x-6">
                <div class="flex w-full gap-x-2 justify-between md:justify-end">
                    <a href="/pembayaran-pdf/{{ $pembayaran->id }}" target="_blank"
                        class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Cetak
                        PDF</a>
                    @if ($pembayaran->status == 'proses')
                        <button type="submit" form="terima-form"
                            class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Konfirmasi
                            pembayaran</button>
                    @endif
                </div>


            </div>
        </form>
    </div>

    <form method="POST" action="/pembayaran/{{ $pembayaran->id }}?token={{ $pembayaran->token }}" id="terima-form"
        class="hidden" onsubmit="return pembayaranDikonfirmasi(event)">
        @csrf
        @method('PATCH')

        <input type="text" hidden name="status" id="status" value="diterima">
    </form>

    <script>
        function confirmUpdate(event) {
            event.preventDefault();

            konfirmasiUpdate = confirm('Update data pembayaran?');

            if (konfirmasiUpdate) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiUpdate;
        }

        function pembayaranDikonfirmasi(event) {
            event.preventDefault();

            konfirmasiSuplier = confirm('Konfirmasi pembayaran ini?');

            if (konfirmasiSuplier) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiSuplier;
        }
    </script>
</x-layout>
