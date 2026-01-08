<x-layout>
    <div class="mx-auto px-4 py-6 w-[90%] sm:w-[50%] max-w-md rounded-lg shadow-md">
        <h1 class="font-bold text-center text-xl">Login</h1>
        <form action="{{ route('login.attempt') }}" method="POST" class="mt-4">
            @csrf

            <div class="mt-4">
                <div class="flex flex-col">
                    <label for="login" class="font-bold">Username atau Email</label>
                    <input type="text" name="login" id="login" placeholder="username atau email"
                        value="{{ old('login') }}" autocomplete="username"
                        class="mt-2 p-2 border border-black/20 active:border-black rounded-md" required />
                </div>
                @error('login')
                    <p class="text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <div class="flex flex-col">
                    <label for="password" class="font-bold">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password"
                        autocomplete="current-password"
                        class="mt-2 p-2 border border-black/20 active:border-black rounded-md" required />
                </div>
                @error('password')
                    <p class="text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" value="1"
                    class="rounded border border-black/20" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="text-sm">Ingat saya</label>
            </div>

            <button type="submit"
                class="px-4 py-2 w-full text-white font-bold mt-4 bg-blue-500 hover:bg-blue-600 transition-color duration-300 rounded-md">Login</button>
        </form>
    </div>
</x-layout>
