@extends('layout.welcome')

@section('content')
    <div class="bg-white p-4 shadow-md rounded-lg mt-6">
        <!-- Flash Message -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-2 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="flex justify-between">
            <h3 class="text-lg font-semibold">Riwayat</h3>
           
        </div>

        <!-- Table -->
        <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Member</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Detail Barang</th>
                    <!-- <th class="p-2 border">Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $key => $item)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">{{ $item->member->nama_member}}</td>
                        <td class="p-2 border">{{ $item->tanggal_jual}}</td>
                        <td class="p-2 border">{{ $item->total }}</td>
                        <td class="p-2 border"><button onclick="openDetailModal('DetailModal',{{ $item->details }})"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Detail barang
                            </button></td>
                        <!-- <td class="p-2 border">
                            <button onclick="openEditModal({{ $item->id }}, {{ $item }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Edit
                            </button>
                            <button onclick="openDeleteModal({{ $item->id }})"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="DetailModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Detail Barang</h2>
            <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Nama Barang</th>
                        <th class="p-2 border">Jumlah Beli</th>
                        <th class="p-2 border">Sub Total</th>
                    </tr>
                </thead>
                <tbody id="detailtable">
                </tbody>
            </table>
            <button type="button" onclick="closeModal('DetailModal')"
                class=" bg-blue-500 px-3 py-1 border rounded">Tutup</button>
        </div>
    </div>
   
    <!-- JavaScript Modal -->
    <script>
        
        let barangs = @json($barangs);

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function openDetailModal(id, item) {
            let tbody = document.getElementById("detailtable");
            tbody.innerHTML = ""; // Hapus isi tbody sebelum mengisi ulang
            item.forEach((item, index) => {
                let barang = barangs.find(b => b.id == item.id_barang); // Cari barang berdasarkan id_barang
                let namaBarang = barang ? barang.nama_barang : "Tidak ditemukan"; // Pastikan tidak error jika barang tidak ada

                let row = `
                            <tr class="text-center">
                                <td class="p-2 border">${index + 1}</td>
                                <td class="p-2 border">${namaBarang}</td> 
                                <td class="p-2 border">${item.jumlah_barang}</td>
                                <td class="p-2 border">${item.sub_total}</td>
                            </tr>
                        `;
                tbody.innerHTML += row;
            });


            // Tampilkan modal
            document.getElementById(id).classList.remove("hidden");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
          
        }

        // Edit Modal


        // Delete Modal
       
       
        document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });

        @endif
        @if(session('error'))
            Swal.fire({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "Coba Lagi"
            });
        @endif
    
    });
    </script>

@endsection