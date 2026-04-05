<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Satgas Operasional</h1>
            <p class="text-sm text-slate-500 mt-1">Pemantauan penugasan, laporan kegiatan, dan armada kapal hari ini.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('satgas.lapor-pengisian') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition shadow-sm shadow-emerald-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Laporan Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Armada Kapal</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_kapal']) }}</h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V8m0 14c-4.418 0-8-3.582-8-8h2.5M12 22c4.418 0 8-3.582 8-8h-2.5M12 8a3 3 0 100-6 3 3 0 000 6zM8 12h8"></path>
                </svg>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Surat Tugas</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_surat_tugas']) }}</h3>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Laporan</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($stats['total_laporan']) }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Permohonan</p>
                <h3 class="text-2xl font-bold text-violet-600 mt-1">{{ number_format($stats['total_permohonan']) }}</h3>
            </div>
            <div class="p-3 bg-violet-50 rounded-xl">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Laporan Pengisian Terbaru</h3>
                    <a href="{{ route('satgas.lapor-pengisian') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">Lihat Detail &rarr;</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Waktu Kegiatan</th>
                                <th class="px-6 py-4 font-semibold">Kapal & SKPD</th>
                                <th class="px-6 py-4 font-semibold">Lokasi Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_laporans as $laporan)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-800">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $laporan->hari }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-indigo-700">{{ $laporan->kapal->nama_kapal ?? 'Kapal Tidak Diketahui' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $laporan->kapal->skpd_ukpd ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-700">{{ \Illuminate\Support\Str::limit($laporan->kegiatan, 30) }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $laporan->lokasi ?? 'Lokasi tidak disebutkan' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-sm">
                                        Belum ada laporan pengisian yang dibuat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Surat Permohonan Terbaru</h3>
                    <a href="{{ route('satgas.surat-permohonan') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">Lihat Detail &rarr;</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Tanggal Surat</th>
                                <th class="px-6 py-4 font-semibold">Nomor Surat</th>
                                <th class="px-6 py-4 font-semibold">Kapal Terkait</th>
                                <th class="px-6 py-4 font-semibold text-right">Klasifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recent_permohonans as $permohonan)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-800">{{ \Carbon\Carbon::parse($permohonan->tanggal_surat)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-violet-700">{{ $permohonan->nomor_surat }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">Lampiran: {{ $permohonan->lampiran ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-slate-800">
                                            {{ $permohonan->suratTugas->laporanSebelumPengisianBbm->kapal->nama_kapal ?? 'Tidak Diketahui' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ $permohonan->klasifikasi ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">
                                        Belum ada surat permohonan yang dicatat.
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
                    <h2 class="text-xl font-bold mb-2">Halo, Tim Satgas! 🛡️</h2>
                    <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                        Pastikan seluruh armada siap sedia dan kelengkapan administrasi lapangan sudah diarsipkan.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Surat Tugas Dikeluarkan</h3>
                </div>
                
                <div class="space-y-4">
                    @forelse($recent_surat_tugas as $surat)
                        <div class="flex items-start p-3 bg-slate-50 rounded-lg border border-slate-100 hover:border-blue-300 transition cursor-default">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-slate-800">{{ $surat->nomor_surat }}/PH.12.00</p>
                                <p class="text-xs text-slate-500 mt-0.5">Tgl: {{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('d M Y') }}</p>
                                <p class="text-xs font-medium text-emerald-600 mt-1">Waktu: {{ $surat->waktu_pelaksanaan }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-4">Tidak ada riwayat Surat Tugas.</p>
                    @endforelse
                </div>

                <div class="flex text-center">
                    <a href="{{ route('satgas.surat-tugas') }}" class=" w-full mt-5 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-200 transition">
                        Lihat Semua Surat Tugas
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>