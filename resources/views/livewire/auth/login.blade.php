<div class="min-h-screen flex items-center justify-center bg-slate-100 px-4 sm:px-6 lg:px-8 py-12 relative overflow-hidden" 
     x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 100)">
    
    <div class="absolute top-0 left-0 w-full h-2 bg-blue-900"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

    <div class="max-w-md w-full space-y-8 bg-white/90 backdrop-blur-sm p-8 sm:p-10 rounded-2xl shadow-2xl border border-blue-100 z-10"
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
                SI-BBM KAPAL
            </h2>
            <p class="text-sm font-semibold text-blue-700 uppercase tracking-widest mt-1">
                Dinas Perhubungan DKI Jakarta
            </p>
            <div class="mt-4 h-1 w-20 bg-yellow-400 mx-auto rounded-full"></div>
        </div>

        @if (session('status'))
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded shadow-sm mt-4 text-sm" role="alert">
                <span class="block sm:inline font-medium">{{ session('status') }}</span>
            </div>
        @endif
        
        <form class="mt-8 space-y-5" wire:submit.prevent="login">
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-blue-900 uppercase tracking-wider ml-1">Email / Username</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input wire:model.blur="email" type="email" required placeholder="admin@dishub.go.id"
                               class="block w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition duration-200 outline-none shadow-sm">
                    </div>
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label class="text-xs font-bold text-blue-900 uppercase tracking-wider ml-1">Kata Sandi</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input wire:model="password" :type="showPassword ? 'text' : 'password'" required placeholder="••••••••"
                               class="block w-full pl-10 pr-12 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition duration-200 outline-none shadow-sm">
                        
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 mt-0">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-xs text-gray-600">Ingat Saya</label>
                </div>
                <a href="/forgot-password" class="text-xs font-bold text-blue-700 hover:text-blue-500 transition duration-200">
                    Lupa Password?
                </a>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95">
                    <span wire:loading.remove wire:target="login">MASUK KE SISTEM</span>
                    <span wire:loading wire:target="login" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        OTENTIKASI...
                    </span>
                </button>
            </div>
            
            <div class="text-center mt-6 pt-6 border-t border-gray-100">
                <p class="text-xs text-gray-400 font-medium italic">
                    Unit Pengelola Angkutan Perairan &copy; {{ date('Y') }}
                </p>
            </div>
        </form>
    </div>
</div>