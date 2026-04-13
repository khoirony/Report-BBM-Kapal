<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Verifikasi Tagihan BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Tinjau dan setujui invoice tagihan dari penyedia BBM.</p>
                </div>
            </div>
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

        {{-- FILTER & SEARCH --}}
        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor invoice atau penyedia..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>
        
                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm">
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
        
            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 transition-all">
                <div class="relative w-full">
                    <select wire:model.live="filterPenyedia" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua Penyedia BBM</option>
                        @foreach($penyedias as $penyedia) <option value="{{ $penyedia->id }}">{{ $penyedia->name }}</option> @endforeach
                    </select>
                </div>
                <div class="relative w-full">
                    <select wire:model.live="filterStatus" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Satgas</option>
                        <option value="satgas_approved">Menunggu PPTK</option>
                        <option value="pptk_approved">Disetujui Final</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                <div class="flex items-end w-full">
                    <button wire:click="resetFilters" class="w-full min-h-[34px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- TABEL INVOICE --}}
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
                            <th class="px-6 py-5 font-bold tracking-wider text-right w-[20%]">Aksi & Status</th>
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
                                <div class="font-bold text-slate-900 text-base tracking-tight mb-1">{{ $inv->nomor_invoice }}</div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-1 block mt-2">Detail Pemohon & Transaksi</span>
                                <div class="text-xs font-bold text-slate-800 mb-0.5">{{ $inv->penyedia->name ?? 'Penyedia Dihapus' }}</div>
                                <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $inv->ukpd->nama ?? 'Semua UKPD' }}</span>
                                
                                <div class="text-xs font-medium text-slate-600 mt-2">
                                    Periode: {{ \Carbon\Carbon::parse($inv->periode_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($inv->periode_akhir)->format('d/m/Y') }}
                                </div>
                                <div class="text-[10px] font-semibold text-slate-500 mt-1">
                                    {{ $inv->suratPermohonan->count() }} Transaksi Tertaut
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Total Tagihan</span>
                                <div class="font-extrabold text-emerald-600 text-lg">Rp {{ number_format($inv->total_tagihan, 0, ',', '.') }}</div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[150px] lg:ml-auto mt-2 lg:mt-0">
                                    
                                    {{-- Badge Status --}}
                                    @if($inv->status == 'pending')
                                        <div class="inline-flex justify-center items-center px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-[11px] font-bold text-amber-700 text-center">Menunggu Satgas</div>
                                    @elseif($inv->status == 'satgas_approved')
                                        <div class="inline-flex justify-center items-center px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-[11px] font-bold text-blue-700 text-center">Menunggu PPTK</div>
                                    @elseif($inv->status == 'pptk_approved')
                                        <div class="inline-flex justify-center items-center px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px] font-bold text-emerald-700 text-center">Disetujui Final</div>
                                    @else
                                        <div class="inline-flex justify-center items-center px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-[11px] font-bold text-rose-700 text-center">Ditolak</div>
                                    @endif

                                    <button wire:click="openDetail({{ $inv->id }})" class="w-full justify-center inline-flex items-center text-indigo-700 font-semibold bg-indigo-50 hover:bg-indigo-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-200 shadow-sm text-xs mt-1">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Tinjau Tagihan
                                    </button>
                                </div>
                            </td>
        
                        </tr>
                        @empty
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="4" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Tidak ada tagihan ditemukan</h3>
                                <p class="text-sm text-gray-500">Belum ada tagihan baru dari penyedia.</p>
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

        {{-- MODAL TINJAUAN DETAIL INVOICE --}}
        @if($isDetailModalOpen && $selectedInvoice)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.closeDetail()" class="relative w-full max-w-5xl bg-slate-50 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Tinjau Kesesuaian Tagihan BBM</h3>
                    </div>
                    <button wire:click="closeDetail()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar flex flex-col lg:flex-row gap-6">
                    
                    {{-- KOLOM KIRI: Informasi Invoice --}}
                    <div class="w-full lg:w-1/3 space-y-5">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-100 pb-2">Informasi Invoice</h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Penyedia</span>
                                    <span class="font-semibold text-slate-800 text-sm">{{ $selectedInvoice->penyedia->name }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Nomor Invoice</span>
                                    <span class="font-semibold text-slate-800 text-sm">{{ $selectedInvoice->nomor_invoice }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Periode Klaim</span>
                                    <span class="font-semibold text-slate-800 text-sm">{{ \Carbon\Carbon::parse($selectedInvoice->periode_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($selectedInvoice->periode_akhir)->format('d/m/Y') }}</span>
                                </div>
                                <div class="pt-2 border-t border-slate-100">
                                    <span class="text-[10px] text-slate-500 font-bold uppercase block mb-1">Total Tagihan Diajukan</span>
                                    <span class="font-extrabold text-emerald-600 text-2xl">Rp {{ number_format($selectedInvoice->total_tagihan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <h5 class="text-sm font-bold text-slate-800 mb-1">Dokumen Lampiran</h5>
                            <p class="text-[10px] text-slate-500 mb-3">Silakan tinjau berkas Invoice fisik atau struk kolektif yang diunggah penyedia.</p>
                            <a href="{{ Storage::url($selectedInvoice->file_evidence) }}" target="_blank" class="w-full justify-center inline-flex items-center text-white font-semibold bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl transition-all text-xs shadow-sm">
                                Buka Dokumen Tagihan
                            </a>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Rincian Transaksi Tertaut --}}
                    <div class="w-full lg:w-2/3 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col h-full">
                        <div class="flex justify-between items-end mb-4 border-b border-slate-100 pb-2">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Transaksi Pengisian</h4>
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $selectedInvoice->suratPermohonan->count() }} Transaksi</span>
                        </div>
                        
                        <div class="overflow-y-auto flex-1 pr-2 custom-scrollbar max-h-[400px]">
                            <div class="space-y-3">
                                @forelse($selectedInvoice->suratPermohonan as $ts)
                                    <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $ts->nomor_surat ?? 'Tanpa Nomor' }}</p>
                                                <p class="text-[10px] text-slate-500 font-medium">Tgl: {{ \Carbon\Carbon::parse($ts->tanggal_surat)->format('d M Y') }}</p>
                                            </div>
                                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">{{ floatval($ts->jumlah_bbm) }} Liter</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-2 text-xs bg-white p-2 rounded-lg border border-slate-100 mt-2">
                                            <div>
                                                <span class="text-[9px] text-slate-400 block uppercase">Armada Kapal</span>
                                                <span class="font-semibold text-slate-700">{{ $ts->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-[9px] text-slate-400 block uppercase">Jenis BBM</span>
                                                <span class="font-semibold text-slate-700">{{ $ts->jenis_bbm }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-slate-500 text-sm">Tidak ada transaksi yang ditautkan pada invoice ini.</div>
                                @endforelse
                            </div>
                        </div>
                        
                        @if($selectedInvoice->status === 'rejected')
                        <div class="mt-4 p-4 bg-rose-50 border border-rose-200 rounded-xl">
                            <h5 class="text-xs font-bold text-rose-800 uppercase tracking-wider mb-1">Alasan Penolakan:</h5>
                            <p class="text-sm text-rose-700">{{ $selectedInvoice->catatan_penolakan }}</p>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="px-6 py-4 bg-white border-t border-slate-200 flex items-center justify-between rounded-b-3xl shrink-0">
                    <div>
                        {{-- Placeholder Kiri --}}
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button wire:click="closeDetail()" type="button" class="px-5 py-2.5 bg-slate-100 border border-transparent text-slate-700 hover:bg-slate-200 text-sm font-semibold rounded-xl transition-colors">Tutup</button>
                        
                        @php $role = auth()->user()?->role?->slug; @endphp
                        
                        {{-- Logika Tombol Aksi Verifikasi --}}
                        @if($selectedInvoice->status === 'pending' && in_array($role, ['satgas', 'superadmin']))
                            <button wire:click="openRejectModal()" type="button" class="px-5 py-2.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 text-sm font-semibold rounded-xl transition-colors shadow-sm">Tolak Invoice</button>
                            <button wire:click="approveSatgas()" type="button" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all">Verifikasi (Satgas)</button>
                        @endif

                        @if($selectedInvoice->status === 'satgas_approved' && in_array($role, ['pptk', 'superadmin']))
                            <button wire:click="openRejectModal()" type="button" class="px-5 py-2.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 text-sm font-semibold rounded-xl transition-colors shadow-sm">Tolak Invoice</button>
                            <button wire:click="approvePptk()" type="button" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all">Setujui Final (PPTK)</button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @endif

        {{-- MODAL PENOLAKAN --}}
        @if($isRejectModalOpen)
        <div class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-all">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-rose-50/50">
                    <h3 class="text-lg font-bold text-rose-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Tolak Invoice Tagihan
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-600 mb-4">Silakan tuliskan alasan mengapa tagihan ini ditolak. Invoice ini akan dikembalikan kepada penyedia untuk diperbaiki.</p>
                    
                    <textarea wire:model="catatan_penolakan" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-200 outline-none transition-all" placeholder="Misal: Terdapat ketidaksesuaian total harga dengan lampiran faktur..."></textarea>
                    @error('catatan_penolakan') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="p-4 bg-slate-50 flex justify-end gap-3 border-t border-slate-100">
                    <button wire:click="closeRejectModal()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button wire:click="rejectInvoice()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-sm">Ya, Tolak Tagihan</button>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>