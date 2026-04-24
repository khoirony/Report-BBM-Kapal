<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 22v-8a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v8"></path>
                        <path d="M3 10V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4"></path>
                        <path d="M7 22v-4"></path>
                        <path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Data Sounding BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Kelola riwayat pengisian dan pemakaian bahan bakar armada.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Tambah Pencatatan
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari keterangan atau armada..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
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
                        <select wire:model.live="sortBy" class="pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full appearance-none shadow-sm hover:bg-slate-100 cursor-pointer">
                            <option value="latest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
            </div>

            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-4 border-t border-slate-100 transition-all duration-200">
                
                <div class="relative">
                    <select wire:model.live="filterKapal" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full appearance-none hover:bg-slate-50 cursor-pointer">
                        <option value="">Semua Armada Kapal</option>
                        @foreach($kapals as $kapal)
                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>

                @if (auth()->user()?->role?->slug == 'superadmin')
                <div class="relative">
                    <select wire:model.live="filterUkpd" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full appearance-none hover:bg-slate-50 cursor-pointer">
                        <option value="">Semua SKPD/UKPD</option>
                        @foreach($ukpds as $ukpd)
                            <option value="{{ $ukpd->id }}">{{ $ukpd->singkatan ?? $ukpd->nama }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
                @endif

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

        @php
            // Grouping diubah menggunakan tanggal_sounding
            $groupedSoundings = $soundings->groupBy(function($item) {
                return $item->kapal_id . '_' . $item->tanggal_sounding;
            })->map(function($group) {
                return $group->sortBy('jam_pemeriksaan');
            });
        @endphp

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-hidden w-full relative">
            
            <div wire:loading class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden md:flex items-center justify-center rounded-2xl">
                <div class="w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 block md:table">
                    <thead class="hidden md:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/3">Keterangan Sounding</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/3">Rincian Volume BBM (Liter)</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/6">Jam Pemeriksaan & PIC</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right w-1/6">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block md:table-row-group md:divide-y md:divide-gray-50">
                        @forelse($groupedSoundings as $groupKey => $records)
                            @php
                                $firstRecord = $records->first();
                                $tanggal = \Carbon\Carbon::parse($firstRecord->tanggal_sounding)->translatedFormat('l, d F Y');
                            @endphp
                            
                            <tr class="block md:table-row bg-indigo-50/60 border-t border-indigo-100 md:border-t-0">
                                <td colspan="4" class="px-4 py-3 md:px-6 md:py-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center text-indigo-900 justify-between">
                                        <div class="flex items-center mb-1 sm:mb-0">
                                            <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600 mr-3 hidden md:block">
                                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="5" r="3"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="22"></line>
                                                    <path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-bold text-base block">{{ $firstRecord->kapal->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                                <span class="text-[10px] bg-indigo-100/80 px-1.5 py-0.5 rounded font-semibold text-indigo-600">
                                                    {{ $firstRecord->kapal->ukpd->singkatan ?? $firstRecord->kapal->ukpd->nama ?? '-' }}
                                                </span>
                                            </div>
                                        </div>

                                        <span class="text-xs sm:text-sm font-semibold text-indigo-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $tanggal }}
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            @foreach($records as $row)
                            <tr class="relative block md:table-row bg-white hover:bg-slate-50/50 transition-colors duration-150 border-b border-gray-100 last:border-b-0">
                                
                                <td class="relative flex flex-col md:table-cell pl-12 pr-4 py-4 md:pl-16 md:pr-6 md:py-5 align-top border-b border-gray-50 md:border-none">
                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 relative z-10 tracking-wider">Keterangan Sounding</span>
                                    
                                    @if(!$loop->first)
                                        <div class="absolute left-[23px] md:left-[31px] top-0 w-[2px] h-[24px] md:h-[28px] bg-indigo-200 z-0"></div>
                                    @endif
                                    
                                    @if(!$loop->last)
                                        <div class="absolute left-[23px] md:left-[31px] top-[24px] md:top-[28px] bottom-0 w-[2px] bg-indigo-200 z-0"></div>
                                    @endif

                                    <div class="absolute left-[19px] md:left-[27px] top-[19px] md:top-[23px] w-[10px] h-[10px] rounded-full bg-white border-[2.5px] border-indigo-500 z-10 ring-4 ring-white"></div>

                                    <div class="relative z-10">
                                        <h3 class="font-bold text-gray-800 text-sm md:text-base">{{ $row->keterangan }}</h3>
                                    </div>
                                </td>
                                
                                <td class="relative flex flex-col md:table-cell pl-12 pr-4 py-3 md:pl-6 md:pr-6 md:py-5 border-b border-gray-50 md:border-none align-middle z-10">
                                    @if(!$loop->last)
                                        <div class="absolute left-[23px] md:hidden top-0 bottom-0 w-[2px] bg-indigo-200 z-0"></div>
                                    @endif

                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-2 tracking-wider relative z-10">Rincian Volume BBM</span>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm bg-gray-50 md:bg-transparent p-3 md:p-0 rounded-xl border border-gray-100 md:border-none relative z-10">
                                        <div class="flex flex-col md:border-r md:border-gray-100 pr-2 pb-2 sm:pb-0 border-b border-gray-100 sm:border-b-0">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Awal</span>
                                            <span class="font-semibold text-gray-700">{{ floatval($row->bbm_awal) }}</span>
                                        </div>
                                        <div class="flex flex-col md:border-r md:border-gray-100 pr-2 pl-0 md:pl-2 pb-2 sm:pb-0 border-b border-gray-100 sm:border-b-0 border-l border-gray-100 sm:border-l-0 pl-2">
                                            <span class="text-[10px] text-emerald-500 uppercase font-bold tracking-wider">Isi</span>
                                            <span class="font-bold text-emerald-600">+ {{ floatval($row->pengisian) }}</span>
                                        </div>
                                        <div class="flex flex-col md:border-r md:border-gray-100 pr-2 pl-0 md:pl-2 pt-2 sm:pt-0">
                                            <span class="text-[10px] text-rose-400 uppercase font-bold tracking-wider">Pakai</span>
                                            <span class="font-bold text-rose-500">- {{ floatval($row->pemakaian) }}</span>
                                        </div>
                                        <div class="flex flex-col pl-0 md:pl-2 pt-2 sm:pt-0 border-l border-gray-100 sm:border-l-0 pl-2">
                                            <span class="text-[10px] text-blue-500 uppercase font-bold tracking-wider">Akhir</span>
                                            <span class="font-extrabold text-blue-600">{{ floatval($row->bbm_akhir) }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="relative flex flex-col md:table-cell pl-12 pr-4 py-3 md:pl-6 md:pr-6 md:py-5 border-b border-gray-50 md:border-none align-middle z-10">
                                    @if(!$loop->last)
                                        <div class="absolute left-[23px] md:hidden top-0 bottom-0 w-[2px] bg-indigo-200 z-0"></div>
                                    @endif

                                    <span class="text-[10px] font-bold text-indigo-400 uppercase md:hidden mb-1 tracking-wider relative z-10">Jam Pemeriksaan & PIC</span>
                                    <div class="flex flex-col space-y-2 w-max relative z-10">
                                        <div class="flex items-center text-xs font-semibold text-gray-700 bg-blue-50/50 px-2.5 py-1.5 rounded-lg border border-blue-100">
                                            <svg class="w-3.5 h-3.5 text-blue-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ \Carbon\Carbon::parse($row->jam_pemeriksaan)->format('H:i') }} WIB
                                        </div>
                                        @if(auth()->user() && auth()->user()?->role?->slug === 'superadmin')
                                            <div class="flex items-center text-[11px] font-medium text-indigo-700 bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100" title="Ditambahkan oleh">
                                                <svg class="w-3 h-3 text-indigo-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $row->user->name ?? 'Sistem / Terhapus' }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="relative flex flex-row md:table-cell pl-12 pr-4 py-4 md:pl-6 md:pr-6 md:py-5 md:text-right gap-2 md:gap-0 align-middle z-10">
                                    @if(!$loop->last)
                                        <div class="absolute left-[23px] md:hidden top-0 bottom-0 w-[2px] bg-indigo-200 z-0"></div>
                                    @endif

                                    <div class="flex md:flex-col lg:flex-row justify-end gap-2 w-full relative z-10">
                                        <button wire:click="edit({{ $row->id }})" class="flex-1 lg:flex-none justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-indigo-100">
                                            <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span class="md:hidden lg:inline text-xs">Edit</span>
                                        </button>
                                        <button wire:click="delete({{ $row->id }})" wire:confirm="Yakin ingin menghapus data sounding ini?" class="flex-1 lg:flex-none justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-rose-100">
                                            <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span class="md:hidden lg:inline text-xs">Hapus</span>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        @empty
                        <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none">
                            <td colspan="4" class="block md:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Pencatatan Sounding Tidak Ditemukan</h3>
                                <p class="text-sm text-gray-500">Gunakan filter atau pencarian yang lain.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $soundings->links() }}
            </div>
            
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-opacity">
            <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[95vh] flex flex-col">
                
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                            {{ $sounding_id ? 'Edit Pencatatan BBM' : 'Tambah Pencatatan BBM' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl text-sm p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                    <form wire:submit.prevent="store" id="form-sounding">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            
                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kapal <span class="text-rose-500">*</span></label>
                                <select wire:model="kapal_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors cursor-pointer" required>
                                    <option value="">-- Pilih Armada Kapal --</option>
                                    @foreach($kapals as $kapal)
                                        <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                                    @endforeach
                                </select>
                                @error('kapal_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-span-1 sm:col-span-2 border-t border-slate-100 my-1"></div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">BBM Awal (Liter) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="bbm_awal" placeholder="0" class="pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500 font-medium">L</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-emerald-700 mb-1.5">Pengisian (Liter) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="pengisian" placeholder="0" class="pl-4 pr-10 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 block w-full transition-colors" required>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-emerald-600 font-bold">+ L</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-rose-700 mb-1.5">Pemakaian (Liter) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="pemakaian" placeholder="0" class="pl-4 pr-10 py-2.5 bg-rose-50 border border-rose-200 text-rose-900 text-sm rounded-xl focus:ring-2 focus:ring-rose-500 block w-full transition-colors" required>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-rose-600 font-bold">- L</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-blue-700 mb-1.5">BBM Akhir (Otomatis)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" wire:model="bbm_akhir" readonly class="pl-4 pr-10 py-2.5 bg-blue-100 border border-blue-200 text-blue-800 font-bold text-sm rounded-xl block w-full cursor-not-allowed">
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-blue-800 font-extrabold">= L</span>
                                </div>
                            </div>

                            <div class="col-span-1 sm:col-span-2 border-t border-slate-100 my-1"></div>

                            <div class="col-span-1 sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Sounding <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model="tanggal_sounding" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                    @error('tanggal_sounding') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Pemeriksaan (WIB) <span class="text-rose-500">*</span></label>
                                    <input type="time" wire:model="jam_pemeriksaan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors" required>
                                    @error('jam_pemeriksaan') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan / Titik Sounding <span class="text-rose-500">*</span></label>
                                
                                <select wire:model.live="keterangan_pilihan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors cursor-pointer" required>
                                    <option value="">-- Pilih Keterangan --</option>
                                    <option value="sebelum isi BBM">Sebelum isi BBM</option>
                                    <option value="setelah isi BBM">Setelah isi BBM</option>
                                    <option value="setelah berlayar/sampai di Pelabuhan">Setelah berlayar/sampai di Pelabuhan</option>
                                    <option value="sebelum berangkat/dari Pelabuhan">Sebelum berangkat/dari Pelabuhan</option>
                                    <option value="other">Lainnya (Isi Manual)</option>
                                </select>
                                @error('keterangan_pilihan') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                
                                @if($keterangan_pilihan === 'other')
                                    <div class="mt-3 animate-fade-in-up">
                                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">Keterangan Manual <span class="text-rose-500">*</span></label>
                                        <textarea wire:model="keterangan" rows="2" placeholder="Tuliskan keterangan detail di sini..." class="px-4 py-2.5 bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors resize-y"></textarea>
                                        @error('keterangan') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                                    </div>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end p-5 border-t border-slate-100 rounded-b-2xl sm:space-x-3 bg-slate-50/80 gap-3 sm:gap-0 shrink-0">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" form="form-sounding" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        Simpan Pencatatan
                    </button>
                </div>
                
            </div>
        </div>
        @endif
        
    </div>
</div>