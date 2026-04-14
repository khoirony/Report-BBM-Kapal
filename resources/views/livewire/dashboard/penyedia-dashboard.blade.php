<div class="p-6 bg-slate-50 min-h-screen" x-data="{ tab: 'dashboard' }">
    
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Penjualan BBM</h1>
        </div>
        
        <div class="flex flex-wrap items-center bg-white p-2 rounded-xl shadow-sm border border-slate-200 gap-2">
            
            @if(auth()->user()?->role?->slug !== 'penyedia')
            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-semibold text-slate-400 uppercase">Penyedia</span>
                <select wire:model.live="filterPenyedia" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer bg-transparent py-0">
                    <option value="">Semua Penyedia</option>
                    @foreach($penyedias as $penyedia)
                        <option value="{{ $penyedia->id }}">{{ $penyedia->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-px h-5 bg-slate-200 hidden sm:block"></div>
            @endif

            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-semibold text-slate-400 uppercase">Dari</span>
                <input type="date" wire:model.live="startDate" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer py-0 bg-transparent">
            </div>
            <div class="w-px h-5 bg-slate-200 hidden sm:block"></div>
            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-semibold text-slate-400 uppercase">Sampai</span>
                <input type="date" wire:model.live="endDate" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer py-0 bg-transparent">
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Penjualan (Rupiah)</p>
                <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($stats['total_penjualan'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Pembelian Dishub (Liter)</p>
                <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['total_liter'] ?? 0, 2, ',', '.') }} L</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 lg:col-span-2">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-700 text-lg">Grafik Total Penjualan Anggaran</h3>
                    <p class="text-xs text-slate-500">Akumulasi total harga BBM yang dijual perusahaan berdasarkan periode</p>
                </div>
                <div id="chartTotalPenjualan" class="h-80 w-full"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-700 text-lg">Grafik Jumlah Pembelian BBM</h3>
                    <p class="text-xs text-slate-500">Total liter BBM yang dibeli oleh Dishub berdasarkan periode</p>
                </div>
                <div id="chartTotalPembelian" class="h-72 w-full"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-4">
                    <h3 class="font-bold text-slate-700 text-lg">Pembelian per Jenis BBM</h3>
                    <p class="text-xs text-slate-500">Distribusi pembelian (liter) berdasarkan jenis BBM</p>
                </div>
                <div id="chartJenisBbm" class="flex justify-center h-72 items-center w-full"></div>
            </div>
        </div>
    </div>

    <script>
        let chartTotalPenjualan, chartTotalPembelian, chartJenisBbm;
        const formatterRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

        const renderCharts = (data) => {
            
            // 1. Grafik Total Penjualan (Rupiah)
            const optPenjualan = {
                chart: { type: 'area', height: 320, toolbar: { show: false } },
                series: [{ name: 'Total Penjualan', data: data.penjualan.series }],
                xaxis: { categories: data.penjualan.labels },
                colors: ['#4f46e5'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => formatterRupiah.format(val) } }
            };
            if(chartTotalPenjualan) chartTotalPenjualan.destroy();
            chartTotalPenjualan = new ApexCharts(document.querySelector("#chartTotalPenjualan"), optPenjualan);
            chartTotalPenjualan.render();

            // 2. Grafik Jumlah Pembelian Dishub (Liter)
            const optPembelian = {
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                series: [{ name: 'Total Pembelian BBM', data: data.pembelian.series }],
                xaxis: { categories: data.pembelian.labels },
                colors: ['#10b981'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID', { minimumFractionDigits: 2 }) + ' Liter' } }
            };
            if(chartTotalPembelian) chartTotalPembelian.destroy();
            chartTotalPembelian = new ApexCharts(document.querySelector("#chartTotalPembelian"), optPembelian);
            chartTotalPembelian.render();

            // 3. Grafik per Jenis BBM (Donut)
            const optJenis = {
                chart: { type: 'donut', height: 300 },
                labels: data.jenisBbm.labels, 
                series: data.jenisBbm.data,   
                colors: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981'],
                tooltip: { y: { formatter: (val) => val.toLocaleString('id-ID', { minimumFractionDigits: 2 }) + ' Liter' } },
                legend: { position: 'bottom' }
            };
            if(chartJenisBbm) chartJenisBbm.destroy();
            chartJenisBbm = new ApexCharts(document.querySelector("#chartJenisBbm"), optJenis);
            chartJenisBbm.render();
        };

        // Trigger update saat filter Livewire berubah
        window.addEventListener('chartsUpdated', e => renderCharts(e.detail[0] || e.detail));
        
        // Initial render saat halaman dimuat pertama kali
        document.addEventListener('DOMContentLoaded', () => {
            const initialData = @json($chartParams);
            if (initialData && initialData.penjualan) renderCharts(initialData);
        });
    </script>
</div>