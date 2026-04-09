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
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Surat Tugas Pengisian BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Pengelolaan Surat Penugasan Pengisian BBM Kapal.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Buat Surat Tugas
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. Surat, Kapal, Lokasi..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>
        
                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-500">
                        <svg x-show="!showFilters" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <svg x-show="showFilters" style="display: none;" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'"></span>
                    </button>
        
                    <div class="relative flex-1 md:flex-none md:w-48">
                        <select wire:model.live="sortBy" class="pl-4 pr-8 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full appearance-none shadow-sm hover:bg-slate-100 cursor-pointer">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
            </div>
        
            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 pt-4 border-t border-slate-100 transition-all duration-200">
                
                @if (auth()->user()->role == 'superadmin')
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
            
            <div wire:loading.delay.longest class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden md:flex items-center justify-center rounded-2xl">
                <div class="w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 block lg:table">
                    <thead class="hidden lg:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold w-1/3">Identitas Surat</th>
                            <th scope="col" class="px-6 py-5 font-bold w-2/5">Informasi Umum & Laporan</th>
                            <th scope="col" class="px-6 py-5 font-bold text-right w-1/4">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group space-y-4 lg:space-y-0 lg:divide-y lg:divide-gray-50">
                        @forelse($surat_tugas as $surat)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Identitas Surat</span>
                                
                                <div class="mb-3">
                                    <div class="bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 inline-block mb-1">
                                        <span class="font-bold text-gray-800 tracking-wide">{{ $surat->nomor_surat }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium ml-1">
                                        Dikeluarkan: <span class="text-gray-700 font-bold">{{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('d M Y') }}</span>
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Waktu Pelaksanaan</span>
                                    <div class="flex flex-wrap gap-2">
                                        <div class="inline-flex items-center text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-bold text-xs">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $surat->waktu_pelaksanaan }}
                                        </div>
                                        
                                        @if(auth()->user() && auth()->user()->role === 'superadmin')
                                            <div class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100" title="Ditambahkan oleh">
                                                <svg class="w-3 h-3 text-indigo-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $surat->user->name ?? 'Sistem / Terhapus' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Informasi Umum & Laporan</span>
                                
                                @if($surat->laporanSisaBbm)
                                    <div class="bg-white border border-gray-100 rounded-xl p-3 shadow-sm">
                                        
                                        <div class="flex items-center justify-between border-b border-gray-50 pb-2 mb-2">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center mr-3 flex-shrink-0">
                                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="5" r="3"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="22"></line>
                                                        <path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900 text-sm">
                                                        {{ $surat->laporanSisaBbm->sounding->kapal->nama_kapal ?? 'Kapal Tidak Ditemukan' }}
                                                    </p>
                                                    <div class="flex items-center gap-1.5 mt-0.5">
                                                        <span class="text-[9px] bg-indigo-100 text-indigo-700 px-1 rounded font-bold">{{ $surat->laporanSisaBbm->sounding->kapal->ukpd->singkatan ?? 'UKPD' }}</span>
                                                        <p class="text-[10px] text-gray-500">Lap ID: #{{ $surat->laporanSisaBbm->id }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded font-bold">{{ $surat->laporanSisaBbm->sounding->kapal->jenis_dan_tipe ?? '-' }}</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                            <div class="flex items-start">
                                                <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-gray-700">
                                                    {{ \Carbon\Carbon::parse($surat->laporanSisaBbm->tanggal_surat)->locale('id')->translatedFormat('l, d M Y') }}
                                                </span>
                                            </div>
                                            <div class="flex items-start">
                                                <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                <span class="text-gray-700 truncate" title="{{ $surat->laporanSisaBbm->sounding->keterangan ?? '-' }}">
                                                    {{ $surat->laporanSisaBbm->sounding->keterangan ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                @else
                                    <div class="bg-rose-50 text-rose-600 border border-rose-100 p-3 rounded-xl text-xs font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Laporan BBM terkait telah dihapus dari sistem.
                                    </div>
                                @endif
                            </td>

                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto">
                                    
                                    <a href="{{ route('surattugas.pdf.preview', $surat->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>PDF</span>
                                    </a>

                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $surat->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <button wire:click="delete({{ $surat->id }})" onclick="confirm('Yakin hapus Surat Tugas ini?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="3" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Surat Tugas</h3>
                                <p class="text-sm text-gray-500">Mulai kelola surat penugasan baru di sini.</p>
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
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[90vh] flex flex-col">
                
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                        {{ $surat_id ? 'Edit Surat Tugas' : 'Buat Surat Tugas' }}
                    </h3>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 rounded-xl p-2 border shadow-sm transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1 p-4 sm:p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-surat">
                        <div class="space-y-5">
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tautkan Laporan BBM <span class="text-rose-500">*</span></label>
                                <select wire:model="laporan_pengisian_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer" required>
                                    <option value="">-- Pilih Laporan BBM --</option>
                                    @foreach($laporans as $lap)
                                        <option value="{{ $lap->id }}">Lap #{{ $lap->nomor }} - {{ $lap->sounding->kapal->nama_kapal ?? 'Kapal' }} (Tgl: {{ \Carbon\Carbon::parse($lap->tanggal_surat)->format('d/m/Y') }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1">Data Kapal, Tanggal, Lokasi, dan Petugas akan otomatis terisi di PDF berdasarkan Laporan ini.</p>
                            </div>

                            <div class="border-t border-slate-100 my-2"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="nomor_surat" placeholder="Contoh: 001/PH.12.00/2026" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Dikeluarkan <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model="tanggal_dikeluarkan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Pelaksanaan (Pukul) <span class="text-rose-500">*</span></label>
                                <div class="flex items-center">
                                    <input type="text" wire:model="waktu_pelaksanaan" placeholder="Contoh: 08:00 - Selesai" class="px-4 py-2.5 bg-slate-50 border border-slate-200 border-r-0 text-sm rounded-l-xl w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <span class="px-3 py-2.5 bg-gray-100 border border-gray-200 text-sm rounded-r-xl font-bold text-gray-600">WIB</span>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end p-4 sm:p-6 border-t border-slate-100 rounded-b-2xl bg-slate-50/80 gap-3">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto px-5 py-2.5 border border-slate-300 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-sm transition-colors order-2 sm:order-1">Batal</button>
                    <button type="submit" form="form-surat" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors order-1 sm:order-2">Simpan Surat</button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>