<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Guru - SMK Budhi Darma</title>
    <link rel="icon" href="{{ asset('images/logo_smk.png') }}" type="image/png">
    
    {{-- Bootstrap 5 & Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;       /* Biru Royal Modern */
            --primary-hover: #1d4ed8;
            --sidebar-bg: #ffffff;          /* Putih Bersih Modern */
            --sidebar-border: #e2e8f0;      /* Garis abu-abu sangat halus */
            --sidebar-hover: #f8fafc;       /* Abu-abu super terang untuk hover menu */
            --body-bg: #f1f5f9;             /* Latar belakang konten (Slate 50) */
            --text-main: #334155;           /* Warna teks utama */
            --text-muted: #64748b;          /* Warna teks pasif/menu */
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Custom Scrollbar Minimalis */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* ================= SIDEBAR KIRI (CLEAN MODERN WHITE) ================= */
        #sidebar {
            width: 270px;
            height: 100vh;
            position: fixed;
            background-color: var(--sidebar-bg);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 1000;
            border-right: 1px solid var(--sidebar-border);
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
        }
        
        /* Branding Logo Area */
        #sidebar .brand {
            padding: 25px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px dashed var(--sidebar-border);
            margin-bottom: 15px;
        }
        #sidebar .brand .logo-box {
            width: 48px;
            height: 48px;
            background: #ffffff;
            border-radius: 12px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: 1px solid #f1f5f9;
        }
        #sidebar .brand img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        #sidebar .brand .brand-text {
            display: flex;
            flex-direction: column;
        }
        #sidebar .brand .brand-text .title {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        #sidebar .brand .brand-text .subtitle {
            color: var(--primary-color);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Menu Links Kapsul */
        #sidebar .nav-link {
            color: var(--text-muted);
            padding: 12px 18px 12px 22px;
            border-radius: 10px;
            margin: 4px 16px;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            position: relative;
        }
        #sidebar .nav-link i {
            font-size: 1.25rem;
            margin-right: 14px;
            transition: 0.3s;
            color: #94a3b8;
        }
        
        /* Efek Hover Menu */
        #sidebar .nav-link:hover {
            color: var(--primary-color);
            background-color: var(--sidebar-hover);
            transform: translateX(4px);
        }
        #sidebar .nav-link:hover i {
            color: var(--primary-color);
        }
        
        /* Menu Aktif (Biru Pastel Menyala) */
        #sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: #eff6ff; /* Biru sangat muda */
            font-weight: 700;
        }
        #sidebar .nav-link.active i {
            color: var(--primary-color);
        }
        /* Garis penanda menu aktif di sebelah kiri */
        #sidebar .nav-link.active::before {
            content: ''; 
            position: absolute; 
            left: -16px; 
            top: 15%; 
            height: 70%; 
            width: 4px; 
            background-color: var(--primary-color); 
            border-radius: 0 4px 4px 0;
        }

        /* Label Kategori Menu */
        #sidebar .menu-label {
            color: #94a3b8;
            font-size: 0.7rem;
            letter-spacing: 1.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding-left: 24px;
            margin-top: 25px;
            margin-bottom: 8px;
        }

        /* Tombol Logout Sidebar Elegan */
        .logout-wrapper {
            padding: 20px 16px;
            border-top: 1px dashed var(--sidebar-border);
            margin-top: auto;
        }
        .logout-btn {
            background-color: #fff1f2;
            color: #e11d48;
            border: 1px dashed #fecdd3;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #e11d48;
            color: #ffffff;
            border-style: solid;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
        }

        /* ================= KONTEN KANAN ================= */
        #content-wrapper {
            margin-left: 270px;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navbar Atas */
        .top-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            padding: 14px 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.02);
            z-index: 999;
            border-bottom: 1px solid var(--sidebar-border);
            position: sticky;
            top: 0;
        }

        /* Mobile Responsiveness & Overlay */
        .sidebar-overlay { display: none; position: fixed; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 998; top: 0; left: 0; transition: 0.3s; }
        
        @media (max-width: 768px) {
            #sidebar { left: -270px; margin-left: 0; }
            #sidebar.active { left: 0; }
            #content-wrapper { margin-left: 0; }
            .top-navbar { padding: 14px 20px; }
            .sidebar-overlay.active { display: block; }
        }
    </style>
</head>
<body>

    {{-- Overlay Hitam untuk HP --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ================= SIDEBAR KIRI ================= --}}
    <nav id="sidebar">
        
        {{-- 🔥 LOGO DAN BRANDING SEKOLAH 🔥 --}}
        <div class="brand">
            <div class="logo-box">
                <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK Budhi Darma">
            </div>
            <div class="brand-text">
                <span class="title">PORTAL GURU</span>
                <span class="subtitle">SMK Budhi Darma</span>
            </div>
        </div>
        
        <div class="flex-grow-1 overflow-y-auto mt-2 pb-3">
            <ul class="nav flex-column mb-auto">
                <li class="nav-item mb-1">
                    <a href="{{ route('walikelas.dashboard') }}" class="nav-link {{ request()->routeIs('walikelas.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard Utama
                    </a>
                </li>
                
                <li class="nav-item">
                    <div class="menu-label">Akademik & Kelas</div>
                </li>
                
                <li class="nav-item mb-1">
                    <a href="{{ route('walikelas.siswa.index') }}" class="nav-link {{ request()->routeIs('walikelas.siswa.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Data Siswa
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('walikelas.nilai.index') }}" class="nav-link {{ request()->routeIs('walikelas.nilai.index') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i> Input Nilai (Rapor)
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('walikelas.rekap') }}" class="nav-link {{ request()->routeIs('walikelas.rekap', 'walikelas.nilai.detail') ? 'active' : '' }}">
                        <i class="bi bi-trophy-fill"></i> Rekap Penilaian
                    </a>
                </li>

                <li class="nav-item">
                    <div class="menu-label">Pengaturan Akun</div>
                </li>
                
                <li class="nav-item mb-1">
                    @php
                        $isProfilActive = request()->routeIs('walikelas.profil', 'walikelas.profil.edit', 'walikelas.password.edit');
                    @endphp
                    <a href="{{ route('walikelas.profil') }}" class="nav-link {{ $isProfilActive ? 'active' : '' }}">
                        <i class="bi bi-person-bounding-box"></i> Profil Saya
                    </a>
                </li>
            </ul>
        </div>
        
        {{-- Tombol Logout Elegan --}}
        <div class="logout-wrapper">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn w-100 rounded-3 fw-bold py-2 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2 fs-5"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </nav>

    {{-- ================= KONTEN KANAN ================= --}}
    <div id="content-wrapper">
        
        {{-- NAVBAR ATAS --}}
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-white border shadow-sm d-md-none rounded-3 d-flex align-items-center justify-content-center bg-white" id="sidebarToggle" style="width: 42px; height: 42px;">
                    <i class="bi bi-list fs-4 text-dark"></i>
                </button>
                <div class="d-none d-md-flex align-items-center bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill border border-primary border-opacity-25" style="font-size: 0.85rem;">
                    <i class="bi bi-mortarboard-fill me-2"></i> Panel Wali Kelas
                </div>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="text-end d-none d-sm-block me-3">
                    <p class="mb-0 fw-bolder text-dark lh-1" style="font-size: 0.9rem;">{{ Auth::user()->name }}</p>
                   
                </div>
                
                {{-- 🔥 LOGIKA AVATAR WALI KELAS 🔥 --}}
                @php
                    $guruLayout = \App\Models\WaliKelas::where('user_id', Auth::id())->first();
                    $avatarGuru = ($guruLayout && $guruLayout->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                @endphp
                <a href="{{ route('walikelas.profil') }}" class="d-block bg-white rounded-circle shadow-sm" style="padding: 3px; border: 2px solid var(--primary-color); transition: all 0.3s;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="{{ asset($avatarGuru) }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                </a>
            </div>
        </nav>

        {{-- AREA KONTEN UTAMA --}}
        <main class="flex-grow-1 p-4 p-md-4">
            
            {{-- Alert Error Global --}}
            @if(session('error'))
            <div class="alert bg-danger bg-opacity-10 border-0 border-start border-danger border-4 text-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Render Konten dari Halaman Lain --}}
            @yield('content')
            
        </main>

        {{-- FOOTER --}}
        <footer class="mt-auto px-4 py-3 text-center text-muted small border-top bg-white">
            <span class="fw-medium">&copy; {{ date('Y') }} Sistem Informasi Manajemen</span> - SMK Swasta Budhi Darma Indrapura
        </footer>
    </div>

    {{-- Script Interaktif Sidebar & Overlay --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Buka Sidebar di HP
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
        });

        // Tutup Sidebar jika area gelap (overlay) diklik
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    </script>
</body>
</html>