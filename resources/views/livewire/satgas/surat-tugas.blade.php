<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Surat Tugas Satgas</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Pengelolaan Surat Penugasan Pengisian BBM Kapal.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Buat Surat Tugas
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded-r-xl shadow-sm">
                <p class="text-sm font-semibold text-teal-800">{{ session('message') }}</p>
            </div>
        @endif

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-hidden w-full">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 block lg:table">
                    <thead class="hidden lg:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold w-1/4">Laporan Terkait</th>
                            <th scope="col" class="px-6 py-5 font-bold w-1/4">Nomor Surat</th>
                            <th scope="col" class="px-6 py-5 font-bold w-1/4">Waktu Pelaksanaan</th>
                            <th scope="col" class="px-6 py-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group space-y-4 lg:space-y-0 lg:divide-y lg:divide-gray-50">
                        @forelse($surat_tugas as $surat)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 align-top">
                                <span class="text-xs font-bold text-teal-500 uppercase lg:hidden mb-2 block">Laporan Terkait</span>
                                
                                @if($surat->laporanBbm)
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900 text-base mb-1">{{ $surat->laporanBbm->kapal->nama_kapal ?? 'Kapal' }}</span>
                                    <span class="text-teal-600 font-semibold text-xs flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $surat->laporanBbm->hari }}, {{ \Carbon\Carbon::parse($surat->laporanBbm->tanggal)->format('d M Y') }}
                                    </span>
                                    <span class="text-gray-500 text-[10px] mt-1.5 flex items-start">
                                        <svg class="w-3 h-3 mr-1 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $surat->laporanBbm->lokasi }}
                                    </span>
                                </div>
                                @else
                                <span class="text-rose-500 text-xs font-bold">Laporan BBM telah dihapus.</span>
                                @endif
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 align-top">
                                <span class="text-xs font-bold text-teal-500 uppercase lg:hidden mb-2 block">Nomor Surat</span>
                                <div class="bg-gray-100 px-3 py-1.5 rounded inline-block border border-gray-200">
                                    <span class="font-bold text-gray-800">{{ $surat->nomor_surat }}/PH.12.00</span>
                                </div>
                                <div class="text-[10px] text-gray-500 mt-2 font-medium">
                                    Dikeluarkan: {{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('d/m/Y') }}
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 align-top">
                                <span class="text-xs font-bold text-teal-500 uppercase lg:hidden mb-2 block">Waktu Pelaksanaan</span>
                                <div class="flex items-center text-gray-700 bg-teal-50 px-2.5 py-1.5 rounded border border-teal-100 inline-block">
                                    <span class="font-bold text-xs">{{ $surat->waktu_pelaksanaan }}</span>
                                </div>
                            </td>

                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[140px] lg:ml-auto">
                                    
                                    <a href="{{ route('surattugas.pdf.preview', $surat->id) }}" target="_blank" class="w-full justify-center inline-flex items-center text-slate-700 font-semibold bg-slate-100 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-slate-200 hover:border-slate-800 shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span>Preview PDF</span>
                                    </a>

                                    <div class="flex gap-2">
                                        <button wire:click="edit({{ $surat->id }})" class="flex-1 justify-center inline-flex items-center text-teal-600 hover:text-white font-semibold bg-teal-50 hover:bg-teal-600 px-3 py-2 rounded-lg transition-all duration-200 border border-teal-100">
                                            <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <button wire:click="delete({{ $surat->id }})" onclick="confirm('Yakin hapus Surat Tugas ini?') || event.stopImmediatePropagation()" class="flex-1 justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100">
                                            <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="4" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
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
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all max-h-[90vh] flex flex-col">
                
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                        {{ $surat_id ? 'Edit Surat Tugas' : 'Buat Surat Tugas' }}
                    </h3>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 rounded-xl p-2 border shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="overflow-y-auto flex-1 p-4 sm:p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-surat">
                        <div class="space-y-5">
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tautkan Laporan BBM <span class="text-rose-500">*</span></label>
                                <select wire:model="laporan_bbm_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                    <option value="">-- Pilih Laporan BBM --</option>
                                    @foreach($laporans as $lap)
                                        <option value="{{ $lap->id }}">Lap #{{ $lap->id }} - {{ $lap->kapal->nama_kapal ?? 'Kapal' }} (Tgl: {{ \Carbon\Carbon::parse($lap->tanggal)->format('d/m/Y') }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-500 mt-1">Data Kapal, Tanggal, Lokasi, dan Petugas akan otomatis terisi di PDF berdasarkan Laporan ini.</p>
                            </div>

                            <div class="border-t border-slate-100 my-2"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center">
                                        <input type="text" wire:model="nomor_surat" placeholder="001" class="px-4 py-2.5 bg-slate-50 border border-slate-200 border-r-0 text-sm rounded-l-xl w-full focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                        <span class="px-3 py-2.5 bg-gray-100 border border-gray-200 text-sm rounded-r-xl font-bold text-gray-600">/PH.12.00</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Dikeluarkan <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model="tanggal_dikeluarkan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-sm rounded-xl w-full focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Pelaksanaan (Pukul) <span class="text-rose-500">*</span></label>
                                <div class="flex items-center">
                                    <input type="text" wire:model="waktu_pelaksanaan" placeholder="Contoh: 08:00 - Selesai" class="px-4 py-2.5 bg-slate-50 border border-slate-200 border-r-0 text-sm rounded-l-xl w-full focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                    <span class="px-3 py-2.5 bg-gray-100 border border-gray-200 text-sm rounded-r-xl font-bold text-gray-600">WIB</span>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end p-4 border-t border-slate-100 rounded-b-2xl bg-slate-50/80 gap-3">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto px-5 py-2.5 border rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-sm transition-colors">Batal</button>
                    <button type="submit" form="form-surat" class="w-full sm:w-auto px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">Simpan Surat</button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>