<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Bendahara - SIM Budhidarma</title>
    <link rel="icon" href="{{ asset('images/logo_smk.png') }}" type="image/png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== VARIABEL TEMA MODERN (CLEAN FINANCE) ===== */
        :root {
            --sidebar-bg: #ffffff;      /* Putih bersih */
            --sidebar-border: #e2e8f0;  /* Garis abu-abu halus */
            --text-color: #64748b;      /* Teks abu-abu redup */
            --text-hover: #0f172a;      /* Teks gelap saat hover */
            --active-bg: #eff6ff;       /* Biru pastel sangat muda untuk menu aktif */
            --active-text: #2563eb;     /* Biru profesional untuk teks aktif */
            --body-bg: #f8fafc;         /* Background body slate-50 */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: #334155;
            overflow-x: hidden;
        }

        /* Custom Scrollbar Minimalis */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ===== SIDEBAR STYLING ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            padding: 15px 14px;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }
        .sidebar.close {
            width: 78px;
        }

        /* Header / Logo Area */
        .sidebar .sidebar-brand {
            height: 60px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 15px;
            border-bottom: 1px dashed var(--sidebar-border);
            margin-bottom: 10px;
        }
        
        .sidebar .sidebar-brand .logo-wrapper {
            min-width: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .sidebar .sidebar-brand .logo-wrapper img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 4px;
            border: 1px solid #f1f5f9;
        }

        .sidebar .sidebar-brand .sidebar-brand-text {
            font-size: 0.85rem;
            color: #0f172a;
            font-weight: 700;
            transition: 0.3s ease;
            white-space: nowrap;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }
        .sidebar.close .sidebar-brand .sidebar-brand-text {
            opacity: 0;
            pointer-events: none;
            display: none;
        }

        /* Tombol Toggle Sidebar */
        .sidebar .sidebar-brand #btn {
            font-size: 22px;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            padding: 4px;
            border-radius: 6px;
        }
        .sidebar .sidebar-brand #btn:hover {
            color: var(--active-text);
            background: var(--active-bg);
        }
        .sidebar.close .sidebar-brand #btn {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Area Menu */
        .sidebar .nav-list {
            margin-top: 15px;
            height: calc(100% - 90px);
            display: flex;
            flex-direction: column;
            padding: 0;
        }
        .sidebar li {
            position: relative;
            list-style: none;
            margin: 4px 0;
        }

        /* Tooltip Teks Melayang (Dark theme untuk kontras) */
        .sidebar li .tooltip-text {
            position: absolute;
            top: -20px;
            left: calc(100% + 15px);
            z-index: 3;
            background: #1e293b;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            opacity: 0;
            white-space: nowrap;
            pointer-events: none;
            transition: 0s;
            color: #ffffff;
        }
        .sidebar li .tooltip-text::before {
            content: ''; position: absolute; left: -4px; top: 50%; transform: translateY(-50%);
            border-width: 5px 5px 5px 0; border-style: solid; border-color: transparent #1e293b transparent transparent;
        }
        .sidebar.close li:hover .tooltip-text {
            opacity: 1; pointer-events: auto; transition: all 0.4s ease; top: 50%; transform: translateY(-50%);
        }

        /* Link Menu Utama */
        .sidebar li a {
            display: flex;
            align-items: center;
            height: 48px;
            width: 100%;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
            position: relative;
            overflow: hidden;
        }
        .sidebar li a i {
            min-width: 50px;
            text-align: center;
            line-height: 48px;
            color: var(--text-color);
            font-size: 20px;
            transition: all 0.3s ease;
        }
        .sidebar li a .links_name {
            color: var(--text-color);
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s ease;
        }
        .sidebar.close li a .links_name {
            opacity: 0;
            pointer-events: none;
        }

        /* Efek Hover & Aktif */
        .sidebar li a:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }
        .sidebar li a:hover i,
        .sidebar li a:hover .links_name {
            color: var(--text-hover);
        }
        
        .sidebar li a.active {
            background: var(--active-bg);
        }
        .sidebar li a.active i,
        .sidebar li a.active .links_name {
            color: var(--active-text);
            font-weight: 700;
        }
        .sidebar li a.active::before {
            content: ''; position: absolute; left: 0; top: 15%; height: 70%; width: 4px; 
            background-color: var(--active-text); border-radius: 0 4px 4px 0;
        }

        /* Tombol Logout Spesial */
        .sidebar li.profile {
            margin-top: auto;
            margin-bottom: 10px;
            border-top: 1px dashed var(--sidebar-border);
            padding-top: 15px;
        }
        .sidebar li.profile a {
            background: #fff1f2;
            border: 1px dashed #fecdd3;
        }
        .sidebar li.profile a i, .sidebar li.profile a .links_name {
            color: #e11d48; 
        }
        .sidebar li.profile a:hover {
            background: #e11d48;
            border-style: solid;
            transform: translateY(-2px) translateX(0);
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
        }
        .sidebar li.profile a:hover i, .sidebar li.profile a:hover .links_name {
            color: #ffffff;
        }

        /* ===== KONTEN UTAMA ===== */
        .main-content {
            position: relative;
            background: var(--body-bg);
            min-height: 100vh;
            left: 260px;
            width: calc(100% - 260px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            flex-direction: column;
        }
        .sidebar.close ~ .main-content {
            left: 78px;
            width: calc(100% - 78px);
        }

        /* Navbar Atas */
        .top-navbar {
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .user-profile-link {
            background-color: #ffffff;
            padding: 6px 16px 6px 6px;
            border-radius: 50px;
            transition: 0.3s;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .user-profile-link:hover {
            border-color: var(--active-text);
            background-color: #f8fafc;
        }

        /* Overlays untuk HP */
        .sidebar-overlay { display: none; position: fixed; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 999; top: 0; left: 0; transition: 0.3s; }
        
        /* Responsif untuk HP */
        @media (max-width: 768px) {
            .sidebar { left: -260px; }
            .sidebar.active-mobile { left: 0; width: 260px; }
            .sidebar-overlay.active { display: block; }
            .sidebar.close ~ .main-content { left: 0; width: 100%; }
            .main-content { left: 0; width: 100%; }
            .mobile-trigger { display: block !important; }
            .top-navbar { padding: 12px 20px; }
        }
        .mobile-trigger { display: none; font-size: 1.6rem; cursor: pointer; color: #475569; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2px 8px; transition: 0.2s;}
        .mobile-trigger:hover { color: var(--active-text); background: var(--active-bg); }
    </style>
</head>
<body>

{{-- Overlay Hitam untuk HP --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- SIDEBAR --}}
<nav class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK" onerror="this.src='https://cdn-icons-png.flaticon.com/512/8074/8074804.png'">
        </div>
        <div class="sidebar-brand-text ms-2">
            BENDAHARA<br>
            <span class="text-primary fw-medium" style="letter-spacing: 0; font-size: 0.75rem;">SMK Budhi Darma</span>
        </div>
        {{-- 🔥 Tombol Toggle Sidebar (Wajib Ada) 🔥 --}}
        <i class="bi bi-list-task ms-auto pe-2" id="btn"></i>
    </div>

    <ul class="nav-list">
        {{-- Menu Dashboard --}}
        <li>
            <a href="{{ route('bendahara.dashboard') }}" class="{{ request()->routeIs('bendahara.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip-text">Dashboard Utama</span>
        </li>
        
        {{-- Menu Riwayat Transaksi --}}
        <li>
            <a href="{{ route('riwayat.index') }}" class="{{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i>
                <span class="links_name">Riwayat Transaksi</span>
            </a>
            <span class="tooltip-text">Riwayat Transaksi</span>
        </li>

        {{-- Menu Data Tagihan --}}
        <li>
            <a href="{{ route('tagihan.index') }}" class="{{ request()->routeIs('tagihan.*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i>
                <span class="links_name">Data Tagihan</span>
            </a>
            <span class="tooltip-text">Kelola Tagihan</span>
        </li>
                
        {{-- Menu Tunggakan SPP --}}
        <li>
            <a href="{{ route('tunggakan.index') }}" class="{{ request()->routeIs('tunggakan.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-octagon"></i>
                <span class="links_name">Tunggakan SPP</span>
            </a>
            <span class="tooltip-text">Pantau Tunggakan</span>
        </li>

        {{-- Menu Logout --}}
        <li class="profile">
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0 p-0">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="links_name">Keluar Sistem</span>
                </a>
            </form>
            <span class="tooltip-text">Logout</span>
        </li>
    </ul>
</nav>

{{-- KONTEN UTAMA --}}
<section class="main-content">
    {{-- Navbar Atas (Glass/White effect) --}}
    <nav class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            {{-- Tombol Toggle Khusus HP --}}
            <i class="bi bi-list mobile-trigger" id="mobileBtn"></i>
            
            @php
                $tahunAktif = \App\Models\TahunAjaran::where('is_active', 1)->first();
            @endphp
            <div class="d-none d-md-flex align-items-center bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill border border-primary border-opacity-25" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                <i class="bi bi-calendar3 me-2"></i> 
                T.A: {{ $tahunAktif ? $tahunAktif->tahun . ' (' . $tahunAktif->semester . ')' : 'Belum Diatur' }}
            </div>
        </div>

        <div>
            @php
                $pegawaiLayout = \App\Models\Pegawai::where('user_id', Auth::id())->first();
                $avatarLayout = ($pegawaiLayout && $pegawaiLayout->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
            @endphp
           <a href="{{ route('profil.edit') }}" class="text-decoration-none d-flex align-items-center user-profile-link" title="Pengaturan Profil">
                <img src="{{ asset($avatarLayout) }}" class="rounded-circle shadow-sm me-0 me-sm-2" style="width: 36px; height: 36px; object-fit: cover; border: 2px solid #2563eb; padding: 2px;">
                <div class="text-start d-none d-sm-block pe-2">
                    <div class="fw-bold mb-0 text-dark lh-1" style="font-size: 0.85rem;">{{ Auth::user()->name }}</div>
                    <span class="text-muted fw-semibold" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Bendahara</span>
                </div>
            </a>
        </div>
    </nav>

    {{-- Area Render Konten Laravel --}}
    <div class="container-fluid px-4 py-4 flex-grow-1">
        @yield('content')
    </div>
</section>

<script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let mobileBtn = document.querySelector("#mobileBtn");
    let overlay = document.querySelector("#sidebarOverlay");

    // Klik tombol garis tiga di dalam sidebar (Desktop)
    if(closeBtn){
        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            menuBtnChange();
        });
    }

    // Tombol pembuka khusus HP
    if(mobileBtn){
        mobileBtn.addEventListener("click", () => {
            sidebar.classList.add("active-mobile");
            overlay.classList.add("active");
        });
    }

    // Menutup sidebar jika background hitam (overlay) diklik di HP
    if(overlay){
        overlay.addEventListener("click", () => {
            sidebar.classList.remove("active-mobile");
            overlay.classList.remove("active");
        });
    }

    // Fungsi mengubah ikon menu
    function menuBtnChange() {
        if(sidebar.classList.contains("close")){
            closeBtn.classList.replace("bi-list-task", "bi-list"); 
        } else {
            closeBtn.classList.replace("bi-list", "bi-list-task"); 
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>