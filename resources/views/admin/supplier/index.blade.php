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
            <h3 class="text-lg font-semibold">Supplier</h3>
            @if ( auth()->user()->role == 'admin' ) <button onclick="openModal('addModal')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                Tambah Supplier
            </button>@endif
        </div>

        <!-- Table -->
        <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Supplier</th>
                    <th class="p-2 border">Alamat</th>
                    <th class="p-2 border">No Telepon</th>
                    @if ( auth()->user()->role == 'admin' ) <th class="p-2 border">Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach ($supplier as $key => $item)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">{{ $item->nama_supplier }}</td>
                        <td class="p-2 border">{{ $item->alamat }}</td>
                        <td class="p-2 border">{{ $item->no_telp }}</td>
                        @if ( auth()->user()->role == 'admin' )<td class="p-2 border">
                            <button
                                onclick="openEditModal({{ $item->id }}, '{{ $item->nama_supplier }}','{{ $item->alamat }}','{{ $item->no_telp }}')"
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

    <!-- Modal Tambah Supplier -->
    <div id="addModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Tambah Supplier</h2>
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_supplier" class="block text-sm font-medium text-gray-700">Nama Suplier</label>
                    <input type="text" id="nama_suplier" name="nama_supplier" placeholder="Masukkan Nama Suplier"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Suplier
                    </label>
                    <input type="text" id="alamat_suplier" name="alamat" placeholder="Masukkan Alamat Suplier"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" placeholder="Masukkan Nomor Telepon"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit"
                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Supplier -->
    <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Edit Supplier</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')


                <div class="mb-3">
                    <label for="editNamaSupplier" class="block text-sm font-medium text-gray-700">Nama Suplier</label>
                    <input type="text" id="editNamaSupplier" name="nama_supplier" placeholder="Masukkan Nama Suplier"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="editNamaSupplier2" class="block text-sm font-medium text-gray-700">Alamat Suplier
                    </label>
                    <input type="text" id="editNamaSupplier2" name="alamat" placeholder="Masukkan Alamat Suplier"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="editNamaSupplier3" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" id="editNamaSupplier3" name="no_telp" placeholder="Masukkan Nomor Telepon"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Supplier -->
    <div id="deleteModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Hapus Supplier</h2>
            <p>Apakah Anda yakin ingin menghapus Supplier ini?</p>
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
     
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            if (id === 'addModal') {
                document.querySelector('#addModal form').reset();
               // Hapus semua barang yang ditambahkan
            } else if (id === 'editModal') {
                document.querySelector('#editModal form').reset();
                // Hapus semua barang yang ditambahkan
            }
        }

        // Edit Modal
        function openEditModal(id, nama, alamat, no) {
            document.getElementById('editNamaSupplier').value = nama;
            document.getElementById('editNamaSupplier2').value = alamat;
            document.getElementById('editNamaSupplier3').value = no;
            document.getElementById('editForm').action = '/supplier/' + id;
            openModal('editModal');
        }

        // Delete Modal
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/supplier/' + id;
            openModal('deleteModal');
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