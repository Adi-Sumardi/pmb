<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center logo-hover hover:opacity-80 transition-opacity duration-200">
                        <i class="bi bi-mortarboard-fill text-2xl text-blue-600 me-2"></i>
                        <span class="font-bold text-lg text-gray-800">PPDB YAPI</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-6 sm:-my-px sm:ml-12 sm:flex">
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                    class="px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('users.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                            <i class="bi bi-people-fill me-2"></i>User
                        </x-nav-link>
                        <x-nav-link :href="route('admin.pendaftar.index')" :active="request()->routeIs('admin.pendaftar.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                            <i class="bi bi-person-lines-fill me-2"></i>Pendaftar
                        </x-nav-link>
                        <x-nav-link :href="route('admin.progres-pendaftaran.index')" :active="request()->routeIs('admin.progres-pendaftaran.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200">
                            <i class="bi bi-graph-up-arrow me-2"></i>Progres
                        </x-nav-link>
                        <x-nav-link :href="route('admin.data-siswa.index')" :active="request()->routeIs('admin.data-siswa.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-teal-50 hover:text-teal-700 transition-all duration-200">
                            <i class="bi bi-people me-2"></i>Data
                        </x-nav-link>
                        <x-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <i class="bi bi-file-earmark-text me-2"></i>Transaksi
                        </x-nav-link>
                        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition-all duration-200">
                            <i class="bi bi-gear-fill me-2"></i>Settings
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')"
                                    class="px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                            <i class="bi bi-house me-2"></i>Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('user.payments.index')" :active="request()->routeIs('user.payments.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                            <i class="bi bi-credit-card me-2"></i>Pembayaran
                        </x-nav-link>
                        <x-nav-link :href="route('user.transactions.index')" :active="request()->routeIs('user.transactions.*')"
                                    class="px-4 py-2 rounded-lg hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                            <i class="bi bi-file-earmark-text me-2"></i>Transaksi
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Role Badge -->
                <div class="me-3">
                    @if(auth()->user()->role === 'admin')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 role-badge">
                            <i class="bi bi-shield-check me-1"></i>Admin
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 role-badge">
                            <i class="bi bi-person me-1"></i>User
                        </span>
                    @endif
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition-all ease-in-out duration-200 shadow-sm hover:shadow-md">
                            <i class="bi bi-person-circle me-2 text-lg"></i>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 transition-transform duration-200 hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profile -->
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                            <i class="bi bi-person me-2"></i>Profile
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 transition-all duration-200">
                                <i class="bi bi-box-arrow-right me-2"></i>Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile/Tablet) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-3 pb-4 space-y-2 px-4">
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                       class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                    <i class="bi bi-speedometer2 me-3"></i>Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('users.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                    <i class="bi bi-people-fill me-3"></i>User
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pendaftar.index')" :active="request()->routeIs('admin.pendaftar.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                    <i class="bi bi-person-lines-fill me-3"></i>Pendaftar
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.progres-pendaftaran.index')" :active="request()->routeIs('admin.progres-pendaftaran.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200">
                    <i class="bi bi-graph-up-arrow me-3"></i>Progres
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.data-siswa.index')" :active="request()->routeIs('admin.data-siswa.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-teal-50 hover:text-teal-700 transition-all duration-200">
                    <i class="bi bi-people me-3"></i>Data
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.transactions.index')" :active="request()->routeIs('admin.transactions.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-purple-50 hover:text-purple-700 transition-all duration-200">
                    <i class="bi bi-file-earmark-text me-3"></i>Transaksi
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition-all duration-200">
                    <i class="bi bi-gear-fill me-3"></i>Settings
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')"
                                       class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                    <i class="bi bi-house me-3"></i>Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.payments.index')" :active="request()->routeIs('user.payments.*')"
                                       class="px-4 py-3 rounded-lg hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                    <i class="bi bi-credit-card me-3"></i>Pembayaran
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 flex items-center">
                    {{ Auth::user()->name }}
                    @if(auth()->user()->role === 'admin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ms-2">
                            <i class="bi bi-shield-check me-1"></i>Admin
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ms-2">
                            <i class="bi bi-person me-1"></i>User
                        </span>
                    @endif
                </div>
                <div class="font-medium text-sm text-gray-500 mt-1">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-2 px-4">
                <x-responsive-nav-link :href="route('profile.edit')"
                                       class="px-4 py-3 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200">
                    <i class="bi bi-person me-3"></i>Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 px-4 py-3 rounded-lg hover:bg-red-50 hover:text-red-800 transition-all duration-200">
                        <i class="bi bi-box-arrow-right me-3"></i>Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Enhanced Navigation Styles -->
<style>
    /* Enhanced nav-link styling */
    .nav-link {
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .nav-link:hover::before {
        left: 100%;
    }

    /* Icon animations */
    .nav-link:hover i {
        transform: scale(1.1);
        transition: transform 0.2s ease-in-out;
    }

    /* Active link styling */
    .nav-link.active {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Dropdown enhancements */
    .dropdown-content {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Mobile menu enhancements */
    .responsive-nav-link {
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .responsive-nav-link:hover {
        border-left-color: #3b82f6;
        transform: translateX(4px);
    }

    .responsive-nav-link.active {
        border-left-color: #1d4ed8;
        background: linear-gradient(90deg, #eff6ff, transparent);
    }

    /* Role badge animations */
    .role-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Logo hover effect */
    .logo-hover:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease-in-out;
    }

    /* Navigation container shadow */
    nav {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    /* Smooth transitions for all interactive elements */
    * {
        transition: all 0.2s ease-in-out;
    }

    /* Custom scrollbar for mobile menu */
    .responsive-menu::-webkit-scrollbar {
        width: 4px;
    }

    .responsive-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .responsive-menu::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }

    .responsive-menu::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
