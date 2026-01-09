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

            <x-page-title>Tambah User</x-page-title>
        </div>

        <form action="/master-user" method="post" onsubmit="showLoadingModal()">
            @csrf

            <div class="mt-4 space-y-4">
                <div class="rounded-lg shadow-lg overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-bold uppercase text-[#099AA7]">Kredensial</p>
                    </div>
                    <div class="p-4">
                        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="username" class="block text-xs font-medium leading-6 text-[#099AA7]">Username</label>
                                <input value="{{ old('username') }}" type="text" id="username" name="username" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                @error('username')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-medium leading-6 text-[#099AA7]">Email</label>
                                <input value="{{ old('email') }}" type="email" id="email" name="email" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                @error('email')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-medium leading-6 text-[#099AA7]">Password</label>
                                <input type="password" id="password" name="password" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                                @error('password')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-xs font-medium leading-6 text-[#099AA7]">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
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
                                <input value="{{ old('nama') }}" type="text" id="nama" name="nama" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="telepon" class="block text-xs font-medium leading-6 text-[#099AA7]">Telepon</label>
                                <input value="{{ old('telepon') }}" type="text" id="telepon" name="telepon" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                            </div>

                            <div>
                                <label for="cabang_id" class="block text-xs font-medium leading-6 text-[#099AA7]">
                                    Cabang</label>
                                <select style="width: 100%" type="text" id="cabang_id" name="cabang_id" required
                                    class="form-select select2 block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    <option value="">Pilih cabang</option>
                                    @foreach ($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-xs font-medium leading-6 text-[#099AA7]">Status</label>
                                <select id="status" name="status" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    <option value="true" {{ old('status', 'true') == 'true' ? 'selected' : '' }}>Aktif</option>
                                    <option value="false" {{ old('status') == 'false' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>

                            <div>
                                <label for="level" class="block text-xs font-medium leading-6 text-[#099AA7]">Level</label>
                                <select id="level" name="level" required
                                    class="block w-full rounded-md border-0 py-2 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6">
                                    <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="qc" {{ old('level') == 'qc' ? 'selected' : '' }}>QC</option>
                                    <option value="manager" {{ old('level') == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="pembayaran" {{ old('level') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                                    <option value="user" {{ old('level', 'user') == 'user' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-6">
                            <a href="/master-user"
                                class="w-full sm:w-auto min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>

                            <button type="submit"
                                class="w-full sm:w-auto min-w-[160px] text-center rounded-md bg-yellow-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">Simpan
                                User</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select an item",
                allowClear: true
            });
        });
    </script>
</x-layout>
