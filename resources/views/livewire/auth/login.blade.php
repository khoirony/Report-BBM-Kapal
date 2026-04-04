<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8 py-12" 
     x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 100)">
    
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-gray-100"
         x-show="show"
         x-transition:enter="transition ease-out duration-700 transform"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak>
         
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                Selamat Datang Kembali
            </h2>
            <p class="mt-2 text-center text-sm text-gray-500">
                Silakan masuk menggunakan kredensial Anda
            </p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative text-sm mt-4 text-center" role="alert">
                <span class="block sm:inline font-medium">{{ session('status') }}</span>
            </div>
        @endif
        
        <form class="mt-8 space-y-4" wire:submit.prevent="login">
            <div class="space-y-5">
                <div>
                    <label class="text-sm font-medium text-gray-700">Email Address</label>
                    <input wire:model.blur="email" type="email" required 
                           class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-200 outline-none">
                    @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input wire:model="password" :type="showPassword ? 'text' : 'password'" required 
                               class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-200 outline-none">
                        
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-500 mt-1">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <a href="/forgot-password" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition duration-200">
                    Lupa password?
                </a>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <span wire:loading.remove wire:target="login">Masuk Sekarang</span>
                    <span wire:loading wire:target="login" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memverifikasi...
                    </span>
                </button>
            </div>
            
            <div class="text-center mt-6">
                <a href="/register" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-200">
                    Belum punya akun? <span class="font-bold text-indigo-600">Daftar di sini</span>
                </a>
            </div>
        </form>
    </div>
</div>