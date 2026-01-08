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

    <div>
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Detail User:
                {{ $user->nama }}</x-page-title>
        </div>

        <div class="rounded-lg shadow-lg p-4">
            <form action="/master-user/{{ $user->id }}" method="post" onsubmit="return submitUpdateConfirm()">
                @csrf
                @method('PATCH')

                <div class="w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    <div>
                        <label for="nama" class="block text-xs font-medium leading-6 text-[#099AA7]">Nama</label>
                        <input value="{{ $user->nama }}" type="text" id="nama" name="nama" required
                            class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                    </div>

                    <div>
                        <label for="telepon" class="block text-xs font-medium leading-6 text-[#099AA7]">Telepon</label>
                        <input value="{{ $user->telepon }}" type="text" id="telepon" name="telepon" required
                            class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                    </div>

                    <div>
                        <label for="cabang_id" class="block text-xs font-medium leading-6 text-[#099AA7]">
                            Cabang</label>
                        <select style="width: 100%" type="text" id="cabang_id" name="cabang_id" required
                            class="form-select select2 block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">

                            @if ($user->cabang_id)
                                <option value="{{ $user->cabang_id }}">{{ $user->cabang->nama }}</option>
                            @else
                                <option value=""></option>
                            @endif

                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_token" class="block text-xs font-medium leading-6 text-[#099AA7]">ID
                            Token</label>
                        <input value="{{ $user->id_token }}" type="text" id="id_token" name="id_token" required
                            class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-medium leading-6 text-[#099AA7]">Status</label>
                        <select id="status" name="status" required
                            class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                            <option value="{{ $user->status ? 'true' : 'false' }}" selected>
                                {{ $user->status ? 'Aktif' : 'Non-Aktif' }}
                            </option>
                            <option value="true">Aktif</option>
                            <option value="false">Non-Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label for="level" class="block text-xs font-medium leading-6 text-[#099AA7]">Level</label>
                        <select id="level" name="level" required
                            class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                            <option value="{{ $user->level }}" selected>{{ Str::ucfirst($user->level) }}</option>
                            <option value="admin">Super Admin</option>
                            <option value="qc">QC</option>
                            <option value="manager">Manager</option>
                            <option value="pembayaran">Pembayaran</option>
                            <option value="user">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-10">
                    <a href="/master-user"
                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                    <button type="submit"
                        class="min-w-[120px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Edit
                        User</button>
                </div>
            </form>
        </div>
    </div>

    <form action="/master-user/{{ $user->id }}" method="POST" id="delete-form" hidden>
        @csrf
        @method('DELETE')
    </form>

    <script>
        $(document).ready(function() {
            // Event listener untuk tombol hapus
            $('#btn-delete').click(function() {
                var id = $(this).data('id'); // Dapatkan id data
                var form = $('#delete-form'); // Dapatkan form berdasarkan id

                // Menampilkan konfirmasi sebelum menghapus
                var confirmed = confirm('Apakah Anda yakin ingin menghapus user ini?');

                // Cegah submit form jika pengguna membatalkan konfirmasi
                if (!confirmed) {
                    event.preventDefault(); // Mencegah pengiriman form jika dibatalkan
                } else {
                    showLoadingModal();

                    form.submit(); // Jika user menekan "OK", submit form
                }
            });
        });

        function submitUpdateConfirm() {
            event.preventDefault();

            konfirmasiUpdateData = confirm('Update data user?');

            if (konfirmasiUpdateData) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasiUpdateData;
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
