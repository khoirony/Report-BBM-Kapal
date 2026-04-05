<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Utama</h1>
            <p class="text-sm text-slate-500 mt-1">Ringkasan operasional dan pemakaian bahan bakar kapal.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('sounding.sounding-bbm') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm shadow-indigo-200">
                Kelola Data Sounding &rarr;
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Catatan</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_transaksi']) }}</h3>
            <p class="text-xs text-slate-400 mt-2">Seluruh riwayat sounding</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Kapal</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($stats['kapal_aktif']) }}</h3>
            <p class="text-xs text-slate-400 mt-2">Kapal dengan riwayat BBM</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium text-slate-500 mb-1">Total Pengisian BBM</p>
                <h3 class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_pengisian'], 0, ',', '.') }} <span class="text-sm font-normal text-slate-400">Liter</span></h3>
            </div>
            <div class="absolute -bottom-4 -right-4 opacity-10">
                <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 9v11a2 2 0 002 2h14a2 2 0 002-2V9l-9-7z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-sm font-medium text-slate-500 mb-1">Total Pemakaian BBM</p>
                <h3 class="text-2xl font-bold text-orange-500">{{ number_format($stats['total_pemakaian'], 0, ',', '.') }} <span class="text-sm font-normal text-slate-400">Liter</span></h3>
            </div>
             <div class="absolute -bottom-4 -right-4 opacity-10">
                <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Sounding Terbaru</h3>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">Lihat Semua &rarr;</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Kapal & Lokasi</th>
                                <th class="px-6 py-4 font-semibold text-right">Pengisian</th>
                                <th class="px-6 py-4 font-semibold text-right">Pemakaian</th>
                                <th class="px-6 py-4 font-semibold text-right">Sisa Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_soundings as $data)
                                <tr class="hover:bg-slate-50 transition-colors text-sm">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800">{{ $data->kapal->nama_kapal ?? 'Kapal Tidak Diketahui' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $data->lokasi }} &bull; {{ $data->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-blue-600 font-medium">+{{ number_format($data->pengisian, 0, ',', '.') }} L</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-orange-500 font-medium">-{{ number_format($data->pemakaian, 0, ',', '.') }} L</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800">
                                        {{ number_format($data->bbm_akhir, 0, ',', '.') }} L
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            Belum ada data pencatatan sounding.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-md p-6 text-white">
                <h2 class="text-xl font-bold mb-2">Sistem Sounding Aktif 🚢</h2>
                <p class="text-indigo-100 text-sm mb-6 leading-relaxed">
                    Pantau pengisian dan pemakaian bahan bakar kapal secara *real-time* untuk memastikan efisiensi operasional.
                </p>
                
                <a href="{{ route('sounding.sounding-bbm') }}" class="w-full bg-white text-indigo-700 hover:bg-slate-50 px-4 py-2.5 rounded-lg font-bold transition duration-200 shadow-sm flex items-center justify-center text-sm">
                    Lakukan Sounding Sekarang
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Informasi Tambahan</h3>
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Status Database</p>
                    <div class="flex items-center">
                        <span class="w-2.5 h-2.5 bg-green-500 rounded-full mr-2"></span>
                        <span class="text-sm font-medium text-slate-700">Terhubung & Stabil</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>