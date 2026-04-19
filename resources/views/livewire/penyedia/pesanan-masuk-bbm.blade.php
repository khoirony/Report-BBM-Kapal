<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen font-sans">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3.5 bg-white border border-slate-200/60 rounded-2xl shadow-sm">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="22" x2="15" y2="22"></line>
                        <line x1="4" y1="9" x2="14" y2="9"></line>
                        <path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"></path>
                        <path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2"></path>
                        <path d="M18 10a2 2 0 0 0-2-2V5.5A2.5 2.5 0 0 0 13.5 3h-.5"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesanan Masuk (Delivery Order)</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Daftar permohonan pengisian BBM dari Dishub yang perlu diproses.</p>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border border-emerald-200 p-4 mb-6 rounded-2xl flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 p-1.5 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor surat permohonan atau kapal..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-amber-500 block w-full transition-colors shadow-sm">
                </div>
        
                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-amber-50 border border-amber-100 text-amber-700 text-sm font-semibold rounded-xl hover:bg-amber-100 transition-colors shadow-sm focus:ring-2 focus:ring-amber-500">
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
                        <select wire:model.live="filterPenyedia" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-amber-500 block w-full appearance-none hover:bg-slate-50 cursor-pointer">
                            <option value="">Semua Perusahaan / Penyedia</option>
                            @if(isset($penyedias))
                                @foreach($penyedias as $penyedia)
                                    <option value="{{ $penyedia->id }}">{{ $penyedia->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                @endif
        
                <div class="relative w-full">
                    <select wire:model.live="filterKapal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-amber-500 block w-full appearance-none hover:bg-slate-50 cursor-pointer">
                        <option value="">Semua Armada Kapal</option>
                        @if(isset($kapals))
                            @foreach($kapals as $kapal)
                                <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
        
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-amber-500 z-10">Dari Tgl</label>
                    <input type="date" wire:model.live="filterTanggalAwal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-amber-500 block w-full hover:bg-slate-50 relative z-0 cursor-pointer">
                </div>
        
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-amber-500 z-10">Sampai Tgl</label>
                    <input type="date" wire:model.live="filterTanggalAkhir" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-amber-500 block w-full hover:bg-slate-50 relative z-0 cursor-pointer">
                </div>
                
                <div class="flex items-end w-full">
                    <button wire:click="resetFilters" class="w-full h-full min-h-[34px] flex justify-center items-center px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden relative w-full">
            <div wire:loading class="absolute inset-0 bg-white/60 backdrop-blur-sm z-20 hidden md:flex items-center justify-center">
                <div class="w-8 h-8 border-4 border-amber-200 border-t-amber-500 rounded-full animate-spin"></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 block md:table">
                    <thead class="hidden md:table-header-group bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-bold w-[25%]">Informasi Permohonan</th>
                            <th class="px-6 py-4 font-bold w-[35%]">Kebutuhan & Rincian BBM</th>
                            <th class="px-6 py-4 font-bold w-[20%]">Status Progres</th>
                            <th class="px-6 py-4 font-bold text-right w-[20%]">Aksi Penyedia</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y-0 md:divide-y divide-slate-100">
                        @forelse($pesanans as $item)
                            @php
                                $progressColor = match($item->progress) {
                                    'not started' => 'bg-rose-50 text-rose-600 border-rose-200',
                                    'on progress' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'done'        => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    default       => 'bg-slate-50 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <tr class="block md:table-row hover:bg-slate-50/60 transition-colors p-4 md:p-0 border border-slate-200/60 md:border-none mb-4 md:mb-0 rounded-2xl md:rounded-none">
                                
                                <td class="block md:table-cell px-2 py-2 md:px-6 md:py-5 align-top">
                                    <span class="md:hidden text-[10px] font-bold text-amber-500 uppercase tracking-wider mb-2 block border-b border-slate-50 pb-1">Info Surat</span>
                                    
                                    <div class="font-bold text-slate-900 mb-1">{{ $item->nomor_surat ?: 'No. Surat Belum Ada' }}</div>
                                    <div class="text-xs text-slate-500 flex items-center mb-3">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d M Y') }}
                                    </div>
                                    
                                    <div class="flex flex-col gap-1.5 bg-slate-50 p-2.5 rounded-lg border border-slate-100 w-fit">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Armada Tujuan:</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-indigo-700">{{ $item->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                            <span class="text-[9px] bg-indigo-100/50 text-indigo-600 px-1 rounded font-bold">{{ $item->suratTugas->LaporanSisaBbm->sounding->kapal->ukpd->singkatan ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="block md:table-cell px-2 py-2 md:px-6 md:py-5 align-top">
                                    <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block mt-2">Kebutuhan & Rincian BBM</span>
                                    
                                    <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-100 mb-2 mt-1 md:mt-0">
                                        <span class="font-bold text-emerald-700 text-sm mr-2">{{ $item->jenis_bbm ?? 'BBM' }}</span>
                                        <span class="text-emerald-300 mr-2">|</span>
                                        <span class="font-extrabold text-emerald-800 text-sm">{{ floatval($item->jumlah_bbm) }} Liter</span>
                                    </div>
                                    
                                    <div class="text-xs text-slate-600 mt-1 flex items-start gap-1.5 line-clamp-2" title="{{ $item->tempat_pengambilan_bbm }}">
                                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="font-semibold text-slate-700 mr-1">Tujuan:</span> {{ $item->tempat_pengambilan_bbm ?? '-' }}
                                    </div>

                                    <div class="mt-3">
                                        @if($item->prosesPenyedia)
                                            <div class="bg-amber-50/40 border border-amber-100 rounded-lg p-2.5 w-full">
                                                <div class="text-[9px] font-bold text-amber-600 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                                    Rincian Delivery Order
                                                    <span class="text-[9px] text-amber-700 bg-amber-100 px-1 rounded">{{ $item->prosesPenyedia->nomor_izin_penyedia }}</span>
                                                </div>
                                                
                                                <div class="flex justify-between items-center text-[10px] mb-1 border-b border-amber-100/50 pb-1">
                                                    <span class="text-slate-500">Harga Satuan:</span>
                                                    <span class="font-semibold text-slate-700">Rp {{ number_format($item->prosesPenyedia->harga_satuan, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between items-center text-[11px] mt-1.5">
                                                    <span class="text-slate-600 font-bold">Total Tagihan:</span>
                                                    <span class="font-extrabold text-amber-700">Rp {{ number_format($item->prosesPenyedia->total_harga, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-lg p-2.5 w-full flex items-start gap-1.5 text-[10px] text-slate-500">
                                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Klik tombol "Proses Pesanan" untuk menentukan Harga Satuan dan SPBU/Izin Pengambilan.
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="block md:table-cell px-2 py-2 md:px-6 md:py-5 align-top">
                                    <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block mt-2">Status Progres</span>
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wider mt-1 md:mt-0 {{ $progressColor }}">
                                        {{ $item->progress }}
                                    </div>
                                    @if($item->prosesPenyedia)
                                        <div class="text-[10px] text-slate-500 mt-2.5 font-medium leading-relaxed">
                                            Diproses Oleh:<br>
                                            <span class="font-bold text-slate-700">{{ $item->prosesPenyedia->user->name ?? 'Sistem' }}</span><br>
                                            {{ \Carbon\Carbon::parse($item->prosesPenyedia->created_at)->format('d M Y - H:i') }}
                                        </div>
                                    @endif
                                </td>

                                <td class="flex justify-end md:table-cell px-2 py-3 md:px-6 md:py-5 align-middle text-right border-t border-slate-100 md:border-none mt-3 md:mt-0">
                                    @if($item->progress === 'not started')
                                        <button wire:click="openProsesModal({{ $item->id }})" class="inline-flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm focus:ring-2 focus:ring-amber-300 w-full lg:w-auto">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            Proses Pesanan
                                        </button>
                                    @else
                                        <div class="flex flex-col gap-2 w-full lg:w-auto items-end">
                                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 w-full lg:w-auto text-center"><svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Telah Diproses</span>
                                            
                                            @if($item->prosesPenyedia && $item->prosesPenyedia->file_evidence)
                                            <a href="{{ Storage::url($item->prosesPenyedia->file_evidence) }}" target="_blank" class="w-full lg:w-auto text-center inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-indigo-600 rounded-lg shadow-sm transition-colors">
                                                Lihat Bukti (DO)
                                            </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr class="block md:table-row">
                                <td colspan="4" class="block md:table-cell px-6 py-16 text-center text-slate-500">
                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 mb-3"><svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2"></path></svg></div>
                                    <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Pesanan Masuk</h3>
                                    <p class="text-xs">Silakan menunggu permohonan pengisian BBM dari pihak Dishub.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pesanans->hasPages())
                <div class="px-6 py-4 md:border-t border-slate-100 bg-white md:rounded-b-2xl">{{ $pesanans->links() }}</div>
            @endif
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div x-data="{ harga: @entangle('harga_satuan').live, liter: {{ floatval($jumlah_liter) }}, get total() { return (this.harga * this.liter) || 0 } }" @click.away="$wire.closeModal()" class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-amber-100 rounded-lg text-amber-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Proses Pesanan Delivery Order</h3>
                            <p class="text-xs text-slate-500 font-medium">Surat Ref: {{ $nomor_surat }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar bg-slate-50/30">
                    <form wire:submit.prevent="storeProses" id="form-proses" class="space-y-6">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tempat Pengambilan (Spesifik) <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="tempat_pengambilan" placeholder="Contoh: Depot Minyak A / SPBU C" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-200 focus:border-amber-500 outline-none transition-all shadow-sm" required>
                                @error('tempat_pengambilan') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Nomor SPBU / Lembaga / Izin <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="nomor_izin_penyedia" placeholder="Contoh: 31.102.02" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-200 focus:border-amber-500 outline-none transition-all shadow-sm" required>
                                @error('nomor_izin_penyedia') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2 bg-amber-50/50 p-4 rounded-xl border border-amber-200 shadow-sm mt-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-600 mb-3 border-b border-amber-200/60 pb-2">Kalkulasi Tagihan (Otomatis)</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">Jumlah BBM</label>
                                        <div class="px-3 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-lg cursor-not-allowed">
                                            {{ floatval($jumlah_liter) }} <span class="text-xs font-medium text-slate-400">Liter</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-800 uppercase mb-1.5">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                                        <input type="number" step="0.01" x-model.number="harga" class="w-full px-3 py-2.5 bg-white border border-amber-300 text-slate-900 font-bold text-sm rounded-lg focus:ring-2 focus:ring-amber-500 outline-none shadow-inner" required placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-800 uppercase mb-1.5">Total Harga (Rp)</label>
                                        <div class="px-3 py-2.5 bg-indigo-50 border border-indigo-200 text-indigo-800 font-extrabold text-sm rounded-lg overflow-x-auto whitespace-nowrap">
                                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)"></span>
                                        </div>
                                    </div>
                                </div>
                                @error('harga_satuan') <span class="text-rose-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Upload Bukti Proses (Surat Jalan/DO) <span class="text-rose-500">*</span></label>
                                <div class="border-2 border-dashed border-slate-300 rounded-xl p-5 bg-white hover:bg-slate-50 transition-colors text-center sm:text-left">
                                    <input type="file" wire:model="file_evidence" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer" required>
                                    
                                    <div wire:loading wire:target="file_evidence" class="text-xs text-amber-600 mt-3 font-semibold flex items-center justify-center sm:justify-start">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Mengunggah dokumen...
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 font-medium">Format: PDF, JPG, PNG. Maks: 3MB.</p>
                                </div>
                                @error('file_evidence') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="closeModal()" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-proses" wire:loading.attr="disabled" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold shadow-sm hover:shadow active:scale-95 transition-all">
                        Simpan & Proses Pesanan
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>