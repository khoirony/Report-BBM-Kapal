<header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 shadow-sm relative">
    
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-indigo-600 focus:outline-none lg:hidden transition-colors mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div class="flex items-center space-x-4">
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center space-x-2 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold border border-indigo-200">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <span class="text-sm font-semibold text-gray-700 hidden md:block">{{ auth()->user()->name ?? 'User' }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="dropdownOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" x-cloak>
                 
                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <p class="text-xs text-gray-500">Login sebagai</p>
                    <p class="text-sm font-bold text-gray-800 uppercase">{{ auth()->user()->role ?? 'Role' }} <span class="text-red-500">{{ auth()->user()->ukpd ? '('.auth()->user()->ukpd->singkatan.')' : '' }}</span></p>
                </div>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Profil Saya</a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>