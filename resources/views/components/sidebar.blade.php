<div x-show="sidebarOpen" 
     x-transition.opacity 
     class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" 
     @click="sidebarOpen = false" x-cloak>
</div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 transition-transform duration-300 ease-in-out transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl lg:shadow-none flex flex-col">
    
    <div class="flex items-center justify-between h-16 border-b border-gray-100 px-6">
        <span class="text-2xl font-extrabold text-indigo-600 tracking-wider">BBM<span class="text-gray-800">KAPAL</span></span>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-red-500 focus:outline-none transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard.' . auth()->user()->role) }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200 
           {{ request()->routeIs('dashboard.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
            
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        @if(auth()->user()->role === 'superadmin')
            <a href="{{ route('superadmin.data-kapal') }}" 
            class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200 
            {{ request()->routeIs('superadmin.data-kapal') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 3.25-2 6-2 2.75 0 3.25 2 6 2 1.3 0 1.9-.5 2.5-1"/>
                    <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/>
                    <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                    <path d="M12 10v4"/>
                    <path d="M12 2v3"/>
                </svg>
                
                Data Kapal
            </a>

            <a href="" 
            class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200 
            {{ request()->routeIs('superadmin.data-laporan') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                
                Data Laporan
            </a>
        @endif

        @if(auth()->user()->role === 'sounding')
            <a href="{{ route('sounding.sounding-bbm') }}" 
            class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200 
            {{ request()->routeIs('sounding.sounding-bbm') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"></path>
                    <path d="M12 14a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
                
                Sounding BBM
            </a>
        @endif

        @if(auth()->user()->role === 'satgas')
            <a href="" 
            class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors duration-200 
            {{ request()->routeIs('satgas.lapor') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                
                Lapor BBM
            </a>
        @endif
    </nav>
</aside>