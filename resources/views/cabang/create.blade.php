<x-layout>
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            toastr.error("{{ $error }}");
        </script>
    @endforeach

    <div >
        <div class="flex items-center gap-2">
            <a href="javascript:window.history.back()" class="text-[#099AA7] hover:text-[#099AA7]/80 p-1 ">
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m15 19-7-7 7-7" />
                </svg>
            </a>

            <x-page-title>Buat Data Cabang</x-page-title>
        </div>

        <div class="rounded-lg shadow-lg p-4">
            <form action="/cabang" method="post" class="" onsubmit="return createCabaangForm(event)">
                @csrf
                @method('POST')

                <div class="w-full flex justify-center items-center gap-2">
                    <div class="w-full border border-[#099AA7]"></div>
                    <p class="w-[200px] text-center font-bold uppercase text-[#099AA7]">Data</p>
                    <div class="w-full border border-t border-[#099AA7]"></div>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label for="nama" class="block text-xs font-medium leading-6 text-[#099AA7]">Nama</label>
                        <input type="text" id="nama" name="nama" required placeholder="Nama"
                            class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs leading-6" />
                    </div>
                </div>

                <div class="flex items-center justify-between mt-10">
                    <a href="/cabang"
                        class="min-w-[120px] text-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Kembali</a>
                    <button type="submit"
                        class="min-w-[120px] text-center rounded-md bg-green-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Buat
                        Data Cabang</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function createCabaangForm(event) {
            event.preventDefault();

            konfirmasi = confirm('Buat data cabang?');

            if (konfirmasi) {
                showLoadingModal();

                event.target.submit();
            }

            return konfirmasi;
        }
    </script>
</x-layout>
