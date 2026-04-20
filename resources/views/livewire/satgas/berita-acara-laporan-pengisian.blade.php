<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Berita Acara Laporan Pengisian</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Rekapitulasi dan legalitas berita acara pengisian BBM armada.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Buat Berita Acara
            </button>
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

        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. BA, PKS atau Kapal..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
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
                    <select wire:model.live="filterUkpd" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua SKPD/UKPD</option>
                        @foreach($ukpds as $ukpd) <option value="{{ $ukpd->id }}">{{ $ukpd->singkatan ?? $ukpd->nama }}</option> @endforeach
                    </select>
                </div>
                @endif
                <div class="relative w-full">
                    <select wire:model.live="filterKapal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua Armada Kapal</option>
                        @foreach($kapals as $kapal) <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option> @endforeach
                    </select>
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Dari Input</label>
                    <input type="date" wire:model.live="filterTanggalAwal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Sampai Input</label>
                    <input type="date" wire:model.live="filterTanggalAkhir" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="flex items-end w-full">
                    <button wire:click="resetFilters" class="w-full min-h-[34px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
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
                            <th class="px-6 py-5 font-bold tracking-wider w-[24%]">Info BA, Kapal & Status</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[26%]">Referensi Dokumen & Awak</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[36%]">Rekapitulasi BBM</th>
                            <th class="px-6 py-5 font-bold tracking-wider text-right w-[14%]">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group md:divide-y md:divide-gray-50 space-y-4 lg:space-y-0">
                        @forelse($laporans as $item)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-indigo-500 uppercase lg:hidden mb-2 block tracking-wider">Info BA & Kapal</span>
                                
                                <div class="flex flex-col gap-1 mb-2">
                                    <div class="flex items-center text-[11px] text-slate-500 font-medium">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Tgl BA: <span class="text-slate-700 ml-1">{{ isset($item->tgl_ba) ? \Carbon\Carbon::parse($item->tgl_ba)->locale('id')->format('d M Y') : '-' }}</span>
                                    </div>
                                    <div class="flex items-center text-[11px] text-slate-500 font-medium">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Tgl Pelaksanaan: <span class="text-indigo-600 font-bold ml-1">{{ isset($item->tgl_pelaksanaan) ? \Carbon\Carbon::parse($item->tgl_pelaksanaan)->locale('id')->format('d M Y') : '-' }}</span>
                                    </div>
                                </div>
                                
                                <div class="font-bold text-indigo-700 text-sm tracking-tight mb-0.5" title="Nomor Berita Acara">
                                    BA: {{ $item->nomor_ba ?? 'Belum ada No. BA' }}
                                </div>
                                <div class="font-semibold text-slate-600 text-xs tracking-tight mb-1" title="Nomor PKS">
                                    PKS: {{ $item->nomor_pks ?? 'Tanpa Referensi PKS' }}
                                </div>
                                
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-2 mt-2 w-fit pr-4">
                                    <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block mb-0.5">Armada Kapal</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-indigo-900">{{ $item->kapal?->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                        <span class="text-[9px] bg-indigo-200/50 text-indigo-700 px-1 rounded font-bold">{{ $item->kapal?->ukpd?->singkatan ?? '-' }}</span>
                                    </div>
                                </div>
        
                                <div class="mt-3 flex flex-col gap-2.5">
                                    <div>
                                        @if($item->disetujui_pptk_at)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-[10px] font-bold text-emerald-700 w-fit shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Penyedia & PPTK (OK)
                                            </div>
                                        @else
                                            <button wire:click="approve({{ $item->id }}, 'penyedia_pptk')" wire:confirm="Setujui BA ini atas nama Penyedia dan PPTK?" class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-amber-50 border border-amber-200 hover:bg-amber-100 text-[10px] font-bold text-amber-700 w-fit shadow-sm transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pending PPTK
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        @if($item->disetujui_kepala_ukpd_at)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-blue-50 border border-blue-200 text-[10px] font-bold text-blue-700 w-fit shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Kepala UKPD (OK)
                                            </div>
                                        @else
                                            <button @if(!$item->disetujui_pptk_at) disabled @endif wire:click="approve({{ $item->id }}, 'kepala_ukpd')" wire:confirm="Setujui BA ini sebagai Kepala UKPD?" class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-bold w-fit shadow-sm transition-colors {{ $item->disetujui_pptk_at ? 'bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100' : 'bg-slate-50 border border-slate-200 text-slate-400 cursor-not-allowed' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pending Ka. UKPD
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-1 block mt-2">Referensi & Awak</span>
                                
                                <div class="mb-3">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mb-0.5">Surat Permohonan Laporan</span>
                                    <div class="text-xs font-bold text-slate-800 line-clamp-2 leading-tight">
                                        {{ $item->laporanPengisian?->suratPermohonan?->nomor_surat ?? '-' }}
                                    </div>
                                </div>
        
                                <div class="flex items-center text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 shadow-sm inline-flex px-2 py-1 rounded-md mb-3 w-fit">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Tgl Isi (Master): {{ isset($item->laporanPengisian?->tanggal) ? \Carbon\Carbon::parse($item->laporanPengisian->tanggal)->format('d/m/Y') : '-' }}
                                </div>
        
                                <div class="bg-indigo-50/40 border border-indigo-100 rounded-lg p-2.5 mb-3">
                                    <span class="text-[9px] text-indigo-600 font-bold uppercase tracking-wider block mb-1.5">Penandatangan BA</span>
                                    <div class="space-y-1.5 text-[10px] text-slate-600">
                                        <div class="flex items-start">
                                            <span class="font-bold text-slate-800 w-16 shrink-0">Ka. UKPD:</span>
                                            <span class="font-medium text-slate-600">{{ $item->nama_kepala_ukpd ?? 'Belum diisi' }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-bold text-slate-800 w-16 shrink-0">PPTK:</span>
                                            <span class="font-medium text-slate-600">{{ $item->nama_pptk ?? 'Belum diisi' }}</span>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="font-bold text-slate-800 w-16 shrink-0">Nakhoda:</span>
                                            <span class="font-medium text-slate-600">{{ $item->nama_nakhoda ?? 'Belum diisi' }}</span>
                                        </div>
                                    </div>
                                </div>

                                @php $petugasList = $item->laporanPengisian?->suratTugas?->petugas ?? []; @endphp
                                <div>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block mb-1.5">Awak Kapal Lainnya (SPT)</span>
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($petugasList as $p)
                                            <div class="flex items-center gap-1 text-[10px] font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                                <div class="w-3 h-3 bg-indigo-200 text-indigo-700 rounded-full flex items-center justify-center text-[7px]">{{ substr($p->nama_petugas, 0, 1) }}</div>
                                                {{ explode(' ', $p->nama_petugas)[0] }}
                                            </div>
                                        @empty
                                            <span class="text-[10px] text-slate-400 italic">Belum ada awak</span>
                                        @endforelse
                                    </div>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Rekapitulasi BBM</span>
                                
                                <div class="grid grid-cols-4 gap-0 text-sm bg-slate-50 lg:bg-transparent rounded-xl lg:rounded-none border border-slate-200/60 lg:border-none overflow-hidden mt-1 lg:mt-0">
                                    <div class="flex flex-col p-2 lg:p-0 lg:pr-2 border-r border-slate-200/60 lg:border-none">
                                        <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Awal</span>
                                        <span class="font-semibold text-slate-700">{{ floatval($item->laporanPengisian?->suratTugas?->LaporanSisaBbm?->sounding?->bbm_awal ?? 0) }}</span>
                                    </div>
                                    <div class="flex flex-col p-2 lg:p-0 lg:pr-2 border-r border-slate-200/60 lg:border-none bg-emerald-50/30 lg:bg-transparent">
                                        <span class="text-[9px] text-emerald-500 uppercase font-bold tracking-wider">Isi</span>
                                        <span class="font-bold text-emerald-600">+{{ floatval($item->laporanPengisian?->jumlah_bbm_pengisian ?? 0) }}</span>
                                    </div>
                                    <div class="flex flex-col p-2 lg:p-0 lg:pr-2 border-r border-slate-200/60 lg:border-none bg-rose-50/30 lg:bg-transparent">
                                        <span class="text-[9px] text-rose-400 uppercase font-bold tracking-wider">Pakai</span>
                                        <span class="font-bold text-rose-500">-{{ floatval($item->laporanPengisian?->suratTugas?->LaporanSisaBbm?->sounding?->pemakaian_bbm ?? 0) }}</span>
                                    </div>
                                    <div class="flex flex-col p-2 lg:p-0 bg-blue-50/50 lg:bg-transparent">
                                        <span class="text-[9px] text-blue-500 uppercase font-bold tracking-wider">Akhir</span>
                                        <span class="font-extrabold text-blue-600">{{ floatval($item->laporanPengisian?->suratTugas?->LaporanSisaBbm?->sounding?->bbm_akhir ?? 0) }}</span>
                                    </div>
                                </div>
        
                                <div class="mt-3 space-y-1.5 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-[10px] text-slate-600 font-medium">Jam: 
                                            <span class="font-bold">{{ $item->laporanPengisian?->suratTugas?->LaporanSisaBbm?->sounding?->jam_berangkat ?? '-' }}</span> s.d 
                                            <span class="font-bold">{{ $item->laporanPengisian?->suratTugas?->LaporanSisaBbm?->sounding?->jam_kembali ?? '-' }}</span> WIB
                                        </span>
                                    </div>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto mt-2 lg:mt-0">
                                    
                                    <div x-data="{ uploading: false, progress: 0 }"
                                         x-on:livewire-upload-start="uploading = true"
                                         x-on:livewire-upload-finish="uploading = false"
                                         x-on:livewire-upload-error="uploading = false"
                                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        
                                        <input type="file" x-ref="fileInput_{{ $item->id }}" wire:model="upload_files.{{ $item->id }}" class="hidden" accept=".pdf,.png,.jpg,.jpeg">
                            
                                        @if($item->file_ba_pengisian)
                                            <div class="flex gap-2">
                                                <a href="{{ Storage::url($item->file_ba_pengisian) }}" target="_blank" class="flex-1 justify-center inline-flex items-center text-emerald-700 font-semibold bg-emerald-50 hover:bg-emerald-600 hover:text-white px-2 py-2 rounded-lg transition-all duration-200 border border-emerald-200 hover:border-emerald-600 shadow-sm text-[11px]" title="Lihat Dokumen BA">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Lihat File
                                                </a>
                                                <button @click="$refs.fileInput_{{ $item->id }}.click()" class="justify-center inline-flex items-center text-amber-600 hover:text-white font-semibold bg-amber-50 hover:bg-amber-600 px-2 py-2 rounded-lg transition-all duration-200 border border-amber-100 text-[11px]" title="Update File BA">
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
                                    <a href="{{ route('berita-acara.pdf.preview', $item->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm text-xs">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>Preview PDF</span>
                                    </a>
                            
                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $item->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100 shadow-sm">
                                            <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span class="lg:hidden ml-1">Edit</span>
                                        </button>
                                        
                                        <button wire:click="delete({{ $item->id }})" onclick="confirm('Yakin ingin menghapus Berita Acara ini beserta dokumennya?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100 shadow-sm">
                                            <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="lg:hidden ml-1">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
        
                        </tr>
                        @empty
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="4" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Berita Acara</h3>
                                <p class="text-sm text-gray-500">Mulai buat Berita Acara Pengisian BBM di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $laporans->links() }}
            </div>
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-opacity">
            <div @click.away="$wire.closeModal()" class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Form Berita Acara BBM</h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:text-slate-900 rounded-xl p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1">
                    <form wire:submit.prevent="store" id="form-ba">
                        
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 mb-6">
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Tautkan ke Laporan Pengisian <span class="text-rose-500">*</span></label>
                            <select wire:model.live="laporan_pengisian_bbm_id" class="px-4 py-3 bg-white border border-slate-200 text-sm rounded-xl block w-full shadow-sm cursor-pointer" required>
                                <option value="">-- Pilih Laporan --</option>
                                @foreach($laporan_pengisian_list as $lp)
                                    <option value="{{ $lp->id }}">Tgl: {{ $lp->tanggal }} | Kapal: {{ $lp->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-indigo-500 mt-2 font-medium">Memilih laporan otomatis memfilter pilihan pejabat & awak kapal sesuai UKPD armada yang bersangkutan.</p>
                        </div>

                        @if($laporan_pengisian_bbm_id)
                            @php 
                                $lpSelected = \App\Models\LaporanPengisianBbm::with([
                                    'suratPermohonan', 
                                    'suratTugas.petugas',
                                    'suratTugas.LaporanSisaBbm.sounding.kapal'
                                ])->find($laporan_pengisian_bbm_id); 
                                
                                $kapalSelected = $lpSelected?->suratTugas?->LaporanSisaBbm?->sounding?->kapal;
                            @endphp

                            <div class="flex flex-col gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-12 h-12 rounded-full bg-white shadow-sm text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                        </div>
                                        <div class="flex flex-col truncate">
                                            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest block mb-0.5">Armada Kapal</span>
                                            <p class="text-base font-extrabold text-slate-800 truncate" title="{{ $kapalSelected?->nama_kapal ?? 'Data kapal tidak ditemukan' }}">
                                                {{ $kapalSelected?->nama_kapal ?? 'Data kapal tidak ditemukan' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                        <span class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest block mb-1.5">Ref. Surat Permohonan</span>
                                        <p class="text-xs font-bold text-slate-800">{{ $lpSelected?->suratPermohonan?->nomor_surat ?? '-' }}</p>
                                    </div>
                                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                        <span class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest block mb-1.5">Daftar Awak Kapal (SPT)</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($lpSelected?->suratTugas?->petugas ?? [] as $p)
                                                <span class="bg-white shadow-sm px-2 py-1 rounded-md text-[10px] border border-slate-200 font-bold text-slate-700">{{ $p->nama_petugas ?? '-' }}</span>
                                            @empty
                                                <span class="text-[10px] text-slate-400 italic font-medium">Belum ada awak ditautkan</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-100 pb-2">Informasi Berita Acara</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Berita Acara</label>
                                <input type="text" wire:model="nomor_ba" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all" placeholder="Contoh: BA/123/2026">
                            </div>
                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal BA <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_ba" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all" required>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tgl Pelaksanaan <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_pelaksanaan" class="w-full px-4 py-2.5 bg-slate-50 border border-indigo-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all" required>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-100 pb-2">Data PKS / Kontrak</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor PKS</label>
                                <input type="text" wire:model="nomor_pks" list="pks_suggestions" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all" placeholder="Ketik atau pilih Nomor PKS">
                                <datalist id="pks_suggestions">
                                    @foreach($pks_suggestions as $pks) <option value="{{ $pks }}"> @endforeach
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal PKS</label>
                                <input type="date" wire:model="tanggal_pks" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all">
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-100 pb-2">Penanggung Jawab Berita Acara</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div x-data="{
                                selectedUser: '',
                                fillData() {
                                    if (this.selectedUser) {
                                        let u = $wire.kepala_ukpd_users.find(u => u.id == this.selectedUser);
                                        if (u) {
                                            $wire.nama_kepala_ukpd = u.name;
                                            $wire.nip_kepala_ukpd = u.nip || '';
                                        }
                                    }
                                }
                            }" class="space-y-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <div>
                                    <label class="block text-sm font-bold text-indigo-700 mb-1.5">Pilih Ka. UKPD</label>
                                    <select x-model="selectedUser" @change="fillData()" class="px-3 py-2 bg-indigo-50 border border-indigo-200 text-sm text-indigo-900 rounded-xl block w-full cursor-pointer focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Cari di Master Ka. UKPD --</option>
                                        <template x-for="u in $wire.kepala_ukpd_users" :key="u.id">
                                            <option :value="u.id" x-text="u.name + (u.nip ? ' - ' + u.nip : '')"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-3 pt-3 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Ka. UKPD <span class="text-rose-500">*</span></label>
                                        <input type="text" wire:model="nama_kepala_ukpd" placeholder="Nama Lengkap..." class="px-4 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP Ka. UKPD</label>
                                        <input type="text" wire:model="nip_kepala_ukpd" placeholder="NIP/NRK..." class="px-4 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full">
                                    </div>
                                </div>
                            </div>

                            <div x-data="{
                                selectedUser: '',
                                fillData() {
                                    if (this.selectedUser) {
                                        let u = $wire.pptk_users.find(u => u.id == this.selectedUser);
                                        if (u) {
                                            $wire.nama_pptk = u.name;
                                            $wire.nip_pptk = u.nip || '';
                                        }
                                    }
                                }
                            }" class="space-y-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <div>
                                    <label class="block text-sm font-bold text-indigo-700 mb-1.5">Pilih PPTK</label>
                                    <select x-model="selectedUser" @change="fillData()" class="px-3 py-2 bg-indigo-50 border border-indigo-200 text-sm text-indigo-900 rounded-xl block w-full cursor-pointer focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Cari di Master PPTK --</option>
                                        <template x-for="u in $wire.pptk_users" :key="u.id">
                                            <option :value="u.id" x-text="u.name + (u.nip ? ' - ' + u.nip : '')"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-3 pt-3 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama PPTK <span class="text-rose-500">*</span></label>
                                        <input type="text" wire:model="nama_pptk" placeholder="Nama Lengkap..." class="px-4 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP PPTK</label>
                                        <input type="text" wire:model="nip_pptk" placeholder="NIP/NRK..." class="px-4 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full">
                                    </div>
                                </div>
                            </div>

                            <div x-data="{
                                selectedUser: '',
                                fillData() {
                                    if (this.selectedUser) {
                                        let u = $wire.nakhoda_users.find(u => u.id == this.selectedUser);
                                        if (u) {
                                            $wire.nama_nakhoda = u.name;
                                            $wire.nip_nakhoda = u.nip || '';
                                        }
                                    }
                                }
                            }" class="space-y-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <div>
                                    <label class="block text-sm font-bold text-indigo-700 mb-1.5">Pilih Nakhoda</label>
                                    <select x-model="selectedUser" @change="fillData()" class="px-3 py-2 bg-indigo-50 border border-indigo-200 text-sm text-indigo-900 rounded-xl block w-full cursor-pointer focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Cari di Master Nakhoda --</option>
                                        <template x-for="u in $wire.nakhoda_users" :key="u.id">
                                            <option :value="u.id" x-text="u.name + (u.nip ? ' - ' + u.nip : '')"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="space-y-3 pt-3 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Nakhoda <span class="text-rose-500">*</span></label>
                                        <input type="text" wire:model="nama_nakhoda" placeholder="Nama Lengkap..." class="px-4 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP/ID Nakhoda</label>
                                        <input type="text" wire:model="nip_nakhoda" placeholder="NIP/ID..." class="px-4 py-2 bg-slate-50 border border-slate-200 text-sm rounded-lg block w-full">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>

                <div class="flex items-center justify-end p-5 border-t border-slate-100 bg-slate-50/80 gap-3 shrink-0 rounded-b-2xl">
                    <button wire:click="closeModal()" type="button" class="text-slate-700 bg-white border border-slate-300 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-ba" class="text-white bg-indigo-600 px-6 py-2.5 rounded-xl text-sm font-semibold shadow-md hover:bg-indigo-700 transition-all">Simpan Berita Acara</button>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>