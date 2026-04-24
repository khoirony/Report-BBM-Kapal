<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <path d="M9 15l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Surat SPJ</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Unggah, pantau, dan setujui Surat Pertanggungjawaban (SPJ).</p>
                </div>
            </div>
            
            @if(in_array(auth()->user()?->role?->slug, ['satgas', 'admin_ukpd', 'superadmin']))
            <button wire:click="openModal()" wire:loading.attr="disabled" wire:target="openModal" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 disabled:opacity-75 disabled:cursor-not-allowed">
                <svg wire:loading.remove wire:target="openModal" class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                <svg wire:loading wire:target="openModal" class="animate-spin w-5 h-5 mr-2 -ml-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                
                <span wire:loading.remove wire:target="openModal">Tambah SPJ Baru</span>
                <span wire:loading wire:target="openModal">Memuat...</span>
            </button>
            @endif
        </div>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl shadow-sm flex justify-between items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-100 p-1 rounded-full mr-3"><svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <p class="text-sm font-semibold text-emerald-800">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6 rounded-r-xl shadow-sm flex justify-between items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-rose-100 p-1 rounded-full mr-3"><svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <p class="text-sm font-semibold text-rose-800">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" placeholder="Fitur pencarian (Opsional)..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm" disabled>
                </div>
        
                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-500">
                        <svg x-show="!showFilters" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <svg x-show="showFilters" style="display: none;" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'">Filter</span>
                    </button>
                </div>
            </div>
        
            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 border-t border-slate-100 transition-all duration-200">
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Pilih Kapal</label>
                    <select wire:model.live="filter_kapal_id" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer relative z-0">
                        <option value="">Semua Armada Kapal</option>
                        @foreach($kapals as $kapal) <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option> @endforeach
                    </select>
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Dari Tanggal</label>
                    <input type="date" wire:model.live="filter_start_date" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Sampai Tanggal</label>
                    <input type="date" wire:model.live="filter_end_date" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="flex items-end w-full">
                    <button wire:click="$set('filter_start_date', null); $set('filter_end_date', null); $set('filter_kapal_id', null);" class="w-full min-h-[34px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
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
                            <th class="px-6 py-5 font-bold tracking-wider w-[30%]">Info Surat & Kapal</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[20%]">Tgl & Biaya SPJ</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[25%]">Status & Dokumen</th>
                            <th class="px-6 py-5 font-bold tracking-wider text-right w-[25%]">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group md:divide-y md:divide-gray-50 space-y-4 lg:space-y-0">
                        @forelse($spjs as $spj)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-indigo-500 uppercase lg:hidden mb-2 block tracking-wider">Info SPJ</span>
                                
                                <div class="font-bold text-slate-900 text-sm tracking-tight mb-1" title="Nomor SPJ">
                                    {{ $spj->nomor_spj }}
                                </div>
                                
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-2 mt-2 w-fit pr-4">
                                    <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block mb-0.5">Armada Kapal</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-indigo-900">{{ $spj->kapal->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                        @if($spj->kapal && $spj->kapal->ukpd)
                                        <span class="text-[9px] bg-indigo-200/50 text-indigo-700 px-1 rounded font-bold">{{ $spj->kapal->ukpd->singkatan ?? '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-[10px] text-slate-500 font-medium mt-2">
                                    Dibuat oleh: <span class="text-slate-700">{{ $spj->creator->name ?? '-' }}</span>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-1 block mt-2">Tgl & Biaya SPJ</span>
                                
                                <div class="flex items-center text-sm text-slate-800 font-semibold mb-2">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($spj->tanggal_spj)->format('d F Y') }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Total Biaya:</span>
                                    <span class="text-sm font-extrabold text-indigo-600 tracking-tight">
                                        Rp {{ number_format($spj->total_biaya, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Status & Dokumen</span>
                                
                                <div class="space-y-3">
                                    <div>
                                        @if($spj->disetujui_pptk_at)
                                            <div class="flex items-center gap-1.5 text-emerald-700 font-bold text-[11px]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                ACC PPTK
                                            </div>
                                            <div class="text-[10px] text-slate-500 leading-tight">
                                                Oleh: {{ $spj->pemberiPersetujuanPptk->name ?? '-' }} <br>
                                                {{ \Carbon\Carbon::parse($spj->disetujui_pptk_at)->format('d/m/y H:i') }}
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 text-amber-600 font-bold text-[11px]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Menunggu PPTK
                                            </div>
                                        @endif
                                    </div>
                            
                                    <div>
                                        @if($spj->disetujui_kepala_ukpd_at)
                                            <div class="flex items-center gap-1.5 text-blue-700 font-bold text-[11px]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                ACC Ka. UKPD
                                            </div>
                                            <div class="text-[10px] text-slate-500 leading-tight">
                                                Oleh: {{ $spj->pemberiPersetujuanKaUkpd->name ?? '-' }} <br>
                                                {{ \Carbon\Carbon::parse($spj->disetujui_kepala_ukpd_at)->format('d/m/y H:i') }}
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 text-slate-400 font-bold text-[11px]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Menunggu Ka. UKPD
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            
                                <div class="mt-4">
                                    <a href="{{ Storage::url($spj->file_spj) }}" target="_blank" class="inline-flex items-center text-[11px] font-bold text-indigo-600 hover:underline">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        LIHAT DOKUMEN
                                    </a>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[150px] lg:ml-auto mt-2 lg:mt-0">
                                    
                                    <a href="{{ route('spj.pdf.preview', $spj->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>Preview PDF</span>
                                    </a>

                                    @php $role = auth()->user()?->role?->slug; @endphp
                                    @if(in_array($role, ['satgas', 'admin_ukpd', 'superadmin']))
                                        <div class="flex gap-2">
                                            <button wire:click="edit({{ $spj->id }})" wire:loading.attr="disabled" wire:target="edit({{ $spj->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100 text-xs disabled:opacity-70 disabled:cursor-not-allowed">
                                                <svg wire:loading.remove wire:target="edit({{ $spj->id }})" class="w-3.5 h-3.5 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                <svg wire:loading wire:target="edit({{ $spj->id }})" class="animate-spin w-3.5 h-3.5 mr-1 lg:mr-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span class="lg:hidden ml-1" wire:loading.remove wire:target="edit({{ $spj->id }})">Edit</span>
                                                <span class="lg:hidden ml-1" wire:loading wire:target="edit({{ $spj->id }})">...</span>
                                            </button>
                                            
                                            <button wire:click="delete({{ $spj->id }})" wire:loading.attr="disabled" wire:target="delete({{ $spj->id }})" onclick="confirm('Yakin ingin menghapus laporan ini beserta filenya?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100 text-xs disabled:opacity-70 disabled:cursor-not-allowed">
                                                <svg wire:loading.remove wire:target="delete({{ $spj->id }})" class="w-3.5 h-3.5 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <svg wire:loading wire:target="delete({{ $spj->id }})" class="animate-spin w-3.5 h-3.5 mr-1 lg:mr-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span class="lg:hidden ml-1" wire:loading.remove wire:target="delete({{ $spj->id }})">Hapus</span>
                                                <span class="lg:hidden ml-1" wire:loading wire:target="delete({{ $spj->id }})">...</span>
                                            </button>
                                        </div>
                                    @endif
                                    
                                    @if(in_array($role, ['pptk', 'superadmin']))
                                        @if(is_null($spj->disetujui_pptk_at))
                                            <button wire:click="approve({{ $spj->id }})" wire:loading.attr="disabled" wire:target="approve({{ $spj->id }})" wire:confirm="Setujui sebagai PPTK?" class="w-full justify-center inline-flex items-center text-emerald-700 font-bold bg-emerald-50 hover:bg-emerald-600 hover:text-white px-3 py-2 rounded-lg border border-emerald-200 transition-all text-[11px] disabled:opacity-70 disabled:cursor-not-allowed">
                                                <svg wire:loading wire:target="approve({{ $spj->id }})" class="animate-spin mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span wire:loading.remove wire:target="approve({{ $spj->id }})">APPROVE PPTK</span>
                                                <span wire:loading wire:target="approve({{ $spj->id }})">PROSES...</span>
                                            </button>
                                        @elseif(!is_null($spj->disetujui_pptk_at) && is_null($spj->disetujui_kepala_ukpd_at))
                                            <button wire:click="cancelApprove({{ $spj->id }})" wire:loading.attr="disabled" wire:target="cancelApprove({{ $spj->id }})" wire:confirm="Batalkan persetujuan PPTK?" class="w-full justify-center inline-flex items-center text-rose-700 font-bold bg-rose-50 hover:bg-rose-600 hover:text-white px-3 py-2 rounded-lg border border-rose-200 transition-all text-[11px] disabled:opacity-70 disabled:cursor-not-allowed">
                                                <svg wire:loading wire:target="cancelApprove({{ $spj->id }})" class="animate-spin mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span wire:loading.remove wire:target="cancelApprove({{ $spj->id }})">BATAL ACC PPTK</span>
                                                <span wire:loading wire:target="cancelApprove({{ $spj->id }})">PROSES...</span>
                                            </button>
                                        @endif
                                    @endif

                                    @if(in_array($role, ['kepala_ukpd', 'superadmin']))
                                        @if(is_null($spj->disetujui_kepala_ukpd_at))
                                            <button wire:click="approve({{ $spj->id }})" wire:loading.attr="disabled" wire:target="approve({{ $spj->id }})" wire:confirm="Setujui sebagai Kepala UKPD?" class="w-full justify-center inline-flex items-center text-blue-700 font-bold bg-blue-50 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-lg border border-blue-200 transition-all text-[11px] disabled:opacity-70 disabled:cursor-not-allowed">
                                                <svg wire:loading wire:target="approve({{ $spj->id }})" class="animate-spin mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span wire:loading.remove wire:target="approve({{ $spj->id }})">APPROVE KA. UKPD</span>
                                                <span wire:loading wire:target="approve({{ $spj->id }})">PROSES...</span>
                                            </button>
                                        @elseif(!is_null($spj->disetujui_kepala_ukpd_at))
                                            <button wire:click="cancelApprove({{ $spj->id }})" wire:loading.attr="disabled" wire:target="cancelApprove({{ $spj->id }})" wire:confirm="Batalkan persetujuan Kepala UKPD?" class="w-full justify-center inline-flex items-center text-rose-700 font-bold bg-rose-50 hover:bg-rose-600 hover:text-white px-3 py-2 rounded-lg border border-rose-200 transition-all text-[11px] disabled:opacity-70 disabled:cursor-not-allowed">
                                                <svg wire:loading wire:target="cancelApprove({{ $spj->id }})" class="animate-spin mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span wire:loading.remove wire:target="cancelApprove({{ $spj->id }})">BATAL ACC KA. UKPD</span>
                                                <span wire:loading wire:target="cancelApprove({{ $spj->id }})">PROSES...</span>
                                            </button>
                                        @endif
                                    @endif
                            
                                    @if(($role === 'satgas' && !is_null($spj->disetujui_pptk_at)) || ($role === 'pptk' && !is_null($spj->disetujui_kepala_ukpd_at)))
                                        <span class="text-[10px] text-slate-400 font-medium italic lg:text-right block">No Action Available</span>
                                    @endif
                            
                                </div>
                            </td>
        
                        </tr>
                        @empty
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="4" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Dokumen SPJ</h3>
                                <p class="text-sm text-gray-500">Dokumen SPJ yang diunggah akan tampil di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $spjs->links() }}
            </div>
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.closeModal()" class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $spj_id ? 'Edit Dokumen SPJ' : 'Buat Dokumen SPJ Baru' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-spj" class="space-y-6">
                        
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Utama SPJ</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tautkan Proses Penyedia BBM (Opsional)</label>
                                <select wire:model.live="proses_penyedia_bbm_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer">
                                    <option value="">-- Tidak Ditautkan (Input Manual) --</option>
                                    @foreach($proses_penyedia_list as $proses)
                                        <option value="{{ $proses->id }}">
                                            Ref Permohonan: {{ $proses->suratPermohonan->nomor_surat ?? 'Tanpa Nomor' }} | Rp {{ number_format($proses->total_harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('proses_penyedia_bbm_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Nomor SPJ</label>
                                <input type="text" wire:model="nomor_spj" placeholder="Masukkan nomor surat..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                @error('nomor_spj') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="col-span-1 md:col-span-2">
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="block text-sm font-semibold text-slate-800">Pilih Kapal <span class="text-rose-500">*</span></label>
                                    <button type="button" wire:click="tarikKapalPenyedia" class="text-[10px] bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold px-2 py-0.5 rounded border border-slate-200 transition-colors {{ !$proses_penyedia_bbm_id ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$proses_penyedia_bbm_id ? 'disabled' : '' }}>
                                        Tarik dr Penyedia
                                    </button>
                                </div>
                                <select wire:model="kapal_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer" required>
                                    <option value="">-- Silakan Pilih Kapal --</option>
                                    @foreach($kapals as $kapal)
                                        <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                                    @endforeach
                                </select>
                                @error('kapal_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal SPJ <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_spj" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer" required>
                                @error('tanggal_spj') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                    
                            <div class="col-span-1">
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="block text-sm font-semibold text-slate-800">Total Biaya <span class="text-rose-500">*</span></label>
                                    <button type="button" wire:click="tarikBiayaPenyedia" class="text-[10px] bg-slate-100 hover:bg-slate-200 text-indigo-700 font-bold px-2 py-0.5 rounded border border-slate-200 transition-colors {{ !$proses_penyedia_bbm_id ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$proses_penyedia_bbm_id ? 'disabled' : '' }}>
                                        Tarik dr Penyedia
                                    </button>
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-slate-500 text-sm font-semibold">Rp</span>
                                    <input type="number" wire:model="total_biaya" placeholder="0" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block transition-colors" required min="0">
                                </div>
                                @error('total_biaya') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Unggah Dokumen (Telah di-TTD)</h4>
                        
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <input type="file" wire:model="file_spj" accept=".pdf, image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" {{ $spj_id ? '' : 'required' }}>
                            <div wire:loading wire:target="file_spj" class="text-xs text-indigo-600 mt-2 font-semibold flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Mengunggah dokumen...
                            </div>
                            @if($spj_id)
                                <p class="text-[11px] text-amber-600 mt-2 font-medium">*Kosongkan jika tidak ingin mengubah dokumen SPJ saat ini.</p>
                            @else
                                <p class="text-[11px] text-slate-500 mt-2">Format yang didukung: PDF, JPG, PNG (Maksimal 5MB).</p>
                            @endif
                            @error('file_spj') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    
                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="closeModal()" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-spj" 
                        wire:loading.attr="disabled" 
                        wire:target="file_spj, store"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center">
                        
                        <svg wire:loading wire:target="store" class="animate-spin mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span wire:loading.remove wire:target="store">
                            {{ $spj_id ? 'Simpan Perubahan' : 'Simpan SPJ' }}
                        </span>
                        <span wire:loading wire:target="store">
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>