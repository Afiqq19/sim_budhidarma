@extends('layouts.walikelas')

@section('content')
<div class="container-fluid px-2">
    {{-- ========================================================================= --}}
    {{-- 1. HEADER HALAMAN & ALERT NOTIFIKASI --}}
    {{-- ========================================================================= --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge-fill text-primary me-2"></i>Pengaturan Profil & Akun</h3>
            <p class="text-muted mb-0 mt-1">Kelola informasi biodata, kontak, dan keamanan kata sandi Anda dalam satu halaman.</p>
        </div>
    </div>

    {{-- ALERT SUKSES --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center p-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
        <div>
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ALERT ERROR VALIDASI (Global) --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 p-3 mb-4" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <strong>Terdapat kesalahan input:</strong>
        </div>
        <ul class="mb-0 small ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        {{-- ========================================================================= --}}
        {{-- 2. KOLOM KIRI: KARTU IDENTITAS SINGKAT (Dibuat Sticky agar nempel saat scroll) --}}
        {{-- ========================================================================= --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden position-sticky" style="top: 30px;">
                <div class="bg-primary" style="height: 120px;"></div>
                <div class="card-body mt-n5 pb-4">
                    
                    {{-- LOGIKA AVATAR PINTAR WALI KELAS --}}
                    @php
                        $avatarGuru = ($waliKelas && $waliKelas->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                    @endphp
                    <div class="mb-3">
                        <div class="rounded-circle border border-4 border-white shadow-sm bg-light d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; margin-top: -60px; overflow: hidden;">
                            <img src="{{ asset($avatarGuru) }}" alt="Avatar Wali Kelas" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $waliKelas->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email ?? $waliKelas->nrg }}</p>
                    
                    <div class="d-grid px-4 mt-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary py-2 rounded-pill fw-bold border border-primary border-opacity-25">
                            <i class="bi bi-person-check-fill me-2"></i> Wali Kelas: {{ $waliKelas->kelas->nama_kelas ?? 'Belum Ditugaskan' }}
                        </span>
                    </div>

                    <div class="row mt-4 small text-start px-3">
                        <div class="col-6 mb-2">
                            <span class="text-muted d-block">NRG</span>
                            <span class="fw-bold text-dark">{{ $waliKelas->nrg }}</span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="text-muted d-block">No. HP</span>
                            <span class="fw-bold text-dark">{{ $waliKelas->no_hp ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- 3. KOLOM KANAN: NAV TABS (FORM EDIT & PASSWORD) --}}
        {{-- ========================================================================= --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-0 rounded-top-4">
                    {{-- Navigasi Tabs Modern --}}
                    <ul class="nav nav-tabs nav-justified border-bottom-0 " id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-tl-4 py-3 fw-bold active-profil-tab" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-pane" type="button" role="tab">
                                <i class="bi bi-pencil-square me-2"></i>Perbarui Biodata & Kontak
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-tr-4 py-3 fw-bold text-danger active-password-tab" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-pane" type="button" role="tab">
                                <i class="bi bi-key-fill me-2"></i>Keamanan (Ubah Password)
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-4 p-md-5 tab-content" id="profileTabsContent">
                    
                    {{-- ======================================================= --}}
                    {{-- TAB PANE 1: FORM EDIT PROFIL & KONTAK --}}
                    {{-- ======================================================= --}}
                    <div class="tab-pane fade show active" id="edit-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0 text-dark">Lengkapi Profil Anda</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25 small">
                                <i class="bi bi-lock-fill me-1"></i> Tanda * tidak dapat diubah
                            </span>
                        </div>

                        <form action="{{ route('walikelas.profil.update') }}" method="POST" onsubmit="return confirm('Simpan perubahan data Anda?');">
                            @csrf
                            @method('PUT')

                            {{-- A. DATA TERKUNCI --}}
                            <div class="row bg-light p-3 rounded-4 mb-4 mx-0 border">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">NRG <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                        <input type="text" class="form-control text-muted bg-white border-start-0 focus-ring-none" value="{{ $waliKelas->nrg }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                        <input type="text" class="form-control text-muted bg-white border-start-0 focus-ring-none" value="{{ $waliKelas->nama_lengkap }}" readonly>
                                    </div>
                                </div>
                               
                            </div>

                            {{-- B. DATA BISA DIUBAH --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Jenis Kelamin</label>
                                    <select name="jk" class="form-select @error('jk') is-invalid @enderror">
                                        <option value="L" {{ $waliKelas->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="P" {{ $waliKelas->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Nomor HP / WhatsApp</label>
                                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $waliKelas->no_hp) }}" required placeholder="Contoh: 08123456789">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold small">Email Sistem (Login)</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="email@sekolah.sch.id">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold small">Alamat Lengkap Domisili</label>
                                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap saat ini...">{{ old('alamat', $waliKelas->alamat) }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                    <i class="bi bi-save-fill me-2"></i>Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- ======================================================= --}}
                    {{-- TAB PANE 2: FORM UBAH PASSWORD --}}
                    {{-- ======================================================= --}}
                    <div class="tab-pane fade" id="password-pane" role="tabpanel" tabindex="0">
                        <div class="text-center mb-5 mt-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-shield-lock-fill" style="font-size: 2.5rem;"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-1">Pembaruan Kata Sandi</h4>
                            <p class="text-muted mx-auto" style="max-width: 400px;">Gunakan kombinasi minimal 8 karakter dengan campuran huruf, angka, dan simbol untuk keamanan optimal.</p>
                        </div>

                        <form action="{{ route('walikelas.password.update') }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengubah password? Anda akan diminta login ulang.');">
                            @csrf
                            @method('PUT')

                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-muted">Password Saat Ini (Lama)</label>
                                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-unlock"></i></span>
                                            <input type="password" name="current_password" class="form-control border-start-0" required placeholder="Masukkan password sekarang">
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4 opacity-25">
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-danger">Password Baru</label>
                                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-white border-end-0 text-danger"><i class="bi bi-key-fill"></i></span>
                                            <input type="password" name="password" class="form-control border-start-0" required placeholder="Minimal 8 karakter">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-danger">Konfirmasi Password Baru</label>
                                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-white border-end-0 text-danger"><i class="bi bi-check-circle-fill"></i></span>
                                            <input type="password" name="password_confirmation" class="form-control border-start-0" required placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid mt-5">
                                        <button type="submit" class="btn btn-danger btn-lg rounded-3 fw-bold shadow">
                                            <i class="bi bi-floppy-fill me-2"></i>Perbarui Kata Sandi Saya
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT UNTUK MENJAGA TAB TETAP AKTIF SAAT ERROR --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Perhatikan penggunaan huruf kecil 'let'
        // 2. Dibungkus tanda kutip "" agar linter VS Code tidak error
        let hasPasswordError = "{{ ($errors->has('current_password') || $errors->has('password')) ? 'true' : 'false' }}" === "true";
        let hasAnyError = "{{ $errors->any() ? 'true' : 'false' }}" === "true";

        // Logika Javascript murni
        if (hasPasswordError) {
            // Jika ada error di password, aktifkan tab password
            let triggerEl = document.querySelector('#password-tab');
            if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
        } else if (hasAnyError) {
            // Jika ada error validasi lain (kontak), aktifkan tab edit
            let triggerEl = document.querySelector('#edit-tab');
            if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
        }
    });
</script>

{{-- STYLING KHUSUS UNTUK TABS AGAR MIRIP GAMBAR --}}
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        background: #f8fafc;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        background: #f1f5f9;
        color: #212529;
    }
    .nav-tabs .nav-link.active.active-profil-tab {
        color: #0d6efd !important;
        background: white !important;
        border-bottom: 3px solid #0d6efd !important;
    }
    .nav-tabs .nav-link.active.active-password-tab {
        color: #dc3545 !important;
        background: white !important;
        border-bottom: 3px solid #dc3545 !important;
    }
    .card-header .nav-tabs {
        border-bottom: none;
    }
    .mt-n5 {
        margin-top: -3rem !important;
    }
    /* Rounded corner khusus untuk tab */
    @media (min-width: 576px) {
        .rounded-tl-4 { border-top-left-radius: 1rem !important; }
        .rounded-tr-4 { border-top-right-radius: 1rem !important; }
    }
</style>
@endsection