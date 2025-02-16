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
        <h3 class="text-lg font-semibold">Member</h3>
        <button onclick="openModal('addModal')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
            Tambah Member
        </button>
    </div>

    <!-- Table -->
    <table id="datatablesSimple" class="w-full mt-4 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama Member</th>
                <th class="p-2 border">Alamat</th>
                <th class="p-2 border">No telepon</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Password</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $key => $item)
                <tr class="text-center">
                    <td class="p-2 border">{{ $key + 1 }}</td>
                    <td class="p-2 border">{{ $item->nama_member }}</td>
                    <td class="p-2 border">{{ $item->alamat }}</td>
                    <td class="p-2 border">{{ $item->no_telp }}</td>
                    <td class="p-2 border">{{ $item->email }}</td>
                    <td class="p-2 border">{{ $item->password }}</td>
                    <td class="p-2 border">
                        <button
                            onclick="openEditModal({{ $item->id }},'{{ $item->nama_member }}', '{{ $item->alamat }}','{{ $item->no_telp }}','{{ $item->email }}','{{ $item->password }}')"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Edit
                        </button>
                        <button onclick="openDeleteModal({{ $item->id }})"
                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Supplier -->
<div id="addModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-3/4">
        <h2 class="text-lg font-semibold mb-4">Tambah Member</h2>
        <form action="{{ route('member.store') }}" method="POST" enctype="multipart/form-data" class="max-h-[500px] overflow-auto ">
            @csrf

            <div class="mb-3">
                <label for="nama_member" class="block text-sm font-medium text-gray-700">Nama Member</label>
                <input type="text" id="nama_member" name="nama_member" placeholder="Masukkan Nama Member"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="alamat" name="alamat" placeholder="Masukkan Alamat"
                    class="w-full p-2 border rounded" required></textarea>
            </div>

            <div class="mb-3">
                <label for="no_telp" class="block text-sm font-medium text-gray-700">No telepon </label>
                <input type="text" id="no_telp" name="no_telp" placeholder="Masukkan No tepelpon"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email </label>
                <input type="email" id="email" name="email" placeholder="Masukkan Email "
                    class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="text" id="password" name="password" placeholder="Masukkan password"
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
    <div class="bg-white p-5 rounded-lg shadow-lg w-3/4">
        <h2 class="text-lg font-semibold mb-4">Edit Supplier</h2>
        <form id="editForm" method="POST" class="max-h-[500px] overflow-auto" enctype="multipart/form-data">
            @csrf
            @method('PUT')


            <div class="mb-3">
                <label for="nama_member_edit" class="block text-sm font-medium text-gray-700">Nama Member</label>
                <input type="text" id="nama_member_edit" name="nama_member" placeholder="Masukkan Nama Member"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="alamat_edit" name="alamat" placeholder="Masukkan Alamat"
                    class="w-full p-2 border rounded" required></textarea>
            </div>

            <div class="mb-3">
                <label for="no_telp" class="block text-sm font-medium text-gray-700">No telepon </label>
                <input type="text" id="no_telp_edit" name="no_telp" placeholder="Masukkan No tepelpon"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email </label>
                <input type="email" id="email_edit" name="email" placeholder="Masukkan Email "
                    class="w-full p-2 border rounded" required disable>
            </div>
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="text" id="password_edit" name="password" placeholder="Masukkan password"
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
        <h2 class="text-lg font-semibold mb-4">Hapus Member</h2>
        <p>Apakah Anda yakin ingin menghapus Member ini?</p>
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
            reader.onload = function(e) {
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
    function openEditModal(id, nama_member,alamat, no_telp, email,password) {
        document.getElementById('nama_member_edit').value = nama_member;
        document.getElementById('alamat_edit').value = alamat;
        document.getElementById('no_telp_edit').value = no_telp;
        document.getElementById('email_edit').value = email;
        document.getElementById('password_edit').value = password;
       
        document.getElementById('editForm').action = '/member/' + id;
        openModal('editModal');
    }

    // Delete Modal
    function openDeleteModal(id) {
        document.getElementById('deleteForm').action = '/member/' + id;
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