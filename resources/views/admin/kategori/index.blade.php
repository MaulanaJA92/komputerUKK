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
        <h3 class="text-lg font-semibold">Kategori</h3>
      @if ( auth()->user()->role == 'admin' )
        
       <button onclick="openModal('addModal')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
            Tambah Kategori
        </button>
      @endif 
    </div>

    <!-- Table -->
    <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama Kategori</th>
                <th class="p-2 border">Barang Terkait</th>
                @if ( auth()->user()->role == 'admin' )  <th class="p-2 border">Aksi</th>@endif
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori as $key => $item)
                <tr class="text-center">
                    <td class="p-2 border">{{ $key + 1 }}</td>
                    <td class="p-2 border">{{ $item->nama_kategori }}</td>
                    <td class="p-2 border">{{ $item->barang_count }}</td>
                    @if ( auth()->user()->role == 'admin' )  <td class="p-2 border">
                        <button onclick="openEditModal({{ $item->id }}, '{{ $item->nama }}')"
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

<!-- Modal Tambah Kategori -->
<div id="addModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Tambah Kategori</h2>
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <input type="text" name="nama_kategori" placeholder="Nama Kategori" class="w-full p-2 border rounded mb-3" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('addModal')" class="px-3 py-1 border rounded">Batal</button>
                <button type="submit"
                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Edit Kategori</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="nama_kategori" id="editNamaKategori" class="w-full p-2 border rounded mb-3" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('editModal')" class="px-3 py-1 border rounded">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Kategori -->
<div id="deleteModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Hapus Kategori</h2>
        <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
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
    function openEditModal(id, nama) {
        document.getElementById('editNamaKategori').value = nama;
        document.getElementById('editForm').action = '/kategori/' + id;
        openModal('editModal');
    }

    // Delete Modal
    function openDeleteModal(id) {
        document.getElementById('deleteForm').action = '/kategori/' + id;
        openModal('deleteModal');
    }

</script>

@endsection