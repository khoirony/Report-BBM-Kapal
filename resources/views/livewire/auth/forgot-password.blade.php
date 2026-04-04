<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8 py-12" 
     x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-gray-100"
         x-show="show" x-transition:enter="transition ease-out duration-700 transform"
         x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
         
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 tracking-tight">Lupa Password?</h2>
            <p class="mt-2 text-center text-sm text-gray-500">Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset password.</p>
        </div>

        @if ($status)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative text-sm" role="alert">
                <span class="block sm:inline">{{ $status }}</span>
            </div>
        @endif
        
        <form class="mt-8 space-y-6" wire:submit.prevent="sendResetLink">
            <div>
                <label class="text-sm font-medium text-gray-700">Email Address</label>
                <input wire:model.blur="email" type="email" required 
                       class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-200 outline-none">
                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                    <span wire:loading.remove wire:target="sendResetLink">Kirim Tautan Reset</span>
                    <span wire:loading wire:target="sendResetLink">Memproses...</span>
                </button>
            </div>
            
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-200">
                    Kembali ke <span class="font-bold text-indigo-600">Halaman Login</span>
                </a>
            </div>
        </form>
    </div>
</div>