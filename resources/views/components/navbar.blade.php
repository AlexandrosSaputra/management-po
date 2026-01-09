<nav class="fixed inset-x-0 top-0 z-20 bg-[#099AA7] text-white shadow-sm">
    <div class="relative mx-auto flex h-16 items-center justify-between px-4 sm:px-6">
        <div class="flex items-center gap-2">
            @auth
                <!-- drawer init and show -->
                <div class="text-center">
                    <button
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md text-white/90 transition-colors duration-200 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/50"
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

            @auth
                <a href="{{ url('/') }}"
                    class="group flex items-center gap-2 rounded-lg px-2 py-1 text-base font-semibold text-white/90 transition-colors duration-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                    <img class="w-14 rounded-lg bg-white/95 p-1.5 shadow-sm ring-1 ring-white/30 transition-colors duration-200 group-hover:bg-white"
                        src="{{ asset('images/nhlogo.png') }}" alt="Logo">
                    <span class="hidden sm:inline">Manajemen PO</span>
                </a>
            @endauth
        </div>

        @guest
            <a href="{{ url('/') }}"
                class="absolute left-1/2 -translate-x-1/2 flex items-center gap-2 rounded-lg px-2 py-1 text-base font-semibold text-white/90 transition-colors duration-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                <img class="w-14 rounded-lg bg-white/95 p-1.5 shadow-sm ring-1 ring-white/30"
                    src="{{ asset('images/nhlogo.png') }}" alt="Logo">
                <span>Manajemen PO</span>
            </a>
        @endguest

        <div class="flex items-center gap-2 md:order-2">
            @auth
                <button type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/20 transition duration-200 hover:bg-white/20 hover:ring-white/40 focus:outline-none focus:ring-2 focus:ring-white/60"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('images/person.jpg') }}"
                        alt="user photo">
                </button>

                <!-- Dropdown menu -->
                <div class="min-w-[200px] z-50 hidden my-4 text-base list-none rounded-xl bg-white shadow-lg ring-1 ring-black/5"
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
                                    class="block w-full px-4 py-2 text-start text-sm font-medium text-gray-700 transition-colors duration-200 hover:bg-gray-100">Sign
                                    out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth

            @auth
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-md text-white/90 transition-colors duration-200 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/50 md:hidden"
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
            <div class="hidden w-full items-center justify-between md:order-1 md:flex md:w-auto" id="navbar-user">
                <ul class="flex flex-col gap-1 font-medium md:flex-row md:items-center md:gap-2">
                    <li>
                        <a href="/nonpo"
                            class="block rounded-md px-3 py-1.5 text-sm font-semibold transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->segment(1) == 'nonpo' ? 'bg-white text-[#099AA7] md:bg-white/15 md:text-white' : 'text-white/80' }}">Non
                            PO</a>
                    </li>
                    <li>
                        <a href="/preorder"
                            class="block rounded-md px-3 py-1.5 text-sm font-semibold transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->segment(1) == 'preorder' ? 'bg-white text-[#099AA7] md:bg-white/15 md:text-white' : 'text-white/80' }}">Pre
                            Order</a>
                    </li>
                    <li>
                        <a href="/order"
                            class="block rounded-md px-3 py-1.5 text-sm font-semibold transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->segment(1) == 'order' ? 'bg-white text-[#099AA7] md:bg-white/15 md:text-white' : 'text-white/80' }}">Order</a>
                    </li>
                    <li>
                        <a href="/pembayaran"
                            class="block rounded-md px-3 py-1.5 text-sm font-semibold transition-colors duration-200 hover:bg-white/10 hover:text-white {{ request()->segment(1) == 'pembayaran' ? 'bg-white text-[#099AA7] md:bg-white/15 md:text-white' : 'text-white/80' }}">Pembayaran</a>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>
