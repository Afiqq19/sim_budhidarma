<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tata Usaha - SIM Sekolah</title>
    <link rel="icon" href="{{ asset('images/logo_smk.png') }}" type="image/png">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Tema Tata Usaha: Indigo Elegan */
            --primary-color: #4f46e5;       /* Indigo 600 */
            --primary-hover: #4338ca;       /* Indigo 700 */
            --sidebar-bg: #ffffff;          /* Putih Bersih */
            --sidebar-border: #e2e8f0;      /* Garis abu-abu sangat halus */
            --sidebar-hover: #f8fafc;       /* Abu-abu super terang untuk hover menu */
            --body-bg: #f8fafc;             /* Slate 50 (Abu-abu terang) */
            --text-main: #334155;
            --text-muted: #64748b;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            margin: 0;
            overflow-x: hidden;
            color: var(--text-main);
        }
        .content-animasi {
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Custom Scrollbar Elegan */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ================= SIDEBAR KIRI (CLEAN WHITE) ================= */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: var(--transition-smooth);
            overflow-y: auto;
            border-right: 1px solid var(--sidebar-border);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed { left: -280px; }

        /* Area Konten */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: var(--transition-smooth);
            width: calc(100% - 280px);
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded { margin-left: 0; width: 100%; }

        /* Branding Sekolah di Sidebar */
        .sidebar-brand {
            padding: 25px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px dashed var(--sidebar-border);
            margin-bottom: 15px;
        }

        .logo-wrapper {
            width: 50px;
            height: 50px;
            background: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
            transition: var(--transition-smooth);
        }

        .logo-wrapper:hover { transform: translateY(-2px) scale(1.05); }

        .logo-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand-text .title {
            color: #0f172a;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .sidebar-brand-text .subtitle {
            color: var(--primary-color);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Label Kategori Menu */
        .sidebar .menu-label {
            color: #94a3b8;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 25px;
            margin-bottom: 8px;
            padding-left: 24px;
        }

        /* Desain Link Menu Kapsul */
        .sidebar a {
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 18px 12px 22px;
            display: flex;
            align-items: center;
            border-radius: 10px;
            margin: 4px 16px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .sidebar a i {
            font-size: 1.25rem;
            margin-right: 14px;
            transition: var(--transition-smooth);
            color: #94a3b8;
        }

        /* Efek Hover Menu */
        .sidebar a:hover {
            background-color: var(--sidebar-hover);
            color: var(--primary-color);
            transform: translateX(4px);
        }
        .sidebar a:hover i { color: var(--primary-color); }

        /* Menu Aktif (Indigo Menyala) */
        .sidebar a.active {
            background-color: #e0e7ff; /* Indigo super muda */
            color: var(--primary-color);
            font-weight: 700;
        }
        .sidebar a.active i { color: var(--primary-color); }

        /* Indikator Garis Kiri untuk Menu Aktif */
        .sidebar a.active::before {
            content: '';
            position: absolute;
            left: -16px;
            top: 15%;
            height: 70%;
            width: 4px;
            background-color: var(--primary-color);
            border-radius: 0 4px 4px 0;
        }

        /* Tombol Logout Elegan */
        .logout-wrapper {
            padding: 20px 16px;
            border-top: 1px dashed var(--sidebar-border);
            margin-top: auto;
        }
        .logout-btn {
            background-color: #fff1f2;
            color: #e11d48;
            border: 1px dashed #fecdd3;
            transition: var(--transition-smooth);
            border-radius: 10px;
        }
        .logout-btn:hover {
            background-color: #e11d48;
            color: #ffffff;
            border-style: solid;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
        }

        /* Navbar Atas - Glassmorphism Premium */
        .top-navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--sidebar-border);
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.02);
        }

        /* Profil User */
        .user-profile-link {
            padding: 5px 16px 5px 6px;
            border-radius: 50px;
            transition: var(--transition-smooth);
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            cursor: pointer;
        }
        .user-profile-link:hover {
            border-color: var(--primary-color);
            background-color: #f8fafc;
        }

        .avatar-img {
            width: 36px; 
            height: 36px; 
            overflow: hidden; 
            border: 2px solid var(--primary-color);
            padding: 2px;
            background: #fff;
        }

        /* Tombol Toggle Mobile */
        .btn-toggle {
            font-size: 1.5rem;
            cursor: pointer;
            color: #475569;
            padding: 6px 10px;
            border-radius: 8px;
            transition: 0.2s;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }
        .btn-toggle:hover {
            background-color: var(--sidebar-hover);
            color: var(--primary-color);
        }

        /* Responsive Overlay */
        @media (max-width: 991.98px) {
            .sidebar { left: -280px; }
            .sidebar.active { left: 0; }
            .sidebar.collapsed { left: 0; }
            .main-content { margin-left: 0; width: 100%; }
            .main-content.expanded { margin-left: 0; }
            
            .overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(4px);
                z-index: 998;
                top: 0;
                left: 0;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .overlay.active {
                display: block;
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <div class="overlay" id="overlay"></div>

    <div class="d-flex">
        {{-- ================= SIDEBAR MENU TU ================= --}}
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/logo_smk.png') }}" alt="Logo Sekolah" onerror="this.src='https://cdn-icons-png.flaticon.com/512/8074/8074804.png'">
                </div>
                <div class="sidebar-brand-text">
                    <span class="title">TATA USAHA</span>
                    <span class="subtitle">SMK Budhi Darma</span>
                </div>
            </div>

            <div class="flex-grow-1 overflow-y-auto pb-3">
                <div class="menu-label">Pemantauan</div>
                <a href="{{ url('tu/dashboard') }}" class="{{ request()->is('tu/dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard Utama
                </a>
                
                <div class="menu-label">Manajemen Kesiswaan</div>
                <a href="{{ route('tu.siswa.index') }}" class="{{ request()->is('tu/siswa*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Kelola Siswa
                </a>
                <a href="{{ route('tu.kelas.index') }}" class="{{ request()->is('tu/kelas*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3-fill"></i> Kelola Kelas
                </a>
                <a href="{{ route('tu.jurusan.index') }}" class="{{ request()->is('tu/jurusan*') ? 'active' : '' }}">
                    <i class="bi bi-bookmark-star-fill"></i> Kelola Jurusan
                </a>

                <div class="menu-label">Akademik & Kurikulum</div>
                <a href="{{ route('tu.tahun-ajaran.index') }}" class="{{ request()->is('tu/tahun-ajaran*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-range-fill"></i> Tahun Ajaran
                </a>
                <a href="{{ route('tu.mapel.index') }}" class="{{ request()->is('tu/mapel*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill"></i> Mata Pelajaran
                </a>
                <a href="{{ route('tu.setting.mapel.index') }}" class="{{ request()->is('tu/setting-mapel*') ? 'active' : '' }}">
                    <i class="bi bi-nut-fill"></i> Setting Mapel
                </a>

                <div class="menu-label">Sirkulasi & Administrasi</div>
                <a href="{{ route('tu.kenaikan-kelas.index') }}" class="{{ request()->is('tu/kenaikan-kelas*') ? 'active' : '' }}">
                    <i class="bi bi-capslock-fill"></i> Kenaikan Kelas
                </a>
                <a href="{{ route('tu.alumni.index') }}" class="{{ request()->is('tu/alumni*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard-fill"></i> Arsip Alumni
                </a>
            </div>

            <div class="logout-wrapper">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn logout-btn w-100 fw-bold py-2 d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-arrow-right me-2 fs-5"></i> Keluar Sistem
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
                    <span class="navbar-brand mb-0 h5 fw-bolder text-dark d-none d-md-block" style="letter-spacing: -0.5px;">Biro Administrasi Pusat</span>
                    <span class="navbar-brand mb-0 h5 fw-bolder text-dark d-md-none">TU Panel</span>

                    <div class="d-flex align-items-center ms-auto">
                        @php
                            $pegawaiLayout = \App\Models\Pegawai::where('user_id', Auth::id())->first();
                            $avatarLayout = ($pegawaiLayout && $pegawaiLayout->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                        @endphp
                        <a href="{{ url('tu/profile') }}" class="text-decoration-none text-secondary user-profile-link d-flex align-items-center">
                            <div class="me-0 me-sm-3 rounded-circle d-flex align-items-center justify-content-center avatar-img">
                                <img src="{{ asset($avatarLayout) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="d-none d-sm-flex flex-column justify-content-center pe-2 text-start">
                                <span class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;">{{ Auth::user()->name ?? 'Staf Administrator' }}</span>
                                <span class="fw-semibold mt-1" style="font-size: 0.65rem; line-height: 1; color: var(--primary-color); text-transform: uppercase; letter-spacing: 0.5px;">Tata Usaha</span>
                            </div>
                        </a>
                    </div>
                </div>
            </nav>

            {{-- Area Konten Dinamis --}}
            <div class="container-fluid px-4 pb-5 flex-grow-1 content-animasi">
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="mt-auto px-4 py-3 text-center text-muted small border-top bg-white">
            <span class="fw-medium">&copy; {{ date('Y') }} Sistem Informasi Manajemen</span> - SMK Swasta Budhi Darma Indrapura
        </footer>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleMenu');
            const overlay = document.getElementById('overlay');

            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth <= 991.98) {
                    sidebar.classList.toggle('active');
                    setTimeout(() => overlay.classList.toggle('active'), 10); 
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Auto-close sidebar on mobile when clicking a link
            const navLinks = document.querySelectorAll('.sidebar a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 991.98) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>