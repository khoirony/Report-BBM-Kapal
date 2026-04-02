<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 3.25-2 6-2 2.75 0 3.25 2 6 2 1.3 0 1.9-.5 2.5-1"/>
                        <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/>
                        <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                        <path d="M12 10v4"/>
                        <path d="M12 2v3"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Data Kapal</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Kelola informasi dan sertifikasi seluruh armada kapal.</p>
                </div>
            </div>
            
            <button wire:click="create()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Kapal
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

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-visible w-full">
            <table class="w-full text-sm text-left text-gray-600 block md:table">
                
                <thead class="hidden md:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Identitas Kapal</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/4">Spesifikasi Fisik</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/6">Performa Mesin</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/6 text-center">Operasional & Dokumen</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/6 text-right">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="block md:table-row-group space-y-6 md:space-y-0 md:divide-y md:divide-gray-50">
                    @forelse($kapals as $kapal)
                    <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none hover:bg-slate-50/80 transition-colors duration-150">
                        
                        <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none align-top">
                            <span class="text-xs font-bold text-indigo-500 uppercase md:hidden mb-2">Identitas Kapal</span>
                            <div class="flex items-start">
                                <div class="hidden md:flex flex-shrink-0 h-10 w-10 items-center justify-center bg-indigo-50 rounded-lg text-indigo-600 mr-3 mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="5" r="3"></circle><line x1="12" y1="22" x2="12" y2="8"></line><path d="M5 12H2a10 10 0 0 0 20 0h-3"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base">{{ $kapal->nama_kapal ?? '-' }}</h3>
                                    <p class="text-sm text-gray-500 font-medium mt-0.5">{{ $kapal->skpd_ukpd ?? 'Tanpa SKPD' }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600 mt-1.5">
                                        Thn: {{ $kapal->tahun_pembuatan ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="flex flex-col md:table-cell px-4 py-3 md:px-6 md:py-5 border-b border-gray-50 md:border-none align-top">
                            <span class="text-xs font-semibold text-gray-400 uppercase md:hidden mb-1">Spesifikasi Fisik</span>
                            <div class="space-y-1">
                                <p class="text-sm"><span class="text-gray-400 mr-1">Jenis:</span> <span class="font-medium text-gray-800">{{ $kapal->jenis_dan_tipe ?? '-' }}</span></p>
                                <p class="text-sm"><span class="text-gray-400 mr-1">Bahan:</span> <span class="font-medium text-gray-800">{{ $kapal->material ?? '-' }}</span></p>
                                <p class="text-sm"><span class="text-gray-400 mr-1">Ukuran:</span> <span class="font-medium text-gray-800">{{ $kapal->ukuran ?? '-' }}</span></p>
                            </div>
                        </td>

                        <td class="flex flex-col md:table-cell px-4 py-3 md:px-6 md:py-5 border-b border-gray-50 md:border-none align-top">
                            <span class="text-xs font-semibold text-gray-400 uppercase md:hidden mb-1">Performa Mesin</span>
                            <div class="space-y-2 mt-1">
                                <div class="flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center mr-2">
                                        <span class="text-[10px] font-bold">GT</span>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $kapal->tonase_kotor_gt ?? '-' }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-md bg-orange-50 text-orange-600 flex items-center justify-center mr-2">
                                        <span class="text-[10px] font-bold">KW</span>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $kapal->tenaga_penggerak_kw ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="flex flex-col md:table-cell px-4 py-4 md:px-6 md:py-5 border-b border-gray-50 md:border-none align-middle md:text-center">
                            
                            <div class="md:hidden flex flex-col space-y-4 w-full text-left">
                                <div>
                                    <span class="text-xs font-semibold text-gray-400 uppercase mb-1 block">Daerah Pelayaran</span>
                                    <p class="text-sm text-gray-800 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        {{ $kapal->daerah_pelayaran ?: 'Belum ada data.' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-gray-400 uppercase mb-1 block">List Sertifikat</span>
                                    <p class="text-sm text-gray-800 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        {{ $kapal->list_sertifikat_kapal ?: 'Belum ada data.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="hidden md:flex flex-col justify-center items-center gap-3">
                                
                                <div class="relative group inline-block">
                                    <button type="button" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors border border-teal-100 cursor-default">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Rute
                                    </button>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-xl opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 z-10 text-left shadow-xl pointer-events-none">
                                        <p class="font-bold text-gray-300 mb-1 border-b border-gray-700 pb-1">Daerah Pelayaran:</p>
                                        <p class="whitespace-normal leading-relaxed">{{ $kapal->daerah_pelayaran ?: 'Belum ada data rute pelayaran.' }}</p>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </div>

                                <div class="relative group inline-block">
                                    <button type="button" class="inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors border border-purple-100 cursor-default">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Dokumen
                                    </button>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 p-3 bg-gray-900 text-white text-xs rounded-xl opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 z-10 text-left shadow-xl pointer-events-none">
                                        <p class="font-bold text-gray-300 mb-1 border-b border-gray-700 pb-1">List Sertifikat:</p>
                                        <p class="whitespace-normal leading-relaxed">{{ $kapal->list_sertifikat_kapal ?: 'Belum ada data sertifikat.' }}</p>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                    </div>
                                </div>

                            </div>
                        </td>
                        
                        <td class="flex flex-row md:table-cell px-4 py-4 md:px-6 md:py-5 md:text-right gap-3 md:gap-0 space-x-0 md:space-x-2 bg-slate-50/50 md:bg-transparent rounded-b-2xl md:rounded-none align-middle">
                            <div class="flex md:flex-col lg:flex-row justify-end gap-2 w-full">
                                <button wire:click="edit({{ $kapal->id }})" class="flex-1 lg:flex-none justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-white md:bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-indigo-100 md:border-none shadow-sm md:shadow-none">
                                    <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span class="md:hidden lg:inline">Edit</span>
                                </button>
                                <button wire:click="delete({{ $kapal->id }})" wire:confirm="Yakin ingin menghapus data ini?" class="flex-1 lg:flex-none justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-white md:bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-rose-100 md:border-none shadow-sm md:shadow-none">
                                    <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    <span class="md:hidden lg:inline">Hapus</span>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none">
                        <td colspan="5" class="block md:table-cell px-6 py-16 text-center text-gray-500">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Tidak ada kapal yang terdaftar</h3>
                            <p class="text-sm text-gray-500">Mulai kelola armada dengan menambahkan data kapal baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-opacity">
            <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl transform transition-all">
                
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $kapal_id ? 'Edit Data Kapal' : 'Tambah Data Kapal' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl text-sm p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                        
                        <div class="space-y-4 sm:space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kapal <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="text" wire:model="nama_kapal" placeholder="Masukkan nama kapal..." class="pl-4 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                                </div>
                                @error('nama_kapal') <span class="text-rose-500 text-xs mt-1.5 block font-medium flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</span>@enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">SKPD / UKPD <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="skpd_ukpd" placeholder="Contoh: Dinas Perhubungan" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                                @error('skpd_ukpd') <span class="text-rose-500 text-xs mt-1.5 block font-medium flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis dan Tipe</label>
                                <input type="text" wire:model="jenis_dan_tipe" placeholder="Contoh: Kapal Penumpang" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Material</label>
                                <input type="text" wire:model="material" placeholder="Contoh: Baja / Fiber" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tahun Pembuatan</label>
                                <input type="number" wire:model="tahun_pembuatan" placeholder="YYYY" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                            </div>
                        </div>

                        <div class="space-y-4 sm:space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ukuran (PxLxD)</label>
                                <input type="text" wire:model="ukuran" placeholder="Contoh: 10m x 5m x 3m" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tonase Kotor (GT)</label>
                                <input type="text" wire:model="tonase_kotor_gt" placeholder="Masukkan angka GT" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tenaga Penggerak (KW)</label>
                                <input type="text" wire:model="tenaga_penggerak_kw" placeholder="Masukkan kapasitas KW" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Daerah Pelayaran</label>
                                <textarea wire:model="daerah_pelayaran" rows="3" placeholder="Masukkan rute atau daerah..." class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors resize-none"></textarea>
                            </div>
                        </div>
                        
                    </div>

                    <div class="mt-2 sm:mt-0 pt-4 border-t border-slate-100">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">List Sertifikat Kapal</label>
                        <textarea wire:model="list_sertifikat_kapal" rows="3" placeholder="Pisahkan dengan koma atau baris baru..." class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors resize-none"></textarea>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end p-5 border-t border-slate-100 rounded-b-2xl sm:space-x-3 bg-slate-50/80 gap-3 sm:gap-0">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Batal
                    </button>
                    <button wire:click="store()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Data
                    </button>
                </div>
                
            </div>
        </div>
        @endif
        
    </div>
</div>