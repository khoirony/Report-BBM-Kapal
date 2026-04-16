<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    <div class="w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg shadow-indigo-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kelola Penyedia (Perusahaan)</h1>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Manajemen akun pengguna khusus untuk entitas Perusahaan/Penyedia BBM.</p>
                </div>
            </div>
            
            <button wire:click="openModal('create')" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 w-full sm:w-auto focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Tambah Penyedia Baru
            </button>
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

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                
                <div class="relative w-full md:w-1/2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..." class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 block w-full transition-colors shadow-sm outline-none">
                </div>
        
                <div class="relative w-full md:w-48">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                    </div>
                    <select wire:model.live="sortBy" class="pl-9 pr-8 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-all appearance-none cursor-pointer shadow-sm hover:bg-slate-100 outline-none">
                        <option value="created_at">Terbaru</option>
                        <option value="name">Nama</option>
                        <option value="email">Email</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-transparent md:bg-white md:rounded-2xl md:shadow-sm md:border md:border-gray-100 overflow-hidden w-full relative">
    
            <div wire:loading wire:target="search, sortBy, sortByField, toggleVerification, delete" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden md:flex items-center justify-center rounded-2xl">
                <div class="w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
            </div>
        
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-600 block lg:table">
                    <thead class="hidden lg:table-header-group text-xs text-gray-500 uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th wire:click="sortByField('name')" class="px-6 py-5 font-bold tracking-wider w-[50%] cursor-pointer hover:text-indigo-600 transition-colors">
                                Profil Penyedia
                                @if($sortBy === 'name') <span class="ml-1 text-indigo-500">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                            </th>
                            <th wire:click="sortByField('role_id')" class="px-6 py-5 font-bold tracking-wider w-[25%] cursor-pointer hover:text-indigo-600 transition-colors">
                                Hak Akses
                                @if($sortBy === 'role_id') <span class="ml-1 text-indigo-500">{!! $sortDirection === 'asc' ? '&#8593;' : '&#8595;' !!}</span> @endif
                            </th>
                            <th class="px-6 py-5 font-bold tracking-wider text-right w-[25%]">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="block lg:table-row-group md:divide-y md:divide-gray-50 space-y-4 lg:space-y-0">
                        @forelse($users as $user)
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none hover:bg-slate-50/50 p-4 lg:p-0 transition-colors">
                            
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-middle">
                                <span class="text-[10px] font-bold text-indigo-500 uppercase lg:hidden mb-2 block tracking-wider">Profil Penyedia</span>
                                
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-100 to-blue-50 border border-indigo-200 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        @if($user->email_verified_at)
                                            <span class="absolute -bottom-1 -right-1 block w-4 h-4 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center" title="Email Terverifikasi">
                                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        @else
                                            <span class="absolute -bottom-1 -right-1 block w-4 h-4 bg-amber-400 border-2 border-white rounded-full flex items-center justify-center" title="Belum Verifikasi">
                                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col">
                                        <div class="font-bold text-slate-900 text-sm tracking-tight">
                                            {{ $user->name }}
                                        </div>
                                        @if($user->username)
                                        <div class="text-[11px] font-semibold text-indigo-500 mt-0.5 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ '@' . $user->username }}
                                        </div>
                                        @endif
                                        <div class="text-xs text-slate-500 font-medium mt-0.5 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-3 lg:px-6 lg:py-5 border-b border-gray-50 lg:border-none align-middle">
                                <span class="text-[10px] font-bold text-slate-400 uppercase lg:hidden mb-2 block mt-2">Hak Akses</span>
                                
                                @php
                                    $theme = $user->role->color ?? 'slate';
                                @endphp

                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase border shadow-sm bg-{{ $theme }}-50 text-{{ $theme }}-700 border-{{ $theme }}-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ $user->role->name ?? 'Penyedia' }}
                                </span>
                            </td>
        
                            <td class="block lg:table-cell px-2 py-4 lg:px-6 lg:py-5 lg:text-right align-middle">
                                <div class="flex flex-wrap gap-2 w-full lg:justify-end mt-2 lg:mt-0">
                                    
                                    <button wire:click="toggleVerification({{ $user->id }})" title="{{ $user->email_verified_at ? 'Batalkan Verifikasi' : 'Verifikasi Manual' }}" class="inline-flex items-center justify-center p-2 rounded-lg transition-all duration-200 border shadow-sm {{ $user->email_verified_at ? 'text-amber-600 bg-amber-50 hover:bg-amber-100 border-amber-200' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border-emerald-200' }}">
                                        @if($user->email_verified_at)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </button>

                                    <button wire:click="openModal('edit', {{ $user->id }})" class="inline-flex items-center text-indigo-600 hover:text-white font-semibold bg-indigo-50 hover:bg-indigo-600 px-3 py-2 rounded-lg transition-all duration-200 border border-indigo-100 shadow-sm">
                                        <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="lg:hidden ml-1 text-sm">Edit</span>
                                    </button>
                                    
                                    <button wire:click="delete({{ $user->id }})" onclick="confirm('Yakin ingin menghapus User Penyedia ini?') || event.stopImmediatePropagation()" class="inline-flex items-center text-rose-600 hover:text-white font-semibold bg-rose-50 hover:bg-rose-600 px-3 py-2 rounded-lg transition-all duration-200 border border-rose-100 shadow-sm">
                                        <svg class="w-4 h-4 mr-1 lg:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span class="lg:hidden ml-1 text-sm">Hapus</span>
                                    </button>
                                </div>
                            </td>
        
                        </tr>
                        @empty
                        <tr class="block lg:table-row bg-white rounded-2xl lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none">
                            <td colspan="3" class="block lg:table-cell px-6 py-16 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Belum ada Data Penyedia</h3>
                                <p class="text-sm text-gray-500">Hasil pencarian atau data pengguna belum tersedia.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

        @if($isModalOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0 transition-all">
            <div @click.away="$wire.closeModal()" class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $modalMode === 'create' ? 'Tambah Penyedia Baru' : 'Edit Data Penyedia' }}
                        </h3>
                    </div>
                    <button wire:click="closeModal()" class="text-slate-400 hover:bg-slate-100 hover:text-slate-600 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-6 custom-scrollbar">
                    <form wire:submit.prevent="save" id="form-user" class="space-y-5">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Nama Perusahaan / Penyedia <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                                @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Email Akun <span class="text-rose-500">*</span></label>
                                <input type="email" wire:model="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all" required>
                                @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div x-data="{ showPassword: false }">
                                <label class="block text-sm font-semibold text-slate-800 mb-2">Password {!! $modalMode === 'create' ? '<span class="text-rose-500">*</span>' : '' !!}</label>
                                
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" wire:model="password" placeholder="{{ $modalMode === 'edit' ? 'Abaikan jika tidak diubah' : 'Minimal 8 Karakter' }}" class="w-full px-4 py-3 pr-12 bg-slate-50 border border-slate-200 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-200 outline-none transition-all" {{ $modalMode === 'create' ? 'required' : '' }}>
                                    
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors" title="Tampilkan/Sembunyikan Password">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 pt-2">Hak Akses & Status</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Role Sistem <span class="text-rose-500">*</span></label>
                                
                                {{-- Display Teks Statis untuk Role Penyedia --}}
                                <div class="w-full px-4 py-3 bg-slate-100 border border-slate-200 text-sm text-slate-600 font-semibold rounded-xl cursor-not-allowed">
                                    Penyedia (Perusahaan)
                                </div>
                                <input type="hidden" wire:model="role_id">
                                @error('role_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Status Verifikasi Email</label>
                                <select wire:model="is_verified" class="w-full px-4 py-3 bg-white border border-slate-200 text-sm rounded-xl focus:ring-2 focus:ring-indigo-200 outline-none transition-all cursor-pointer shadow-sm">
                                    <option value="1">Sudah Diverifikasi</option>
                                    <option value="0">Belum Diverifikasi</option>
                                </select>
                                <p class="text-[10.5px] text-slate-500 mt-2 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> 
                                    Akses sistem aktif.
                                </p>
                                @error('is_verified') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </form>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl shrink-0">
                    <button wire:click="closeModal()" type="button" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold rounded-xl transition-colors shadow-sm">Batal</button>
                    <button type="submit" form="form-user" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow active:scale-95 transition-all">
                        {{ $modalMode === 'create' ? 'Simpan Data Penyedia' : 'Perbarui Data Penyedia' }}
                    </button>
                </div>

            </div>
        </div>
        @endif
        
    </div>
</div>