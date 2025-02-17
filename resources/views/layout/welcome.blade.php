
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
    @vite(['resources/js/app.js', 'resources/css/styles.css','resources/js/datatable.js'])
    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="css/css.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script> -->
</head>

<body class="bg-gray-100 flex flex-col min-h-screen overflow-hidden">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white p-4 flex justify-between items-center w-screen">
    <!-- Tombol Sidebar -->
    <button id="sidebarToggle" class="text-white text-xl md:hidden">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Kontainer utama -->
    <div class="flex flex-1 justify-between items-center">
        <!-- Teks di kiri -->
        <div class="text-white font-bold text-2xl">
            <p>Toko Komputer</p>
        </div>

        <!-- Icon dan Dropdown di kanan -->
        <div class="relative">
            <button id="userMenuButton" class="text-white">
                <i class="fas fa-user text-2xl mr-5"></i>
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white text-gray-900 shadow-lg hidden" id="userDropdown">
                <!-- <a href="#" class="block px-4 py-2 hover:bg-gray-200">Profil</a> -->
                <hr>
                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-200">Logout</a>
            </div>
        </div>
    </div>
</nav>


    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-900 text-white p-4 absolute md:relative min-h-screen hidden md:block">

            <nav>

                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'pimpinan')
                    <a href="/login" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                    <a href="{{ route('kategori.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-th-list mr-3"></i> Kategori
                    </a>
                    <a href="{{ route('barang.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-box mr-3"></i> Barang
                    </a>
                    <a href="{{ route('pembelian.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-shopping-cart mr-3"></i> Pembelian
                    </a>
                    <a href="{{ route('supplier.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-truck mr-3"></i> Suplayer
                    </a>
                    <!-- <a href="{{ route('penjualan.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-money-bill-wave mr-3"></i> Penjualan

                    </a> -->
                @endif
                @if(auth()->user()->role == 'member')
                    <a href="{{ route('dashboard.member') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                    <a href="{{ route('riwayat.member') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-history mr-3"></i> Riwayat pembelian
                    </a>
                @endif
                @if(auth()->user()->role == 'kasir')
                    <a href="{{ route('dashboard.kasir') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                    </a>
                    <a href="{{ route('member.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fas fa-users mr-3"></i> Member
                    </a>
                    <a href="{{ route('penjualan.index') }}" class="flex items-center py-2 px-4 hover:bg-gray-700 w-full">
                        <i class="fas fa-cash-register mr-3"></i> Penjualan
                    </a>
                @endif

            </nav>



        </aside>

        <!-- Main Content -->
        <main class="flex-1 ">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <!-- <footer class="bg-gray-200 p-4 text-center">
        <p>&copy; Your Website 2023</p>
        <div>
            <a href="#" class="text-blue-500">Privacy Policy</a> &middot;
            <a href="#" class="text-blue-500">Terms & Conditions</a>
        </div>
    </footer> -->

    <!-- Scripts -->
    <script>

        document.getElementById('userMenuButton').addEventListener('click', function (event) {
            event.stopPropagation(); // Mencegah klik menutup langsung
            document.getElementById('userDropdown').classList.toggle('hidden');
        });

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function () {
            document.getElementById('userDropdown').classList.add('hidden');
        });

        // Biar dropdown tetap terbuka saat diklik di dalamnya
        document.getElementById('userDropdown').addEventListener('click', function (event) {
            event.stopPropagation();
        });
    </script>




</body>

</html>