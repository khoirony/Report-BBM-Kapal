<div x-show="sidebarOpen" 
     x-transition.opacity 
     class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" 
     @click="sidebarOpen = false" x-cloak>
</div>

@php
    $role = auth()->user()?->role?->slug ?? null;
    $baseClass = "flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200";
    $activeClass = "bg-indigo-50 text-indigo-700";
    $inactiveClass = "text-gray-600 hover:bg-gray-50 hover:text-indigo-600";
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl lg:shadow-none flex flex-col">
    
       <div class="flex items-center justify-between h-16 border-b border-gray-100 px-6">
        <a href="#" class="flex items-center gap-1 group">
            <img src="img/logo-dishub-jakarta.jpeg" alt="Logo Dishub" class="h-13">
            
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-0.5">
                    Sistem Monitoring
                </span>
                
                <span class="text-xl font-black tracking-tight leading-none text-gray-800">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">BBM</span>KAPAL
                </span>
                
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                    Dishub Jakarta
                </span>
            </div>
        </a>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 focus:outline-none transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        
        <a href="{{ route('dashboard.' . str_replace('_', '-', $role)) }}" 
           class="{{ $baseClass }} {{ request()->routeIs('dashboard.*') ? $activeClass : $inactiveClass }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="14" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
            </svg>
            Dashboard
        </a>

        {{-- SUPERADMIN ONLY --}}
        @if(in_array($role, ['superadmin', 'admin_ukpd']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Manajemen Sistem</h3>
            <a href="{{ route('superadmin.kelola-user') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('superadmin.kelola-user') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Kelola User
            </a>
        @endif

        @if(in_array($role, ['superadmin']))
            
            <a href="{{ route('superadmin.kelola-ukpd') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('superadmin.kelola-ukpd') ? $activeClass : $inactiveClass }}">
               <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Kelola UKPD
            </a>

            <a href="{{ route('superadmin.kelola-perusahaan') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('superadmin.kelola-perusahaan') ? $activeClass : $inactiveClass }}">
               <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Kelola Perusahaan
            </a>
        @endif

        {{-- DATA KAPAL: Diakses oleh Superadmin, Satgas, Admin UKPD --}}
        @if(in_array($role, ['superadmin', 'satgas', 'sounding', 'admin_ukpd', 'kepala_ukpd', 'nakhoda']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Data Master</h3>
            <a href="{{ route('data-kapal') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('data-kapal') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 3.25-2 6-2 2.75 0 3.25 2 6 2 1.3 0 1.9-.5 2.5-1"/><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/><path d="M12 10v4"/><path d="M12 2v3"/>
                </svg>
                Data Kapal
            </a>
        @endif

        {{-- SOUNDING BBM: Diakses oleh Superadmin & Sounding --}}
        @if(in_array($role, ['superadmin', 'admin_ukpd', 'sounding']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Modul Sounding</h3>
            <a href="{{ route('sounding.sounding-bbm') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('sounding.sounding-bbm') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" x2="15" y1="22" y2="22"/><line x1="4" x2="14" y1="9" y2="9"/><path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"/><path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5"/>
                </svg>
                Sounding BBM
            </a>
        @endif
        
        {{-- ADMINISTRASI SATGAS: Superadmin, Satgas, Admin UKPD, PPTK --}}
        @if(in_array($role, ['superadmin', 'satgas', 'admin_ukpd','kepala_ukpd', 'pptk', 'pengawas']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Administrasi Satgas</h3>
            
            @if(in_array($role, ['pengawas', 'superadmin', 'satgas', 'admin_ukpd', 'kepala_ukpd']))
            <a href="{{ route('satgas.laporan-sisa-bbm') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.laporan-sisa-bbm') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 16v-4"/><path d="M8 16v-2"/><path d="M16 16v-6"/>
                </svg>
                Laporan Sisa BBM
            </a>
            @endif

            @if(in_array($role, ['superadmin', 'satgas', 'admin_ukpd', 'kepala_ukpd']))
            <a href="{{ route('satgas.surat-tugas') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.surat-tugas') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Surat Tugas Pengisian
            </a>
            @endif

            @if(in_array($role, ['superadmin', 'satgas', 'admin_ukpd', 'pptk', 'kepala_ukpd']))
            <a href="{{ route('satgas.surat-permohonan') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.surat-permohonan') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                Surat Permohonan
            </a>
            @endif

            @if(in_array($role, ['superadmin', 'satgas', 'admin_ukpd','kepala_ukpd', 'pptk']))
            <a href="{{ route('satgas.surat-spj') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.surat-spj') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <path d="M9 15l2 2 4-4"></path> </svg>
                Surat SPJ
            </a>

            {{-- MENU BARU: Verifikasi Tagihan (Sisi Dishub) --}}
            <a href="{{ route('satgas.verifikasi-tagihan') }}" 
               class="{{ $baseClass }} {{ request()->routeIs('satgas.verifikasi-tagihan') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    <path d="M9 12l2 2 4-4"></path>
                </svg>
                Verifikasi Tagihan
            </a>
            @endif
        @endif

        @php
            $pengisianRoutes = [
                'satgas.pencatatan-pengisian', 
                'satgas.laporan-pengisian', 
                'satgas.berita-acara-pengisian'
            ];
            $isPengisianActive = request()->routeIs($pengisianRoutes);
        @endphp

        @if(in_array($role, ['superadmin', 'satgas', 'admin_ukpd', 'nakhoda', 'pengawas', 'penyedia', 'kepala_ukpd', 'pptk']))
            <h3 class="px-4 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Operasional Kapal</h3>

            <div x-data="{ dropdownOpen: {{ $isPengisianActive ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="dropdownOpen = !dropdownOpen" 
                        class="w-full flex items-center justify-between {{ $baseClass }} {{ $isPengisianActive ? $activeClass : $inactiveClass }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>
                        Pengisian BBM
                    </div>
                    <svg :class="dropdownOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="dropdownOpen" x-transition.opacity class="pl-11 pr-2 py-1 space-y-1" x-cloak>
                    
                    @if(in_array($role, ['superadmin', 'nakhoda', 'pengawas', 'admin_ukpd', 'kepala_ukpd', 'penyedia']))
                    <a href="{{ route('satgas.pencatatan-pengisian') }}" 
                    class="block px-4 py-2 text-sm font-medium rounded-xl transition-colors duration-200 {{ request()->routeIs('satgas.pencatatan-pengisian') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                        Pencatatan Hasil Pengisian
                    </a>
                    @endif
                    
                    @if(in_array($role, ['superadmin', 'nakhoda', 'satgas', 'admin_ukpd','kepala_ukpd']))
                        <a href="{{ route('satgas.laporan-pengisian') }}" 
                        class="block px-4 py-2 text-sm font-medium rounded-xl transition-colors duration-200 {{ request()->routeIs('satgas.laporan-pengisian') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            Laporan Pengisian BBM
                        </a>
                    @endif

                    @if(in_array($role, ['superadmin', 'satgas', 'admin_ukpd', 'pptk', 'kepala_ukpd']))
                        <a href="{{ route('satgas.berita-acara-pengisian') }}" 
                        class="block px-4 py-2 text-sm font-medium rounded-xl transition-colors duration-200 {{ request()->routeIs('satgas.berita-acara-pengisian') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            Berita Acara Pengisian BBM
                        </a>
                    @endif
                    
                </div>
            </div>
        @endif

        {{-- PENYEDIA ONLY --}}
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

            {{-- MENU BARU: Tagihan & Rekonsiliasi (Sisi Penyedia) --}}
            <a href="{{ route('penyedia.invoice-tagihan') }}" 
            class="{{ $baseClass }} {{ request()->routeIs('penyedia.invoice-tagihan') ? $activeClass : $inactiveClass }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Tagihan & Rekonsil
            </a>
        @endif

    </nav>
</aside>