<div class="p-6 bg-slate-50 min-h-screen" x-data="{ tab: 'dashboard' }">
    
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-4">
    
        <div class="w-full lg:w-auto overflow-hidden">
            <h1 class="text-2xl font-bold text-slate-800">Dashboard PPTK</h1>
            
            <div class="flex gap-4 mt-4 border-b border-slate-200 overflow-x-auto" style="scrollbar-width: none;">
                <button @click="tab = 'dashboard'" :class="tab === 'dashboard' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500'" class="pb-2 text-sm font-medium transition whitespace-nowrap">Dashboard Utama</button>
                <button @click="tab = 'laporan'" :class="tab === 'laporan' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500'" class="pb-2 text-sm font-medium transition whitespace-nowrap">Laporan Transaksi</button>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center bg-white p-3 sm:p-2 rounded-xl shadow-sm border border-slate-200 gap-3 sm:gap-0 w-full lg:w-auto mt-2 lg:mt-0">
            
            <div class="flex items-center justify-between sm:justify-start gap-2 px-2 w-full sm:w-auto">
                <span class="text-xs font-semibold text-slate-400 uppercase">Dari</span>
                <input type="date" wire:model.live="startDate" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer bg-transparent w-auto text-right sm:text-left">
            </div>
            
            <div class="hidden sm:block w-px h-5 bg-slate-200 mx-2"></div>
            <div class="block sm:hidden w-full h-px bg-slate-100"></div>
            
            <div class="flex items-center justify-between sm:justify-start gap-2 px-2 w-full sm:w-auto">
                <span class="text-xs font-semibold text-slate-400 uppercase">Sampai</span>
                <input type="date" wire:model.live="endDate" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer bg-transparent w-auto text-right sm:text-left">
            </div>
            
        </div>
    </div>

    <div x-show="tab === 'dashboard'" class="space-y-6" x-transition>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Pagu Anggaran</p>
                <p class="text-xl font-bold text-slate-800">Rp {{ number_format($stats['pagu'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Realisasi (Invoice)</p>
                <p class="text-xl font-bold text-red-600">Rp {{ number_format($stats['realisasi'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Konsumsi Pengisian</p>
                <p class="text-xl font-bold text-indigo-600">{{ number_format($stats['liter']) }} L</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Armada Terdaftar</p>
                <p class="text-xl font-bold text-slate-800">{{ number_format($stats['armada']) }} Unit</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-700 mb-1">Tren Konsumsi BBM Harian (Liter)</h3>
            <p class="text-xs text-slate-500 mb-4">Total pemakaian harian berdasarkan hasil sounding</p>
            <div id="chartKonsumsiHarian" class="h-72"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-700 mb-1">Penggunaan Anggaran Per UKPD</h3>
                <p class="text-xs text-slate-500 mb-4">Pagu vs Realisasi Invoice (Rupiah)</p>
                <div id="chartAnggaranUkpd"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-700 mb-1">Penggunaan Anggaran Per Kapal</h3>
                <p class="text-xs text-slate-500 mb-4">Total rupiah yang dihabiskan berdasarkan masing-masing Kapal</p>
                <div id="chartAnggaranKapal"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-700 mb-1">Konsumsi BBM Per Kapal (Sounding)</h3>
                <p class="text-xs text-slate-500 mb-4">Pemakaian liter per UKPD (Stacked)</p>
                <div id="chartKonsumsiKapal"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-700 mb-1">Pembelian BBM Per Kapal (Pengisian)</h3>
                <p class="text-xs text-slate-500 mb-4">Volume liter yang diisi per UKPD (Stacked)</p>
                <div id="chartPembelian"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
                <h3 class="font-bold text-slate-700 mb-1">Pembelian per Jenis BBM</h3>
                <p class="text-xs text-slate-500 mb-4">Distribusi BBM berdasarkan jenisnya (Liter)</p>
                <div id="chartJenisBbm" class="flex justify-center"></div>
            </div>
        </div>
    </div>

    <div x-show="tab === 'laporan'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" style="display: none;" x-transition>
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Laporan Pelaksanaan Kegiatan Penyediaan BBM KDO</h3>
                <p class="text-xs text-slate-500 mt-1">Periode Transaksi: <span class="font-semibold">{{ Carbon\Carbon::parse($startDate)->format('d M Y') }}</span> s/d <span class="font-semibold">{{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</span></p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 border-b border-slate-100">UKPD</th>
                        <th class="px-6 py-4 border-b border-slate-100">Nama Kapal</th>
                        <th class="px-6 py-4 border-b border-slate-100">Tgl Transaksi</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-right">Jml Liter</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-right">Harga Satuan</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reportData as $row)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $row->ukpd }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $row->nama_kapal }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ date('d/m/Y', strtotime($row->tanggal_pengisian)) }}</td>
                            <td class="px-6 py-4 text-right text-slate-800 font-medium">{{ number_format($row->liter) }} L</td>
                            <td class="px-6 py-4 text-right text-slate-600">Rp {{ number_format((float)$row->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-indigo-600 font-bold">Rp {{ number_format((float)$row->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let chartKonsumsiHarian, chartAnggaranUkpd, chartAnggaranKapal, chartKonsumsiKapal, chartPembelian, chartJenisBbm;
        const formatterRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

        const renderCharts = (data) => {
            
            const optKonsumsiHarian = {
                chart: { type: 'area', height: 300, toolbar: { show: false } },
                series: data.konsumsiHarian.series,
                xaxis: { categories: data.konsumsiHarian.labels },
                colors: ['#4f46e5'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1 } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID') + ' Liter' } }
            };
            if(chartKonsumsiHarian) chartKonsumsiHarian.destroy();
            chartKonsumsiHarian = new ApexCharts(document.querySelector("#chartKonsumsiHarian"), optKonsumsiHarian);
            chartKonsumsiHarian.render();

            const optAnggaranUkpd = {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                series: data.anggaranUkpd.series,
                xaxis: { categories: data.anggaranUkpd.labels },
                colors: ['#cbd5e1', '#4f46e5'],
                plotOptions: { bar: { borderRadius: 4, dataLabels: { position: 'top' } } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => formatterRupiah.format(val) } },
                legend: { position: 'bottom' }
            };
            if(chartAnggaranUkpd) chartAnggaranUkpd.destroy();
            chartAnggaranUkpd = new ApexCharts(document.querySelector("#chartAnggaranUkpd"), optAnggaranUkpd);
            chartAnggaranUkpd.render();

            const optAnggaranKapal = {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                series: data.anggaranKapal.series,
                xaxis: { categories: data.anggaranKapal.labels },
                colors: ['#e11d48'],
                tooltip: { y: { formatter: (val) => formatterRupiah.format(val) } },
                legend: { position: 'bottom' },
                plotOptions: { bar: { borderRadius: 4 } }
            };
            if(chartAnggaranKapal) chartAnggaranKapal.destroy();
            chartAnggaranKapal = new ApexCharts(document.querySelector("#chartAnggaranKapal"), optAnggaranKapal);
            chartAnggaranKapal.render();

            const optKonsumsiKapal = {
                chart: { type: 'bar', height: 320, stacked: true, toolbar: { show: false } },
                series: data.konsumsiKapal.series,
                xaxis: { categories: data.konsumsiKapal.labels },
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID') + ' Liter' } },
                legend: { position: 'bottom' },
                plotOptions: { bar: { borderRadius: 2 } }
            };
            if(chartKonsumsiKapal) chartKonsumsiKapal.destroy();
            chartKonsumsiKapal = new ApexCharts(document.querySelector("#chartKonsumsiKapal"), optKonsumsiKapal);
            chartKonsumsiKapal.render();

            const optPembelian = {
                chart: { type: 'bar', height: 320, stacked: true, toolbar: { show: false } },
                series: data.pembelian.series,
                xaxis: { categories: data.pembelian.labels },
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID') + ' Liter' } },
                legend: { position: 'bottom' },
                plotOptions: { bar: { borderRadius: 2 } }
            };
            if(chartPembelian) chartPembelian.destroy();
            chartPembelian = new ApexCharts(document.querySelector("#chartPembelian"), optPembelian);
            chartPembelian.render();

            const optJenis = {
                chart: { type: 'donut', height: 320 },
                labels: data.jenisBbm.labels,
                series: data.jenisBbm.data,
                colors: ['#f59e0b', '#ef4444', '#3b82f6', '#10b981'],
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID') + ' Liter' } },
                legend: { position: 'right' }
            };
            if(chartJenisBbm) chartJenisBbm.destroy();
            chartJenisBbm = new ApexCharts(document.querySelector("#chartJenisBbm"), optJenis);
            chartJenisBbm.render();
        };

        window.addEventListener('chartsUpdated', e => renderCharts(e.detail[0] || e.detail));
        document.addEventListener('DOMContentLoaded', () => {
            const initialData = @json($chartParams);
            if (initialData && initialData.anggaranUkpd) renderCharts(initialData);
        });
    </script>
</div>