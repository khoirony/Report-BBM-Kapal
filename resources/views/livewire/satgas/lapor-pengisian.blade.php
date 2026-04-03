<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
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
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Laporan Pengisian BBM</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Rekapitulasi dan pelaporan tugas pengisian bahan bakar armada.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Buat Laporan
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-xl shadow-sm animate-fade-in-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-100 p-1 rounded-full">
                        <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-emerald-800">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-hidden w-full">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 block lg:table">
                    <thead class="hidden lg:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Informasi Umum</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Detail Penugasan</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/5">Tim Petugas</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Riwayat Sounding</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group space-y-4 lg:space-y-0 lg:divide-y lg:divide-gray-50">
                        @forelse($laporans as $laporan)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 transition-colors duration-150 p-4 lg:p-0">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Informasi Umum</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900 text-base mb-1">{{ $laporan->hari }},</span>
                                    <span class="text-indigo-600 font-semibold mb-2">
                                        {{ $laporan->tanggal ? $laporan->tanggal->format('d M Y') : '-' }}
                                    </span>
                                    <div class="flex items-start mt-2">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-xs text-gray-600 leading-tight">{{ $laporan->lokasi }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Detail Penugasan</span>
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Dasar Hukum</span>
                                        <span class="text-sm font-semibold text-gray-800">{{ $laporan->dasar_hukum ?: '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Kegiatan</span>
                                        <span class="text-xs text-gray-700 bg-gray-100 px-2 py-1 rounded inline-block mt-1">{{ $laporan->kegiatan }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Tujuan</span>
                                        <p class="text-xs text-gray-600 line-clamp-2" title="{{ $laporan->tujuan }}">{{ $laporan->tujuan }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Tim Petugas</span>
                                <div class="space-y-2">
                                    @if(is_array($laporan->petugas_list))
                                        @php
                                            $validPetugas = collect($laporan->petugas_list)->filter(fn($p) => !empty($p['nama']));
                                        @endphp
                                        @forelse($validPetugas->take(3) as $petugas)
                                            <div class="flex items-center text-xs">
                                                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-2 text-[10px] flex-shrink-0">
                                                    {{ substr($petugas['nama'], 0, 1) }}
                                                </div>
                                                <div class="flex flex-col truncate">
                                                    <span class="font-semibold text-gray-800 truncate">{{ $petugas['nama'] }}</span>
                                                    <span class="text-[10px] text-gray-500 truncate">{{ $petugas['jabatan'] }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">Belum ada petugas terdata.</span>
                                        @endforelse
                                        
                                        @if($validPetugas->count() > 3)
                                            <div class="text-xs text-indigo-600 font-medium pl-7 mt-1">
                                                + {{ $validPetugas->count() - 3 }} petugas lainnya
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Data petugas tidak valid.</span>
                                    @endif
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top lg:w-1/4 w-full">
                                <span class="text-xs font-bold text-indigo-500 uppercase lg:hidden mb-2 block">Riwayat Sounding</span>
                                
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded truncate max-w-[70%]">
                                        {{ $laporan->kapal->nama_kapal ?? 'Kapal Terhapus' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full whitespace-nowrap">
                                        {{ $laporan->soundings->count() }} Titik
                                    </span>
                                </div>

                                @if($laporan->soundings->count() > 0)
                                    <div class="space-y-3 max-h-[16rem] overflow-y-auto custom-scrollbar pr-1">
                                        @foreach($laporan->soundings as $snd)
                                        <div class="bg-blue-50/40 border border-blue-100 rounded-lg p-2.5 relative overflow-hidden">
                                            <div class="absolute top-0 left-0 w-1 h-full bg-blue-400"></div>
                                            
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-xs font-bold text-blue-900 truncate pr-2">{{ $snd->lokasi }}</span>
                                                <span class="text-[9px] text-gray-500 font-medium bg-white px-1.5 py-0.5 rounded border border-gray-100 flex-shrink-0">
                                                    {{ \Carbon\Carbon::parse($snd->jam_berangkat)->format('H:i') }} WIB
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-1 sm:grid-cols-4 sm:gap-0 text-[9px] text-center bg-white rounded border border-blue-50 p-1.5 shadow-sm">
                                                <div class="flex flex-col sm:border-r border-gray-50 sm:pb-0 pb-1 border-b sm:border-b-0">
                                                    <span class="text-gray-400 uppercase font-bold tracking-wider mb-0.5">Awal</span>
                                                    <span class="font-bold text-gray-700">{{ floatval($snd->bbm_awal) }}</span>
                                                </div>
                                                <div class="flex flex-col sm:border-r border-gray-50 sm:pb-0 pb-1 border-b sm:border-b-0 sm:border-l-0 border-l border-gray-50">
                                                    <span class="text-gray-400 uppercase font-bold tracking-wider mb-0.5">Isi</span>
                                                    <span class="font-bold text-emerald-600">+{{ floatval($snd->pengisian) }}</span>
                                                </div>
                                                <div class="flex flex-col sm:border-r border-gray-50 pt-1 sm:pt-0">
                                                    <span class="text-gray-400 uppercase font-bold tracking-wider mb-0.5">Pakai</span>
                                                    <span class="font-bold text-rose-500">-{{ floatval($snd->pemakaian) }}</span>
                                                </div>
                                                <div class="flex flex-col bg-blue-50/50 rounded-br-sm sm:rounded-r-sm pt-1 sm:pt-0 border-l border-gray-50 sm:border-l-0">
                                                    <span class="text-blue-400 uppercase font-bold tracking-wider mb-0.5">Akhir</span>
                                                    <span class="font-extrabold text-blue-700">{{ floatval($snd->bbm_akhir) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-amber-50 text-amber-600 text-[10px] p-2 rounded-lg border border-amber-100 mt-2">
                                        Belum ada data sounding tercatat untuk laporan ini.
                                    </div>
                                @endif
                            </td>
                            
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto">
                                    
                                    <a href="{{ route('laporan.pdf.preview', $laporan->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm">
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
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="5" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Laporan Satgas</h3>
                                <p class="text-sm text-gray-500">Mulai buat laporan penugasan BBM baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                            {{ $laporan_id ? 'Edit Laporan' : 'Buat Laporan Baru' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl text-sm p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto custom-scrollbar flex-1 p-4 sm:p-6">
                    <form wire:submit.prevent="store" id="form-laporan">
                        <div class="space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Armada Kapal <span class="text-rose-500">*</span></label>
                                    <select wire:model.live="kapal_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors" required>
                                        <option value="">-- Pilih Armada Kapal --</option>
                                        @foreach($kapals as $kapal)
                                            <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Hari <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="hari" placeholder="Misal: Senin" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model.live="tanggal" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors" required>
                                </div>

                                <div class="col-span-1 md:col-span-2 mt-2">
                                    <label class="block text-sm font-bold text-indigo-700 mb-2 border-b border-indigo-100 pb-2">
                                        Pilih Data Sounding yang Dimasukkan ke Laporan:
                                    </label>
                                    
                                    @if(empty($kapal_id) || empty($tanggal))
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-500 text-center">
                                            Silakan pilih <b>Armada Kapal</b> dan <b>Tanggal</b> terlebih dahulu untuk melihat data sounding.
                                        </div>
                                    @elseif(count($available_soundings) == 0)
                                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-600 text-center font-medium">
                                            Tidak ada pencatatan Sounding untuk armada ini pada tanggal yang dipilih.
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($available_soundings as $snd)
                                                <label class="flex items-start p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ in_array($snd->id, $selected_soundings) ? 'border-indigo-500 bg-indigo-50/30 shadow-sm' : 'border-gray-200 bg-white' }}">
                                                    <div class="flex items-center h-5 mt-0.5">
                                                        <input type="checkbox" wire:model="selected_soundings" value="{{ $snd->id }}" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                                    </div>
                                                    <div class="ml-3 flex flex-col w-full">
                                                        <div class="flex justify-between items-start w-full mb-2">
                                                            <span class="text-sm font-bold text-gray-900 pr-2 truncate max-w-[150px]">{{ $snd->lokasi }}</span>
                                                            <span class="text-[10px] text-gray-500 bg-white border border-gray-200 px-1.5 py-0.5 rounded shadow-sm flex-shrink-0">
                                                                {{ \Carbon\Carbon::parse($snd->jam_berangkat)->format('H:i') }}
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="grid grid-cols-2 gap-1 sm:grid-cols-4 sm:gap-2 text-xs bg-white/60 p-2 rounded border {{ in_array($snd->id, $selected_soundings) ? 'border-indigo-100' : 'border-gray-100' }}">
                                                            <div class="flex flex-col flex-1 sm:border-r border-gray-200 border-b sm:border-b-0 pb-1 sm:pb-0">
                                                                <span class="text-[9px] text-gray-400 uppercase font-bold tracking-wide">Awal</span>
                                                                <span class="font-semibold text-gray-700">{{ floatval($snd->bbm_awal) }}</span>
                                                            </div>
                                                            <div class="flex flex-col flex-1 sm:border-r border-gray-200 sm:pl-2 border-b sm:border-b-0 pb-1 sm:pb-0 border-l sm:border-l-0 pl-2">
                                                                <span class="text-[9px] text-emerald-500 uppercase font-bold tracking-wide">Isi</span>
                                                                <span class="font-bold text-emerald-600">+{{ floatval($snd->pengisian) }}</span>
                                                            </div>
                                                            <div class="flex flex-col flex-1 sm:border-r border-gray-200 sm:pl-2 pt-1 sm:pt-0">
                                                                <span class="text-[9px] text-rose-400 uppercase font-bold tracking-wide">Pakai</span>
                                                                <span class="font-bold text-rose-500">-{{ floatval($snd->pemakaian) }}</span>
                                                            </div>
                                                            <div class="flex flex-col flex-1 sm:pl-2 pt-1 sm:pt-0 border-l border-gray-200 sm:border-l-0 pl-2">
                                                                <span class="text-[9px] text-blue-500 uppercase font-bold tracking-wide">Akhir</span>
                                                                <span class="font-extrabold text-blue-600">{{ floatval($snd->bbm_akhir) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('selected_soundings') <span class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</span> @enderror
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-slate-100"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dasar Hukum / Nomor Surat <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="dasar_hukum" placeholder="Surat Perintah No. 123/2026" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi Kegiatan <span class="text-rose-500">*</span></label>
                                    <input type="text" wire:model="lokasi" placeholder="Pelabuhan Sunda Kelapa" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors" required>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kegiatan Utama</label>
                                    <input type="text" wire:model="kegiatan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tujuan Kegiatan</label>
                                    <textarea wire:model="tujuan" rows="2" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors"></textarea>
                                </div>
                            </div>

                            <div class="border-t border-slate-100"></div>

                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-bold text-slate-800">Daftar Tim Satgas</h4>
                                    <span class="text-xs bg-indigo-100 text-indigo-700 font-semibold px-2.5 py-1 rounded-lg">Maks. 7 Orang</span>
                                </div>
                                
                                <div class="overflow-x-auto bg-slate-50 rounded-xl border border-slate-200">
                                    <table class="w-full text-sm text-left min-w-[500px]">
                                        <thead class="text-xs text-slate-500 uppercase bg-slate-100 border-b border-slate-200">
                                            <tr>
                                                <th class="px-3 py-3 text-center w-10 font-bold">No</th>
                                                <th class="px-3 py-3 font-bold w-1/2">Nama Petugas</th>
                                                <th class="px-3 py-3 font-bold w-1/2">Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200">
                                            @for($i = 0; $i < 7; $i++)
                                            <tr class="bg-white hover:bg-slate-50 transition-colors">
                                                <td class="px-3 py-2 text-center text-slate-400 font-medium">{{ $i + 1 }}</td>
                                                <td class="px-3 py-2">
                                                    <input type="text" wire:model="petugas_list.{{ $i }}.nama" placeholder="Nama..." class="w-full bg-transparent border-0 focus:ring-0 text-sm p-1 placeholder-slate-300">
                                                </td>
                                                <td class="px-3 py-2">
                                                    <input type="text" wire:model="petugas_list.{{ $i }}.jabatan" placeholder="Jabatan..." class="w-full bg-transparent border-0 focus:ring-0 text-sm p-1 placeholder-slate-300">
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-2">* Kosongkan baris yang tidak diperlukan.</p>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end p-4 sm:p-6 border-t border-slate-100 rounded-b-2xl sm:space-x-3 bg-slate-50/80 gap-3 sm:gap-0 mt-auto">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm order-2 sm:order-1">
                        <svg class="w-4 h-4 mr-2 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Batal
                    </button>
                    <button type="submit" form="form-laporan" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all shadow-sm hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 order-1 sm:order-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Laporan
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>