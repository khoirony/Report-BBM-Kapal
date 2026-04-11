<div class="min-h-screen flex items-center justify-center bg-slate-100 px-4 sm:px-6 lg:px-8 py-12 relative overflow-hidden" 
     x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 100)">
    
    <div class="absolute top-0 left-0 w-full h-2 bg-blue-900"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

    <div class="max-w-md w-full space-y-8 bg-white/95 backdrop-blur-sm p-8 sm:p-10 rounded-2xl shadow-2xl border border-blue-100 z-10"
         x-show="show"
         x-transition:enter="transition ease-out duration-700 transform"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak>
         
        <div class="text-center">
            <div class="flex justify-center gap-4 mb-6">
                <img src="https://dishub.jakarta.go.id/wp-content/uploads/2026/03/cropped-logo-dishub-jakarta-300x130.webp" alt="Logo Dishub" class="h-16 w-auto object-contain">
            </div>
            
            <h2 class="text-2xl font-black text-blue-900 tracking-tight uppercase">
                RESET KATA SANDI
            </h2>
            <p class="text-xs font-semibold text-blue-700 uppercase tracking-widest mt-1">
                SI-BBM Kapal Dishub Jakarta
            </p>
            <div class="mt-4 h-1 w-20 bg-yellow-400 mx-auto rounded-full"></div>
            
            <p class="mt-6 text-sm text-gray-500 leading-relaxed">
                Masukkan email yang terdaftar. Sistem akan mengirimkan instruksi pemulihan ke alamat tersebut.
            </p>
        </div>

        @if ($status)
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded shadow-sm mt-4 text-sm" role="alert">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $status }}</span>
                </div>
            </div>
        @endif
        
        <form class="mt-8 space-y-6" wire:submit.prevent="sendResetLink">
            <div>
                <label class="text-[11px] font-bold text-blue-900 uppercase tracking-wider ml-1">Email Terdaftar</label>
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input wire:model.blur="email" type="email" required placeholder="admin@jakarta.go.id"
                           class="block w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition duration-200 outline-none shadow-sm">
                </div>
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95">
                    <span wire:loading.remove wire:target="sendResetLink uppercase">KIRIM TAUTAN PEMULIHAN</span>
                    <span wire:loading wire:target="sendResetLink" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        MEMPROSES...
                    </span>
                </button>
            </div>
            
            <div class="text-center mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('login') }}" class="text-xs font-bold text-gray-400 hover:text-blue-700 transition duration-200 uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>