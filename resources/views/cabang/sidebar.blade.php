<!-- drawer component -->
<div id="drawer-navigation"
    class="fixed top-0 left-0 z-40 w-64 h-screen p-4 custom-scrollbar overflow-y-auto transition-transform -translate-x-full bg-[#099AA7]"
    tabindex="-1" aria-labelledby="drawer-navigation-label">

    <div class="flex items-center">
        <a href="{{ url('/') }}"
            class="flex gap-x-2 p-2 justify-center items-center focus:ring-2 focus:ring-gray-300 rounded-lg font-bold text-white group">
            <img class="w-[50px] bg-white p-2 rounded-lg group-hover:bg-gray-300" src="{{ asset('images/nhlogo.png') }}"
                alt="Logo">

            <span class="group-hover:text-gray-300">Manajemen PO</span>
        </a>

        <button
            class="text-white hover:text-gray-300 focus:ring-2 focus:ring-gray-300 font-medium rounded-lg text-sm focus:outline-none "
            type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation">
            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
            </svg>
        </button>
    </div>

    <div class="py-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="/dashboard"
                    class="flex items-center p-2 text-white rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == '' ? 'bg-white text-[#099AA7]' : 'text-white' }}">
                    <svg class="w-5 h-5 transition duration-75 group-hover:text-[#099AA7]" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>

                    <span class="ms-3 group-hover:text-[#099AA7]">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="/item-penawaran"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'item-penawaran' ? 'bg-white text-[#099AA7]' : 'text-white' }}">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3" />
                    </svg>

                    <span class="ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Komparasi Item</span>
                </a>
            </li>

            <li>
                <a href="/templateorder"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'templateorder' ? 'bg-white text-[#099AA7]' : 'text-white' }}">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z"
                            clip-rule="evenodd" />
                    </svg>


                    <span class="ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Template Order</span>
                </a>
            </li>

            <li>
                <a href="/harga"
                    class="flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'harga' ? 'bg-white text-[#099AA7]' : 'text-white' }}">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
                    </svg>

                    <span class="ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Template Harga</span>
                </a>
            </li>

            @if (Auth::user()->level == 'admin' || Auth::user()->level == 'pembayaran')
                <li>
                    <a href="/arsip"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'arsip' ? 'bg-white text-[#099AA7]' : 'text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M20 10H4v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8ZM9 13v-1h6v1a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Z"
                                clip-rule="evenodd" />
                            <path d="M2 6a2 2 0 0 1 2-2h16a2 2 0 1 1 0 4H4a2 2 0 0 1-2-2Z" />
                        </svg>


                        <span class="ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Arsip Pembayaran</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->level == 'admin')
                <li>
                    <a href="/master-user"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'master-user' ? 'bg-white text-[#099AA7]' : 'text-white' }}">
                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 18">
                            <path
                                d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>

                        <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Users</span>
                    </a>
                </li>
            @endif

            <li>
                <a href="/stok"
                    class="w-full flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'stok' ? 'bg-white text-[#099AA7]' : 'text-white' }}"
                    aria-controls="dropdown-stok" data-collapse-toggle="dropdown-stok">
                    <div class="w-full flex justify-between items-center">
                        <div class="flex items-center">

                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5" />
                            </svg>


                            <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Stok</span>
                        </div>
                    </div>
                </a>
            </li>

            <li>
                <button
                    class="w-full flex items-center p-2 rounded-lg hover:bg-gray-100 group {{ request()->segment(1) == 'suplier' || request()->segment(1) == 'gudang' || request()->segment(1) == 'item' || request()->segment(1) == 'satuan' || request()->segment(1) == 'jenis' || request()->segment(1) == 'tipe-pembayaran' || request()->segment(1) == 'cabang' ? 'bg-white text-[#099AA7]' : 'text-white' }}"
                    aria-controls="dropdown-masters" data-collapse-toggle="dropdown-masters">
                    <div class="w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857Zm10 0A1.857 1.857 0 0 0 13 14.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 19.143v-4.286A1.857 1.857 0 0 0 19.143 13h-4.286Z"
                                    clip-rule="evenodd" />
                            </svg>

                            <span class="flex-1 ms-3 whitespace-nowrap group-hover:text-[#099AA7]">Masters</span>
                        </div>

                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#099AA7]"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                <ul id="dropdown-masters" class="hidden h-[160px] overflow-auto custom-scrollbar py-2 space-y-2">
                    <li>
                        <a href="/suplier"
                            class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'suplier' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Suplier</a>
                    </li>

                    <li>
                        <a href="/gudang"
                            class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'gudang' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Gudang</a>
                    </li>

                    <li>
                        <a href="/cabang"
                            class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'cabang' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Cabang</a>
                    </li>

                    <li>
                        <a href="/item"
                            class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'item' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Item</a>
                    </li>

                    <li>
                        <a href="/satuan"
                            class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'satuan' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Satuan</a>
                    </li>
                    <li>
                        <a href="/jenis"
                            class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'jenis' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Jenis</a>
                    </li>

                    @if (Auth::user()->level == 'admin' || Auth::user()->level == 'pembayaran')
                        <li>
                            <a href="/tipe-pembayaran"
                                class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-white hover:text-[#099AA7] {{ request()->segment(1) == 'tipe-pembayaran' ? 'bg-white text-[#099AA7]' : 'text-white' }}">Tipe
                                Pembayaran</a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</div>
