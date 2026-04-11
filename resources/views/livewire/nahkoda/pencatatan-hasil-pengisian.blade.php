<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pencatatan Hasil Pengisian</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Rekam foto bukti pengisian dan data operasional (Mendukung Scan Barcode).</p>
                </div>
            </div>
            
            @if(in_array(auth()->user()?->role?->slug, ['nahkoda', 'satgas', 'superadmin']))
            <button wire:click="openModal()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Catat Pengisian Baru
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

        <div x-data="{ showFilters: false }" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="relative w-full md:w-1/2 lg:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama kapal..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm">
                </div>
        
                <div class="flex flex-row gap-3 w-full md:w-auto">
                    <button @click="showFilters = !showFilters" type="button" class="md:hidden flex-1 flex items-center justify-center px-4 py-2.5 bg-indigo-50 border border-indigo-100 text-indigo-700 text-sm font-semibold rounded-xl hover:bg-indigo-100 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-500">
                        <span x-text="showFilters ? 'Tutup Filter' : 'Filter'">Filter</span>
                    </button>
                </div>
            </div>
        
            <div :class="{'hidden md:grid': !showFilters, 'grid': showFilters}" class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-4 border-t border-slate-100 transition-all duration-200">
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Dari Tgl Pengisian</label>
                    <input type="date" wire:model.live="filter_start_date" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="relative w-full">
                    <label class="absolute -top-2 left-2 inline-block bg-white px-1 text-[10px] font-semibold text-indigo-600 z-10">Sampai Tgl Pengisian</label>
                    <input type="date" wire:model.live="filter_end_date" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg focus:ring-2 focus:ring-indigo-500 block w-full relative z-0">
                </div>
                <div class="flex items-end w-full">
                    <button wire:click="$set('search', ''); $set('filter_start_date', ''); $set('filter_end_date', '');" class="w-full min-h-[34px] flex justify-center items-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-lg transition-colors border border-rose-100">
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
                <table class="w-full text-sm text-left text-gray-600 block lg:table">
                    <thead class="hidden lg:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-5 font-bold tracking-wider w-[30%]">Surat Permohonan & Kapal</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[25%]">Volume & Bukti Evidence</th>
                            <th class="px-6 py-5 font-bold tracking-wider w-[25%]">Status Persetujuan</th>
                            <th class="px-6 py-5 font-bold tracking-wider text-right w-[20%]">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group md:divide-y md:divide-gray-50 space-y-4 lg:space-y-0">
                        @forelse($pencatatans as $item)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-indigo-500 uppercase lg:hidden mb-2 block tracking-wider">Info Surat & Kapal</span>
                                
                                <div class="font-bold text-slate-900 text-sm tracking-tight mb-1" title="Nomor Surat Permohonan">
                                    {{ $item->suratPermohonan->nomor_surat ?? '-' }}
                                </div>
                                <div class="flex items-center text-xs text-slate-500 font-medium mb-1.5">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengisian)->format('d M Y') }}
                                </div>
                                
                                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-2 mt-2 w-fit pr-4">
                                    <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block mb-0.5">Armada Kapal</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-indigo-900">{{ $item->kapal->nama_kapal ?? 'Kapal Terhapus' }}</span>
                                    </div>
                                </div>

                                <div class="mt-2 text-[10px] text-slate-400 bg-slate-50 border border-slate-100 px-2 py-1 rounded w-fit inline-flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Oleh: <span class="font-bold text-slate-600 ml-1">{{ $item->creator->name ?? '-' }}</span>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-1 block mt-2">Volume & Bukti Evidence</span>
                                
                                <div class="mb-3 mt-1">
                                    <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider block mb-0.5">Jumlah BBM Diisi</span>
                                    <span class="font-extrabold text-blue-600 text-base bg-blue-50/50 px-2.5 py-1 rounded-lg border border-blue-100 inline-block">{{ number_format($item->jumlah_pengisian, 0, ',', '.') }} L</span>
                                </div>
                                
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <a href="{{ Storage::url($item->foto_proses) }}" target="_blank" class="inline-flex items-center text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 px-2 py-1.5 rounded border border-slate-200 transition-colors shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Proses
                                    </a>
                                    <a href="{{ Storage::url($item->foto_flow_meter) }}" target="_blank" class="inline-flex items-center text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 px-2 py-1.5 rounded border border-slate-200 transition-colors shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Meter
                                    </a>
                                    <a href="{{ Storage::url($item->foto_struk) }}" target="_blank" class="inline-flex items-center text-[10px] font-semibold text-slate-600 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 px-2 py-1.5 rounded border border-slate-200 transition-colors shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Struk
                                    </a>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-top">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Status Persetujuan</span>
                                
                                <div class="flex flex-col gap-2 mt-1">
                                    <div>
                                        @if($item->disetujui_pengawas_at)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-[10px] font-bold text-emerald-700 w-fit shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pengawas: Disetujui
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-amber-50 border border-amber-200 text-[10px] font-bold text-amber-700 w-fit shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Pengawas: Menunggu
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($item->disetujui_penyedia_at)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-[10px] font-bold text-emerald-700 w-fit shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Penyedia: Disetujui
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-amber-50 border border-amber-200 text-[10px] font-bold text-amber-700 w-fit shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Penyedia: Menunggu
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-col gap-2 w-full lg:max-w-[150px] lg:ml-auto mt-2 lg:mt-0">
                                    
                                    @php $role = auth()->user()?->role?->slug; @endphp
                                    
                                    {{-- Tombol Edit (Nahkoda / Superadmin) --}}
                                    @if(in_array($role, ['nahkoda', 'superadmin']))
                                        <button wire:click="edit({{ $item->id }})" class="w-full justify-center inline-flex items-center text-indigo-600 font-semibold bg-indigo-50 hover:bg-indigo-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-200 shadow-sm text-xs">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit Data & Foto
                                        </button>
                                    @endif

                                    {{-- Pengawas / Superadmin --}}
                                    @if(in_array($role, ['pengawas', 'superadmin']) && !$item->disetujui_pengawas_at)
                                        <button wire:click="approve({{ $item->id }}, 'pengawas')" wire:confirm="Setujui dokumen ini sebagai Pengawas?" class="w-full justify-center inline-flex items-center text-indigo-700 font-semibold bg-indigo-50 hover:bg-indigo-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-200 shadow-sm text-xs">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Setujui Pengawas
                                        </button>
                                    @endif

                                    {{-- Penyedia / Superadmin --}}
                                    @if(in_array($role, ['penyedia', 'superadmin']) && !$item->disetujui_penyedia_at)
                                        <button wire:click="approve({{ $item->id }}, 'penyedia')" wire:confirm="Setujui dokumen ini sebagai Penyedia?" class="w-full justify-center inline-flex items-center text-teal-700 font-semibold bg-teal-50 hover:bg-teal-600 hover:text-white px-3 py-2 rounded-lg transition-all duration-200 border border-teal-200 shadow-sm text-xs">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Setujui Penyedia
                                        </button>
                                    @endif

                                    {{-- Status Selesai / Read Only --}}
                                    @if($item->disetujui_pengawas_at && $item->disetujui_penyedia_at)
                                        <div class="w-full justify-center inline-flex items-center text-emerald-600 font-semibold bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100 shadow-sm text-xs mt-1">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Proses Selesai
                                        </div>
                                    @elseif(!in_array($role, ['pengawas', 'penyedia', 'nahkoda', 'superadmin']))
                                        <span class="text-xs text-slate-400 font-medium italic lg:text-right block">Hanya melihat</span>
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
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Data Pencatatan</h3>
                                <p class="text-sm text-gray-500">Hasil pengisian BBM yang direkam awak kapal akan tampil di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $pencatatans->links() }}
            </div>
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.closeModal()" class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $edit_id ? 'Edit Data & Bukti Foto' : 'Form Pencatatan Pengisian' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar">
                    <form wire:submit.prevent="store" id="form-pencatatan" class="space-y-5">
                        
                        @if(request()->query('kapal') && !$edit_id)
                            <div class="bg-indigo-50 text-indigo-700 text-xs font-bold p-4 rounded-xl border border-indigo-100 flex items-center shadow-sm">
                                <svg class="w-5 h-5 mr-2.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Sistem memuat permohonan otomatis dari pindai Barcode Kapal.
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Tautkan Surat Permohonan BBM <span class="text-rose-500">*</span></label>
                                <select wire:model.live="surat_permohonan_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer shadow-sm" required {{ $edit_id ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Surat Permohonan BBM --</option>
                                    @foreach($permohonans as $pm)
                                        <option value="{{ $pm->id }}">
                                            {{ $pm->nomor_surat ?? 'No. Surat Belum Ada' }} 
                                            (Tgl: {{ \Carbon\Carbon::parse($pm->tanggal_surat)->format('d/m') }} | 
                                            Kapal: {{ $pm->suratTugas->LaporanSisaBbm->sounding->kapal->nama_kapal ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('surat_permohonan_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Kapal (Otomatis)</label>
                                    <input type="text" wire:model="nama_kapal_readonly" class="w-full px-4 py-3 bg-slate-100 border border-slate-200 text-sm text-slate-500 font-medium rounded-xl cursor-not-allowed" readonly placeholder="-">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal Isi (Otomatis)</label>
                                    <input type="date" wire:model="tanggal_pengisian" class="w-full px-4 py-3 bg-slate-100 border border-slate-200 text-sm text-slate-500 font-medium rounded-xl cursor-not-allowed" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Jumlah BBM Diisi (Liter) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" wire:model="jumlah_pengisian" placeholder="Contoh: 5000.00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all shadow-sm" required>
                                @error('jumlah_pengisian') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Upload Evidence (Bukti Foto)</h4>
                        
                        <div class="space-y-4">
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                                <label class="block text-xs font-semibold text-slate-700 mb-2">1. Foto Proses Pengisian BBM {!! !$edit_id ? '<span class="text-rose-500">*</span>' : '' !!}</label>
                                @if($edit_id && $old_foto_proses)
                                    <div class="mb-3 flex items-center bg-white p-2 border border-slate-200 rounded-lg w-fit">
                                        <img src="{{ Storage::url($old_foto_proses) }}" class="h-10 w-10 object-cover rounded shadow-sm">
                                        <span class="text-[10px] text-slate-500 ml-2 font-medium">Foto saat ini</span>
                                    </div>
                                @endif
                                <input type="file" wire:model="new_foto_proses" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                                @if($edit_id) <p class="text-[10px] text-slate-400 mt-1.5">Abaikan/biarkan kosong jika tidak ingin mengubah foto lama.</p> @endif
                                @error('new_foto_proses') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                                <label class="block text-xs font-semibold text-slate-700 mb-2">2. Foto Flow Meter / Dispenser {!! !$edit_id ? '<span class="text-rose-500">*</span>' : '' !!}</label>
                                @if($edit_id && $old_foto_flow_meter)
                                    <div class="mb-3 flex items-center bg-white p-2 border border-slate-200 rounded-lg w-fit">
                                        <img src="{{ Storage::url($old_foto_flow_meter) }}" class="h-10 w-10 object-cover rounded shadow-sm">
                                        <span class="text-[10px] text-slate-500 ml-2 font-medium">Foto saat ini</span>
                                    </div>
                                @endif
                                <input type="file" wire:model="new_foto_flow_meter" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                                @if($edit_id) <p class="text-[10px] text-slate-400 mt-1.5">Abaikan/biarkan kosong jika tidak ingin mengubah foto lama.</p> @endif
                                @error('new_foto_flow_meter') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-slate-100 transition-colors">
                                <label class="block text-xs font-semibold text-slate-700 mb-2">3. Foto Struk / Surat Kirim DO {!! !$edit_id ? '<span class="text-rose-500">*</span>' : '' !!}</label>
                                @if($edit_id && $old_foto_struk)
                                    <div class="mb-3 flex items-center bg-white p-2 border border-slate-200 rounded-lg w-fit">
                                        <img src="{{ Storage::url($old_foto_struk) }}" class="h-10 w-10 object-cover rounded shadow-sm">
                                        <span class="text-[10px] text-slate-500 ml-2 font-medium">Foto saat ini</span>
                                    </div>
                                @endif
                                <input type="file" wire:model="new_foto_struk" accept="image/*" capture="environment" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                                @if($edit_id) <p class="text-[10px] text-slate-400 mt-1.5">Abaikan/biarkan kosong jika tidak ingin mengubah foto lama.</p> @endif
                                @error('new_foto_struk') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            @if(!$edit_id)
                                <p class="text-[11px] text-slate-500 mt-2 flex items-start">
                                    <svg class="w-4 h-4 mr-1.5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Akses web ini lewat HP dan gunakan opsi kamera (capture) untuk langsung memfoto bukti di lapangan.
                                </p>
                            @endif
                        </div>

                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="closeModal()" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-pencatatan" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow active:scale-95 transition-all">Simpan Data</button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>