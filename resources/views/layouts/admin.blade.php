<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yayasan - SIM Sekolah</title>
    <link rel="icon" href="{{ asset('images/logo_smk.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Base Styling */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; /* Abu-abu sangat muda agar card putih lebih menonjol */
            color: #334155;
            margin: 0; 
            overflow-x: hidden; 
        }
        
        /* Custom Scrollbar Minimalis */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ================= SIDEBAR (Modern Clean White) ================= */
        .sidebar { 
            width: 280px; 
            height: 100vh; 
            position: fixed; 
            top: 0; left: 0; 
            background: #ffffff; 
            z-index: 1000; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            overflow-y: auto; 
            border-right: 1px solid #e2e8f0;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02); 
        }
        .sidebar.collapsed { left: -280px; }
        
        /* Area Konten */
        .main-content { 
            margin-left: 280px; 
            min-height: 100vh; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            width: calc(100% - 280px); 
            display: flex; 
            flex-direction: column; 
        }
        .main-content.expanded { margin-left: 0; width: 100%; }
        
        /* Branding Sekolah di Sidebar */
        .sidebar-brand { 
            padding: 35px 20px 25px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            text-align: center; 
            border-bottom: 1px dashed #e2e8f0; 
            margin-bottom: 15px; 
        }
        .logo-wrapper { 
            width: 70px; height: 70px; 
            background: #ffffff; 
            border-radius: 16px; 
            display: flex; align-items: center; justify-content: center; 
            padding: 8px; margin-bottom: 15px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.06); 
            border: 1px solid #f1f5f9;
        }
        .logo-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .sidebar-brand-text { color: #0f172a; font-weight: 700; font-size: 1.1rem; line-height: 1.4; }
        
        /* Label Kategori Menu */
        .sidebar .menu-label { 
            color: #94a3b8; font-size: 0.75rem; font-weight: 700; 
            letter-spacing: 1.2px; text-transform: uppercase; 
            margin-top: 25px; margin-bottom: 8px; padding-left: 28px; 
        }
        
        /* Desain Link Menu Dasar */
        .sidebar a { 
            color: #64748b; 
            text-decoration: none; padding: 12px 20px 12px 24px; 
            display: flex; align-items: center; 
            border-radius: 8px; margin: 4px 16px; 
            font-weight: 500; font-size: 0.95rem; 
            transition: all 0.3s ease; position: relative; 
        }
        .sidebar a i { font-size: 1.2rem; margin-right: 14px; transition: all 0.3s ease; color: #94a3b8; }
        
        /* Efek Hover Menu */
        .sidebar a:hover { 
            background-color: #f8fafc; 
            color: #0f172a; 
            transform: translateX(4px); 
        }
        .sidebar a:hover i { color: #0f172a; }
        
        /* Menu Aktif (Soft Emerald Elegance) */
        .sidebar a.active { 
            background-color: #ecfdf5; 
            color: #059669; font-weight: 600;
        }
        .sidebar a.active i { color: #059669; }
        .sidebar a.active::before {
            content: ''; position: absolute; left: -16px; top: 10%; height: 80%; width: 4px; 
            background-color: #10b981; border-radius: 0 4px 4px 0;
        }

        /* ================= NAVBAR ATAS ================= */
        .top-navbar { 
            background: rgba(255, 255, 255, 0.9) !important; 
            backdrop-filter: blur(12px); 
            border-bottom: 1px solid rgba(0,0,0,0.04); 
            position: sticky; top: 0; z-index: 999; 
        }
        
        /* Profil User di Kanan Atas */
        .user-profile-link { 
            padding: 5px 16px 5px 6px; border-radius: 30px; 
            transition: all 0.2s ease; background-color: #ffffff; 
            border: 1px solid #e2e8f0; cursor: pointer;
        }
        .user-profile-link:hover { border-color: #10b981; background-color: #f8fafc; }

        /* Tombol Toggle Sidebar */
        .btn-toggle { 
            font-size: 1.5rem; cursor: pointer; color: #475569; 
            padding: 6px 10px; border-radius: 8px; transition: 0.2s; 
            background: #ffffff; border: 1px solid #e2e8f0; 
        }
        .btn-toggle:hover { background-color: #f1f5f9; color: #10b981; }

        /* Tombol Logout Elegan */
        .logout-btn { 
            background-color: #fff1f2; color: #e11d48; 
            border: 1px dashed #fecdd3; transition: all 0.3s ease; 
        }
        .logout-btn:hover { 
            background-color: #e11d48; color: #ffffff; 
            border-style: solid;
            transform: translateY(-2px); box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2); 
        }

        /* Responsive Settings */
        @media (max-width: 991.98px) {
            .sidebar { left: -280px; } .sidebar.active { left: 0; } .sidebar.collapsed { left: 0; }
            .main-content { margin-left: 0; width: 100%; } .main-content.expanded { margin-left: 0; }
            .overlay { display: none; position: fixed; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 998; top: 0; left: 0; }
            .overlay.active { display: block; }
        }
    </style>
</head>
<body>

<div class="overlay" id="overlay"></div>

<div class="d-flex">
    {{-- ================= SIDEBAR MENU YAYASAN ================= --}}
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-wrapper">
                <img src="{{ asset('images/logo_smk.png') }}" alt="Logo Yayasan" onerror="this.src='https://cdn-icons-png.flaticon.com/512/8074/8074804.png'">
            </div>
            <div class="sidebar-brand-text">
                PENGURUS YAYASAN<br>
                <span class="text-success fw-normal" style="font-size: 0.85rem; letter-spacing: 0;">SMK Swasta Budhi Darma</span>
            </div>
        </div>
        
        <div class="menu-label">Pemantauan Eksekutif</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard Utama
        </a>
        <a href="{{ route('admin.riwayat.index') }}" class="{{ request()->is('admin/riwayat-transaksi*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Riwayat Transaksi
        </a>

        <div class="menu-label mt-4">Manajemen SDM</div>
        <a href="{{ route('pegawai.index') }}" class="{{ request()->is('admin/pegawai*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Data Pegawai
        </a>
        <a href="{{ route('walikelas.index') }}" class="{{ request()->is('admin/walikelas*') ? 'active' : '' }}">
            <i class="bi bi-person-video3"></i> Data Guru
        </a>

        <div class="menu-label mt-4">Sistem & Keamanan</div>
        <a href="{{ route('admin.profile') }}" class="{{ request()->is('admin/profile*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i> Profile
        </a>

        <div class="mt-5 border-top border-secondary border-opacity-10 pt-4 mx-4 mb-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn w-100 fw-bold rounded-3 py-2">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </div>

    {{-- ================= KONTEN UTAMA ================= --}}
    <div class="main-content" id="mainContent">
        
        {{-- Navbar Atas --}}
        <nav class="navbar navbar-expand-lg top-navbar mb-4 px-4 py-3">
            <div class="container-fluid">
                <button class="btn-toggle me-3 d-flex align-items-center justify-content-center" id="toggleMenu">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-brand mb-0 h5 fw-bold text-dark d-none d-md-block" style="letter-spacing: -0.5px;">Panel Eksekutif Yayasan</span>
                <span class="navbar-brand mb-0 h5 fw-bold text-dark d-md-none">Yayasan</span>
                
                <div class="d-flex align-items-center ms-auto">
                    
                    {{-- Badge Profil User --}}
                    <a href="{{ route('admin.profile') }}" class="text-decoration-none fw-semibold text-secondary user-profile-link d-flex align-items-center" title="Pengaturan Akun">
                        <div class="me-2 shadow-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width: 36px; height: 36px; overflow: hidden; border: 2px solid #10b981; padding: 2px;">
                            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo Yayasan" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <span class="d-none d-sm-inline me-2" style="font-size: 0.9rem; color: #334155;">{{ Auth::user()->name ?? 'Pimpinan Yayasan' }}</span>
                    </a>

                </div>
            </div>
        </nav>

        {{-- Area Konten Dinamis --}}
        <div class="container-fluid px-4 pb-5 flex-grow-1">
            @yield('content')
        </div>
        
        {{-- Footer --}}
        <footer class="mt-auto px-4 py-3 text-center text-muted small border-top bg-white">
            <span class="fw-medium">&copy; {{ date('Y') }} Sistem Informasi Manajemen</span> - SMK Swasta Budhi Darma Indrapura
        </footer>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleMenu');
    const overlay = document.getElementById('overlay');

    toggleBtn.addEventListener('click', () => {
        if (window.innerWidth <= 991.98) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>