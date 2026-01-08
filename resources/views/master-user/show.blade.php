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

        <form action="/master-user/{{ $user->id }}" method="post" id="master-user-form">
            @csrf
            @method('PATCH')

            <div class="mt-4 space-y-4">
                <div class="rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-bold uppercase text-[#099AA7]">Edit Kredensial</p>
                    </div>
                    <div class="p-4">
                        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="username" class="block text-xs font-medium leading-6 text-[#099AA7]">Username</label>
                                <input value="{{ old('username', $user->username) }}" type="text" id="username" name="username" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                @error('username')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-medium leading-6 text-[#099AA7]">Email</label>
                                <input value="{{ old('email', $user->email) }}" type="email" id="email" name="email" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                @error('email')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="password" class="flex items-center gap-2 mb-2 text-xs font-medium leading-6 text-[#099AA7]">
                                    <span>Password</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-500">
                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm0-11a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm1 2a1 1 0 1 0-2 0v4a1 1 0 1 0 2 0V9Z" clip-rule="evenodd" />
                                        </svg>
                                        Opsional
                                    </span>
                                </label>
                                <input type="password" id="password" name="password"
                                    placeholder="Kosongkan jika tidak ingin mengganti password"
                                    class="block w-full rounded-md border-0 bg-gray-50 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                <p class="hidden text-xs text-gray-500 mt-1 group-focus-within:block">Isi hanya jika ingin mengganti password.</p>
                                @error('password')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="password_confirmation"
                                    class="flex items-center gap-2 mb-2 text-xs font-medium leading-6 text-[#099AA7]">
                                    <span>Konfirmasi Password</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-gray-500">
                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm0-11a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm1 2a1 1 0 1 0-2 0v4a1 1 0 1 0 2 0V9Z" clip-rule="evenodd" />
                                        </svg>
                                        Opsional
                                    </span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Ulangi hanya jika mengganti password"
                                    class="block w-full rounded-md border-0 bg-gray-50 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                <p class="hidden text-xs text-gray-500 mt-1 group-focus-within:block">Isi hanya jika ingin mengganti password.</p>
                            </div>

                            <div class="md:col-span-2">
                                <div class="rounded-md border border-blue-200 bg-blue-50 px-3 py-2 text-xs text-blue-800 leading-relaxed">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Password bersifat opsional.</li>
                                        <li>Jika password tidak diisi, maka password user tidak akan berubah.</li>
                                        <li>Jika password diisi, maka password user akan diperbarui.</li>
                                        <li>Password default sebelumnya adalah: 12345678.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-bold uppercase text-[#099AA7]">Data User</p>
                    </div>
                    <div class="p-4">
                        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-6">
                            <a href="/master-user"
                                class="w-full sm:w-auto min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                            <button type="submit"
                                class="w-full sm:w-auto min-w-[160px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Simpan
                                Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <form action="/master-user/{{ $user->id }}" method="POST" id="delete-form" hidden>
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('master-user-form');
            let isSubmitting = false;

            if (form) {
                form.addEventListener('submit', function(event) {
                    if (isSubmitting) {
                        return;
                    }

                    event.preventDefault();

                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan data user ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#099AA7',
                        cancelButtonColor: '#6b7280',
                        buttonsStyling: true,
                        customClass: {
                            confirmButton: 'swal2-confirm-btn',
                            cancelButton: 'swal2-cancel-btn',
                        },
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isSubmitting = true;
                            showLoadingModal();
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>

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
