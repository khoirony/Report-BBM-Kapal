<div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
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
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Pencatatan
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

        @php
            $groupedSoundings = $soundings->groupBy(function($item) {
                return $item->kapal_id . '_' . \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
            });
        @endphp

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-visible w-full">
            <table class="w-full text-sm text-left text-gray-600 block md:table">
                
                <thead class="hidden md:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/3">Titik / Lokasi Sounding</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/3">Rincian Volume BBM (Liter)</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/6">Waktu Operasi</th>
                        <th scope="col" class="px-6 py-5 font-bold tracking-wider w-1/6 text-right">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="block md:table-row-group md:divide-y md:divide-gray-50">
                    @forelse($groupedSoundings as $groupKey => $records)
                        @php
                            $firstRecord = $records->first();
                            $tanggal = \Carbon\Carbon::parse($firstRecord->created_at)->translatedFormat('l, d F Y');
                        @endphp
                        
                        <tr class="block md:table-row bg-indigo-50/60 border-t border-indigo-100">
                            <td colspan="4" class="px-4 py-3 md:px-6 md:py-4">
                                <div class="flex items-center text-indigo-900">
                                    <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600 mr-3 hidden md:block">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                    </div>
                                    <span class="font-bold text-base">{{ $firstRecord->kapal->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                    <span class="mx-3 text-indigo-300">|</span>
                                    <span class="text-sm font-semibold text-indigo-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $tanggal }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                        @foreach($records as $row)
                        <tr class="relative block md:table-row bg-white hover:bg-slate-50/50 transition-colors duration-150 border-b border-gray-100 last:border-b-0">
                            
                            <td class="static md:relative flex flex-col md:table-cell pl-12 pr-4 py-4 md:pl-16 md:pr-6 md:py-5 align-top border-b border-gray-50 md:border-none">
                                <span class="text-xs font-bold text-indigo-500 uppercase md:hidden mb-2 relative z-10">Lokasi Sounding</span>
                                
                                @if(!$loop->first)
                                    <div class="absolute left-[23px] md:left-[31px] top-0 w-[2px] h-[24px] md:h-[28px] bg-indigo-300 z-0"></div>
                                @endif
                                
                                @if(!$loop->last)
                                    <div class="absolute left-[23px] md:left-[31px] top-[24px] md:top-[28px] bottom-0 w-[2px] bg-indigo-300 z-0"></div>
                                @endif

                                <div class="absolute left-[19px] md:left-[27px] top-[19px] md:top-[23px] w-[10px] h-[10px] rounded-full bg-white border-[2.5px] border-indigo-600 z-10 shadow-sm ring-4 ring-white"></div>

                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm md:text-base relative z-10">{{ $row->lokasi }}</h3>
                                </div>
                            </td>
                            
                            <td class="flex flex-col md:table-cell pl-12 pr-4 py-3 md:px-6 md:py-5 border-b border-gray-50 md:border-none align-middle relative z-10">
                                <span class="text-xs font-semibold text-gray-400 uppercase md:hidden mb-2">Rincian Volume BBM</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm bg-gray-50 md:bg-transparent p-3 md:p-0 rounded-xl border border-gray-100 md:border-none">
                                    <div class="flex flex-col md:border-r md:border-gray-100 pr-2">
                                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Awal</span>
                                        <span class="font-semibold text-gray-800">{{ $row->bbm_awal }}</span>
                                    </div>
                                    <div class="flex flex-col md:border-r md:border-gray-100 pr-2 pl-0 md:pl-2">
                                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Isi</span>
                                        <span class="font-bold text-emerald-600">+ {{ $row->pengisian }}</span>
                                    </div>
                                    <div class="flex flex-col md:border-r md:border-gray-100 pr-2 pl-0 md:pl-2">
                                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Pakai</span>
                                        <span class="font-bold text-rose-500">- {{ $row->pemakaian }}</span>
                                    </div>
                                    <div class="flex flex-col pl-0 md:pl-2">
                                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Akhir</span>
                                        <span class="font-extrabold text-blue-600">{{ $row->bbm_akhir }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="flex flex-col md:table-cell pl-12 pr-4 py-3 md:px-6 md:py-5 border-b border-gray-50 md:border-none align-middle relative z-10">
                                <span class="text-xs font-semibold text-gray-400 uppercase md:hidden mb-1">Waktu Operasi</span>
                                <div class="flex items-center text-sm font-medium text-gray-700 bg-blue-50/50 p-2 rounded-lg border border-blue-100 inline-block">
                                    <svg class="w-4 h-4 text-blue-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ \Carbon\Carbon::parse($row->jam_berangkat)->format('H:i') }} - {{ \Carbon\Carbon::parse($row->jam_kembali)->format('H:i') }} <span class="text-xs ml-1 text-gray-500 font-normal">WIB</span>
                                </div>
                            </td>
                            
                            <td class="flex flex-row md:table-cell pl-12 pr-4 py-4 md:px-6 md:py-5 md:text-right gap-3 md:gap-0 space-x-0 md:space-x-2 md:bg-transparent rounded-b-2xl md:rounded-none align-middle relative z-10">
                                <div class="flex md:flex-col lg:flex-row justify-end gap-2 w-full">
                                    <button wire:click="edit({{ $row->id }})" class="flex-1 lg:flex-none justify-center inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-white md:bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-indigo-100 md:border-none shadow-sm md:shadow-none">
                                        <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="md:hidden lg:inline">Edit</span>
                                    </button>
                                    <button wire:click="delete({{ $row->id }})" wire:confirm="Yakin ingin menghapus data sounding ini?" class="flex-1 lg:flex-none justify-center inline-flex items-center text-rose-600 hover:text-white font-semibold bg-white md:bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-xl md:rounded-lg transition-all duration-200 border border-rose-100 md:border-none shadow-sm md:shadow-none">
                                        <svg class="w-4 h-4 md:mr-0 lg:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span class="md:hidden lg:inline">Hapus</span>
                                    </button>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    @empty
                    <tr class="block md:table-row bg-white rounded-2xl md:rounded-none shadow-sm md:shadow-none border border-gray-100 md:border-none">
                        <td colspan="4" class="block md:table-cell px-6 py-16 text-center text-gray-500">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada pencatatan Sounding</h3>
                            <p class="text-sm text-gray-500">Mulai kelola bahan bakar dengan menambahkan data baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-opacity">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all">
                
                <div class="flex items-center justify-between p-5 sm:p-6 border-b border-slate-100 rounded-t-2xl bg-slate-50/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $sounding_id ? 'Edit Pencatatan BBM' : 'Tambah Pencatatan BBM' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl text-sm p-2 transition-colors border border-slate-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-5 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="col-span-1 sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kapal <span class="text-rose-500">*</span></label>
                                <select wire:model="kapal_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                                    <option value="">-- Pilih Armada Kapal --</option>
                                    @foreach($kapals as $kapal)
                                        <option value="{{ $kapal->id }}">{{ $kapal->nama_kapal }}</option>
                                    @endforeach
                                </select>
                                @error('kapal_id') <span class="text-rose-500 text-xs mt-1.5 block font-medium flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"></path></svg> {{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Titik / Lokasi <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="lokasi" placeholder="Contoh: Pom Bensin / Titik A" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                                @error('lokasi') <span class="text-rose-500 text-xs mt-1.5 block font-medium flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"></path></svg> {{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-span-1 sm:col-span-2 border-t border-slate-100 my-1"></div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">BBM Awal (Liter)</label>
                            <div class="relative">
                                <input type="number" step="0.01" wire:model.live.debounce.300ms="bbm_awal" placeholder="0" class="pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-gray-500 font-medium">L</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-emerald-700 mb-1.5">Pengisian (Liter)</label>
                            <div class="relative">
                                <input type="number" step="0.01" wire:model.live.debounce.300ms="pengisian" placeholder="0" class="pl-4 pr-10 py-2.5 bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 block w-full transition-colors">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm text-emerald-600 font-bold">+ L</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-rose-700 mb-1.5">Pemakaian (Liter)</label>
                            <div class="relative">
                                <input type="number" step="0.01" wire:model.live.debounce.300ms="pemakaian" placeholder="0" class="pl-4 pr-10 py-2.5 bg-rose-50 border border-rose-200 text-rose-900 text-sm rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 block w-full transition-colors">
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

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Berangkat (WIB)</label>
                            <input type="time" wire:model="jam_berangkat" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Kembali (WIB)</label>
                            <input type="time" wire:model="jam_kembali" class="px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-colors">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end p-5 border-t border-slate-100 rounded-b-2xl sm:space-x-3 bg-slate-50/80 gap-3 sm:gap-0">
                    <button wire:click="closeModal()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-xl text-sm px-5 py-2.5 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Batal
                    </button>
                    <button wire:click="store()" type="button" class="w-full sm:w-auto inline-flex justify-center items-center text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Pencatatan
                    </button>
                </div>
                
            </div>
        </div>
        @endif
        
    </div>
</div>