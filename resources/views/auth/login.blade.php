<x-layout>
    <div class="min-h-[calc(100vh-64px)] pb-20 flex items-center justify-center px-4">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow">
            <h1 class="text-center text-xl font-bold text-gray-900">Login</h1>

            <form action="{{ route('login.attempt') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="login" class="mb-2 block text-sm font-semibold text-gray-900">Username atau Email</label>
                    <input type="text" name="login" id="login" placeholder="username atau email"
                        value="{{ old('login') }}" autocomplete="username"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-[#099AA7] focus:ring-[#099AA7]"
                        required />
                    @error('login')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-gray-900">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password"
                        autocomplete="current-password"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-[#099AA7] focus:ring-[#099AA7]"
                        required />
                    @error('password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-[#099AA7] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#087f8a] focus:outline-none focus:ring-4 focus:ring-[#099AA7]/30">
                    Login
                </button>
            </form>
        </div>
    </div>
</x-layout>
