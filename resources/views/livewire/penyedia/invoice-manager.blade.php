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
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Tagihan & Rekonsiliasi</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">
                        {{ auth()->user()?->role?->slug === 'superadmin' ? 'Pantau semua tagihan invoice masuk.' : 'Upload Invoice dan tautkan dengan transaksi pengisian BBM.' }}
                    </p>
                </div>
            </div>
            
            @if(auth()->user()?->role?->slug !== 'superadmin')
                <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                    Upload Invoice
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

        {{-- BLOCK FILTER, SEARCH, & SORT --}}
        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor invoice atau penyedia..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
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
        
            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 border-t border-slate-100 transition-all duration-200">
                @if (auth()->user()?->role?->slug == 'superadmin')
                <div class="relative w-full">
                    {{-- DIUBAH MENJADI FILTER PENYEDIA --}}
                    <select wire:model.live="filterPenyedia" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua Penyedia BBM</option>
                        @foreach($penyedias as $penyedia) 
                            <option value="{{ $penyedia->id }}">{{ $penyedia->name }}</option> 
                        @endforeach
                    </select>
                </div>
                @else
                <div class="hidden md:block"></div>
                @endif
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Dari Tgl Invoice</label>
                    <input type="date" wire:model.live="filterTanggalAwal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Sampai Tgl Invoice</label>
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
                            <th class="px-6 py-5 font-bold tracking-wider w-[25%]">Nomor & Tanggal</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[35%]">Penyedia & Target UKPD</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[20%]">Total Tagihan</th>
                            <th class="px-6 py-5 font-bold tracking-wider text-right w-[20%]">Status</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group md:divide-y md:divide-gray-50 space-y-4 lg:space-y-0">
                        @forelse($invoices as $inv)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-indigo-500 uppercase lg:hidden mb-2 block tracking-wider">Info Invoice</span>
                                
                                <div class="flex items-center text-xs text-slate-500 font-medium mb-1.5">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d M Y') }}
                                </div>
                                <div class="font-bold text-slate-900 text-base tracking-tight mb-1" title="Nomor Invoice">
                                    {{ $inv->nomor_invoice }}
                                </div>
                                
                                <a href="{{ Storage::url($inv->file_evidence) }}" target="_blank" class="inline-flex items-center gap-1.5 mt-2 px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-md transition-colors border border-slate-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Lihat Berkas Tagihan
                                </a>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-1 block mt-2">Detail Pemohon & Transaksi</span>
                                
                                <div class="mb-2">
                                    @if(auth()->user()?->role?->slug === 'superadmin')
                                        <div class="text-xs font-bold text-slate-800 mb-0.5">{{ $inv->penyedia->name ?? 'Penyedia Tidak Ditemukan' }}</div>
                                    @endif
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-semibold text-indigo-700">{{ $inv->ukpd->nama ?? 'Semua UKPD' }}</span>
                                    </div>
                                </div>
        
                                <div class="flex items-start gap-1.5 mb-2 mt-1">
                                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-medium text-slate-600 line-clamp-2 leading-tight">
                                        Periode: {{ \Carbon\Carbon::parse($inv->periode_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($inv->periode_akhir)->format('d/m/Y') }}
                                    </span>
                                </div>
        
                                <div class="flex items-center text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 shadow-sm inline-flex px-2 py-1 rounded-md mb-2">
                                    <svg class="w-3.5 h-3.5 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    Terdapat {{ $inv->suratPermohonan->count() }} Transaksi Pengisian
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Total Harga</span>
                                <div class="font-extrabold text-emerald-600 text-lg">
                                    Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Status Verifikasi</span>
                                
                                <div class="flex flex-col gap-2 w-full lg:max-w-[150px] lg:ml-auto mt-2 lg:mt-0">
                                    @if($inv->status == 'pending')
                                        <div class="inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-lg bg-amber-50 border border-amber-200 text-xs font-bold text-amber-700 w-full shadow-sm text-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu Satgas
                                        </div>
                                    @elseif($inv->status == 'satgas_approved')
                                        <div class="inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 text-xs font-bold text-blue-700 w-full shadow-sm text-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Menunggu PPTK
                                        </div>
                                    @elseif($inv->status == 'pptk_approved')
                                        <div class="inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 w-full shadow-sm text-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Disetujui
                                        </div>
                                    @else
                                        <div class="inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-lg bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700 w-full shadow-sm text-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Ditolak
                                        </div>
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
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Laporan Tagihan</h3>
                                <p class="text-sm text-gray-500">Mulai unggah Invoice Anda melalui menu di atas.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.set('isModalOpen', false)" class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Upload Invoice & Tautkan Transaksi
                        </h3>
                    </div>
                    <button wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-invoice" class="space-y-6">
                        
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Administratif Invoice</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Pilih UKPD Tujuan <span class="text-rose-500">*</span></label>
                                <select wire:model.live="ukpd_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl outline-none focus:bg-white focus:ring-2 focus:ring-indigo-200 transition-all cursor-pointer" required>
                                    <option value="">-- Pilih UKPD --</option>
                                    @foreach($ukpds as $u) 
                                        <option value="{{ $u->id }}">{{ $u->nama }}</option> 
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Nomor Invoice <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="nomor_invoice" placeholder="Contoh: INV/001/2026" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                                @error('nomor_invoice') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal Invoice Dikeluarkan <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_invoice" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Total Harga Tagihan (Rp) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-slate-500">Rp</span>
                                    <input type="number" wire:model="total_tagihan" placeholder="0" class="w-full pl-10 pr-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-200 outline-none transition-all" required>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 mt-4">Filter Periode Transaksi & Tautan</h4>
                        <p class="text-[11px] text-slate-500 -mt-4 mb-3">Tentukan batasan tanggal untuk mencari riwayat transaksi pengisian kapal yang ingin diklaim ke dalam tagihan ini.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-indigo-50/40 p-4 rounded-xl border border-indigo-100">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Dari Tanggal <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model.live="periode_awal" class="w-full px-4 py-3 bg-white border border-slate-200 text-sm rounded-xl focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Sampai Tanggal <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model.live="periode_akhir" class="w-full px-4 py-3 bg-white border border-slate-200 text-sm rounded-xl focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                            </div>
                        </div>

                        <div class="border-2 border-dashed border-indigo-200 rounded-xl p-4 bg-white mt-4">
                            <h4 class="font-bold text-slate-800 mb-3 flex items-center text-sm">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                Checklist Transaksi Pengisian (Status: Selesai)
                            </h4>
                            
                            @if(count($transaksi_tersedia) > 0)
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($transaksi_tersedia as $ts)
                                        <label class="flex items-start p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-300">
                                            <input type="checkbox" wire:model="selected_transaksi" value="{{ $ts->id }}" class="mt-1 w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <div class="ml-3 flex-1">
                                                <div class="flex justify-between items-start">
                                                    <p class="text-sm font-bold text-slate-900">{{ $ts->nomor_surat ?? 'Surat Tanpa Nomor' }}</p>
                                                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($ts->tanggal_surat)->format('d/m/Y') }}</span>
                                                </div>
                                                <p class="text-xs text-slate-600 mt-1 font-medium">
                                                    Kapal: <span class="font-bold text-indigo-700">{{ $ts->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }}</span> | 
                                                    Total BBM: <span class="font-bold text-emerald-600">{{ floatval($ts->jumlah_bbm) }} L</span>
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 bg-amber-50 text-amber-800 rounded-xl text-sm border border-amber-200 flex items-center">
                                    <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Harap pilih UKPD dan Rentang Tanggal di atas untuk menampilkan riwayat transaksi yang siap ditagihkan.
                                </div>
                            @endif
                            @error('selected_transaksi') <span class="text-rose-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Unggah Dokumen Bukti</h4>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <input type="file" wire:model="file_evidence" accept=".pdf,image/png,image/jpeg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" required>
                            <p class="text-[10px] text-slate-500 mt-2">Maksimal ukuran file: 5MB. Format yang didukung: PDF, JPG, PNG.</p>
                            
                            <div wire:loading wire:target="file_evidence" class="text-xs text-indigo-600 mt-2 font-semibold flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Sedang memproses file...
                            </div>
                            @error('file_evidence') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="$set('isModalOpen', false)" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-invoice" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow active:scale-95 transition-all">Simpan & Ajukan Tagihan</button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>