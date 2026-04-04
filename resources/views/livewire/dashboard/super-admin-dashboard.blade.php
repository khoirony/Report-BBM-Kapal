<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Super Admin</h1>
            <p class="text-sm text-slate-500 mt-1">Pemantauan operasional menyeluruh: Armada, Laporan, Sounding BBM, dan Administrasi.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm shadow-blue-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Data
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Armada Kapal</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_kapal']) }}</h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V8m0 14c-4.418 0-8-3.582-8-8h2.5M12 22c4.418 0 8-3.582 8-8h-2.5M12 8a3 3 0 100-6 3 3 0 000 6zM8 12h8"></path></svg>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Sounding</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_sounding']) }}</h3>
            </div>
            <div class="p-3 bg-violet-50 rounded-xl">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14h12V8h2a2 2 0 012 2v3a2 2 0 01-2 2h-2M10 10h4m-4 3h4"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Laporan Pengisian</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_laporan']) }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Surat Permohonan</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($stats['surat_permohonan']) }}</h3>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Pencatatan Sounding BBM Terbaru</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Waktu & Lokasi</th>
                                <th class="px-6 py-4 font-semibold">Kapal</th>
                                <th class="px-6 py-4 font-semibold text-right">Awal + Isi</th>
                                <th class="px-6 py-4 font-semibold text-right">Sisa Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_soundings as $sounding)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-800">{{ $sounding->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            {{ \Carbon\Carbon::parse($sounding->jam_berangkat)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($sounding->jam_kembali)->format('H:i') }} | {{ $sounding->lokasi }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-violet-700">{{ $sounding->kapal->nama_kapal ?? 'Kapal Hapus/Null' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-sm text-slate-600">{{ number_format($sounding->bbm_awal, 0, ',', '.') }}L</div>
                                        <div class="text-xs font-medium text-emerald-600 mt-0.5">+{{ number_format($sounding->pengisian, 0, ',', '.') }}L</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-sm font-bold text-slate-800">{{ number_format($sounding->bbm_akhir, 0, ',', '.') }}L</div>
                                        <div class="text-xs font-medium text-orange-500 mt-0.5">Pakai: {{ number_format($sounding->pemakaian, 0, ',', '.') }}L</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">
                                        Belum ada data sounding yang dicatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Laporan Pengisian Terbaru</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Kapal / SKPD</th>
                                <th class="px-6 py-4 font-semibold">Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_laporans as $laporan)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-800">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-indigo-700">{{ $laporan->kapal->nama_kapal ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $laporan->kapal->skpd_ukpd ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-700">{{ \Illuminate\Support\Str::limit($laporan->kegiatan, 40) }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-sm">
                                        Belum ada laporan pengisian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-md p-6 text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10">
                    <svg class="w-32 h-32 transform translate-x-4 -translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                </div>
                
                <div class="relative z-10">
                    <h2 class="text-xl font-bold mb-2">Pusat Kendali 🎛️</h2>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Anda masuk sebagai Super Admin. Pastikan persetujuan administrasi dan ketersediaan BBM armada selalu ter-update.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Surat Permohonan</h3>
                </div>
                
                <div class="space-y-4">
                    @forelse($recent_permohonan as $surat)
                        <div class="flex items-start p-3 bg-slate-50 rounded-lg border border-slate-100 hover:border-amber-300 transition cursor-default">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <div class="ml-3 w-full">
                                <p class="text-sm font-bold text-slate-800 break-words">{{ $surat->nomor_surat ?? 'Draft/Belum ada nomor' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Tgl: {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d M Y') }}</p>
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-[10px] uppercase font-bold bg-slate-200 text-slate-600 px-2 py-1 rounded">
                                        {{ $surat->klasifikasi ?? 'Umum' }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $surat->lampiran }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-4">Tidak ada Surat Permohonan terbaru.</p>
                    @endforelse
                </div>

                <div class="flex text-center">
                    <a href="#" class="w-full mt-5 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                        Kelola Semua Surat
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>