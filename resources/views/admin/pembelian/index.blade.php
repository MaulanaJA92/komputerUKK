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
            <h3 class="text-lg font-semibold">Pembelian</h3>
            <a href="{{ route('exportPdf.pembelian') }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                Export Penjualan
            </a>

            @if ( auth()->user()->role == 'admin' )  
            <button onclick="openModal('addModal')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                Tambah Pembelian
            </button>@endif
        </div>

        <!-- Table -->
        <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Supplier</th>
                    <th class="p-2 border">Tanggal Beli</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Detail Barang</th>
                   @if ( auth()->user()->role == 'admin' ) <th class="p-2 border">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelian as $key => $item)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">{{ $item->supplier->nama_supplier}}</td>
                        <td class="p-2 border">{{ $item->tanggal_beli }}</td>
                        <td class="p-2 border">{{ $item->total }}</td>
                        <td class="p-2 border"><button onclick="openDetailModal('DetailModal',{{ $item->details }})"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Detail barang
                            </button></td>
                            @if ( auth()->user()->role == 'admin' ) <td class="p-2 border">
                            <button onclick="openEditModal({{ $item->id }}, {{ $item }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Edit
                            </button>
                            <button onclick="openDeleteModal({{ $item->id }})"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </td>@endif
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
    <!-- Modal Tambah Kategori -->
    <div id="addModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-[500px]">
            <h2 class="text-lg font-semibold mb-4">Tambah Pembelian</h2>
            <form action="{{ route('pembelian.store') }}" method="POST">
                @csrf

                <!-- Dropdown Supplier -->
                <div class="mb-3">
                    <label for="supplier" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
                    <select id="supplier" name="id_supplier" class="w-full p-2 border rounded">
                        <option value="" disabled selected>Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pembelian -->
                <div class="mb-3">
                    <label for="tanggal_beli" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                    <input type="date" id="tanggal_beli" name="tanggal_beli" class="w-full p-2 border rounded">
                </div>

                <!-- Total (Disabled) -->
                <div class="mb-3">
                    <label for="total" class="block text-sm font-medium text-gray-700">Total</label>
                    <input type="text" id="total" name="total" class="w-full p-2 border rounded bg-gray-100" disabled>
                </div>

                <!-- Barang yang bisa ditambah -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Barang</label>
                    <div id="barang-container" class="max-h-[200px] overflow-y-auto border p-2 rounded">
                        <div class="barang-item flex gap-2 mb-2">
                            <select name="barang_id[]" class="w-1/2 p-2 border rounded">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}">
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="jumlah[]" placeholder="Jumlah" class="w-1/4 p-2 border rounded"
                                oninput="hitungSubtotal(this)">
                            <input type="text" name="subtotal[]" placeholder="Subtotal"
                                class="w-1/4 p-2 border rounded bg-gray-100" readonly>
                            <button type="button" onclick="hapusBarang(this)"
                                class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                        </div>
                    </div>
                    <button type="button" onclick="tambahBarang()"
                        class="bg-blue-500 text-white px-3 py-1 rounded mt-2">Tambah Barang</button>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit"
                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal Edit Pembelian -->
    <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-[500px]">
            <h2 class="text-lg font-semibold mb-4">Edit Pembelian</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <!-- Dropdown Supplier -->
                <div class="mb-3">
                    <label for="editSupplier" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
                    <select id="editSupplier" name="id_supplier" class="w-full p-2 border rounded">
                        <option value="" disabled>Pilih Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Pembelian -->
                <div class="mb-3">
                    <label for="editTanggalBeli" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                    <input type="date" id="editTanggalBeli" name="tanggal_beli" class="w-full p-2 border rounded">
                </div>

                <!-- Total (Disabled) -->
                <div class="mb-3">
                    <label for="editTotal" class="block text-sm font-medium text-gray-700">Total</label>
                    <input type="text" id="editTotal" name="total" class="w-full p-2 border rounded bg-gray-100" disabled>
                </div>

                <!-- Barang yang bisa ditambah -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Barang</label>
                    <div id="editBarangContainer" class="max-h-[200px] overflow-y-auto border p-2 rounded"></div>
                    <button type="button" onclick="tambahBarangEdit()"
                        class="bg-blue-500 text-white px-3 py-1 rounded mt-2">Tambah Barang</button>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit"
                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Update</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Hapus Kategori -->
    <div id="deleteModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Hapus pembelian</h2>
            <p>Apakah Anda yakin ingin menghapus pembelian ini?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal('deleteModal')" class="px-3 py-1 border rounded">Batal</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Modal -->
    <script>
         
        let barangs = @json($barangs);
        let suppliers = @json($suppliers);

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
            if (id === 'addModal') {
                document.querySelector('#addModal form').reset();
                document.getElementById('barang-container').innerHTML = ''; // Hapus semua barang yang ditambahkan
            } else if (id === 'editModal') {
                document.querySelector('#editModal form').reset();
                document.getElementById('editBarangContainer').innerHTML = ''; // Hapus semua barang yang ditambahkan
            }
        }

        // Edit Modal


        // Delete Modal
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/pembelian/' + id;
            openModal('deleteModal');
        }
        function tambahBarang() {
            let container = document.getElementById('barang-container');
            let newRow = document.createElement('div');
            newRow.classList.add('barang-item', 'flex', 'gap-2', 'mb-2');
            newRow.innerHTML = `
                            <select name="barang_id[]" class="w-1/2 p-2 border rounded" ">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="jumlah[]" placeholder="Jumlah" class="w-1/4 p-2 border rounded" oninput="hitungSubtotal(this)">
                            <input type="text" name="subtotal[]" placeholder="Subtotal" class="w-1/4 p-2 border rounded bg-gray-100" readonly>
                            <button type="button" onclick="hapusBarang(this)" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                        `;
            container.appendChild(newRow);
        }

        function hapusBarang(button) {
            button.parentElement.remove();
            hitungTotal();
        }

        function hitungSubtotal(input) {
            let row = input.parentElement;
            let jumlah = input.value;
            let harga = row.querySelector("select").selectedOptions[0].getAttribute("data-harga");
            let subtotal = jumlah * harga;

            row.querySelector("input[name='subtotal[]']").value = isNaN(subtotal) ? '' : subtotal;
            hitungTotal();
        }

        function hitungTotal() {
            let total = 0;
            document.querySelectorAll("input[name='subtotal[]']").forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById("total").value = total;

        }
        function hapusBarangEdit(button) {
            button.parentElement.remove();
            hitungTotalEdit();
        }

        function hitungSubtotalEdit(input) {
            let row = input.parentElement;
            let jumlah = input.value;
            let harga = row.querySelector("select").selectedOptions[0].getAttribute("data-harga");
            let subtotal = jumlah * harga;

            row.querySelector("input[name='subtotal[]']").value = isNaN(subtotal) ? '' : subtotal;
            hitungTotalEdit();
        }

        function hitungTotalEdit() {
            let total = 0;
            document.querySelectorAll("input[name='subtotal[]']").forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            document.getElementById("editTotal").value = total;
        }
        function openEditModal(id, item) {
            document.getElementById('editSupplier').value = item.id_supplier;
            document.getElementById('editTanggalBeli').value = item.tanggal_beli;
            document.getElementById('editTotal').value = item.total;
            document.getElementById('editForm').action = '/pembelian/' + id;

            // Bersihkan container sebelum mengisi ulang
            let container = document.getElementById('editBarangContainer');
            container.innerHTML = '';

            // Loop data barang dan tambahkan input sesuai jumlahnya
            item.details.forEach((barang, index) => {
                let newBarang = `
                            <div class="barang-item flex gap-2 mb-2">
                                <select name="barang_id[]" class="w-1/2 p-2 border rounded value="${barang.id}" >
                                    <option value="" disabled>Pilih Barang</option>
                                    ${generateBarangOptions(barang.id_barang)}
                                </select>
                                <input type="number" name="jumlah[]" placeholder="Jumlah" value="${barang.jumlah_barang}" class="w-1/4 p-2 border rounded" oninput="hitungSubtotalEdit(this)">
                                <input type="text" name="subtotal[]" placeholder="Subtotal" value="${barang.sub_total}" class="w-1/4 p-2 border rounded bg-gray-100" readonly>
                                <button type="button" onclick="hapusBarangEdit(this)" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                            </div>
                        `;
                container.innerHTML += newBarang;
            });

            openModal('editModal');
        }

        // Fungsi untuk membuat dropdown barang tetap sesuai daftar yang ada
        function generateBarangOptions(selectedId) {
            let options = '';
            @foreach($barangs as $barang)
                var isSelected = {{ $barang->id }} == selectedId ? 'selected' : '';

                options += `<option value="{{ $barang->id }}" ${isSelected} data-harga="{{ $barang->harga }}">{{ $barang->nama_barang }}</option>`;
            @endforeach
            return options;
        }

        // Fungsi untuk menambahkan barang baru di modal edit
        function tambahBarangEdit() {
            let container = document.getElementById('editBarangContainer');
            let newBarang = `
                        <div class="barang-item flex gap-2 mb-2">
                            <select name="barang_id[]" class="w-1/2 p-2 border rounded
                            ">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach($barangs as $barang)
                                     <option value="{{ $barang->id }}" data-harga="{{ $barang->harga }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="jumlah[]" placeholder="Jumlah" class="w-1/4 p-2 border rounded" oninput="hitungSubtotalEdit(this)">
                            <input type="text" name="subtotal[]" placeholder="Subtotal" class="w-1/4 p-2 border rounded bg-gray-100" readonly>
                            <button type="button" onclick="hapusBarangEdit(this)" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                        </div>
                    `;
            container.innerHTML += newBarang;
        }
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