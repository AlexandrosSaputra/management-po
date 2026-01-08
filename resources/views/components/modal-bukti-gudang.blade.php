<!-- Main modal -->
<div id="modal-bukti-gudang-{{ $index }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-start w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div id="modal-bukti-gudang-header-{{ $index }}"
                class="cursor-move flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-[#099AA7]">
                    Bukti Gudang Item ke-{{ $index + 1 }}
                </h3>

                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="modal-bukti-gudang-{{ $index }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <div id="default-carousel" class="relative w-full" data-carousel="static">
                    <!-- Carousel wrapper -->
                    <div data-tooltip-target="tooltip-bukti-gudang"
                        class="relative h-56 overflow-hidden rounded-lg md:h-96">
                        @foreach ($itemPenawaran->bukti_gudangs as $index => $bukti_gudang)
                            <!-- Item -->
                            @if ($index == 0)
                                <a href="{{ asset('folder-image-truenas/' . $bukti_gudang->foto) }}"
                                    class="hidden duration-700 ease-linear" target="_blank" data-carousel-item="active">
                                    <img src="{{ asset('folder-image-truenas/' . $bukti_gudang->foto) }}"
                                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                                        alt="...">
                                </a>
                            @else
                                <a href="{{ asset('folder-image-truenas/' . $bukti_gudang->foto) }}"
                                    class="hidden duration-700 ease-linear" target="_blank" data-carousel-item>
                                    <img src="{{ asset('folder-image-truenas/' . $bukti_gudang->foto) }}"
                                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                                        alt="...">
                                </a>
                            @endif
                        @endforeach
                    </div>

                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                        @for ($i = 0; $i < count($itemPenawaran->bukti_gudangs); $i++)
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true"
                                aria-label="Slide {{ $i + 1 }}"
                                data-carousel-slide-to="{{ $i }}"></button>
                        @endfor
                    </div>

                    <!-- Slider controls -->
                    <button type="button"
                        class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                        data-carousel-prev>
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50  group-focus:ring-4 group-focus:ring-white  group-focus:outline-none">
                            <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 1 1 5l4 4" />
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>

                    <button type="button"
                        class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                        data-carousel-next>
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                            <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                </div>

                <div id="tooltip-bukti-gudang" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-[#099AA7] rounded-lg shadow-sm opacity-0 tooltip">
                    Klik untuk detail gambar
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-bukti-gudang-{{ $index }}');
    const header = document.getElementById('modal-bukti-gudang-header-{{ $index }}');

    let isDragging = false;
    let dragStartX, dragStartY, modalStartX, modalStartY;

    header.addEventListener('mousedown', function(e) {
        e.preventDefault(); // prevent text selection
        isDragging = true;

        // Get initial mouse position
        dragStartX = e.clientX;
        dragStartY = e.clientY;

        // Get computed styles for position
        const computedStyle = window.getComputedStyle(modal);
        modalStartX = parseInt(computedStyle.left, 10);
        modalStartY = parseInt(computedStyle.top, 10);

        // Add listeners to document for dragging
        document.addEventListener('mousemove', onDrag);
        document.addEventListener('mouseup', onStopDrag);
    });

    function onDrag(e) {
        if (!isDragging) return;
        const deltaX = e.clientX - dragStartX;
        const deltaY = e.clientY - dragStartY;

        modal.style.left = modalStartX + deltaX + 'px';
        modal.style.top = modalStartY + deltaY + 'px';
    }

    function onStopDrag() {
        isDragging = false;
        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', onStopDrag);
    }

    function closeModal() {
        modal.style.display = 'none';
    }
</script>
