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
            <h3 class="text-lg font-semibold">Barang</h3>
            @if (auth()->user()->role == 'admin')
                <button onclick="openModal('addModal')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                    Tambah Barang
                </button>

            @endif

        </div>

        <!-- Table -->
        <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Barang</th>
                    <th class="p-2 border">kategori</th>
                    <th class="p-2 border">detail</th>
                    <th class="p-2 border">berat</th>
                    <th class="p-2 border">harga</th>
                    <th class="p-2 border">foto</th>
                    <th class="p-2 border">stok</th>
                    @if (auth()->user()->role == 'admin')
                    <th class="p-2 border">Aksi</th >@endif
                </tr>
            </thead>
            <tbody>
                @foreach ($barang as $key => $item)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $key + 1 }}</td>
                        <td class="p-2 border">{{ $item->nama_barang }}</td>
                        <td class="p-2 border">{{ $item->kategori->nama_kategori }}</td>
                        <td class="p-2 border">{{ $item->detail_barang }}</td>
                        <td class="p-2 border">{{ $item->berat }}</td>
                        <td class="p-2 border">{{ $item->harga }}</td>
                        <td class="p-2 border"><img src="{{ asset('image/' . $item->foto) }}" alt="Foto Barang"
                                class="w-20 h-20 object-cover"></td>
                        <td class="p-2 border">{{ $item->stok }}</td>
                        @if (auth()->user()->role == 'admin')
                            <td class="p-2 border">
                                <button
                                    onclick="openEditModal({{ $item->id }},'{{ $item->kategori->id }}', '{{ $item->nama_barang }}','{{ $item->detail_barang }}','{{ $item->berat }}','{{ $item->harga }}','{{ $item->harga_jual }}')"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    Edit
                                </button>
                                <button onclick="openDeleteModal({{ $item->id }})"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </td>

                        @endif              </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Supplier -->
    <div id="addModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-3/4">
            <h2 class="text-lg font-semibold mb-4">Tambah Barang</h2>
            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data"
                class="max-h-[500px] overflow-auto ">
                @csrf

                <div class="mb-3">
                    <label for="id_kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="id_kategori" name="id_kategori" class="w-full p-2 border rounded" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" placeholder="Masukkan Nama Barang"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="detail_barang" class="block text-sm font-medium text-gray-700">Detail Barang</label>
                    <textarea id="detail_barang" name="detail_barang" placeholder="Masukkan Detail Barang"
                        class="w-full p-2 border rounded" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="berat" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                    <input type="number" id="berat" name="berat" placeholder="Masukkan Berat Barang"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga" placeholder="Masukkan Harga Barang"
                        class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-3">
                    <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual(Rp)</label>
                    <input type="number" id="harga_jual" name="harga_jual" placeholder="Masukkan Harga Barang"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto Barang</label>
                    <input type="file" id="foto" name="foto" class="w-full p-2 border rounded" accept="image/*"
                        onchange="previewImage(event)">
                    <small class="text-gray-500">Format: JPG, PNG, GiF,JPEG | Max: 2MB</small>
                    <img id="preview" src="" class="mt-2 w-40 h-40 object-cover hidden">
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
        <div class="bg-white p-5 rounded-lg shadow-lg w-3/4">
            <h2 class="text-lg font-semibold mb-4">Edit Supplier</h2>
            <form id="editForm" method="POST" class="max-h-[500px] overflow-auto" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="mb-3">
                    <label for="id_kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="id_kategori_edit" name="id_kategori" class="w-full p-2 border rounded" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    <input type="text" id="nama_barang_edit" name="nama_barang" placeholder="Masukkan Nama Barang"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="detail_barang" class="block text-sm font-medium text-gray-700">Detail Barang</label>
                    <textarea id="detail_barang_edit" name="detail_barang" placeholder="Masukkan Detail Barang"
                        class="w-full p-2 border rounded" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="berat" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                    <input type="number" id="berat_edit" name="berat" placeholder="Masukkan Berat Barang"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" id="harga_edit" name="harga" placeholder="Masukkan Harga Barang"
                        class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-3">
                    <label for="harga_jual_edit" class="block text-sm font-medium text-gray-700">Harga Jual(Rp)</label>
                    <input type="number" id="harga_jual_edit" name="harga_jual" placeholder="Masukkan Harga Barang"
                        class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-3">
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto Barang</label>
                    <input type="file" id="foto_edit" name="foto" class="w-full p-2 border rounded" accept="image/*"
                        onchange="previewImage(event)" <small class="text-gray-500">Format: JPG, PNG, GiF,JPEG | Max:
                    2MB</small>
                    <img id="preview" src="" class="mt-2 w-40 h-40 object-cover hidden">
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
            <h2 class="text-lg font-semibold mb-4">Hapus Barang</h2>
            <p>Apakah Anda yakin ingin menghapus Barang ini?</p>
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
        function previewImage(event) {
            var input = event.target;
            var file = input.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Menampilkan gambar
                };
                reader.readAsDataURL(file);
            }
        }

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

            }
        }

        // Edit Modal
        function openEditModal(id, id_kategori, nama_barang, detail_barang, berat, harga) {
            document.getElementById('id_kategori_edit').value = id_kategori;
            document.getElementById('nama_barang_edit').value = nama_barang;
            document.getElementById('detail_barang_edit').value = detail_barang;
            document.getElementById('berat_edit').value = berat;
            document.getElementById('harga_edit').value = harga;
            document.getElementById('harga_jual_edit').value = harga_jual;
            document.getElementById('editForm').action = '/barang/' + id;
            openModal('editModal');
        }

        // Delete Modal
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = '/barang/' + id;
            openModal('deleteModal');
        }
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
    </script>

@endsection