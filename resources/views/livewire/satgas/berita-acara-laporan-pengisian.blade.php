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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. PKS atau Kapal..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
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
                <table class="w-full text-sm text-left text-gray-600 block xl:table">
                    <thead class="hidden xl:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-4 font-bold tracking-wider w-[20%]">Info Kapal & Waktu BA</th>
                            <th class="px-5 py-4 font-bold tracking-wider w-[25%]">Referensi Dokumen & Kontrak</th>
                            <th class="px-5 py-4 font-bold tracking-wider w-[25%]">Daftar Awak Kapal (Petugas)</th>
                            <th class="px-5 py-4 font-bold tracking-wider w-[18%]">Status Persetujuan</th>
                            <th class="px-5 py-4 font-bold tracking-wider text-right w-[12%]">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block xl:table-row-group md:divide-y md:divide-gray-50 space-y-4 xl:space-y-0">
                        @forelse($laporans as $item)
                        <tr class="block xl:table-row bg-white rounded-2xl xl:rounded-none shadow-sm xl:shadow-none border border-gray-100 xl:border-none hover:bg-slate-50/50 p-4 xl:p-0 transition-colors">
                            
                            <td class="block xl:table-cell px-2 py-3 xl:px-5 xl:py-4 border-b border-gray-50 xl:border-none align-top">
                                <span class="text-[10px] font-bold text-indigo-500 uppercase xl:hidden mb-2 block tracking-wider">Info Kapal & Waktu</span>
                                
                                <div class="font-bold text-slate-900 text-sm tracking-tight mb-2">
                                    {{ $item->kapal?->nama_kapal ?? 'Kapal Terhapus' }}
                                </div>
                                <div class="flex items-center text-[11px] text-slate-600 bg-slate-100 border border-slate-200 rounded px-2 py-1 font-semibold w-fit">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ isset($item->tgl_ba) ? \Carbon\Carbon::parse($item->tgl_ba)->locale('id')->format('l, d-M-Y') : '-' }}
                                </div>
                            </td>

                            <td class="block xl:table-cell px-2 py-3 xl:px-5 xl:py-4 border-b border-gray-50 xl:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase xl:hidden mb-2 block mt-2">Referensi & Kontrak</span>
                                
                                <div class="space-y-2">
                                    @if($item->nomor_pks)
                                    <div class="bg-emerald-50/50 p-2.5 rounded-lg border border-emerald-100">
                                        <span class="text-[9px] text-emerald-500 font-bold uppercase tracking-wider block mb-1">Data Kontrak / PKS</span>
                                        <div class="text-[11px] font-bold text-emerald-700">{{ $item->nomor_pks }}</div>
                                        @if($item->tanggal_pks)
                                            <div class="text-[9px] text-slate-500 mt-1">Tgl: {{ \Carbon\Carbon::parse($item->tanggal_pks)->format('d/m/Y') }}</div>
                                        @endif
                                    </div>
                                    @endif

                                    <div class="bg-blue-50/50 p-2.5 rounded-lg border border-blue-100">
                                        <span class="text-[9px] text-blue-500 font-bold uppercase tracking-wider block mb-1">Ref. Laporan Pengisian</span>
                                        <div class="flex items-center text-[11px] text-slate-700 mb-0.5">
                                            <span class="w-12 font-semibold">Tgl Isi</span>: {{ isset($item->laporanPengisian?->tanggal) ? \Carbon\Carbon::parse($item->laporanPengisian->tanggal)->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="flex items-center text-[11px] text-slate-700">
                                            <span class="w-12 font-semibold">Volume</span>: <span class="font-bold text-blue-700 ml-1">{{ floatval($item->laporanPengisian?->jumlah_bbm_pengisian ?? 0) }} L</span>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-indigo-50/50 p-2.5 rounded-lg border border-indigo-100">
                                        <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider block mb-1">Surat Permohonan</span>
                                        <div class="text-[11px] font-bold text-indigo-700 break-words leading-tight">
                                            {{ $item->laporanPengisian?->suratPermohonan?->nomor_surat ?? 'Tidak ada referensi surat' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="block xl:table-cell px-2 py-3 xl:px-5 xl:py-4 border-b border-gray-50 xl:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase xl:hidden mb-2 block mt-2">Daftar Awak Kapal</span>
                                
                                @php
                                    $petugasList = $item->laporanPengisian?->suratTugas?->petugas ?? [];
                                @endphp

                                @if(count($petugasList) > 0)
                                    <div class="space-y-1.5 max-h-[140px] overflow-y-auto custom-scrollbar pr-1">
                                        @foreach($petugasList as $petugas)
                                        <div class="flex items-center justify-between bg-slate-50 border border-slate-100 px-2 py-1.5 rounded-md">
                                            <div class="flex items-center gap-2 overflow-hidden">
                                                <div class="w-5 h-5 shrink-0 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-[9px] font-bold">
                                                    {{ substr($petugas->nama_petugas ?? 'U', 0, 1) }}
                                                </div>
                                                <div class="flex flex-col truncate">
                                                    <span class="text-[11px] font-bold text-slate-700 truncate" title="{{ $petugas->nama_petugas ?? '-' }}">{{ $petugas->nama_petugas ?? '-' }}</span>
                                                    <span class="text-[9px] text-slate-500 truncate" title="{{ $petugas->jabatan ?? '-' }}">{{ $petugas->jabatan ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex items-center justify-center p-3 border border-dashed border-slate-200 rounded-lg bg-slate-50 text-slate-400 text-[10px] italic">
                                        Belum ada data petugas tertaut
                                    </div>
                                @endif
                            </td>

                            <td class="block xl:table-cell px-2 py-3 xl:px-5 xl:py-4 border-b border-gray-50 xl:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase xl:hidden mb-2 block mt-2">Status Persetujuan</span>
                                
                                <div class="flex flex-col gap-2.5">
                                    <div>
                                        @if($item->disetujui_pptk_at)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1.5 rounded-md bg-emerald-50 border border-emerald-200 text-[10px] font-bold text-emerald-700 shadow-sm w-full">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Penyedia & PPTK OK
                                            </div>
                                            <div class="text-[9px] text-slate-500 font-medium ml-1 mt-0.5">
                                                {{ \Carbon\Carbon::parse($item->disetujui_pptk_at)->format('d/m/y H:i') }}
                                            </div>
                                        @else
                                            <button wire:click="approve({{ $item->id }}, 'penyedia_pptk')" wire:confirm="Setujui BA ini atas nama Penyedia dan PPTK?" class="w-full inline-flex justify-center items-center gap-1.5 px-2 py-1.5 rounded-md bg-amber-50 border border-amber-200 text-[10px] font-bold text-amber-700 shadow-sm hover:bg-amber-100 transition-colors">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pending PPTK
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        @if($item->disetujui_kepala_ukpd_at)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1.5 rounded-md bg-blue-50 border border-blue-200 text-[10px] font-bold text-blue-700 shadow-sm w-full">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Kepala UKPD OK
                                            </div>
                                            <div class="text-[9px] text-slate-500 font-medium ml-1 mt-0.5">
                                                {{ \Carbon\Carbon::parse($item->disetujui_kepala_ukpd_at)->format('d/m/y H:i') }}
                                            </div>
                                        @else
                                            <button @if(!$item->disetujui_pptk_at) disabled @endif wire:click="approve({{ $item->id }}, 'kepala_ukpd')" wire:confirm="Setujui BA ini sebagai Kepala UKPD?" class="w-full inline-flex justify-center items-center gap-1.5 px-2 py-1.5 rounded-md text-[10px] font-bold shadow-sm transition-colors {{ $item->disetujui_pptk_at ? 'bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100' : 'bg-slate-50 border border-slate-200 text-slate-400 cursor-not-allowed' }}">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pending Ka. UKPD
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="block xl:table-cell px-2 py-4 xl:px-5 xl:py-4 xl:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full xl:max-w-[120px] xl:ml-auto">
                                    
                                    <a href="{{ route('berita-acara.pdf.preview', $item->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>CETAK PDF</span>
                                    </a>

                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $item->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100 shadow-sm">
                                            <svg class="w-3.5 h-3.5 xl:mr-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span class="xl:hidden ml-1 text-xs">Edit</span>
                                        </button>
                                        
                                        <button wire:click="delete({{ $item->id }})" onclick="confirm('Yakin ingin menghapus BA ini?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100 shadow-sm">
                                            <svg class="w-3.5 h-3.5 xl:mr-0 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="xl:hidden ml-1 text-xs">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="block xl:table-row bg-white rounded-2xl xl:rounded-none shadow-sm xl:shadow-none border border-gray-100 xl:border-none">
                            <td colspan="5" class="block xl:table-cell px-6 py-16 text-center text-gray-500">
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
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.closeModal()" class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $laporan_id ? 'Edit Berita Acara BBM' : 'Form Berita Acara BBM' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-ba" class="space-y-6">
                        
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Tautkan Laporan Pengisian BBM (Data Master) <span class="text-rose-500">*</span></label>
                            <select wire:model.live="laporan_pengisian_bbm_id" class="w-full px-4 py-3 bg-white border border-slate-200 text-sm rounded-xl outline-none focus:ring-2 focus:ring-indigo-200 transition-all cursor-pointer shadow-sm" required>
                                <option value="">-- Pilih Laporan Pengisian yang Selesai --</option>
                                @foreach($laporan_pengisian_list as $lp)
                                    <option value="{{ $lp->id }}">
                                        Kapal: {{ $lp->suratTugas?->LaporanSisaBbm?->sounding?->kapal?->nama_kapal ?? 'Tidak ada data kapal' }} | Tgl Isi: {{ \Carbon\Carbon::parse($lp->tanggal)->format('d/m/Y') }} | (Vol: {{ floatval($lp->jumlah_bbm_pengisian) }} L)
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10.5px] text-slate-500 mt-2 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                Memilih laporan akan otomatis menarik referensi Surat Permohonan dan Awak Kapal (SPT).
                            </p>
                        </div>

                        @if($laporan_pengisian_bbm_id)
                            @php $lpSelected = \App\Models\LaporanPengisianBbm::with(['suratPermohonan', 'suratTugas.petugas'])->find($laporan_pengisian_bbm_id); @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                <div>
                                    <span class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest block mb-1">Ref. Surat Permohonan</span>
                                    <p class="text-xs font-bold text-slate-800">{{ $lpSelected?->suratPermohonan?->nomor_surat ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest block mb-1">Daftar Awak Kapal (SPT)</span>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($lpSelected?->suratTugas?->petugas ?? [] as $p)
                                            <span class="bg-indigo-50 px-2 py-0.5 rounded text-[10px] border border-indigo-100 font-semibold text-indigo-700">{{ $p->nama_petugas ?? '-' }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Manual Berita Acara</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Armada Kapal <span class="text-rose-500">*</span></label>
                                <select wire:model="kapal_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer" required>
                                    <option value="">-- Pilih Armada Kapal --</option>
                                    @foreach($kapals as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kapal }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Berita Acara <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_ba" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer" required>
                                <p class="text-[10px] text-slate-400 mt-1">Hari, bulan, dan tahun diekstrak otomatis oleh sistem.</p>
                            </div>

                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Data Kontrak / Kerja Sama</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Nomor PKS / Kontrak</label>
                                <input type="text" wire:model="nomor_pks" list="pks_suggestions" placeholder="Pilih yang sudah ada atau ketik baru..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-text">
                                <datalist id="pks_suggestions">
                                    @foreach($pks_suggestions as $pks) <option value="{{ $pks }}"> @endforeach
                                </datalist>
                                <p class="text-[10px] text-slate-400 mt-1">Sistem otomatis merekomendasikan Nomor PKS yang pernah digunakan.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal PKS / Kontrak</label>
                                <input type="date" wire:model="tanggal_pks" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer">
                            </div>
                        </div>

                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="closeModal()" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-ba" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow active:scale-95 transition-all">Simpan Berita Acara</button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>