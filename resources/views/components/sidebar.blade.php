<div x-show="sidebarOpen" 
     x-transition.opacity 
     class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" 
     @click="sidebarOpen = false" x-cloak>
</div>

@php
    $role = auth()->user()->role;
    $baseClass = "flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200";
    $activeClass = "bg-indigo-50 text-indigo-700";
    $inactiveClass = "text-gray-600 hover:bg-gray-50 hover:text-indigo-600";
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl lg:shadow-none flex flex-col">
    
    <div class="flex items-center justify-between h-16 border-b border-gray-100 px-6">
        <div class="flex flex-col">
            <span class="text-2xl font-extrabold text-indigo-600 tracking-wider leading-none">BBM<span class="text-gray-800">KAPAL</span></span>
        </div>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 focus:outline-none transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        
        <a href="{{ route('dashboard.' . $role) }}" 
           class="{{ $baseClass }} {{ request()->routeIs('dashboard.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="14" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
            </svg>
            Dashboard
        </a>

        @if(in_array($role, ['superadmin', 'satgas']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Data Master</h3>
            <a href="{{ route('data-kapal') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('data-kapal') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 3.25-2 6-2 2.75 0 3.25 2 6 2 1.3 0 1.9-.5 2.5-1"/><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/><path d="M12 10v4"/><path d="M12 2v3"/>
                </svg>
                Data Kapal
            </a>
        @endif

        @if(in_array($role, ['superadmin', 'sounding']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Modul Sounding</h3>
            <a href="{{ route('sounding.sounding-bbm') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('sounding.sounding-bbm') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" x2="15" y1="22" y2="22"/><line x1="4" x2="14" y1="9" y2="9"/><path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"/><path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5"/>
                </svg>
                Sounding BBM
            </a>
        @endif
        
        @if(in_array($role, ['superadmin', 'satgas']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Administrasi Satgas</h3>
            
            <a href="{{ route('satgas.laporan-sisa-bbm') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.laporan-sisa-bbm') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 16v-4"/><path d="M8 16v-2"/><path d="M16 16v-6"/>
                </svg>
                Laporan Sisa BBM
            </a>

            <a href="{{ route('satgas.surat-tugas') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.surat-tugas') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>
                </svg>
                Surat Tugas Pengisian
            </a>

            <a href="{{ route('satgas.surat-permohonan') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.surat-permohonan') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                Surat Permohonan
            </a>
        @endif

        @php
            $pengisianRoutes = [
                'satgas.pencatatan-pengisian', 
                'satgas.laporan-pengisian', 
                'satgas.berita-acara-pengisian'
            ];
            $isPengisianActive = request()->routeIs($pengisianRoutes);
        @endphp

        @if(in_array($role, ['superadmin', 'satgas', 'abk']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Operasional Kapal</h3>
            
            <div x-data="{ dropdownOpen: {{ $isPengisianActive ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="dropdownOpen = !dropdownOpen" 
                        class="w-full flex items-center justify-between {{ $baseClass }} {{ $isPengisianActive ? $activeClass : $inactiveClass }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>
                        </svg>
                        Pengisian BBM
                    </div>
                    <svg :class="dropdownOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="dropdownOpen" 
                    x-transition.opacity
                    class="pl-11 pr-2 py-1 space-y-1"
                    x-cloak>
                    
                    @if(in_array($role, ['superadmin', 'abk']))
                        <a href="{{ route('satgas.pencatatan-pengisian') }}" 
                        class="block px-4 py-2 text-sm font-medium rounded-xl transition-colors duration-200 {{ request()->routeIs('satgas.pencatatan-pengisian') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            Pencatatan Hasil Pengisian
                        </a>
                    @endif
                    
                    @if(in_array($role, ['superadmin', 'satgas']))
                        <a href="{{ route('satgas.laporan-pengisian') }}" 
                        class="block px-4 py-2 text-sm font-medium rounded-xl transition-colors duration-200 {{ request()->routeIs('satgas.laporan-pengisian') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            Laporan Pengisian BBM
                        </a>
                        
                        <a href="{{ route('satgas.berita-acara-pengisian') }}" 
                        class="block px-4 py-2 text-sm font-medium rounded-xl transition-colors duration-200 {{ request()->routeIs('satgas.berita-acara-pengisian') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            Berita Acara Pengisian BBM
                        </a>
                    @endif
                    
                </div>
            </div>
        @endif

        @if(in_array($role, ['superadmin','penyedia']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Portal Penyedia</h3>
            <a href="{{ route('penyedia.pesanan-bbm') }}" 
            class="{{ $baseClass }} {{ request()->routeIs('penyedia.pesanan-bbm') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="22" x2="15" y2="22"></line>
                    <line x1="4" y1="9" x2="14" y2="9"></line>
                    <path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"></path>
                    <path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2"></path>
                    <path d="M18 10a2 2 0 0 0-2-2V5.5A2.5 2.5 0 0 0 13.5 3h-.5"></path>
                </svg>
                Pemesanan BBM
            </a>
        @endif

    </nav>
</aside>