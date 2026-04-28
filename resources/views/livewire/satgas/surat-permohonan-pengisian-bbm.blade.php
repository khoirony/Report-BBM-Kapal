<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen font-sans">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Surat Permohonan BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Kelola surat permohonan dan penyediaan bahan bakar armada.</p>
                </div>
            </div>
            
            <button wire:click="create()" wire:loading.attr="disabled" wire:target="create" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 disabled:opacity-75 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="create" class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                <svg wire:loading wire:target="create" class="animate-spin w-5 h-5 mr-2 -ml-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            
                <span wire:loading.remove wire:target="create">Tambah Surat</span>
                <span wire:loading wire:target="create">Memuat...</span>
            </button>
        </div>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl shadow-sm animate-fade-in-down flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-100 p-1 rounded-full mr-3">
                        <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-emerald-800">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari surat, penyedia, SPBU, atau kapal..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>

                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
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

            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-4 border-t border-slate-100 transition-all duration-200">
                
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
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-500 z-10">Dari Tgl</label>
                    <input type="date" wire:model.live="filterTanggalAwal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full hover:bg-slate-50 relative z-0 cursor-pointer">
                </div>

                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-500 z-10">Sampai Tgl</label>
                    <input type="date" wire:model.live="filterTanggalAkhir" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full hover:bg-slate-50 relative z-0 cursor-pointer">
                </div>
                
                <div class="flex items-end w-full">
                    <button wire:click="resetFilters" class="w-full h-full min-h-[34px] flex justify-center items-center px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
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
                <table class="w-full text-sm text-left text-gray-600 block md:table">
                    <thead class="hidden md:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Nomor & Tanggal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Kapal & Surat Tugas</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Rincian Penyedia BBM</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Penanggung Jawab</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right w-1/5">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block md:table-row-group md:divide-y md:divide-gray-50 space-y-4 md:space-y-0">
                        @forelse($permohonans as $item)
                            @php
                                $sounding = $item->suratTugas->LaporanSisaBbm->sounding ?? null;
                                
                                $jumlahLiter = $item->jumlah_bbm ?? ($sounding ? $sounding->sum('pengisian') : 0);
                                $jenisBbm = $item->jenis_bbm ?? ($sounding->jenis_bbm ?? '-');
                            @endphp
                            
                            <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none hover:bg-slate-50/50 transition-colors duration-150 border border-gray-100 md:border-none">
                                
                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10 align-top">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Nomor & Tanggal</span>
                                    <h3 class="font-bold text-gray-900 text-base mb-1">{{ $item->nomor_surat ?? '-' }}</h3>
                                    
                                    <div class="flex items-center text-xs text-gray-500 mb-2">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal_surat)->translatedFormat('d M Y') }}
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mt-2 items-center">
                                        @if($item->klasifikasi)
                                            <span class="inline-block text-[10px] bg-slate-100 px-2 py-0.5 rounded font-semibold text-slate-600">{{ $item->klasifikasi }}</span>
                                        @endif
                                        @if($item->disetujui_pptk_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100" title="Disetujui pada {{ \Carbon\Carbon::parse($item->disetujui_pptk_at)->translatedFormat('d M Y H:i') }}">
                                                <svg class="w-3 h-3 text-emerald-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                <svg class="w-3 h-3 text-amber-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Menunggu Persetujuan
                                            </span>
                                        @endif
                                        
                                        @if(auth()->user() && auth()->user()?->role?->slug === 'superadmin')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100" title="Ditambahkan oleh">
                                                <svg class="w-3 h-3 text-indigo-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $item->user->name ?? 'Sistem' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10 align-top">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Kapal & Surat Tugas</span>
                                    <div class="flex items-center mt-1">
                                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 mr-3 hidden md:block flex-shrink-0">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="5" r="3"></circle>
                                                <line x1="12" y1="8" x2="12" y2="22"></line>
                                                <path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-800 block">{{ $item->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? 'Tidak ada data kapal' }}</span>
                                            <span class="text-xs text-gray-500 font-bold bg-slate-100 px-1.5 py-0.5 rounded inline-block mt-0.5">{{ $item->suratTugas->LaporanSisaBbm->sounding->kapal->ukpd->singkatan ?? '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 bg-slate-50 border border-slate-100 rounded-lg p-2.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Ref. Surat Tugas</span>
                                        <div class="flex items-start">
                                            <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <div>
                                                <span class="text-xs font-semibold text-slate-700 block">{{ $item->suratTugas->nomor_surat ?? 'Tidak ada data' }}</span>
                                                @if($item->suratTugas && $item->suratTugas->tanggal_surat)
                                                    <span class="text-[10px] text-slate-500 mt-0.5 block">{{ \Carbon\Carbon::parse($item->suratTugas->tanggal_surat)->translatedFormat('d M Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10 align-top">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Rincian Penyedia BBM</span>
                                    
                                    <div class="mb-2">
                                        <span class="text-xs font-bold text-slate-800 block line-clamp-1">{{ $item->penyedia->name ?? 'Penyedia Belum Diset' }}</span>
                                        <span class="text-[10px] text-slate-500 font-medium block">{{ $item->tempat_pengambilan_bbm ?? '-' }}</span>
                                        @if($item->nomor_spbu)
                                            <span class="text-[10px] text-indigo-600 font-semibold mt-0.5 block">No. SPBU: {{ $item->nomor_spbu }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-50 border border-emerald-100 mt-1">
                                        <span class="font-bold text-emerald-700 text-[11px] mr-1 truncate max-w-[80px]" title="{{ $jenisBbm }}">{{ $jenisBbm }}</span>
                                        <span class="text-emerald-500 mx-1">|</span>
                                        <span class="font-extrabold text-emerald-800 text-[11px]">{{ rtrim(rtrim(number_format($jumlahLiter, 2, ',', '.'), '0'), ',') }} L</span>
                                    </div>
                                </td>

                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10 align-top">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Penanggung Jawab</span>
                                    
                                    <div class="mb-2">
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block mb-0.5">Nakhoda</span>
                                        <div class="font-bold text-slate-800 text-xs truncate">{{ $item->nama_nakhoda ?? 'Belum diisi' }}</div>
                                        @if($item->id_nakhoda)
                                            <div class="text-[10px] text-indigo-600 font-semibold mt-0.5">ID: {{ $item->id_nakhoda }}</div>
                                        @endif
                                    </div>

                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block mb-0.5">PPTK</span>
                                        <div class="font-bold text-slate-800 text-xs truncate">{{ $item->nama_pptk ?? 'Belum diisi' }}</div>
                                        @if($item->id_pptk)
                                            <div class="text-[10px] text-emerald-600 font-semibold mt-0.5">NIP: {{ $item->id_pptk }}</div>
                                        @endif
                                    </div>
                                </td>

                                <td class="flex justify-end md:table-cell px-4 py-4 md:px-6 md:py-5 md:text-right align-middle">
                                    <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto">
                                
                                        @if(auth()->user()?->role?->slug !== 'penyedia')
                                            <div x-data="{ uploading: false, progress: 0 }"
                                                 x-on:livewire-upload-start="uploading = true"
                                                 x-on:livewire-upload-finish="uploading = false"
                                                 x-on:livewire-upload-error="uploading = false"
                                                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                
                                                <input type="file" x-ref="fileInput_{{ $item->id }}" wire:model="upload_files.{{ $item->id }}" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                                
                                                @if($item->file_surat_permohonan)
                                                    <div class="flex gap-2">
                                                        <a href="{{ Storage::url($item->file_surat_permohonan) }}" target="_blank" class="flex-1 justify-center inline-flex items-center text-emerald-700 font-semibold bg-emerald-50 hover:bg-emerald-600 hover:text-white px-2 py-2 rounded-lg transition-all duration-200 border border-emerald-200 hover:border-emerald-600 shadow-sm text-[11px]" title="Lihat Dokumen">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            Lihat File
                                                        </a>
                                                        <button @click="$refs.fileInput_{{ $item->id }}.click()" class="justify-center inline-flex items-center text-amber-600 hover:text-white font-semibold bg-amber-50 hover:bg-amber-600 px-2 py-2 rounded-lg transition-all duration-200 border border-amber-100 text-[11px]" title="Update File">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <button @click="$refs.fileInput_{{ $item->id }}.click()" class="w-full justify-center inline-flex items-center text-blue-700 font-semibold bg-blue-50 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-blue-200 hover:border-blue-600 shadow-sm text-xs" :disabled="uploading">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                        <span x-show="!uploading">Upload Dokumen</span>
                                                        <span x-show="uploading" x-text="`Uploading... ${progress}%`"></span>
                                                    </button>
                                                @endif
                                            </div>
                                            <a href="{{ route('suratpermohonan.pdf.preview', $item->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm text-xs">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <span>Preview PDF</span>
                                            </a>
                                    
                                            <div class="flex flex-col gap-2">
                                                <div class="flex gap-2">
                                                    <button wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" wire:target="edit({{ $item->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100 text-xs disabled:opacity-70 disabled:cursor-not-allowed">
                                                        <svg wire:loading.remove wire:target="edit({{ $item->id }})" class="w-3.5 h-3.5 md:mr-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        <svg wire:loading wire:target="edit({{ $item->id }})" class="animate-spin w-3.5 h-3.5 md:mr-0 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                        <span class="md:hidden ml-1" wire:loading.remove wire:target="edit({{ $item->id }})">Edit</span>
                                                        <span class="md:hidden ml-1" wire:loading wire:target="edit({{ $item->id }})">...</span>
                                                    </button>
                                                    
                                                    <button wire:click="delete({{ $item->id }})" wire:loading.attr="disabled" wire:target="delete({{ $item->id }})" onclick="confirm('Yakin ingin menghapus laporan ini?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100 text-xs disabled:opacity-70 disabled:cursor-not-allowed">
                                                        <svg wire:loading.remove wire:target="delete({{ $item->id }})" class="w-3.5 h-3.5 md:mr-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        <svg wire:loading wire:target="delete({{ $item->id }})" class="animate-spin w-3.5 h-3.5 md:mr-0 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                        <span class="md:hidden ml-1" wire:loading.remove wire:target="delete({{ $item->id }})">Hapus</span>
                                                        <span class="md:hidden ml-1" wire:loading wire:target="delete({{ $item->id }})">...</span>
                                                    </button>
                                                </div>
                                            
                                                @if(in_array(auth()->user()?->role?->slug, ['superadmin', 'pptk']))
                                                    @if(!$item->disetujui_pptk_at)
                                                        <button wire:click="setujui({{ $item->id }})" wire:loading.attr="disabled" wire:target="setujui({{ $item->id }})" onclick="confirm('Setujui permohonan ini?') || event.stopImmediatePropagation()" class="w-full justify-center inline-flex items-center text-emerald-700 font-semibold bg-emerald-100 hover:bg-emerald-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-emerald-200 hover:border-emerald-600 shadow-sm text-xs disabled:opacity-70 disabled:cursor-not-allowed">
                                                            <svg wire:loading.remove wire:target="setujui({{ $item->id }})" class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            <svg wire:loading wire:target="setujui({{ $item->id }})" class="animate-spin w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                            <span wire:loading.remove wire:target="setujui({{ $item->id }})">Setujui</span>
                                                            <span wire:loading wire:target="setujui({{ $item->id }})">Proses...</span>
                                                        </button>
                                                    @else
                                                        <button wire:click="batalSetuju({{ $item->id }})" wire:loading.attr="disabled" wire:target="batalSetuju({{ $item->id }})" onclick="confirm('Batal setujui permohonan ini?') || event.stopImmediatePropagation()" class="w-full justify-center inline-flex items-center text-amber-700 font-semibold bg-amber-100 hover:bg-amber-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-amber-200 hover:border-amber-600 shadow-sm text-xs disabled:opacity-70 disabled:cursor-not-allowed">
                                                            <svg wire:loading.remove wire:target="batalSetuju({{ $item->id }})" class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            <svg wire:loading wire:target="batalSetuju({{ $item->id }})" class="animate-spin w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                            <span wire:loading.remove wire:target="batalSetuju({{ $item->id }})">Batal Setuju</span>
                                                            <span wire:loading wire:target="batalSetuju({{ $item->id }})">Proses...</span>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                        <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none">
                            <td colspan="5" class="block md:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Data Surat Tidak Ditemukan</h3>
                                <p class="text-sm text-gray-500">Gunakan pencarian atau buat surat permohonan baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $permohonans->links() }}
            </div>
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-opacity">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[95vh] flex flex-col">
                
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                            {{ $permohonan_id ? 'Edit Surat Permohonan BBM' : 'Buat Surat Permohonan Baru' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl text-sm p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1">
                    <form wire:submit.prevent="store" id="form-data-surat">
                        
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 mb-6">
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Tautkan ke Surat Tugas <span class="text-rose-500">*</span></label>
                            <select wire:model.live="surat_tugas_id" class="px-4 py-3 bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm cursor-pointer" required>
                                <option value="">-- Pilih Surat Tugas --</option>
                                @foreach($surat_tugas_list as $st)
                                    <option value="{{ $st->id }}">
                                        {{ $st?->nomor_surat ?: 'No. Surat Belum Ada' }} | 
                                        {{ \Carbon\Carbon::parse($st->tanggal_surat)->locale('id')->translatedFormat('l, d/M/Y') }} 
                                        (Kapal: {{ $st->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('surat_tugas_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            <p class="text-[10px] text-gray-500 mt-1.5 flex items-center"><svg class="w-3.5 h-3.5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Data Identitas Kapal akan disalin secara otomatis.</p>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-100 pb-2">Informasi Administrasi Surat</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat</label>
                                <input type="text" wire:model="nomor_surat" placeholder="Contoh: 001/PH.12.00" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                                @error('nomor_surat') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_surat" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                @error('tanggal_surat') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Klasifikasi</label>
                                <input type="text" wire:model="klasifikasi" placeholder="Biasa / Penting / Segera" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran</label>
                                <input type="text" wire:model="lampiran" placeholder="1 (satu) berkas" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-100 pb-2 mt-6">Detail Penyedia & Pengiriman BBM</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Perusahaan (Penyedia BBM)</label>
                                <select wire:model="penyedia_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors cursor-pointer">
                                    <option value="">-- Pilih Perusahaan Penyedia --</option>
                                    @foreach($penyediaList as $penyedia)
                                        <option value="{{ $penyedia->id }}">{{ $penyedia->name }}</option>
                                    @endforeach
                                </select>
                                @error('penyedia_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div x-data="{ jenis_penyedia: @entangle('jenis_penyedia_bbm') }">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Penyedia BBM</label>
                                <select x-model="jenis_penyedia" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors cursor-pointer mb-2">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Stasiun Pengisian Bahan Bakar Umum (SPBU)">Stasiun Pengisian Bahan Bakar Umum (SPBU)</option>
                                    <option value="Agen BBM">Agen BBM</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                
                                <div x-show="jenis_penyedia === 'Lainnya'" x-collapse>
                                    <input type="text" wire:model="jenis_penyedia_bbm_lainnya" placeholder="Tuliskan Jenis Penyedia..." class="px-4 py-2.5 bg-white border border-indigo-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-inner">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor SPBU</label>
                                <input type="text" wire:model="nomor_spbu" placeholder="Contoh: 31.102.02" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                                @error('nomor_spbu') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="block text-sm font-semibold text-slate-700">Tempat Pengambilan BBM</label>
                                    <button type="button" wire:click="setLokasiSama" class="text-[10px] bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold px-2 py-0.5 rounded border border-slate-200 transition-colors" {{ !$surat_tugas_id ? 'disabled class=opacity-50 cursor-not-allowed' : '' }}>
                                        Sama dg Surat Tugas
                                    </button>
                                </div>
                                <input type="text" wire:model="tempat_pengambilan_bbm" placeholder="Contoh: Muara Angke" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Metode Pengiriman</label>
                                <select wire:model="metode_pengiriman" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors cursor-pointer">
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="Ambil ditempat">Ambil di Tempat</option>
                                    <option value="Pengiriman Jalur Darat">Pengiriman Jalur Darat</option>
                                    <option value="Pengiriman Jalur Laut">Pengiriman Jalur Laut</option>
                                </select>
                            </div>
                            
                            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5 border-t border-slate-100 pt-5 mt-2">
                                <div x-data="{ jenis_bbm_state: @entangle('jenis_bbm') }">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Bahan Bakar</label>
                                    <select x-model="jenis_bbm_state" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors cursor-pointer mb-2">
                                        <option value="">-- Pilih Jenis BBM --</option>
                                        <option value="Pertamax/sekelas">Pertamax / Sekelas</option>
                                        <option value="Pertamina Dex/sekelas">Pertamina Dex / Sekelas</option>
                                        <option value="Dexlite/sekelas">Dexlite / Sekelas</option>
                                        <option value="Lainnya">Lainnya...</option>
                                    </select>
                                    
                                    <div x-show="jenis_bbm_state === 'Lainnya'" x-collapse>
                                        <input type="text" wire:model="jenis_bbm_lainnya" placeholder="Tuliskan Nama BBM..." class="px-4 py-2.5 bg-white border border-indigo-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-inner">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Bahan Bakar (Liter)</label>
                                    <div class="relative">
                                        <input type="text" wire:model="jumlah_bbm" placeholder="Contoh: 1500.50" class="pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500 font-bold">L</span>
                                    </div>
                                    @error('jumlah_bbm') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 border-b border-slate-100 pb-2 mt-6">Penanggung Jawab</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div x-data="{
                                users: {{ json_encode($nakhoda_users ?? []) }},
                                selectedUser: '',
                                fillData() {
                                    if (this.selectedUser) {
                                        let u = this.users.find(u => u.id == this.selectedUser);
                                        if (u) {
                                            $wire.nama_nakhoda = u.name;
                                            $wire.id_nakhoda = u.nip || '';
                                        }
                                    }
                                }
                            }" class="space-y-4 bg-white/50 p-4 rounded-xl border border-slate-200">
                                <div>
                                    <label class="block text-sm font-bold text-indigo-700 mb-1.5">Pilih Data Nakhoda</label>
                                    <select x-model="selectedUser" @change="fillData()" class="px-4 py-2.5 bg-indigo-50 border border-indigo-200 text-sm text-indigo-900 rounded-xl block w-full cursor-pointer focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Cari di Master User Nakhoda --</option>
                                        <template x-for="u in users" :key="u.id">
                                            <option :value="u.id" x-text="u.name + (u.nip ? ' - ' + u.nip : '')"></option>
                                        </template>
                                    </select>
                                    <p class="text-[10px] text-slate-500 mt-1.5">Otomatis mengisi form, atau ketik manual jika data tidak ada.</p>
                                </div>

                                <div class="space-y-4 pt-3 border-t border-slate-100">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Nakhoda <span class="text-rose-500">*</span></label>
                                        <input type="text" wire:model="nama_nakhoda" placeholder="Nama Lengkap Nakhoda..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full" required>
                                        @error('nama_nakhoda') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">ID Nakhoda</label>
                                        <input type="text" wire:model="id_nakhoda" placeholder="Contoh: 19800101..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                                        @error('id_nakhoda') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div x-data="{
                                users: {{ json_encode($pptk_users ?? []) }},
                                selectedUser: '',
                                fillData() {
                                    if (this.selectedUser) {
                                        let u = this.users.find(u => u.id == this.selectedUser);
                                        if (u) {
                                            $wire.nama_pptk = u.name;
                                            $wire.id_pptk = u.nip || '';
                                        }
                                    }
                                }
                            }" class="space-y-4 bg-white/50 p-4 rounded-xl border border-slate-200">
                                <div>
                                    <label class="block text-sm font-bold text-indigo-700 mb-1.5">Pilih Data PPTK</label>
                                    <select x-model="selectedUser" @change="fillData()" class="px-4 py-2.5 bg-indigo-50 border border-indigo-200 text-sm text-indigo-900 rounded-xl block w-full cursor-pointer focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Cari di Master User PPTK --</option>
                                        <template x-for="u in users" :key="u.id">
                                            <option :value="u.id" x-text="u.name + (u.nip ? ' - ' + u.nip : '')"></option>
                                        </template>
                                    </select>
                                    <p class="text-[10px] text-slate-500 mt-1.5">Otomatis mengisi form, atau ketik manual jika data tidak ada.</p>
                                </div>

                                <div class="space-y-4 pt-3 border-t border-slate-100">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama PPTK <span class="text-rose-500">*</span></label>
                                        <input type="text" wire:model="nama_pptk" placeholder="Nama Lengkap PPTK..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full" required>
                                        @error('nama_pptk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP/NRK PPTK</label>
                                        <input type="text" wire:model="id_pptk" placeholder="Contoh: 19900202..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                                        @error('id_pptk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </form>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end p-5 border-t border-slate-100 rounded-b-2xl sm:space-x-3 bg-slate-50/80 gap-3 sm:gap-0 shrink-0">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" form="form-data-surat" wire:loading.attr="disabled" wire:target="store" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-6 py-2.5 transition-all shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 disabled:opacity-75 disabled:cursor-not-allowed">
                        <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    
                        <span wire:loading.remove wire:target="store">Simpan Data</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>