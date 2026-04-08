<div class="p-4 sm:p-6 lg:px-8 lg:py-6 bg-slate-50 min-h-screen">
    
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Dishub BBM</h1>
            <p class="text-sm text-slate-500 mt-1">Pemantauan Anggaran, Konsumsi, dan Rekonsiliasi BBM Kapal per UKPD.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                <select wire:model.live="filterBulan" class="border-none text-sm focus:ring-0 py-2.5 pl-3 pr-8 bg-transparent cursor-pointer text-slate-700">
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                    @endforeach
                </select>
                <div class="w-px h-5 bg-slate-200"></div>
                <select wire:model.live="filterTahun" class="border-none text-sm focus:ring-0 py-2.5 pl-3 pr-8 bg-transparent cursor-pointer text-slate-700">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>

            <a href="#" class="bg-indigo-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm shadow-indigo-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan KDO Triwulanan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pagu Anggaran Tahun Ini</p>
            <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['pagu_anggaran'], 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Anggaran Terpakai (Rekon)</p>
            <h3 class="text-2xl font-bold text-red-600">Rp {{ number_format($stats['anggaran_terpakai'], 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Armada Kapal</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_kapal']) }} Unit</h3>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Entri Data Bulan Ini</p>
            <h3 class="text-2xl font-bold text-indigo-600">{{ $stats['total_sounding'] + $stats['total_laporan'] }} Dokumen</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Penggunaan Anggaran (Rupiah)</h3>
                <p class="text-xs text-slate-500">Perbandingan Pagu vs Realisasi per UKPD</p>
            </div>
            <div id="chartAnggaran" class="w-full mt-auto"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Biaya BBM Per Kapal</h3>
                <p class="text-xs text-slate-500">Total Rupiah yang dihabiskan per unit kapal (Top 5)</p>
            </div>
            <div id="chartBiayaKapal" class="w-full mt-auto"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 lg:col-span-2">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Tren Konsumsi BBM Harian - Hasil Sounding (Liter)</h3>
                <p class="text-xs text-slate-500">Total pemakaian vs Kapal Utama</p>
            </div>
            <div id="chartKonsumsi" class="w-full h-80"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Pembelian BBM per Kapal (Liter)</h3>
                <p class="text-xs text-slate-500">Data diambil dari besar Rekonsiliasi per UKPD</p>
            </div>
            <div id="chartPembelian" class="w-full mt-auto"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-800">Komposisi Jenis BBM (Liter)</h3>
                <p class="text-xs text-slate-500">Total pembelian berdasarkan jenis BBM tahun ini</p>
            </div>
            <div id="chartJenisBbm" class="w-full mt-auto flex justify-center py-4"></div>
        </div>

    </div>

    {{-- ============================================================================== --}}
    {{-- JAVASCRIPT UNTUK RENDER APEXCHARTS --}}
    {{-- ============================================================================== --}}
    <script>
        // Variabel global untuk menyimpan instance chart agar bisa di-destroy/update
        let chartAnggaran, chartBiayaKapal, chartKonsumsi, chartPembelian, chartJenisBbm;

        // Fungsi utility untuk format Rupiah di dalam grafik
        const formatterRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        const formatSumbuYAnggaran = (value) => {
            if (value >= 1000000000) return (value / 1000000000).toFixed(1) + ' M';
            if (value >= 1000000) return (value / 1000000).toFixed(0) + ' Jt';
            return value;
        };

        // Fungsi Utama untuk Inisialisasi dan Render Grafik
        const renderCharts = (data) => {
            // -- Konfigurasi Warna Umum --
            const colors = ['#4f46e5', '#ef4444', '#10b981', '#f59e0b']; 

            // 1. Grafik Anggaran (Grouped Bar)
            const optAnggaran = {
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                colors: [colors[0], colors[1]],
                series: data.anggaran.series,
                xaxis: { categories: data.anggaran.labels },
                yaxis: { labels: { formatter: formatSumbuYAnggaran } },
                plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 4 } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => formatterRupiah.format(val) } },
                legend: { position: 'top' }
            };
            if(chartAnggaran) chartAnggaran.destroy();
            chartAnggaran = new ApexCharts(document.querySelector("#chartAnggaran"), optAnggaran);
            chartAnggaran.render();

            // 2. Grafik Biaya Kapal (Horizontal Bar)
            const optBiayaKapal = {
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                colors: [colors[2]],
                series: [{ name: 'Total Biaya', data: data.biayaKapal.data }],
                xaxis: { categories: data.biayaKapal.labels, labels: { formatter: formatSumbuYAnggaran } },
                plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                dataLabels: { enabled: true, formatter: formatSumbuYAnggaran, style: { colors: ['#fff'] } },
                tooltip: { y: { formatter: (val) => formatterRupiah.format(val) } }
            };
            if(chartBiayaKapal) chartBiayaKapal.destroy();
            chartBiayaKapal = new ApexCharts(document.querySelector("#chartBiayaKapal"), optBiayaKapal);
            chartBiayaKapal.render();

            // 3. Grafik Konsumsi (Area Chart Harian)
            const optKonsumsi = {
                chart: { type: 'area', height: 320, toolbar: { show: true }, zoom: { enabled: false } },
                colors: [colors[0], colors[3]],
                series: data.konsumsi.series,
                xaxis: { categories: data.konsumsi.labels },
                yaxis: { title: { text: 'Liter' } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1 } },
                legend: { position: 'top' }
            };
            if(chartKonsumsi) chartKonsumsi.destroy();
            chartKonsumsi = new ApexCharts(document.querySelector("#chartKonsumsi"), optKonsumsi);
            chartKonsumsi.render();

            // 4. Grafik Pembelian (X-Axis bertingkat UKPD -> Kapal)
            const optPembelian = {
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                series: [
                    { name: data.pembelian.series[0].name, data: data.pembelian.series[0].data },
                    { name: data.pembelian.series[1].name, data: data.pembelian.series[1].data }
                ],
                // Gabungkan label kapal untuk sumbu X sederhana (ApexCharts standar susah bikin sumbu bertingkat tanpa plugin)
                xaxis: { categories: [...data.pembelian.kapal_labels[0], ...data.pembelian.kapal_labels[1]] },
                yaxis: { title: { text: 'Liter' } },
                plotOptions: { bar: { columnWidth: '60%' } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID') + ' Liter' } },
                legend: { position: 'top' }
            };
            if(chartPembelian) chartPembelian.destroy();
            chartPembelian = new ApexCharts(document.querySelector("#chartPembelian"), optPembelian);
            chartPembelian.render();

            // 5. Grafik Jenis BBM (Donut)
            const optJenisBbm = {
                chart: { type: 'donut', height: 280 },
                colors: ['#0369a1', '#be123c'], // Custom biru & merah tua
                labels: data.jenisBbm.labels,
                series: data.jenisBbm.data,
                legend: { position: 'bottom' },
                dataLabels: { enabled: true, formatter: (val) => val.toFixed(1) + "%" },
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID') + ' Liter' } },
                plotOptions: { pie: { donut: { labels: { show: true, total: { show: true, label: 'Total', formatter: (w) => w.globals.seriesTotals.reduce((a,b)=>a+b, 0).toLocaleString('id-ID') + ' L' } } } } }
            };
            if(chartJenisBbm) chartJenisBbm.destroy();
            chartJenisBbm = new ApexCharts(document.querySelector("#chartJenisBbm"), optJenisBbm);
            chartJenisBbm.render();
        };

        // --- Integrasi Livewire ---

        // 1. Render pertama kali saat DOM siap menggunakan data awal dari Livewire
        document.addEventListener('DOMContentLoaded', () => {
            // Ambil data initial yang dilempar dari mount() Livewire via properti chartParams
            const initialData = @json($chartParams);
            if (initialData && initialData.anggaran) {
                renderCharts(initialData);
            }
        });

        // 2. Dengarkan event 'chartsUpdated' dari Livewire saat filter diubah
        window.addEventListener('chartsUpdated', event => {
            // Render ulang grafik dengan data baru
            renderCharts(event.detail);
        });
    </script>
</div>