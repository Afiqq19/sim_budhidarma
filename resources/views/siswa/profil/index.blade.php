@extends('layouts.siswa')

@section('content')

@php
    // 🔥 LOGIKA TEMA STATUS, KELAS, & AVATAR SISWA 🔥
    $badgeStatus = 'bg-success text-success border-success'; 
    $teksKelas = $siswa->kelas->nama_kelas ?? '-';
    $avatarSiswa = ($siswa && $siswa->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';

    if ($siswa->status_siswa == 'Alumni') {
        $badgeStatus = 'bg-secondary text-secondary border-secondary';
        $teksKelas = 'Alumni (Eks. ' . ($siswa->kelas->nama_kelas ?? '-') . ')';
    } elseif ($siswa->status_siswa == 'Pindah') {
        $badgeStatus = 'bg-warning text-dark border-warning';
        $teksKelas = 'Status Pindah';
    }
@endphp

<div class="container-fluid px-2">
    {{-- ========================================================================= --}}
    {{-- 1. HEADER HALAMAN & ALERT NOTIFIKASI --}}
    {{-- ========================================================================= --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge-fill text-primary me-2"></i>Profil & Pengaturan Akun</h3>
            <p class="text-muted mb-0 mt-1">Kelola data akademik, biodata diri, dan keamanan akun Anda.</p>
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
        {{-- 2. KOLOM KIRI: KARTU IDENTITAS SINGKAT (Sticky) --}}
        {{-- ========================================================================= --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden position-sticky" style="top: 30px;">
                <div class="bg-primary" style="height: 120px;"></div>
                <div class="card-body mt-n5 pb-4">
                    
                    <div class="mb-3">
                        <div class="rounded-circle border border-4 border-white shadow-sm bg-light d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; margin-top: -60px; overflow: hidden;">
                            <img src="{{ asset($avatarSiswa) }}" alt="Avatar Siswa" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1">{{ $siswa->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email ?? 'Email belum diatur' }}</p>
                    
                    <div class="d-grid px-3 mt-3 gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary py-2 rounded-pill fw-bold border border-primary border-opacity-25">
                            <i class="bi bi-building me-2"></i> Kelas: {{ $teksKelas }}
                        </span>
                        <span class="badge {{ $badgeStatus }} bg-opacity-10 py-2 rounded-pill fw-bold border border-opacity-25">
                            <i class="bi bi-info-circle-fill me-2"></i> Status: {{ $siswa->status_siswa ?? 'Aktif' }}
                        </span>
                    </div>

                    <div class="row mt-4 small text-start px-3">
                        <div class="col-6 mb-2">
                            <span class="text-muted d-block">NISN</span>
                            <span class="fw-bold text-dark">{{ $siswa->nisn }}</span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="text-muted d-block">No. WhatsApp</span>
                            <span class="fw-bold text-dark">{{ $siswa->no_hp_siswa ?? '-' }}</span>
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
                    {{-- Navigasi Tabs --}}
                    <ul class="nav nav-tabs nav-justified border-bottom-0" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-tl-4 py-3 fw-bold active-profil-tab" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-pane" type="button" role="tab">
                                <i class="bi bi-pencil-square me-2"></i>Biodata & Kontak
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-tr-4 py-3 fw-bold text-danger active-password-tab" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-pane" type="button" role="tab">
                                <i class="bi bi-shield-lock-fill me-2"></i>Keamanan Akun
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

                        <form action="{{ route('siswa.profil.update') }}" method="POST" onsubmit="return confirm('Simpan perubahan biodata Anda?');">
                            @csrf
                            
                            {{-- A. DATA TERKUNCI --}}
                            <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">1. Data Induk Sistem (Terkunci)</h6>
                            <div class="row bg-light p-3 rounded-4 mb-4 mx-0 border border-secondary border-opacity-25">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold small text-muted">NISN <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                        <input type="text" class="form-control text-muted bg-white border-start-0" value="{{ $siswa->nisn }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                        <input type="text" class="form-control text-muted bg-white border-start-0" value="{{ $siswa->nama_lengkap }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i>Jika terdapat kesalahan data induk, silakan hubungi Tata Usaha Sekolah.</div>
                                </div>
                            </div>

                            {{-- B. DATA BISA DIUBAH --}}
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">2. Biodata & Informasi Kontak (Dapat Diubah)</h6>
                            <div class="row px-2">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold">Jenis Kelamin</label>
                                    <select name="jk" class="form-select border-primary bg-primary bg-opacity-10 shadow-sm">
                                        <option value="L" {{ $siswa->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                        <option value="P" {{ $siswa->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" placeholder="Contoh: Medan">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                                </div>

                                <div class="col-md-12 mb-3 mt-2">
                                    <label class="form-label small fw-bold">Nomor WhatsApp Siswa</label>
                                    <input type="text" name="no_hp_siswa" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ old('no_hp_siswa', $siswa->no_hp_siswa) }}" placeholder="Contoh: 08123456789">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label small fw-bold">Alamat Lengkap Domisili</label>
                                    <textarea name="alamat" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" rows="3" placeholder="Masukkan alamat lengkap saat ini...">{{ old('alamat', $siswa->alamat) }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3 mt-2">
                                    <label class="form-label small fw-bold">Nama Orang Tua / Wali</label>
                                    <input type="text" name="nama_orang_tua" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ old('nama_orang_tua', $siswa->nama_orang_tua) }}" placeholder="Nama Ayah/Ibu/Wali">
                                </div>
                                <div class="col-md-6 mb-3 mt-2">
                                    <label class="form-label small fw-bold">Nomor HP Orang Tua / Wali</label>
                                    <input type="text" name="no_hp_ortu" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}" placeholder="Contoh: 08123456789">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                    <i class="bi bi-save-fill me-2"></i>Simpan Perubahan
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
                            <h4 class="fw-bold text-dark mb-1">Keamanan Akun</h4>
                            <p class="text-muted mx-auto" style="max-width: 400px;">Kelola kata sandi Anda secara berkala untuk melindungi kerahasiaan data akademik.</p>
                        </div>

                        <form action="{{ route('siswa.profil.password.update') }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengubah password? Anda akan diminta login ulang setelah ini.');">
                            @csrf
                            
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
                                            {{-- Penyesuaian nama field menjadi new_password sesuai kode asli Bapak --}}
                                            <input type="password" name="new_password" class="form-control border-start-0" required placeholder="Minimal 8 karakter">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-danger">Konfirmasi Password Baru</label>
                                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                            <span class="input-group-text bg-white border-end-0 text-danger"><i class="bi bi-check-circle-fill"></i></span>
                                            {{-- Penyesuaian nama field menjadi new_password_confirmation --}}
                                            <input type="password" name="new_password_confirmation" class="form-control border-start-0" required placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid mt-5">
                                        <button type="submit" class="btn btn-danger btn-lg rounded-3 fw-bold shadow">
                                            <i class="bi bi-floppy-fill me-2"></i>Simpan Password Baru
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
        // Ambil status error dengan JS Murni agar VS Code tidak menampilkan garis merah
        let hasPasswordError = "{{ ($errors->has('current_password') || $errors->has('new_password')) ? 'true' : 'false' }}" === "true";
        let hasAnyError = "{{ $errors->any() ? 'true' : 'false' }}" === "true";

        if (hasPasswordError) {
            let triggerEl = document.querySelector('#password-tab');
            if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
        } else if (hasAnyError) {
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
    @media (min-width: 576px) {
        .rounded-tl-4 { border-top-left-radius: 1rem !important; }
        .rounded-tr-4 { border-top-right-radius: 1rem !important; }
    }
</style>
@endsection