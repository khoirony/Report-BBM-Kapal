<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SI-BBM KAPAL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen relative overflow-hidden flex items-center justify-center">
    <div class="absolute top-0 left-0 w-full h-2 bg-blue-900"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-50 rounded-full filter blur-3xl opacity-70"></div>

    <div class="max-w-md w-full px-4 z-10">
        <div class="bg-white/95 backdrop-blur-sm p-8 sm:p-10 rounded-2xl shadow-2xl border border-blue-100 space-y-8">
            
            <div class="text-center">
                <div class="flex justify-center gap-3 mb-6">
                    <img src="https://dishub.jakarta.go.id/wp-content/uploads/2026/03/cropped-logo-dishub-jakarta-300x130.webp" alt="Logo Dishub" class="h-16 w-auto object-contain">
                </div>
                
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-full mb-6 ring-8 ring-blue-50/50">
                    <svg class="w-10 h-10 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-black text-blue-900 tracking-tight uppercase">CEK EMAIL DINAS</h2>
                <div class="mt-2 h-1 w-16 bg-yellow-400 mx-auto rounded-full"></div>
                
                <p class="mt-6 text-sm text-gray-600 leading-relaxed">
                    Terima kasih telah mendaftar di <span class="font-bold text-blue-800">SI-BBM KAPAL</span>. Mohon verifikasi email Anda dengan mengklik tautan yang kami kirimkan.
                </p>
            </div>

            @if (session('message') == 'Verification link sent!')
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 px-4 py-3 rounded text-xs font-bold text-center animate-pulse" role="alert">
                    Tautan verifikasi baru telah dikirim!
                </div>
            @endif

            <div class="mt-8 flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent text-xs font-black rounded-xl text-white bg-blue-800 hover:bg-blue-900 shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 uppercase tracking-widest">
                        Kirim Ulang Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border-2 border-gray-100 text-xs font-bold rounded-xl text-gray-400 bg-white hover:text-red-600 hover:border-red-100 transition-all duration-300 uppercase tracking-tighter">
                        Keluar Akun
                    </button>
                </form>
            </div>

            <div class="pt-4 text-center">
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">
                    &copy; 2026 Unit Pengelola Angkutan Perairan
                </p>
            </div>
        </div>
    </div>
</body>
</html>