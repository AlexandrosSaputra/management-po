<nav class="fixed w-full z-20 top-0 start-0 bg-[#099AA7] border-gray-300">
    <div class="flex flex-wrap items-center justify-between mx-auto px-4 py-1">
        <div class="flex items-center">
            @auth
                <!-- drawer init and show -->
                <div class="text-center">
                    <button
                        class="text-white hover:text-gray-300 focus:ring-2 focus:ring-gray-300 font-medium rounded-lg p-1 md:p-2 text-sm focus:outline-none "
                        type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation"
                        aria-controls="drawer-navigation">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                d="M5 7h14M5 12h14M5 17h10" />
                        </svg>

                    </button>
                </div>
            @endauth

            <a href="{{ url('/') }}"
                class="flex gap-x-2 p-2 justify-center items-center focus:ring-2 focus:ring-gray-300 rounded-lg font-bold text-white group">
                <img class="w-[50px] bg-white p-2 rounded-lg group-hover:bg-gray-300"
                    src="{{ asset('images/nhlogo.png') }}" alt="Logo">

                <span class="group-hover:text-gray-300">Manajemen PO</span>
            </a>
        </div>

        <div class="flex items-center md:order-2 gap-2 rtl:space-x-reverse">

            @guest
                <a href="{{ route('login') }}"
                    class="block py-2 px-3 text-white hover:text-gray-300 font-semibold rounded hover:bg-gray-100 md:hover:bg-transparent md:p-0">Login</a>
            @endguest

            @auth
                <button type="button"
                    class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 "
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="{{ asset('images/person.jpg') }}" alt="user photo">
                </button>

                <!-- Dropdown menu -->
                <div class="min-w-[200px] z-50 hidden my-4 text-base list-none bg-gray-100 divide-y divide-gray-300 rounded-lg shadow"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span
                            class="block text-sm font-bold text-gray-900 truncate uppercase">{{ Auth::user()->nama }}</span>
                        <span
                            class="block text-sm font-semibold text-gray-500 truncate uppercase">{{ Auth::user()->level }}</span>
                    </div>

                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <form action="{{ route('logout') }}" id="logout" method="POST" class="w-full">@csrf
                                <button type="submit"
                                    class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-gray-300">Sign
                                    out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            @auth
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden focus:outline-none focus:ring-2 focus:ring-gray-300"
                    aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14" />
                    </svg>

                </button>

            @endauth
        </div>

        @auth
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul class="flex flex-col md:flex-row font-medium px-4 py-2 rounded-lg rtl:space-x-reverse">
                    {{-- <li>
                        <button id="dropdown-inventaris-button" data-dropdown-toggle="dropdown-inventaris"
                            class="block py-1 px-2 text-left hover:text-gray-300 rounded-md {{ request()->segment(1) == 'inventaris' ? 'bg-white md:bg-transparent text-[#099AA7] md:text-gray-300' : 'text-white' }}"
                            aria-current="page">Stok</button>
                    </li> --}}
                    <li>
                        <a href="/nonpo"
                            class="block py-1 px-2 hover:text-gray-300 rounded-md {{ request()->segment(1) == 'nonpo' ? 'bg-white md:bg-transparent text-[#099AA7] md:text-gray-300' : 'text-white' }}">Non
                            PO</a>
                    </li>
                    <li>
                        <a href="/preorder"
                            class="block py-1 px-2 hover:text-gray-300 rounded-md {{ request()->segment(1) == 'preorder' ? 'bg-white md:bg-transparent text-[#099AA7] md:text-gray-300' : 'text-white' }}">Pre
                            Order</a>
                    </li>
                    <li>
                        <a href="/order"
                            class="block py-1 px-2 hover:text-gray-300 rounded-md {{ request()->segment(1) == 'order' ? 'bg-white md:bg-transparent text-[#099AA7] md:text-gray-300' : 'text-white' }}">Order</a>
                    </li>
                    <li>
                        <a href="/pembayaran"
                            class="block py-1 px-2 hover:text-gray-300 rounded-md {{ request()->segment(1) == 'pembayaran' ? 'bg-white md:bg-transparent text-[#099AA7] md:text-gray-300' : 'text-white' }}">Pembayaran</a>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>
