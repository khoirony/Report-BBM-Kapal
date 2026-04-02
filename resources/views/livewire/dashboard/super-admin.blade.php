<div> 
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Super Admin</h1>
        <p class="text-sm text-gray-500">Ringkasan aktivitas dan operasional Anda hari ini.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Selamat Datang, {{ auth()->user()->name ?? 'User' }}!</h2>
        <p class="text-gray-500">Ini adalah dashboard dinamis berbasis Livewire.</p>
        
        <button x-data @click="alert('Alpine Jalan Mantap!')" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
            Klik Saya (Test Alpine.js)
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold mb-4">Data Terbaru (Contoh)</h3>
        <ul class="divide-y divide-gray-200">
            @forelse($data_dummy as $data)
                <li class="py-3 text-gray-600">{{ $data->name }} - {{ $data->email }}</li>
            @empty
                <li class="py-3 text-gray-400">Belum ada data di database.</li>
            @endforelse
        </ul>
    </div>

</div>