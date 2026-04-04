<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Surat Permohonan BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Kelola surat permohonan pengisian bahan bakar armada.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Tambah Surat
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl shadow-sm animate-fade-in-down flex items-center">
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor surat atau kapal..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>

                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-500">
                        <svg x-show="!showFilters" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <svg x-show="showFilters" style="display: none;" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'"></span>
                    </button>

                    <div class="relative flex-1 md:flex-none md:w-48">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        </div>
                        <select wire:model.live="sortBy" class="pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full appearance-none shadow-sm hover:bg-slate-100">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
            </div>

            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-4 border-t border-slate-100 transition-all duration-200">
                
                <div class="relative">
                    <select wire:model.live="filterKapal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full appearance-none hover:bg-slate-50">
                        <option value="">Semua Armada Kapal</option>
                        @foreach($kapals as $kapal)
                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>

                <div class="relative">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-500 z-10">Dari Tgl</label>
                    <input type="date" wire:model.live="filterTanggalAwal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full hover:bg-slate-50 relative z-0">
                </div>

                <div class="relative">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-500 z-10">Sampai Tgl</label>
                    <input type="date" wire:model.live="filterTanggalAkhir" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full hover:bg-slate-50 relative z-0">
                </div>
                
                <div class="flex items-end">
                    <button wire:click="resetFilters" class="w-full h-full min-h-[34px] flex justify-center items-center px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
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
                <table class="w-full text-sm text-left text-gray-600 block md:table">
                    <thead class="hidden md:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Nomor & Tanggal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Informasi Kapal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Rincian Volume BBM</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right w-1/4">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block md:table-row-group md:divide-y md:divide-gray-50 space-y-4 md:space-y-0">
                        @forelse($permohonans as $item)
                            @php
                                $soundings = $item->suratTugas->laporanSebelumPengisianBbm->soundings ?? collect();
                                $jumlahLiter = $soundings->sum('pengisian');
                                $jenisBbm = $soundings->first()->jenis_bbm ?? 'Dexlite';
                            @endphp
                            
                            <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none hover:bg-slate-50/50 transition-colors duration-150 border border-gray-100 md:border-none">
                                
                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Nomor & Tanggal</span>
                                    <h3 class="font-bold text-gray-900 text-base mb-1">{{ $item->nomor_surat ?? '-' }}</h3>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->tanggal_surat)->translatedFormat('d M Y') }}
                                    </div>
                                    @if($item->klasifikasi)
                                        <span class="inline-block mt-2 text-[10px] bg-slate-100 px-2 py-0.5 rounded font-semibold text-slate-600">{{ $item->klasifikasi }}</span>
                                    @endif
                                </td>

                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Informasi Kapal</span>
                                    <div class="flex items-center">
                                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 mr-3 hidden md:block">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-800 block">{{ $item->suratTugas->laporanSebelumPengisianBbm->kapal->nama_kapal ?? 'Tidak ada data kapal' }}</span>
                                            <span class="text-xs text-gray-500">{{ $item->suratTugas->laporanSebelumPengisianBbm->kapal->skpd_ukpd ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none relative z-10">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider">Rincian Volume BBM</span>
                                    <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-100">
                                        <span class="font-bold text-emerald-700 text-sm mr-1">{{ $jenisBbm }}</span>
                                        <span class="text-emerald-500 mx-1">|</span>
                                        <span class="font-extrabold text-emerald-800 text-sm">{{ rtrim(rtrim(number_format($jumlahLiter, 2, ',', '.'), '0'), ',') }} L</span>
                                    </div>
                                </td>
                                
                                <td class="flex flex-row md:table-cell px-4 py-4 md:px-6 md:py-5 md:text-right gap-2 md:gap-0 align-middle relative z-10">
                                    <div class="flex flex-wrap md:flex-col lg:flex-row justify-end gap-2 w-full">
                                        <a href="#" target="_blank" class="flex-1 lg:flex-none justify-center inline-flex items-center text-emerald-600 hover:text-white font-semibold bg-emerald-50 hover:bg-emerald-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-emerald-100">
                                            <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            <span class="md:hidden lg:inline text-xs">Cetak</span>
                                        </a>

                                        <button wire:click="edit({{ $item->id }})" class="flex-1 lg:flex-none justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-indigo-100">
                                            <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span class="md:hidden lg:inline text-xs">Edit</span>
                                        </button>

                                        <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin ingin menghapus surat permohonan ini?" class="flex-1 lg:flex-none justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-rose-100">
                                            <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="md:hidden lg:inline text-xs">Hapus</span>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                        <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none">
                            <td colspan="4" class="block md:table-cell px-6 py-16 text-center text-gray-500">
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
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[95vh] flex flex-col">
                
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                            {{ $permohonan_id ? 'Edit Surat Permohonan' : 'Tambah Surat Permohonan' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl text-sm p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                    <form wire:submit.prevent="store" id="form-surat">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            
                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Surat Tugas <span class="text-rose-500">*</span></label>
                                <select wire:model="surat_tugas_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                    <option value="">-- Pilih Surat Tugas Terkait --</option>
                                    @foreach($surat_tugas_list as $st)
                                        <option value="{{ $st->id }}">{{ $st->nomor_surat }} (Kapal: {{ $st->laporanSebelumPengisianBbm->kapal->nama_kapal ?? '-' }})</option>
                                    @endforeach
                                </select>
                                @error('surat_tugas_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="nomor_surat" placeholder=".../PH.12.00" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                @error('nomor_surat') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Surat <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="tanggal_surat" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                @error('tanggal_surat') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Klasifikasi</label>
                                <input type="text" wire:model="klasifikasi" placeholder="Biasa / Penting" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran</label>
                                <input type="text" wire:model="lampiran" placeholder="1 (satu) berkas" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors">
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end p-5 border-t border-slate-100 rounded-b-2xl sm:space-x-3 bg-slate-50/80 gap-3 sm:gap-0 shrink-0">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" form="form-surat" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        Simpan Surat
                    </button>
                </div>
                
            </div>
        </div>
        @endif
        
    </div>
</div>