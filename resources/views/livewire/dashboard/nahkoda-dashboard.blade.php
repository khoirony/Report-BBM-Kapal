<div class="p-6 bg-slate-50 min-h-screen" x-data="{ tab: 'dashboard' }">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Nahkoda</h1>
        </div>
        
        <div class="flex items-center bg-white p-2 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-semibold text-slate-400 uppercase">Dari</span>
                <input type="date" wire:model.live="startDate" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer">
            </div>
            <div class="w-px h-5 bg-slate-200 mx-2"></div>
            <div class="flex items-center gap-2 px-2">
                <span class="text-xs font-semibold text-slate-400 uppercase">Sampai</span>
                <input type="date" wire:model.live="endDate" class="text-sm border-none focus:ring-0 text-slate-600 cursor-pointer">
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-700 mb-1">Tren Konsumsi BBM Harian (Liter)</h3>
            <p class="text-xs text-slate-500 mb-4">Total pemakaian harian berdasarkan hasil sounding kapal Anda</p>
            <div id="chartKonsumsiHarian" class="h-72"></div>
        </div>
    </div>

    <script>
        let chartKonsumsiHarian;

        const renderCharts = (data) => {
            // Pastikan data tersedia sebelum dirender
            if (!data || !data.konsumsiHarian) return;

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
        };

        window.addEventListener('chartsUpdated', e => renderCharts(e.detail[0] || e.detail));
        
        document.addEventListener('DOMContentLoaded', () => {
            const initialData = @json($chartParams);
            if (initialData && initialData.konsumsiHarian) {
                renderCharts(initialData);
            }
        });
    </script>
</div>