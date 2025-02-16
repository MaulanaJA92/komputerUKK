@extends('layout.welcome')

@section('content')
    <div class="flex w-full justify-center items-center h-10 mt-5">
        <h3 class="text-lg font-semibold mb-4">Daftar Barang</h3>
    </div>

    <div class="bg-white p-2 shadow-md rounded-lg overflow-y-auto h-1/2">

        @foreach ($kategori as $kat)
            <div class="mb-6">
                <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $kat->nama_kategori }}</h4>
                <div class="overflow-x-auto flex gap-4 pb-2">
                    @foreach ($barang->where('id_kategori', $kat->id) as $item)
                        <div class="w-60 p-4 bg-gray-100 rounded-lg shadow-md flex-none">
                            <img src="{{ asset('image/' . $item->foto) }}" alt="{{ $item->nama_barang }}"
                                class="w-full h-40 object-cover rounded-md">
                            <h5 class="mt-2 font-semibold text-gray-900">{{ $item->nama_barang }}</h5>
                            <p class="text-sm text-gray-600">{{ $item->detail_barang }}</p>
                            <p class="text-lg font-bold mt-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <p class="mt-1 text-sm {{ $item->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->stok > 0 ? 'Stok: ' . $item->stok : 'Sold Out' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection