<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi PDAM Pribadi</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex; /* Mengaktifkan flexbox untuk body */
            min-height: 100vh; /* Minimal tinggi body adalah tinggi viewport */
            flex-direction: column; /* Konten utama dan footer akan bertumpuk secara vertikal */
        }
        .wrapper {
            display: flex;
            width: 100%;
            flex-grow: 1; /* Wrapper akan mengisi sisa ruang vertikal */
        }
        .sidebar {
            background-color: #007bff; /* Biru PDAM */
            color: #fff;
            padding-top: 15px;
            width: 230px; /* Lebar sidebar */
            min-height: 100vh; /* Sidebar setinggi viewport */
            position: fixed; /* Sidebar tetap di tempat saat scroll */
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,.1);
            z-index: 1000; /* Pastikan sidebar di atas konten lain */
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 15px 20px;
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.3rem;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }
        .sidebar-brand:hover {
            color: #f8f9fa;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1; /* Menu mengisi ruang yang tersedia di sidebar */
        }
        .sidebar-menu li.nav-item a.nav-link {
            display: block;
            padding: 12px 20px;
            color: #e9ecef; /* Warna teks link */
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-size: 0.95rem;
        }
        .sidebar-menu li.nav-item a.nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff; /* Warna teks link saat hover */
        }
        .sidebar-menu li.nav-item a.nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: bold;
            color: #fff; /* Warna teks link aktif */
            border-left: 3px solid #fff; /* Indikator aktif */
            padding-left: 17px;
        }
        .sidebar-menu li.nav-item a.nav-link i {
            margin-right: 10px; /* Jarak ikon dengan teks */
        }
        .content-wrapper {
            flex-grow: 1;
            padding: 20px;
            margin-left: 230px; /* Sesuaikan dengan lebar sidebar */
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Konten wrapper juga setinggi viewport */
        }
        .main-content {
            flex-grow: 1; /* Konten utama akan mengisi ruang yang tersedia */
        }
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
            margin-bottom: 1.5rem; /* Jarak antar card */
        }
        .card-header {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 0.75rem 1.25rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table th {
            background-color: #f1f1f1;
        }
        .alert {
            border-radius: 0.25rem;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 20px;
            text-align: center;
            width: calc(100% - 230px); /* Lebar footer = 100% - lebar sidebar */
            margin-left: 230px; /* Sesuaikan dengan lebar sidebar */
            /* Jika ingin footer tetap di bawah meskipun konten pendek, ini dihandle oleh flex di body dan content-wrapper */
        }
        .form-label {
            font-weight: 500;
        }
        .action-icons a {
            margin-right: 8px;
            color: #007bff;
        }
        .action-icons a.text-danger {
            color: #dc3545 !important;
        }
        .action-icons a:hover {
            text-decoration: none;
            opacity: 0.8;
        }
        .badge {
            font-size: 0.9em;
        }

        /* Untuk tampilan mobile, sidebar bisa disembunyikan atau diubah jadi off-canvas */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: static; /* Atau gunakan offcanvas Bootstrap */
                box-shadow: none;
                /* Jika mau diubah jadi navbar atas lagi di mobile */
                /* display: flex; flex-direction: row; align-items: center; padding-top:0; */
            }
            /* .sidebar-brand { text-align: left; margin-bottom:0;} */
            /* .sidebar-menu { display: flex; flex-direction: row; } */
            /* .sidebar-menu li.nav-item a.nav-link.active { border-left: none; border-bottom: 3px solid #fff; padding-left:20px; } */

            /* Jika sidebar tetap di kiri tapi butuh toggle */
             .sidebar {
                left: -230px; /* Sembunyikan sidebar */
                transition: left 0.3s ease;
            }
            .sidebar.active {
                left: 0; /* Tampilkan sidebar */
            }
            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .footer {
                width: 100%;
                margin-left: 0;
            }
            /* Anda perlu menambahkan tombol untuk toggle sidebar di mobile */
            .mobile-toggle-btn {
                display: block; /* Tampilkan tombol toggle di mobile */
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1001; /* Di atas sidebar */
                background-color: #007bff;
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 3px;
            }
        }
        @media (min-width: 769px) {
            .mobile-toggle-btn {
                display: none; /* Sembunyikan tombol toggle di desktop */
            }
        }

    </style>
</head>
<body>
    <button class="btn mobile-toggle-btn" id="sidebarToggle"><i class="bi bi-list"></i></button>

    <div class="sidebar" id="sidebar">
        <a class="sidebar-brand" href="{{ route('pelanggan.index') }}">
            <i class="bi bi-droplet-fill"></i> PDAM App
        </a>
        <ul class="sidebar-menu">
        <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelanggan.index') || request()->routeIs('pelanggan.create') || request()->routeIs('pelanggan.show') || request()->routeIs('pelanggan.edit') ? 'active' : '' }}" href="{{ route('pelanggan.index') }}">
                    <i class="bi bi-people-fill"></i> Pelanggan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tagihan.index') || request()->routeIs('tagihan.bayar') ? 'active' : '' }}" href="{{ route('tagihan.index') }}">
                    <i class="bi bi-receipt"></i> Tagihan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tagihan.riwayat') ? 'active' : '' }}" href="{{ route('tagihan.riwayat') }}">
                    <i class="bi bi-clock-history"></i> Riwayat Pembayaran
                </a>
            </li>
        </ul>
    </div>

    <div class="content-wrapper">
        <div class="main-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <footer class="footer">
        &copy; {{ date('Y') }} Aplikasi PDAM Pribadi. Dibuat dengan <i class="bi bi-heart-fill text-danger"></i>.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk toggle sidebar di mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const contentWrapper = document.querySelector('.content-wrapper'); // Ambil content wrapper
        const footer = document.querySelector('.footer'); // Ambil footer

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }
    </script>
</body>
</html>