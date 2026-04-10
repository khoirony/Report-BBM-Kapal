<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen font-sans">
    <div class="w-full max-w-[1400px] mx-auto">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3.5 bg-white border border-slate-200/60 rounded-2xl shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Laporan Pengisian BBM</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Rekapitulasi dan dokumentasi aktual pengisian bahan bakar kapal.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="group inline-flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 shadow-sm active:scale-95 w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2 -ml-1 text-slate-300 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Buat Laporan Baru
            </button>
        </div>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 border border-emerald-200/50 p-4 mb-6 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100/50 p-1.5 rounded-full text-emerald-600"><svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('message') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <div x-data="{ showFilters: false }" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/60 mb-6">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari lokasi pengisian atau kapal..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100 focus:bg-white border-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm rounded-xl outline-none transition-all">
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 text-sm font-medium rounded-xl transition-colors w-full md:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'">Filter</span>
                    </button>

                    <div class="relative w-full md:w-40 shrink-0">
                        <select wire:model.live="sortBy" class="w-full pl-3 pr-8 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl outline-none focus:ring-2 focus:ring-indigo-200 cursor-pointer">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></div>
                    </div>
                </div>
            </div>

            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-4 mt-4 border-t border-slate-100">
                <div>
                    <select wire:model.live="filterKapal" class="w-full px-3 py-2 bg-slate-50 border-transparent text-slate-700 text-sm rounded-lg outline-none focus:ring-2 focus:ring-indigo-200 cursor-pointer">
                        <option value="">Semua Armada</option>
                        @foreach($kapals as $kapal) <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option> @endforeach
                    </select>
                </div>
                @if (auth()->user()->role == 'superadmin')
                <div>
                    <select wire:model.live="filterUkpd" class="w-full px-3 py-2 bg-slate-50 border-transparent text-slate-700 text-sm rounded-lg outline-none focus:ring-2 focus:ring-indigo-200 cursor-pointer">
                        <option value="">Semua UKPD</option>
                        @foreach($ukpds as $ukpd) <option value="{{ $ukpd->id }}">{{ $ukpd->singkatan ?? $ukpd->nama }}</option> @endforeach
                    </select>
                </div>
                @endif
                <input type="date" wire:model.live="filterTanggalAwal" class="w-full px-3 py-2 bg-slate-50 border-transparent text-slate-700 text-sm rounded-lg outline-none" title="Dari Tanggal">
                <input type="date" wire:model.live="filterTanggalAkhir" class="w-full px-3 py-2 bg-slate-50 border-transparent text-slate-700 text-sm rounded-lg outline-none" title="Sampai Tanggal">
                <button wire:click="resetFilters" class="w-full h-full min-h-[38px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-sm font-medium rounded-lg transition-colors border border-rose-100/50">Reset Filter</button>
            </div>
        </div>

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-slate-200/80 w-full relative">
            <div wire:loading.delay.longest class="absolute inset-0 bg-white/60 backdrop-blur-sm z-20 hidden md:flex items-center justify-center md:rounded-2xl">
                <div class="w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
            </div>

            <div class="w-full">
                <table class="w-full text-left border-collapse block md:table">
                    <thead class="hidden md:table-header-group bg-slate-50/80 text-[11px] uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-bold w-1/4">Informasi Surat & Kapal</th>
                            <th class="px-6 py-4 font-bold w-1/4">Detail Pelaksanaan</th>
                            <th class="px-6 py-4 font-bold w-1/4">Kalkulasi BBM (L)</th>
                            <th class="px-6 py-4 font-bold text-right w-1/4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y-0 md:divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($laporans as $item)
                            <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-slate-200/60 md:border-none mb-4 md:mb-0 hover:bg-slate-50/60 transition-colors p-4 md:p-0">
                                
                                <td class="block md:table-cell px-2 py-2 md:px-6 md:py-4 align-top">
                                    <span class="md:hidden text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-2 block border-b border-slate-50 pb-1">Info Surat & Kapal</span>
                                    <div class="font-bold text-slate-900 mb-1 tracking-tight">{{ $item->suratTugas->nomor_surat ?? '-' }}</div>
                                    <div class="flex items-center text-xs text-slate-500 font-medium mb-3">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </div>
                                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-2 mt-1 w-fit pr-4">
                                        <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block mb-0.5">Armada Kapal</span>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-indigo-900">{{ $item->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                            <span class="text-[9px] bg-indigo-200/50 text-indigo-700 px-1 rounded font-bold">{{ $item->suratTugas->LaporanSisaBbm->sounding->kapal->ukpd->singkatan ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="block md:table-cell px-2 py-2 md:px-6 md:py-4 align-top">
                                    <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block mt-2">Detail Pelaksanaan</span>
                                    <div class="flex items-start gap-1.5 mb-2 mt-1 md:mt-0">
                                        <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-sm font-medium text-slate-800 line-clamp-2 leading-tight" title="{{ $item->lokasi_pengisian }}">{{ $item->lokasi_pengisian }}</span>
                                    </div>
                                    <div class="flex items-center text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 shadow-sm inline-flex px-2 py-1 rounded-md ml-0 md:ml-5 mt-1">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->jam_berangkat)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_kembali)->format('H:i') }} WIB
                                    </div>
                                    @php $fotos = json_decode($item->dokumentasi_foto, true) ?? []; @endphp
                                    @if(count($fotos) > 0)
                                        <div class="ml-0 md:ml-5 mt-2 flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ count($fotos) }} Foto Terlampir
                                        </div>
                                    @endif
                                </td>

                                <td class="block md:table-cell px-2 py-2 md:px-6 md:py-4 align-top">
                                    <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block mt-2">Kalkulasi BBM</span>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-0 text-sm bg-slate-50 md:bg-transparent rounded-xl md:rounded-none border border-slate-200/60 md:border-none overflow-hidden mt-1 md:mt-0">
                                        <div class="flex flex-col p-2 md:p-0 md:pr-2 border-r border-b sm:border-b-0 border-slate-200/60 md:border-none">
                                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Awal</span>
                                            <span class="font-semibold text-slate-700">{{ floatval($item->jumlah_bbm_awal) }}</span>
                                        </div>
                                        <div class="flex flex-col p-2 md:p-0 md:pr-2 border-b sm:border-b-0 sm:border-r border-slate-200/60 md:border-none bg-emerald-50/30 md:bg-transparent">
                                            <span class="text-[10px] text-emerald-500 uppercase font-bold tracking-wider">Isi</span>
                                            <span class="font-bold text-emerald-600">+{{ floatval($item->jumlah_bbm_pengisian) }}</span>
                                        </div>
                                        <div class="flex flex-col p-2 md:p-0 md:pr-2 border-r border-slate-200/60 md:border-none bg-rose-50/30 md:bg-transparent">
                                            <span class="text-[10px] text-rose-400 uppercase font-bold tracking-wider">Pakai</span>
                                            <span class="font-bold text-rose-500">-{{ floatval($item->pemakaian_bbm) }}</span>
                                        </div>
                                        <div class="flex flex-col p-2 md:p-0 bg-blue-50/50 md:bg-transparent rounded-br-xl sm:rounded-none">
                                            <span class="text-[10px] text-blue-500 uppercase font-bold tracking-wider">Akhir</span>
                                            <span class="font-extrabold text-blue-600">{{ floatval($item->jumlah_bbm_akhir) }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="flex justify-end md:table-cell px-2 py-3 md:px-6 md:py-4 align-middle text-right border-t border-slate-100 md:border-none mt-3 md:mt-0">
                                    <div class="flex items-center gap-2">
                                        <a href="#" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white hover:text-indigo-600 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-100 rounded-lg transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="md:hidden">PDF</span>
                                        </a>
                                        <button wire:click="edit({{ $item->id }})" class="p-1.5 text-slate-600 bg-white hover:text-emerald-600 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-100 rounded-lg transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus Laporan ini?" class="p-1.5 text-slate-600 bg-white hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-100 rounded-lg transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr class="block md:table-row">
                                <td colspan="4" class="block md:table-cell px-6 py-16 text-center bg-white md:bg-slate-50/30 rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-slate-200/60 md:border-none">
                                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-50 md:bg-white border border-slate-200 mb-3 shadow-sm">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 mb-1">Tidak Ada Data</h3>
                                    <p class="text-xs text-slate-500">Belum ada Laporan Hasil Pengisian BBM.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporans->hasPages())
                <div class="px-6 py-4 md:border-t md:border-slate-100 md:bg-white md:rounded-b-2xl mt-2 md:mt-0">
                    {{ $laporans->links() }}
                </div>
            @endif
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.closeModal()" class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $laporan_id ? 'Edit Laporan Pengisian BBM' : 'Buat Laporan Pengisian BBM Baru' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-laporan" class="space-y-6">
                        
                        <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Tautkan Surat Permohonan BBM <span class="text-rose-500">*</span></label>
                            <select wire:model.live="surat_permohonan_id" class="w-full px-4 py-3 bg-white border border-slate-200 text-sm rounded-xl outline-none focus:ring-2 focus:ring-indigo-200 transition-all cursor-pointer shadow-sm" required>
                                <option value="">-- Pilih Surat Permohonan yang Disetujui --</option>
                                @foreach($permohonan_list as $pm)
                                    <option value="{{ $pm->id }}">{{ $pm->nomor_surat }} (Kapal: {{ $pm->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }} | Isi: {{ floatval($pm->jumlah_bbm) }} L)</option>
                                @endforeach
                            </select>
                            <p class="text-[10.5px] text-slate-500 mt-2 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                Memilih permohonan akan memuat otomatis Petugas, Lokasi, dan Volume BBM.
                            </p>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Informasi Pelaksanaan & Waktu</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Lokasi Aktual Pengisian <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="lokasi_pengisian" placeholder="Otomatis dari Permohonan/Surat Tugas" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Jam Berangkat (WIB)</label>
                                <input type="time" wire:model="jam_berangkat" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Jam Kembali (WIB)</label>
                                <input type="time" wire:model="jam_kembali" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer">
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Dasar Hukum Laporan</label>
                                <textarea wire:model="dasar_hukum" rows="2" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all resize-y"></textarea>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Kegiatan & Tujuan Laporan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div x-data="{ state_kegiatan: @entangle('kegiatan') }">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Kegiatan <span class="text-rose-500">*</span></label>
                                <select x-model="state_kegiatan" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer mb-2" required>
                                    <option value="Pengisian BBM KDO Khusus">Pengisian BBM KDO Khusus</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                <div x-show="state_kegiatan === 'Lainnya'" x-collapse>
                                    <input type="text" wire:model="kegiatan_lainnya" placeholder="Ketikan kegiatan..." class="w-full px-4 py-3 bg-white border border-indigo-200 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none shadow-inner">
                                </div>
                            </div>
                            <div x-data="{ state_tujuan: @entangle('tujuan') }">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tujuan <span class="text-rose-500">*</span></label>
                                <select x-model="state_tujuan" class="w-full px-4 py-3 bg-slate-50 border-transparent text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer mb-2" required>
                                    <option value="Memastikan ketersediaan BBM Kapal untuk menunjang kegiatan Operasional">Memastikan ketersediaan BBM Kapal...</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                <div x-show="state_tujuan === 'Lainnya'" x-collapse>
                                    <input type="text" wire:model="tujuan_lainnya" placeholder="Ketikan tujuan..." class="w-full px-4 py-3 bg-white border border-indigo-200 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none shadow-inner">
                                </div>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Kalkulasi Bahan Bakar</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Data Sounding Awal</label>
                                <select wire:model.live="sounding_awal_id" class="w-full px-3 py-2.5 bg-white border border-slate-200 text-sm rounded-lg focus:ring-2 focus:ring-indigo-200 outline-none cursor-pointer shadow-sm">
                                    <option value="">-- Manual (Tidak Tarik Sounding) --</option>
                                    @foreach($available_soundings as $snd)
                                        <option value="{{ $snd->id }}">{{ \Carbon\Carbon::parse($snd->tanggal_sounding)->format('d/m') }} - {{ $snd->keterangan }} ({{ floatval($snd->bbm_akhir) }}L)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Data Sounding Akhir</label>
                                <select wire:model.live="sounding_akhir_id" class="w-full px-3 py-2.5 bg-white border border-slate-200 text-sm rounded-lg focus:ring-2 focus:ring-indigo-200 outline-none cursor-pointer shadow-sm">
                                    <option value="">-- Manual (Tidak Tarik Sounding) --</option>
                                    @foreach($available_soundings as $snd)
                                        <option value="{{ $snd->id }}">{{ \Carbon\Carbon::parse($snd->tanggal_sounding)->format('d/m') }} - {{ $snd->keterangan }} ({{ floatval($snd->bbm_akhir) }}L)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">BBM Awal</label>
                                <input type="number" step="0.01" wire:model="jumlah_bbm_awal" class="w-full px-3 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold text-sm rounded-lg focus:border-indigo-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-emerald-500 uppercase tracking-wider mb-1.5">Isi (Dari Permohonan)</label>
                                <input type="number" step="0.01" wire:model="jumlah_bbm_pengisian" class="w-full px-3 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-sm rounded-lg outline-none cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-rose-500 uppercase tracking-wider mb-1.5">Pemakaian Perjalanan</label>
                                <input type="number" step="0.01" wire:model="pemakaian_bbm" class="w-full px-3 py-2.5 bg-white border border-rose-200 focus:ring-2 focus:ring-rose-200 text-rose-700 font-semibold text-sm rounded-lg outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1.5">BBM Akhir</label>
                                <input type="number" step="0.01" wire:model="jumlah_bbm_akhir" class="w-full px-3 py-2.5 bg-blue-50 border border-blue-200 text-blue-700 font-extrabold text-sm rounded-lg outline-none">
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Dokumentasi (Foto/Struk)</h4>
                        
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                            <input type="file" wire:model="dokumentasi_baru" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                            <div wire:loading wire:target="dokumentasi_baru" class="text-xs text-indigo-600 mt-2 font-semibold flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Mengunggah gambar...
                            </div>
                            @error('dokumentasi_baru.*') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @if(!empty($dokumentasi_lama))
                        <div class="flex flex-wrap gap-3 mt-3">
                            @foreach($dokumentasi_lama as $idx => $path)
                            <div class="relative group rounded-lg overflow-hidden border border-slate-200 shadow-sm">
                                <img src="{{ Storage::url($path) }}" class="h-20 w-20 object-cover">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" wire:click="deleteFoto({{ $idx }})" class="text-white hover:text-rose-500 p-1 bg-white/20 rounded-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="closeModal()" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-laporan" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow active:scale-95 transition-all">Simpan Laporan</button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>