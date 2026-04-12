<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 16v-4"/><path d="M8 16v-2"/><path d="M16 16v-6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Laporan Sisa BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Rekapitulasi sisa bahan bakar kapal sebelum pengisian.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Buat Laporan
            </button>
        </div>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl shadow-sm animate-fade-in-down flex justify-between items-center">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-100 p-1 rounded-full">
                        <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-emerald-800">{{ session('message') }}</p>
                    </div>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor, perihal, nakhoda..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>

                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm">
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'">Filter</span>
                    </button>

                    <div class="relative flex-1 md:flex-none md:w-48">
                        <select wire:model.live="sortBy" class="pl-4 pr-8 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full appearance-none cursor-pointer">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 pt-4 border-t border-slate-100">
                
                @if (auth()->user()?->role?->slug == 'superadmin')
                <div class="relative w-full">
                    <select wire:model.live="filterUkpd" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua SKPD/UKPD</option>
                        @foreach($ukpds as $ukpd)
                            <option value="{{ $ukpd->id }}">{{ $ukpd->singkatan ?? $ukpd->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="relative w-full">
                    <select wire:model.live="filterKapal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua Kapal</option>
                        @foreach($kapals as $kapal)
                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal ?? $kapal->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Dari Tgl</label>
                    <input type="date" wire:model.live="filterTanggalDari" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full">
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Sampai Tgl</label>
                    <input type="date" wire:model.live="filterTanggalSampai" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full">
                </div>
                <div class="flex items-end w-full">
                    <button wire:click="resetFilters" class="w-full min-h-[34px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg border border-rose-100 transition-colors">
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
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Info Laporan & Kapal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Detail Surat</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Penanggung Jawab</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Data Sounding</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group space-y-4 lg:space-y-0 lg:divide-y lg:divide-gray-50">
                        @forelse($laporans as $laporan)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Info Laporan & Kapal</span>
                                <div class="flex flex-col mb-3">
                                    <div class="flex items-center gap-1 mb-1">
                                        <span class="font-semibold text-gray-900">
                                            {{ $laporan->tanggal_surat ? \Carbon\Carbon::parse($laporan->tanggal_surat)->format('d M Y') : '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                            No: {{ $laporan->nomor }}
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-2.5">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block">Kapal Terkait</span>
                                        <span class="text-[9px] bg-indigo-200/50 text-indigo-700 px-1 rounded font-bold">{{ $laporan->ukpd->singkatan ?? $laporan->sounding->kapal->ukpd->singkatan ?? '-' }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-indigo-900 truncate">{{ $laporan->sounding->kapal->nama_kapal ?? 'Kapal Terhapus' }}</p>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Detail Surat</span>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Perihal</span>
                                        <p class="text-xs font-semibold text-gray-800 line-clamp-2" title="{{ $laporan->perihal }}">{{ $laporan->perihal }}</p>
                                    </div>
                                    <div class="flex gap-4">
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Klasifikasi</span>
                                            <span class="text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">{{ $laporan->klasifikasi ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Lampiran</span>
                                            <span class="text-xs text-gray-700 bg-gray-100 px-2 py-0.5 rounded inline-block mt-1">{{ $laporan->lampiran ?: '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Penanggung Jawab</span>
                                <div class="space-y-3">
                                    <div class="flex items-start text-xs">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-2 text-[10px] flex-shrink-0 mt-0.5">
                                            N
                                        </div>
                                        <div class="flex flex-col truncate">
                                            <span class="font-bold text-gray-800 truncate">{{ $laporan->nama_nakhoda }}</span>
                                            <span class="text-[10px] text-gray-500 truncate">Nakhoda</span>
                                            @if($laporan->id_nakhoda)
                                                <span class="text-[10px] text-blue-600 font-medium">NIP/NRK: {{ $laporan->id_nakhoda }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-start text-xs">
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold mr-2 text-[10px] flex-shrink-0 mt-0.5">
                                            P
                                        </div>
                                        <div class="flex flex-col truncate">
                                            <span class="font-bold text-gray-800 truncate">{{ $laporan->nama_pengawas }}</span>
                                            <span class="text-[10px] text-gray-500 truncate">Pengawas UPAP</span>
                                            @if($laporan->id_pengawas)
                                                <span class="text-[10px] text-emerald-600 font-medium">NIP/NRK: {{ $laporan->id_pengawas }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top lg:w-1/4 w-full">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Data Sounding</span>
                                @if($laporan->sounding)
                                    <div class="bg-blue-50/40 border border-blue-100 rounded-lg p-2.5 relative overflow-hidden">
                                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-400"></div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold text-blue-900 truncate pr-2">{{ $laporan->sounding->keterangan }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-1 sm:grid-cols-4 sm:gap-0 text-[9px] text-center bg-white rounded border border-blue-50 p-1.5 shadow-sm">
                                            <div class="flex flex-col sm:border-r border-gray-50 sm:pb-0 pb-1 border-b sm:border-b-0">
                                                <span class="text-gray-400 uppercase font-bold tracking-wider mb-0.5">Awal</span>
                                                <span class="font-bold text-gray-700">{{ floatval($laporan->sounding->bbm_awal) }}</span>
                                            </div>
                                            <div class="flex flex-col sm:border-r border-gray-50 sm:pb-0 pb-1 border-b sm:border-b-0 sm:border-l-0 border-l border-gray-50">
                                                <span class="text-gray-400 uppercase font-bold tracking-wider mb-0.5">Isi</span>
                                                <span class="font-bold text-emerald-600">+{{ floatval($laporan->sounding->pengisian) }}</span>
                                            </div>
                                            <div class="flex flex-col sm:border-r border-gray-50 pt-1 sm:pt-0">
                                                <span class="text-gray-400 uppercase font-bold tracking-wider mb-0.5">Pakai</span>
                                                <span class="font-bold text-rose-500">-{{ floatval($laporan->sounding->pemakaian) }}</span>
                                            </div>
                                            <div class="flex flex-col bg-blue-50/50 rounded-br-sm sm:rounded-r-sm pt-1 sm:pt-0 border-l border-gray-50 sm:border-l-0">
                                                <span class="text-blue-400 uppercase font-bold tracking-wider mb-0.5">Akhir</span>
                                                <span class="font-extrabold text-blue-700">{{ floatval($laporan->sounding->bbm_akhir) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-red-500 italic">Data sounding terhapus</span>
                                @endif
                            </td>

                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto">
                                    
                                    <a href="{{ route('laporan-sisa-bbm.pdf.preview', $laporan->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>PDF</span>
                                    </a>

                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $laporan->id }})" class="flex-1 justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100">
                                            <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span class="lg:hidden">Edit</span>
                                        </button>
                                        
                                        <button wire:click="delete({{ $laporan->id }})" onclick="confirm('Yakin ingin menghapus laporan ini?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100">
                                            <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="lg:hidden">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm mt-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Laporan</h3>
                                <p class="text-sm">Silakan buat laporan sisa BBM baru.</p>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[90vh] flex flex-col">
                
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                            {{ $laporan_id ? 'Edit Laporan BBM' : 'Buat Laporan BBM Baru' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 rounded-xl text-sm p-2 border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto custom-scrollbar flex-1 p-4 sm:p-6">
                    <form wire:submit.prevent="store" id="form-laporan">
                        <div class="space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kapal <span class="text-rose-500">*</span></label>
                                    <select wire:model.live="kapal_id" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full cursor-pointer" required>
                                        <option value="">-- Pilih Kapal --</option>
                                        @foreach($kapals as $kapal)
                                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal ?? $kapal->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kapal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model="tanggal_surat" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full" required>
                                    @error('tanggal_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="nomor" placeholder="001/PH.12.00" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full" required>
                                    @error('nomor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Klasifikasi</label>
                                    <input type="text" wire:model="klasifikasi" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran</label>
                                    <input type="text" wire:model="lampiran" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Perihal</label>
                                <input type="text" wire:model="perihal" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                            </div>

                            <div class="border-t border-slate-100"></div>

                            <div>
                                <label class="block text-sm font-bold text-indigo-700 mb-2 border-b border-indigo-100 pb-2">
                                    Pilih Data Sounding <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="sounding_id" class="px-4 py-2.5 bg-slate-50 border border-indigo-200 rounded-xl block w-full cursor-pointer" required {{ empty($available_soundings) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Sounding Terkait Kapal --</option>
                                    @foreach($available_soundings as $snd)
                                        <option value="{{ $snd->id }}">Keterangan: {{ $snd->keterangan }} | Sisa BBM: {{ floatval($snd->bbm_akhir) }} L | Tgl: {{ \Carbon\Carbon::parse($snd->tanggal_sounding)->format('d/m/Y') }}</option>
                                    @endforeach
                                </select>
                                @error('sounding_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @if(empty($available_soundings) && $kapal_id)
                                    <p class="text-xs text-orange-500 mt-1 font-medium">Tidak ada data sounding untuk kapal ini.</p>
                                @endif
                            </div>

                            <div class="border-t border-slate-100"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Nakhoda <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="nama_nakhoda" placeholder="Masukkan Nama Nakhoda..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full" required>
                                    @error('nama_nakhoda') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP/NRK Nakhoda</label>
                                    <input type="text" wire:model="id_nakhoda" placeholder="Contoh: 19800101..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                                    @error('id_nakhoda') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pengawas UPAP <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="nama_pengawas" placeholder="Masukkan Nama Pengawas..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full" required>
                                    @error('nama_pengawas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">NIP/NRK Pengawas</label>
                                    <input type="text" wire:model="id_pengawas" placeholder="Contoh: 19900202..." class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl block w-full">
                                    @error('id_pengawas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end p-4 sm:p-6 border-t border-slate-100 rounded-b-2xl bg-slate-50/80 gap-3 sm:gap-0 sm:space-x-3 mt-auto">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm order-2 sm:order-1">
                        Batal
                    </button>
                    <button type="submit" form="form-laporan" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all shadow-sm order-1 sm:order-2">
                        Simpan Laporan
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>