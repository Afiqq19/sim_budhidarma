@extends('layouts.app')

@section('content')
<style>
    /* Mengunci overflow untuk menghindari scroll horizontal */
    body { overflow-x: hidden; font-family: 'Inter', sans-serif; }

    /* ================== NAVBAR GLASSMORPHISM ================== */
    .navbar-glass {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .brand-logo { width: 45px; height: auto; transition: transform 0.3s ease; }
    .navbar-brand:hover .brand-logo { transform: scale(1.1) rotate(-5deg); }

    /* ================== HERO SECTION DINAMIS ================== */
    .hero-section {
        position: relative;
        background: linear-gradient(135deg, #f8f9fa 0%, #e0eafc 100%);
        min-height: 85vh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    
    /* Ornamen Latar Belakang Hero */
    .hero-section::before {
        content: ''; position: absolute; top: -10%; right: -10%;
        width: 50vw; height: 50vw;
        background: radial-gradient(circle, rgba(13,110,253,0.05) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%; z-index: 0;
    }
    .hero-section::after {
        content: ''; position: absolute; bottom: -10%; left: -5%;
        width: 40vw; height: 40vw;
        background: radial-gradient(circle, rgba(25,135,84,0.05) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%; z-index: 0;
    }

    .hero-content { position: relative; z-index: 1; }
    
    /* Logo Hero Berdenyut Halus */
    .hero-logo {
        width: 140px; 
        filter: drop-shadow(0px 15px 15px rgba(13,110,253,0.2));
        animation: floatLogo 4s ease-in-out infinite;
    }
    @keyframes floatLogo {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    /* ================== CARD FITUR ================== */
    .feature-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Animasi membal */
        position: relative;
        overflow: hidden;
    }
    .feature-card::before {
        content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 4px;
        background: linear-gradient(90deg, #0d6efd, #0dcaf0);
        transform: scaleX(0); transform-origin: left; transition: transform 0.4s ease;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }
    .feature-card:hover::before { transform: scaleX(1); }

    .icon-box {
        width: 80px; height: 80px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 20px; margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }
    .feature-card:hover .icon-box { transform: scale(1.1) rotate(5deg); }
    
    .icon-spp { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .icon-rapor { background: rgba(255, 193, 7, 0.1); color: #ffc107; }

    /* Footer Ringkas */
    .footer-custom { background: #fff; border-top: 1px solid #eee; padding: 1.5rem 0; margin-top: 5rem; }
</style>

{{-- Navbar dengan efek Blur (Glassmorphism) --}}
<nav class="navbar navbar-expand-lg navbar-light navbar-glass shadow-sm sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bolder text-primary" href="#">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK" class="brand-logo me-3 shadow-sm rounded-circle bg-white p-1">
            <span style="letter-spacing: -0.5px;">SMK Swasta Budhi Darma Indrapura</span>
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto mt-3 mt-lg-0">
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-in-right me-2 fs-5"></i> Masuk Sistem
                </a>
            </div>
        </div>
    </div>
</nav>

{{-- Hero Section --}}
<div class="hero-section">
    <div class="container text-center hero-content py-5">
        
        <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK Budhi Darma" class="hero-logo mb-4 bg-white rounded-circle p-2 shadow-sm">
        
        <h1 class="display-4 fw-bolder text-dark mb-2" style="letter-spacing: -1px;">Sistem Informasi Terpadu</h1>
        <h2 class="h3 fw-bold text-primary mb-4">SMK SWASTA BUDHI DARMA INDRAPURA</h2>
        
        <p class="lead text-secondary mb-5 mx-auto px-3" style="max-width: 800px; font-size: 1.1rem; line-height: 1.6;">
            Portal digital resmi untuk mewujudkan transparansi dan efisiensi. Nikmati kemudahan layanan Administrasi Keuangan (SPP) dan Monitoring Akademik (E-Rapor) dalam satu genggaman.
        </p>
        
        <div class="d-flex justify-content-center flex-column flex-sm-row gap-3 px-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg fw-bold d-flex align-items-center justify-content-center">
                Mulai Akses Dashboard <i class="bi bi-arrow-right-circle ms-2 fs-5"></i>
            </a>
            <a href="#modul" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill fw-bold bg-white d-flex align-items-center justify-content-center">
                <i class="bi bi-grid-1x2 me-2 fs-5"></i> Eksplorasi Modul
            </a>
        </div>
    </div>
</div>

{{-- Section Fitur Utama --}}
<div id="modul" class="container py-5 mt-4">
    <div class="text-center mb-5">
        <h6 class="text-primary fw-bold text-uppercase tracking-wide">Fasilitas Utama</h6>
        <h3 class="fw-bolder">Dua Pilar Modul Unggulan</h3>
    </div>

    <div class="row justify-content-center g-4 text-center">
        
        {{-- Card 1: Keuangan/SPP --}}
        <div class="col-md-6 col-lg-5">
            <div class="card h-100 feature-card p-4 p-lg-5 shadow-sm">
                <div class="card-body p-0">
                    <div class="icon-box icon-spp">
                        <i class="bi bi-wallet2 display-5"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-dark">Administrasi SPP</h4>
                    <p class="text-muted mb-0" style="line-height: 1.6;">
                        Sistem pencatatan terpusat untuk monitoring pembayaran uang sekolah. Akurat, terstruktur, dan sangat transparan bagi Bendahara maupun Siswa.
                    </p>
                </div>
            </div>
        </div>
        
        {{-- Card 2: E-Rapor --}}
        <div class="col-md-6 col-lg-5">
            <div class="card h-100 feature-card p-4 p-lg-5 shadow-sm">
                <div class="card-body p-0">
                    <div class="icon-box icon-rapor">
                        <i class="bi bi-journal-check display-5"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-dark">Akademik & E-Rapor</h4>
                    <p class="text-muted mb-0" style="line-height: 1.6;">
                        Kelola dan akses laporan nilai akademik siswa secara digital. Dirancang khusus untuk mempermudah tugas Wali Kelas dan Admin sekolah.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Simple Footer --}}
<footer class="footer-custom text-center mt-auto">
    <div class="container">
        <p class="mb-0 text-muted small fw-medium">
            &copy; {{ date('Y') }} SMK Swasta Budhi Darma Indrapura. All rights reserved.
        </p>
    </div>
</footer>
@endsection