@extends('layouts.app')

@section('content')
<style>
    /* ===== BACKGROUND & OVERLAY ===== */
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        background: url("{{ asset('images/background.png') }}") center center / cover no-repeat fixed;
        background-color: #1b2735; 
    }

    /* Efek Kaca Gelap (Glassmorphism) */
    .bg-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.75) 100%);
        backdrop-filter: blur(8px); 
        -webkit-backdrop-filter: blur(8px);
        z-index: 1;
    }

    /* ===== CONTAINER & KARTU LOGIN ===== */
    .login-container {
        position: relative;
        z-index: 2;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-card {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 420px;
        padding: 3rem 2.5rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        opacity: 0;
        transform: translateY(30px);
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes slideUpFade {
        to { transform: translateY(0); opacity: 1; }
    }

    /* ===== ELEMEN FORM ===== */
    .form-label {
        font-weight: 700;
        color: #334155;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .input-group {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .input-group:focus-within {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    }

    .input-group-text {
        background: transparent;
        border: none;
        color: #64748b;
        padding-left: 1.2rem;
    }

    .form-control {
        background: transparent;
        border: none;
        padding: 0.8rem 1rem 0.8rem 0.5rem;
        color: #0f172a;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .form-control:focus {
        box-shadow: none;
        background: transparent;
    }

    /* ===== TOMBOL LOGIN ===== */
    .btn-login {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(37, 99, 235, 0.45);
        color: white;
    }

    /* Preloader */
    #preloader { 
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: #0f172a; z-index: 9999; display: flex; justify-content: center; 
        align-items: center; flex-direction: column; transition: opacity 0.5s ease; 
    }
</style>

{{-- Preloader Modern --}}
<div id="preloader">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
    <h6 class="mt-3 text-white-50 fw-bold" style="letter-spacing: 2px;">MENYIAPKAN SISTEM...</h6>
</div>

{{-- Lapisan Blur Gelap --}}
<div class="bg-overlay"></div>

{{-- Kontainer Utama --}}
<div class="login-container">
    <div class="login-card">
        
        {{-- Header & Logo --}}
        <div class="text-center mb-4 pb-2">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK" class="mb-3 rounded-circle shadow-sm" style="width: 85px; background: white; padding: 6px; border: 1px solid #e2e8f0;">
            <h3 class="fw-bolder text-dark mb-1">Otorisasi Sistem</h3>
            <p class="text-muted small mb-0">Sistem Informasi Manajemen Terpadu
                 SMK Budhi Darma Indrapura</p>
        </div>

        {{-- Alert Notifikasi Error --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small shadow-sm py-2" role="alert" style="border-left: 4px solid #ef4444;">
                <i class="bi bi-shield-x me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
            </div>
        @endif

        {{-- Form Login --}}
        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
            @csrf
            
            {{-- Input Username --}}
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control" required placeholder="Masukkan Username" autocomplete="off">
                </div>
            </div>
            
            {{-- Input Password --}}
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" required placeholder="Masukkan Password">
                    <span class="input-group-text text-muted" style="cursor: pointer; padding-right: 1.2rem;" onclick="togglePassword()">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            {{-- Tombol Login --}}
            <button type="submit" class="btn w-100 btn-login d-flex justify-content-center align-items-center gap-2 mb-2" id="btnSubmit">
                <span>Login ke Dashboard</span> <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </form>
        
    </div>
</div>

<script>
    // 1. Hilangkan Preloader saat load selesai
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        preloader.style.opacity = '0';
        setTimeout(() => { preloader.style.display = 'none'; }, 500);
    });

    // 2. Fitur Intip Password
    function togglePassword() {
        const passInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            eyeIcon.style.color = '#3b82f6'; 
        } else {
            passInput.type = 'password';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            eyeIcon.style.color = '';
        }
    }

    // 3. Animasi Tombol Loading saat disubmit
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memverifikasi...';
        btn.style.opacity = '0.9';
        btn.style.pointerEvents = 'none';
    });
</script>
@endsection