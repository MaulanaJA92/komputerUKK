@extends('layout.welcome')
@section('content')

<div class="container mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
    
    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <h5 class="text-lg">Total Barang</h5>
                <p class="text-2xl font-bold">{{ $jumlahBarang }}</p>
            </div>
            <i class="fa-solid fa-box text-4xl opacity-60"></i>
        </div>
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <h5 class="text-lg">Total Barang Terjual</h5>
                <p class="text-2xl font-bold">{{ $totalBarangTerjual }}</p>
            </div>
            <i class="fa-solid fa-shopping-cart text-4xl opacity-60"></i>
        </div>
        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md flex justify-between items-center">
            <div>
                <h5 class="text-lg">Total Member</h5>
                <p class="text-2xl font-bold">{{ $jumlahMember }}</p>
            </div>
            <i class="fa-solid fa-users text-4xl opacity-60"></i>
        </div>
       
    </div>

    <!-- {{-- Grafik Penjualan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
        <div class="bg-white p-4 shadow-md rounded-lg">
            <h3 class="text-lg font-semibold">Grafik Penjualan</h3>
            <canvas id="myAreaChart"></canvas>
        </div>
        <div class="bg-white p-4 shadow-md rounded-lg">
            <h3 class="text-lg font-semibold">Grafik Kategori Produk</h3>
            <canvas id="myBarChart"></canvas>
        </div>
    </div> -->

   
</div>

@endsection
