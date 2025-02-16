<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
   

</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Login</h2>

        <!-- Pesan error -->
        <!-- @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                <p>{{ $errors->first() }}</p>
            </div>
        @endif -->

        <!-- Pesan sukses -->
        <!-- @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
                <p>{{ session('success') }}</p>
            </div>
        @endif -->

        <form action="{{ route('login') }}" method="POST">
            @csrf <!-- Token CSRF untuk keamanan -->

            <div class="mb-4">
                <label for="email" class="block text-gray-600 text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}"
                    class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="mb-4 relative">
    <label for="password" class="block text-gray-600 text-sm font-medium">Password</label>
    <div class="relative">
        <input type="password" id="password" name="password" required value="{{ old('password') }}"
            class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10">
        
        <!-- Tombol Tampilkan/Sembunyikan Password -->
        <button type="button" onclick="togglePassword()"
            class="absolute inset-y-0 right-3 flex items-center text-gray-600">
            <i id="eyeIcon" class="fa-solid fa-eye"></i>
        </button>
    </div>
</div>


            <button type="submit"
                class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                Login
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            let passwordField = document.getElementById("password");
            let eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
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

</body>

</html>