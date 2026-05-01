<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Surat Perintah Tugas Pengisian BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Pengelolaan Surat Perintah Penugasan Pengisian BBM Kapal.</p>
                </div>
            </div>
            
            <button wire:click="create()" wire:loading.attr="disabled" wire:target="create" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 disabled:opacity-75 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="create" class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                <svg wire:loading wire:target="create" class="animate-spin w-5 h-5 mr-2 -ml-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                
                <span wire:loading.remove wire:target="create">Buat Surat Tugas</span>
                <span wire:loading wire:target="create">Memuat...</span>
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl shadow-sm flex items-center">
                <div class="flex-shrink-0 bg-emerald-100 p-1 rounded-full mr-3"><svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                <p class="text-sm font-semibold text-emerald-800">{{ session('message') }}</p>
            </div>
        @endif

        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
    
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. Surat, Kapal, Lokasi, Petugas..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>
        
                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-500">
                        <svg x-show="!showFilters" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <svg x-show="showFilters" style="display: none;" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'">Filter</span>
                    </button>
        
                    <div class="relative flex-1 md:flex-none md:w-48">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        </div>
                        <select wire:model.live="sortBy" class="pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-all appearance-none cursor-pointer shadow-sm hover:bg-slate-100">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
            </div>
        
            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 pt-4 border-t border-slate-100 transition-all duration-200">
                
                @if (auth()->user()?->role?->slug == 'superadmin')
                <div class="relative w-full">
                    <select wire:model.live="filterUkpd" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full appearance-none hover:bg-slate-50 cursor-pointer">
                        <option value="">Semua SKPD/UKPD</option>
                        @foreach($ukpds as $ukpd)
                            <option value="{{ $ukpd->id }}">{{ $ukpd->singkatan ?? $ukpd->nama }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
                @endif

                <div class="relative w-full">
                    <select wire:model.live="filterKapal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full appearance-none hover:bg-slate-50 cursor-pointer">
                        <option value="">Semua Armada Kapal</option>
                        @foreach($kapals as $kapal)
                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
        
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Dari Tgl</label>
                    <input type="date" wire:model.live="filterTanggalDari" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full hover:bg-slate-50 relative z-0 cursor-pointer">
                </div>
        
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Sampai Tgl</label>
                    <input type="date" wire:model.live="filterTanggalSampai" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full hover:bg-slate-50 relative z-0 cursor-pointer">
                </div>
        
                <div class="flex items-end w-full">
                    <button wire:click="resetFilters" class="w-full min-h-[34px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset Filter
                    </button>
                </div>
        
            </div>
        </div>

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-hidden w-full relative">
            
            <div wire:loading class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden md:flex items-center justify-center rounded-2xl">
                <div class="w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
            </div>
        
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 block lg:table">
                    <thead class="hidden lg:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Nomor & Tanggal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Armada & Surat Permohonan</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Giat & Lokasi</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">P. Jawab & Personel</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group space-y-4 lg:space-y-0 lg:divide-y lg:divide-gray-50">
                        @forelse($surat_tugas as $surat)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Identitas Surat Tugas</span>
                                
                                <div class="font-bold text-slate-900 mb-1 tracking-tight">{{ $surat->nomor_surat }}</div>
                                <div class="flex items-center text-xs text-slate-500 font-medium" title="Tanggal Surat Dikeluarkan">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d M Y') }}
                                </div>
                            </td>
            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Armada & Surat Permohonan</span>
                                
                                @if($surat->suratPermohonan)
                                    <div class="font-semibold text-slate-900 mb-1 flex items-center gap-2">
                                        {{ $surat->suratPermohonan->LaporanSisaBbm->sounding->kapal->nama_kapal ?? 'Tanpa Nama' }}
                                        <span class="px-1.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold tracking-wide">
                                            {{ $surat->suratPermohonan->LaporanSisaBbm->sounding->kapal->ukpd->singkatan ?? 'UKPD' }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-3 bg-slate-50 border border-slate-100 rounded-lg p-2.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Ref. Surat Permohonan</span>
                                        <div class="flex items-start">
                                            <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <div>
                                                <span class="text-xs font-semibold text-slate-700 block">{{ $surat->suratPermohonan->nomor_surat ?? 'Permohonan #'.$surat->suratPermohonan->id }}</span>
                                                @if($surat->suratPermohonan->tanggal_surat)
                                                    <span class="text-[10px] text-slate-500 mt-0.5 block">{{ \Carbon\Carbon::parse($surat->suratPermohonan->tanggal_surat)->translatedFormat('d M Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-rose-600 bg-rose-50 px-2 py-1 rounded-md border border-rose-100">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        Permohonan Dihapus
                                    </span>
                                @endif
                            </td>
            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top whitespace-normal">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Giat & Lokasi</span>
                                
                                <div class="flex items-start gap-1.5 mb-2.5">
                                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-sm font-medium text-slate-800 line-clamp-2 leading-tight" title="{{ $surat->lokasi }}">{{ $surat->lokasi }}</span>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-1.5 ml-0 lg:ml-5">
                                    @if($surat->tanggal_pelaksanaan)
                                    <div class="flex items-center text-[11px] font-semibold text-slate-700 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-200" title="Tanggal Pelaksanaan">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($surat->tanggal_pelaksanaan)->translatedFormat('d M Y') }}
                                    </div>
                                    @endif
            
                                    <div class="flex items-center text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100" title="Waktu Pelaksanaan">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $surat->waktu_pelaksanaan }}
                                    </div>
                                    
                                    @if($surat->pakaian)
                                    <div class="flex items-center text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100" title="Pakaian">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        {{ $surat->pakaian }}
                                    </div>
                                    @endif
                                </div>
                            </td>
            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">P. Jawab & Personel</span>
                                
                                <div class="mb-3">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block mb-0.5">Kepala UKPD</span>
                                    @if($surat->nama_kepala_ukpd)
                                        <div class="font-bold text-slate-800 text-xs truncate leading-tight">{{ $surat->nama_kepala_ukpd }}</div>
                                        @if($surat->id_kepala_ukpd)
                                            <div class="text-[10px] text-indigo-600 font-semibold mt-0.5">NIP: {{ $surat->id_kepala_ukpd }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum diisi</span>
                                    @endif
                                </div>
            
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block mb-1">Petugas Bertugas</span>
                                @if($surat->petugas->count() > 0)
                                    <div x-data="{ open: false }" class="relative inline-block w-full lg:w-auto">
                                        <button type="button" @click="open = !open" @click.outside="open = false" class="w-full lg:w-auto justify-between lg:justify-start inline-flex items-center gap-2 px-3 py-2 lg:py-1.5 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-indigo-100">
                                            <div class="flex items-center gap-2">
                                                <div class="flex -space-x-2">
                                                    <div class="w-5 h-5 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[9px] font-bold text-indigo-700 z-10">{{ strtoupper(substr($surat->petugas->first()->nama_petugas, 0, 1)) }}</div>
                                                    @if($surat->petugas->count() > 1)
                                                        <div class="w-5 h-5 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[9px] font-bold text-slate-600">+{{ $surat->petugas->count() - 1 }}</div>
                                                    @endif
                                                </div>
                                                <span>{{ $surat->petugas->count() }} Orang</span>
                                            </div>
                                            <svg class="w-3 h-3 text-slate-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
            
                                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 left-0 lg:left-auto lg:right-0 mt-2 w-full lg:w-64 bg-white rounded-xl shadow-xl shadow-slate-200/50 border border-slate-200 p-2" style="display: none;">
                                            <div class="text-[10px] uppercase font-bold text-slate-400 px-2 pb-1.5 mb-1.5 border-b border-slate-100 tracking-wider">Daftar Personel</div>
                                            <div class="max-h-48 overflow-y-auto custom-scrollbar space-y-1">
                                                @foreach($surat->petugas as $petugas)
                                                    <div class="flex flex-col px-2.5 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-transparent hover:border-slate-100">
                                                        <span class="text-xs font-bold text-slate-800">{{ $petugas->nama_petugas }}</span>
                                                        <span class="text-[10px] font-medium text-indigo-600">{{ $petugas->jabatan }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1.5 rounded-md border border-slate-200 w-full lg:w-auto">
                                        Belum diatur
                                    </span>
                                @endif
                            </td>
            
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto mt-2 lg:mt-0">
                                    
                                    <div x-data="{ uploading: false, progress: 0 }"
                                         x-on:livewire-upload-start="uploading = true"
                                         x-on:livewire-upload-finish="uploading = false"
                                         x-on:livewire-upload-error="uploading = false"
                                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        
                                        <input type="file" x-ref="fileInput_{{ $surat->id }}" wire:model="upload_files.{{ $surat->id }}" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                            
                                        @if($surat->file_surat_tugas)
                                            <div class="flex gap-2">
                                                <a href="{{ Storage::url($surat->file_surat_tugas) }}" target="_blank" class="flex-1 justify-center inline-flex items-center text-emerald-700 font-semibold bg-emerald-50 hover:bg-emerald-600 hover:text-white px-2 py-2 rounded-lg transition-all duration-200 border border-emerald-200 hover:border-emerald-600 shadow-sm text-[11px]" title="Lihat Dokumen Surat Tugas">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Lihat File
                                                </a>
                                                <button @click="$refs.fileInput_{{ $surat->id }}.click()" class="justify-center inline-flex items-center text-amber-600 hover:text-white font-semibold bg-amber-50 hover:bg-amber-600 px-2 py-2 rounded-lg transition-all duration-200 border border-amber-100 text-[11px]" title="Update File Surat Tugas">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </button>
                                            </div>
                                        @else
                                            <button @click="$refs.fileInput_{{ $surat->id }}.click()" class="w-full justify-center inline-flex items-center text-blue-700 font-semibold bg-blue-50 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-blue-200 hover:border-blue-600 shadow-sm text-xs" :disabled="uploading">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                <span x-show="!uploading">Upload Dokumen</span>
                                                <span x-show="uploading" x-text="`Uploading... ${progress}%`"></span>
                                            </button>
                                        @endif
                                    </div>
                                    <a href="{{ route('surattugas.pdf.preview', $surat->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>Preview PDF</span>
                                    </a>
                            
                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $surat->id }})" wire:loading.attr="disabled" wire:target="edit({{ $surat->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100 shadow-sm text-xs disabled:opacity-70">
                                            <svg wire:loading.remove wire:target="edit({{ $surat->id }})" class="w-3.5 h-3.5 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <svg wire:loading wire:target="edit({{ $surat->id }})" class="animate-spin w-3.5 h-3.5 mr-1 lg:mr-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            
                                            <span class="lg:hidden ml-1" wire:loading.remove wire:target="edit({{ $surat->id }})">Edit</span>
                                            <span class="lg:hidden ml-1" wire:loading wire:target="edit({{ $surat->id }})">Loading...</span>
                                        </button>
                                        
                                        <button wire:click="delete({{ $surat->id }})" wire:loading.attr="disabled" wire:target="delete({{ $surat->id }})" onclick="confirm('Yakin ingin menghapus Surat Tugas ini beserta filenya?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100 shadow-sm text-xs disabled:opacity-70">
                                            <svg wire:loading.remove wire:target="delete({{ $surat->id }})" class="w-3.5 h-3.5 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <svg wire:loading wire:target="delete({{ $surat->id }})" class="animate-spin w-3.5 h-3.5 mr-1 lg:mr-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            
                                            <span class="lg:hidden ml-1" wire:loading.remove wire:target="delete({{ $surat->id }})">Hapus</span>
                                            <span class="lg:hidden ml-1" wire:loading wire:target="delete({{ $surat->id }})">Loading...</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
            
                        </tr>
                        @empty
                        <tr class="block lg:table-row">
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm mt-4 block lg:table-cell">
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Surat Tugas</h3>
                                <p class="text-sm">Silakan buat Surat Tugas pengisian BBM baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $surat_tugas->links() }}
            </div>
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[90vh] flex flex-col">
                
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                        {{ $surat_id ? 'Edit Surat Tugas' : 'Buat Surat Tugas' }}
                    </h3>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 rounded-xl p-2 border shadow-sm transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1 p-4 sm:p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-surat">
                        <div class="space-y-6">
                            
                            <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tautkan Surat Permohonan <span class="text-rose-500">*</span></label>
                                    
                                    <select wire:model="surat_permohonan_id" class="px-4 py-2.5 bg-white border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer" required>
                                        <option value="">-- Pilih Surat Permohonan --</option>
                                        @foreach($permohonanList as $perm)
                                            <option value="{{ $perm->id }}">{{ $perm->nomor_surat ?: 'No. Surat Belum Ada' }} | {{ \Carbon\Carbon::parse($perm->tanggal_surat)->locale('id')->translatedFormat('d/M/Y') }} (Kapal: {{ $perm->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }})</option>
                                        @endforeach
                                    </select>

                                    <p class="text-[10px] text-gray-500 mt-1">Data Kapal & Identitas akan otomatis ditarik dari Surat Permohonan yang dipilih.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat</label>
                                        <input type="text" wire:model="nomor_surat" placeholder="Contoh: 001/PH.12.00/2026" class="px-4 py-2.5 bg-white border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                                        <input type="date" wire:model="tanggal_surat" class="px-4 py-2.5 bg-white border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi Pengisian <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="lokasi" placeholder="Contoh: SPBU Pertamina 31.102..." class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pakaian <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="pakaian" placeholder="Contoh: PDL / PDH" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model="tanggal_pelaksanaan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Pelaksanaan <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center">
                                        <input type="text" wire:model="waktu_pelaksanaan" placeholder="Contoh: 08:00 - Selesai" class="px-4 py-2.5 bg-slate-50 border border-slate-200 border-r-0 text-sm rounded-l-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <span class="px-3 py-2.5 bg-gray-100 border border-gray-200 text-sm rounded-r-xl font-bold text-gray-600">WIB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 my-2"></div>

                            <div x-data="{
                                users: {{ json_encode($kepala_users ?? []) }},
                                selectedUser: '',
                                fillData() {
                                    if (this.selectedUser) {
                                        let u = this.users.find(u => u.id == this.selectedUser);
                                        if (u) {
                                            $wire.nama_kepala_ukpd = u.name;
                                            $wire.id_kepala_ukpd = u.nip || '';
                                        }
                                    }
                                }
                            }" class="space-y-4 bg-white/50 p-4 rounded-xl border border-slate-200">
                                
                                <div>
                                    <label class="block text-sm font-bold text-indigo-700 mb-1.5">Penanggung Jawab (Kepala UKPD)</label>
                                    <select x-model="selectedUser" @change="fillData()" class="px-4 py-2.5 bg-indigo-50 border border-indigo-200 text-sm text-indigo-900 rounded-xl block w-full cursor-pointer focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Cari di Master User Kepala (Opsional) --</option>
                                        <template x-for="u in users" :key="u.id">
                                            <option :value="u.id" x-text="u.name + (u.nip ? ' - ' + u.nip : '')"></option>
                                        </template>
                                    </select>
                                    <p class="text-[10px] text-slate-500 mt-1.5">Pilih untuk isi otomatis, atau ketik manual di bawah jika data belum ada di master user.</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kepala UKPD <span class="text-rose-500">*</span></label>
                                        <input type="text" wire:model="nama_kepala_ukpd" placeholder="Masukkan Nama Kepala..." class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl block w-full text-sm" required>
                                        @error('nama_kepala_ukpd') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP/NRK</label>
                                        <input type="text" wire:model="id_kepala_ukpd" placeholder="Masukkan NIP/NRK..." class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl block w-full text-sm">
                                        @error('id_kepala_ukpd') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 my-2"></div>

                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-sm font-bold text-slate-800">Daftar Petugas Bertugas <span class="text-rose-500">*</span></label>
                                    <button type="button" wire:click="addPetugas" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Tambah Petugas
                                    </button>
                                </div>
                                
                                @error('petugasList.*.nama_petugas') <span class="text-xs text-rose-500 block mb-2">{{ $message }}</span> @enderror
                                @error('petugasList.*.jabatan') <span class="text-xs text-rose-500 block mb-2">{{ $message }}</span> @enderror

                                <div class="space-y-3">
                                    @foreach($petugasList as $index => $petugas)
                                    <div class="flex flex-col sm:flex-row gap-3 items-end bg-slate-50 p-3 rounded-xl border border-slate-200">
                                        <div class="w-full sm:w-1/2">
                                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Petugas</label>
                                            <input type="text" wire:model="petugasList.{{ $index }}.nama_petugas" placeholder="Nama Lengkap" class="px-3 py-2 bg-white border border-slate-200 text-sm rounded-lg w-full focus:ring-2 focus:ring-indigo-500" required>
                                        </div>
                                        <div class="w-full sm:w-2/5">
                                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jabatan</label>
                                            <input type="text" wire:model="petugasList.{{ $index }}.jabatan" placeholder="Contoh: Nakhoda, KKM, ABK, dll" class="px-3 py-2 bg-white border border-slate-200 text-sm rounded-lg w-full focus:ring-2 focus:ring-indigo-500" required>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <button type="button" wire:click="removePetugas({{ $index }})" class="w-full justify-center inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-rose-600 bg-white hover:bg-rose-50 border border-rose-200 transition-colors" title="Hapus Baris">
                                                <svg class="w-5 h-5 sm:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <span class="sm:hidden ml-2">Hapus Petugas</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @if(count($petugasList) === 0)
                                        <div class="text-center py-6 border-2 border-dashed border-slate-200 rounded-xl">
                                            <p class="text-sm text-slate-500 font-medium">Belum ada petugas ditambahkan.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end p-4 sm:p-6 border-t border-slate-100 rounded-b-2xl bg-slate-50/80 gap-3 mt-auto">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto px-5 py-2.5 border border-slate-300 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-sm transition-colors order-2 sm:order-1">Batal</button>
                    <button type="submit" form="form-surat" wire:loading.attr="disabled" wire:target="store" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors order-1 sm:order-2 disabled:opacity-75 disabled:cursor-not-allowed flex items-center justify-center">
    
                        <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    
                        <span wire:loading.remove wire:target="store">Simpan Surat Tugas</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>