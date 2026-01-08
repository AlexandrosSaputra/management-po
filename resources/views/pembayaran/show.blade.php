<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    @session('errorMessage')
        <script type="text/javascript">
            toastr.error("{{ session('errorMessage') }}");
        </script>
    @endsession

    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

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
            <form method="POST" action="/pembayaran/{{ $pembayaran->id }}" enctype="multipart/form-data"
                onsubmit="return confirmUpdate(event)">
                @csrf
                @method('PATCH')

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
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier</label>
                                <input id="suplier" name="suplier" disabled value="{{ $pembayaran->suplier->nama }}"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                </input>
                            </div>

                            <div>
                                <label for="gudang"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Gudang</label>
                                <input id="gudang" name="gudang" disabled
                                    value="{{ $pembayaran->gudang ? $pembayaran->gudang->nama . ' - ' . $pembayaran->gudang->telepon : '--' }}"
                                    class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                </input>
                            </div>

                            <div>
                                <label for="pemesan"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Pemesan</label>
                                <input id="pemesan" name="pemesan" value="{{ $pembayaran->user->nama }}" disabled
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                </input>

                            </div>

                            <div>

                                <label for="periode"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Periode</label>
                                <input id="periode" name="periode"
                                    value="{{ $pembayaran->periode_tgl }} s/d {{ $pembayaran->sampai_tgl }}" disabled
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                </input>
                            </div>

                            <div>

                                <label for="kasir_id"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Kasir</label>
                                <input id="kasir_id" name="kasir_id"
                                    value="{{ $pembayaran->user->where('id', $pembayaran->kasir_id)->first()->nama }}"
                                    disabled
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                </input>
                            </div>

                            @if (Auth::user()->level == 'pembayaran')
                                <div>
                                    <label for="tipe_pembayaran_id"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Pilih
                                        Tipe Pembayaran <span
                                            class="text-xs font-semibold italic text-yellow-500">*wajib
                                            saat data
                                            dibayar</span></label>
                                    <select id="tipe_pembayaran_id" name="tipe_pembayaran_id" required form="bayar-form"
                                        class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                                        @foreach ($tipePembayarans as $tipePembayaran)
                                            <option value="{{ $tipePembayaran->id }}">
                                                {{ $tipePembayaran->nama . ' (' . $tipePembayaran->norek . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (Auth::user()->level == 'pembayaran')
                                <div>
                                    <label for="tgl_bayar"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Tanggal
                                        Bayar <span class="text-xs font-semibold italic text-yellow-500">*wajib saat
                                            data
                                            dibayar</span></label>
                                    <input id="tgl_bayar" name="tgl_bayar" required form="bayar-form" type="date"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                </div>
                            @endif

                            @if ($pembayaran->status == 'proses')
                                <div>
                                    <label for="foto"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Upload
                                        Foto Bukti <span
                                            class="text-green-500 italic text-xs">*updateable</span></label>
                                    <input type="file" accept="image/*" id="foto" name="foto"
                                        onchange="imageInput(event, 'preview', 'image-link')"
                                        {{ $pembayaran->status == 'proses' ? '' : 'disabled' }}
                                        class="upload block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    </input>

                                </div>
                            @endif

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
                                            href="{{ asset('folder-image-truenas/' . $pembayaran->foto) }}"
                                            target="_blank"
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
                                        $total +=
                                            floatval($order->total_biaya) -
                                            floatval($order->dp_1 ?? 0) -
                                            floatval($order->dp_2 ?? 0); // Menambahkan setiap nilai ke total
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
                            name="total_biaya" class="format-rupiah w-full bg-white border border-transparent"
                            disabled>
                    </p>
                </div>

                <div class="mx-4 w-full flex flex-wrap gap-1">
                    @foreach ($countItemPenawaran as $item)
                        <div class="px-2 py-1 rounded-xl bg-[#099AA7] text-white text-xs font-semibold">
                            <p>{{ $item['nama'] }}: <span>{{ $item['jumlah'] }} {{ $item['satuan'] }}</span></p>
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
                    <a href="/pembayaran"
                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                    <div class="flex flex-col md:flex-row gap-2">
                        @if ($index == 0)
                            <p class="text-red-500 text-xs text-center mb-2 font-semibold">PO Kosong!</p>
                        @endif

                        <a href="/pembayaran-pdf/{{ $pembayaran->id }}" target="_blank"
                            class="rounded-md min-w-[100px] text-center bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Cetak
                            PDF</a>

                        @if ($pembayaran->status == 'proses' || $pembayaran->status == 'diterima')
                            @if (Auth::user()->id == $pembayaran->user_id)
                                <button type="submit" form="cancel-form"
                                    class="rounded-md min-w-[100px] bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cancel</button>
                                <button type="submit"
                                    class="rounded-md min-w-[100px] bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Update</button>
                            @endif

                            @if (Auth::user()->level == 'pembayaran' && Auth::user()->id == $pembayaran->kasir_id)
                                <button type="submit" form="bayar-form"
                                    class="rounded-md min-w-[100px] bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Sudah
                                    Dibayar</button>
                            @endif
                        @endif

                    </div>

                </div>

            </form>
        </div>

        <form action="/arsip/{{ $pembayaran->id }}" method="POST" hidden id="bayar-form"
            onsubmit="return pembayaranDibayar(event)">
            @csrf
            @method('POST')
        </form>

        <form action="/pembayaran/{{ $pembayaran->id }}" method="POST" hidden id="cancel-form"
            onsubmit="return pembayaranDicancel(event)">
            @csrf
            @method('DELETE')


        </form>
    </div>

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

        function pembayaranDibayar(event) {
            event.preventDefault();

            konfirmasiBayar = confirm('Pembayaran ini sudah di bayar?');

            if (konfirmasiBayar) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiBayar;
        }

        function imageInput(event, previewId, linkId) {
            const file = event.target.files[0];
            if (file) {
                var output = document.getElementById(previewId);
                var link = document.getElementById(linkId);
                // Membuat URL gambar yang diunggah
                const imageUrl = URL.createObjectURL(file);

                // Menampilkan gambar di halaman
                output.src = imageUrl;
                output.style.display = 'block';

                // Mengatur link untuk membuka gambar
                link.href = imageUrl;
                link.textContent = 'Foto baru: ' + file.name;
                link.style.display = 'block';

                // compress gambar
                compressImage(event, previewId);
            }
        }

        function compressImage(event, itemId) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.readAsDataURL(file);

            reader.onload = (e) => {
                const img = new Image();
                img.src = e.target.result;

                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Resize settings
                    const MAX_WIDTH = 800;
                    const MAX_HEIGHT = 800;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    // Set canvas dimensions and compress image
                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);

                    // Compress to 70% quality
                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: "image/jpeg",
                            lastModified: Date.now()
                        });

                        // Update the file input with compressed file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        event.target.files = dataTransfer.files;

                        // Display compressed image preview
                        const imgPreview = document.getElementById(`compressedImage_${itemId}`);
                        imgPreview.src = URL.createObjectURL(compressedFile);
                        imgPreview.style.display = "block";
                    }, 'image/jpeg', 0.7);
                };
            };
        }
    </script>
</x-layout>
