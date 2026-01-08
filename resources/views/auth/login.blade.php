<x-layout>
    <div class="mx-auto px-4 py-6 w-[75%] sm:w-[50%] rounded-lg shadow-md">
        <h1 class="font-bold text-center text-xl">Login Admin</h1>
        <form action="/login" method="POST">
            {{-- @csrf --}}
            @method('POST')

            {{-- <div class="mt-10">
                <div class="flex flex-col">
                    <label for="id_user" class="font-bold">User ID</label>
                    <input type="text" name="id_user" id="id_user" placeholder="User ID"
                        class="mt-2 p-2 border border-black/20 active:border-black rounded-md" />
                </div>
            </div> --}}

            <div class="mt-4">
                <div class="flex flex-col">
                    <label for="token" class="font-bold">Token</label>
                    <input type="text" name="token" id="token" placeholder="User Token"
                        class="mt-2 p-2 border border-black/20 active:border-black rounded-md" />
                </div>
            </div>

            @error('token')
                <p class="text-red-500 mt-2">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="px-4 py-2 w-full text-white font-bold mt-4 bg-blue-500 hover:bg-blue-600 transition-color duration-300 rounded-md">Login</button>
        </form>
    </div>
</x-layout>
