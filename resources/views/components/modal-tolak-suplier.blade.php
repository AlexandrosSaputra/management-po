{{-- form modal tolak suplier --}}
<!-- Main modal -->
<div id="modal-tolak-suplier" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Catatan Menolak Order
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="modal-tolak-suplier">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form action="/preorder/{{ $preorder->id }}?token={{ $preorder->token }}" method="POST" id="tolak-form"
                    onsubmit="return tolakPreorderForm(event)">
                    @csrf
                    @method('PATCH')

                    <input type="text" value="ditolak" name="tolak" hidden />
                    <div class="mb-4">
                        <textarea rows="4" name="catatan_suplier" form="tolak-form"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required placeholder="Masukkan catatan tolak order">{{ $preorder->catatan_suplier }}</textarea>
                    </div>

                    <button type="submit" form="tolak-form" value="tolak" name="tolak"
                        class="w-full text-white bg-[#099AA7] hover:bg-[#099AA7]/80 focus:ring-4 focus:outline-none focus:ring-[#099AA7]/30 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Kirim</button>
                </form>
            </div>
        </div>
    </div>
</div>
