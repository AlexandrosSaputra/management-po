<x-layout>
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

            <x-page-title>Buat Data Pembayaran</x-page-title>
        </div>

        <div class="rounded-lg shadow-lg p-4">
            <form method="POST" action="/pembayaran" enctype="multipart/form-data"
                onsubmit="return buatPembayaranForm(event)">
                @csrf
                @method('POST')

                <div class="w-full flex flex-col gap-2">
                    <div>
                        <div class="w-full flex justify-center items-center gap-2">
                            <div class="w-full border border-[#099AA7]"></div>
                            <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                            <div class="w-full border border-t border-[#099AA7]"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2" id="input-group">
                            <div class="w-full">
                                <label for="suplier"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Suplier</label>
                                <select id="suplier_id" name="suplier_id" onchange="selectChange()"
                                    form="form-change-select" style="width: 100%" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6">
                                    @if ($selectedSuplierId != null)
                                        <option hidden value="{{ $selectedSuplierId }}" selected="selected">
                                            {{ $supliers->where('id', $selectedSuplierId)->first()->nama }}</option>
                                    @else
                                        <option value=""></option>
                                    @endif

                                    @foreach ($supliers as $suplier)
                                        <option value={{ $suplier->id }}>{{ $suplier->nama }}</option>
                                    @endforeach

                                </select>
                                @if ($selectedSuplierId != null)
                                    <input value="{{ $selectedSuplierId }}" name="selected_suplier_id" hidden
                                        id="selected_suplier_id" />
                                @else
                                    <input value="{{ $supliers->first()->id }}" name="selected_suplier_id" hidden
                                        id="selected_suplier_id" />
                                @endif
                            </div>

                            <div class="w-full">
                                <label for="gudang"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Gudang</label>
                                <select id="gudang_id" name="gudang_id" onchange="selectChange()"
                                    form="form-change-select" style="width: 100%" required
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6">
                                    @if ($selectedGudangId != null)
                                        <option value="{{ $selectedGudangId }}" selected="selected">
                                            {{ $gudangs->where('id', $selectedGudangId)->first()->nama }}</option>
                                    @else
                                        <option value=""></option>
                                    @endif

                                    @foreach ($gudangs as $gudang)
                                        <option value={{ $gudang->id }}>{{ $gudang->nama }}</option>
                                    @endforeach
                                </select>
                                @if ($selectedGudangId != null)
                                    <input value="{{ $selectedGudangId }}" name="selected_gudang_id" hidden
                                        id="selected_gudang_id" />
                                @endif
                            </div>

                            <div>
                                <label for="pemesan"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Pemesan</label>
                                <input id="pemesan" name="pemesan" value="{{ $user->nama }}" disabled
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6">
                                </input>

                            </div>

                            <div class="w-full">
                                <label for="kasir_id" class="block text-xs font-medium leading-6 text-[#099AA7]">Pilih
                                    Kasir <span class="text-xs font-semibold italic text-yellow-500">*wajib pilih
                                        kasir</span></label>
                                <select id="kasir_id" name="kasir_id" required style="width: 100%"
                                    class="form-select select2 block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6">

                                    <option value=""></option>
                                    @foreach ($kasirs as $kasir)
                                        <option value="{{ $kasir->id }}">{{ $kasir->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <div class="w-full">
                                    <label for="periode-awal"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Periode
                                        Awal (M/D/Y)</label>

                                    <input id="periode-awal" name="periode_awal" form="form-change-select"
                                        onchange="selectChange()" type="date" value="{{ $periode_awal }}"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    </input>
                                </div>

                                <input type="text" hidden id="periode_awal" name="periode_awal"
                                    value="{{ $periode_awal }}">
                            </div>

                            <div>
                                <div class="w-full">
                                    <label for="periode-akhir"
                                        class="block text-xs font-medium leading-6 text-[#099AA7]">Periode
                                        Akhir (M/D/Y)</label>
                                    <input id="periode-akhir" name="periode_akhir" form="form-change-select"
                                        onchange="selectChange()" type="date" value="{{ $periode_akhir }}"
                                        class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    </input>
                                </div>

                                <input type="text" hidden id="periode_akhir" name="periode_akhir"
                                    value="{{ $periode_akhir }}">
                            </div>

                            <div class="w-full">
                                <label for="cabangDana"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Pilih
                                    Cabang <span class="text-xs font-semibold italic text-yellow-500">*wajib pilih
                                        Cabang</span></label>
                                <select id="cabangDana" name="cabang" style="width:100%" required
                                    class="form-select select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value=""></option>
                                    @foreach ($cabangsDana as $cabangDana)
                                        <option value="{{ $cabangDana->id_cabang }}">{{ $cabangDana->cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full">
                                <label for="divisiDana"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Pilih
                                    Divisi <span class="text-xs font-semibold italic text-yellow-500">*wajib pilih
                                        Divisi</span></label>
                                <select id="divisiDana" name="divisi" style="width:100%" required
                                    class="form-select select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value=""></option>
                                    @foreach ($divisisDana as $divisiDana)
                                        <option value="{{ $divisiDana->program_pusat_id }}">{{ $divisiDana->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full">
                                <label for="judulDana"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Pilih
                                    Judul <span class="text-xs font-semibold italic text-yellow-500">*wajib pilih
                                        Judul</span></label>
                                <select id="judulDana" name="judul" style="width:100%" required
                                    class="form-select select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value=""></option>
                                    @foreach ($judulsDana as $judulDana)
                                        <option value="{{ $judulDana->id_keg }}">{{ $judulDana->kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="foto"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Upload
                                    Foto Bukti <span class="text-xs text-black/40 italic">*opsional</span></label>
                                <input type="file" accept="image/*" id="foto" name="foto"
                                    value="{{ $user->id_cabang }}"
                                    onchange="imageInput(event, 'preview', 'image-link')"
                                    class="upload block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                </input>

                            </div>

                            <div class="flex w-full items-end gap-2">
                                <img id="preview" src="path/to/your/image.jpg" alt="Preview Gambar"
                                    class="h-[40px] max-w-[40px] rounded-md border border-black/20 hidden">

                                <a id="image-link" href="#" target="_blank"
                                    class="hover:underline text-blue-400 font-semibold hidden py-3 whitespace-nowrap truncate"></a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 w-full flex justify-center items-center gap-2">
                        <div class="w-full border border-[#099AA7]"></div>
                        <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">List PO</p>
                        <div class="w-full border border-t border-[#099AA7]"></div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex items-center bg-black/10 p-1 rounded-md">
                            <input id="check-all-checkbox" type="checkbox" onchange="checkAll(this)"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        </div>

                        <label class="font-semibold text-md" for="check-all-checkbox">Pilih Semua Order</label>
                    </div>

                    <div class="relative overflow-x-auto shadow-lg rounded-md sm:rounded-lg">
                        <table class="mt-4 w-full text-xs text-center rtl:text-right text-[#099AA7] font-semibold ">
                            <thead class="text-2xs text-white uppercase bg-[#099AA7] ">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        No.
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Pilih PO
                                    </th>
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
                                @foreach ($orders as $index => $order)
                                    @if ($index == count($orders) - 1)
                                        <tr>
                                            <td class="px-6 py-4 font-semibold">
                                                {{ $loop->index+1 }}
                                            </td>
                                            <th class="py-4 px-6"><input id="default-checkbox-{{ $index }}"
                                                    type="checkbox" value="{{ $order->id }}" name="check_po[]"
                                                    onchange="hitungTotal(this, {{ $loop->index }}, {{ floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0) }})"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            </th>
                                            <td scope="row" class="px-6 py-4 font-bold whitespace-nowrap">
                                                {{ $order->kode }}
                                            </td>
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
                                            <td class="px-6 py-4 font-semibold">
                                                {{ $loop->index+1 }}
                                            </td>
                                            <th class="py-4 px-6"><input id="default-checkbox-{{ $index }}"
                                                    type="checkbox" value="{{ $order->id }}" name="check_po[]"
                                                    onchange="hitungTotal(this, {{ $loop->index }}, {{ floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0) }})"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            </th>
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
                        @if (count($orders) <= 0)
                            <div class="flex justify-center my-4 text-yellow-400 font-semibold text-md">
                                <p>Data Kosong!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mx-4">
                    <p class="flex items-center mt-2 text-lg font-bold">Total: <input id="total-keseluruhan"
                            type="text" name="total_biaya"
                            class="format-rupiah w-full bg-white border border-transparent" readonly>
                    </p>
                </div>

                <div class="w-full mt-6 mb-4 px-4 flex gap-y-4 items-end justify-between gap-x-6">
                    <a href="/pembayaran"
                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                    <div>
                        @if ($index == 0)
                            <p class="text-red-500 text-md text-center mb-2 font-bold">PO Kosong!</p>
                        @else
                            <button type="submit" @if ($index == 0) disabled @endif
                                class="rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                                Pembayaran</button>
                        @endif
                    </div>

                </div>
            </form>
        </div>

        <form method="POST" action="/pembayaran/create" id="form-change-select" onchange="showLoadingModal()">
            @csrf
            @method('GET')


        </form>
    </div>

    <script>
        function buatPembayaranForm(event) {
            event.preventDefault();

            var kasirSelect = document.getElementById('kasir_id');
            var kasirSelectIndex = kasirSelect.selectedIndex;
            var kasirSelectOption = kasirSelect.options[kasirSelectIndex];
            var kasirSelectValue = kasirSelectOption.value;
            var kasirSelectInnerHTML = kasirSelectOption.innerHTML;

            console.log(kasirSelectValue);
            console.log(kasirSelectInnerHTML);

            konfirmasi = confirm(`Buat data pembayaran dengan kasir ${kasirSelectInnerHTML}?`);

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>

    <script>
        let total_tagihan = 0.00;
        const data = @json($orders);

        function checkAll(checkBox) {
            total_tagihan = 0.00;
            for (let i = 0; i < data.length; i++) {

                let itemCheckbox = document.getElementById(`default-checkbox-${i}`);

                if (itemCheckbox) itemCheckbox.checked = checkBox.checked;

                if (checkBox.checked) {
                    total_tagihan += parseFloat(data[i].total_biaya) - parseFloat(data[i].dp_1 ?? 0) - parseFloat(data[i]
                        .dp_2 ?? 0);
                }
            }
            document.getElementById('total-keseluruhan').value = total_tagihan.toFixed(2);
        }
    </script>

    <script>
        function pembayaranConfirm() {
            return confirm('Buat pembayaran?');
        }
    </script>

    <script>
        function hitungTotal(checkbox, index, total_biaya) {
            if (checkbox.checked) {
                total_tagihan += total_biaya;
            } else {
                total_tagihan -= total_biaya;
            }

            document.getElementById('total-keseluruhan').value = total_tagihan.toFixed(2);
        }

        function selectChange() {
            showLoadingModal();
            document.getElementById("form-change-select").submit();
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

    <script>
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
                link.textContent = file.name;
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
