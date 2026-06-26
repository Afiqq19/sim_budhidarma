@extends('layouts.admin')

@section('content')
{{-- Header Halaman --}}
<div class="mb-4">
    <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Pengaturan Profil Saya</h3>
    <p class="text-secondary mb-0" style="font-size: 0.95rem;">Kelola informasi nama, username, email, dan keamanan password akun Anda.</p>
    
    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 border-start border-danger border-4 shadow-sm rounded-3 mt-3 mb-0 py-2">
            <ul class="mb-0 ps-3 py-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<form action="{{ route('admin.profile.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- ================= SISI KIRI: RINGKASAN AKUN ================= --}}
        <div class="col-md-4">
            <div class="card bg-white rounded-4 overflow-hidden h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                {{-- Background Header Profil Hijau Elegan --}}
                <div class="py-5 position-relative" style="height: 130px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                </div>
                
                <div class="card-body mt-n5 pb-4 px-4 text-center">
                    {{-- Avatar --}}
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle border border-4 border-white bg-white d-flex align-items-center justify-content-center" style="width: 110px; height: 110px; padding: 8px; z-index: 2; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo Yayasan" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    </div>
                    
                    <h5 class="fw-bolder text-dark mb-1">{{ $user->name }}</h5>
                    <div class="mt-2 mb-4">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <i class="bi bi-bookmark-star-fill me-1"></i> PENGURUS YAYASAN
                        </span>
                    </div>
                    
                    <hr class="border-light mb-4">
                    
                    {{-- Info Singkat (Style Kotak Modern) --}}
                    <div class="d-flex flex-column gap-2 text-start">
                        <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                            <span class="d-block small text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase;">Username Login</span>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-fill text-success me-2 fs-5"></i>
                                <span class="text-dark fw-bold">{{ $user->username }}</span>
                            </div>
                        </div>

                        <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                            <span class="d-block small text-muted fw-semibold mb-1" style="font-size: 0.75rem; text-transform: uppercase;">Alamat Email</span>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope-fill text-success me-2 fs-5"></i>
                                <span class="text-dark fw-bold">{{ $user->email ?? 'Belum diset' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ================= SISI KANAN: FORM EDIT AKUN ================= --}}
        <div class="col-md-8">
            <div class="card bg-white rounded-4 h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                <div class="card-header bg-transparent py-4 border-bottom border-light">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-gear-fill text-success me-2"></i>Form Perbarui Akun</h6>
                </div>
                
                <div class="card-body p-4 pt-3">
                    
                    {{-- Bagian Informasi Dasar --}}
                    <h6 class="fw-bold text-muted mb-3 small text-uppercase" style="letter-spacing: 1px; font-size: 0.75rem;">Informasi Dasar</h6>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Nama Lengkap Sistem <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control py-2 px-3" value="{{ old('name', $user->name) }}" required style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Username Login <i class="bi bi-lock-fill text-muted ms-1 small"></i> <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control py-2 px-3" value="{{ old('username', $user->username) }}" required style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">Gunakan huruf kecil/angka tanpa spasi.</small>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Alamat Email <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="email" name="email" class="form-control py-2 px-3" value="{{ old('email', $user->email) }}" placeholder="admin@sekolah.com" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        </div>
                    </div>

                    {{-- Bagian Keamanan (Password) --}}
                    <h6 class="fw-bold text-muted mb-3 mt-2 small text-uppercase" style="letter-spacing: 1px; font-size: 0.75rem;">Ubah Keamanan (Password)</h6>
                    
                    <div class="alert bg-warning bg-opacity-10 border-0 border-start border-warning border-4 text-dark rounded-3 small py-3 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-circle-fill text-warning fs-5 me-2 mt-n1"></i>
                            <span>Biarkan kolom di bawah ini <strong>kosong</strong> jika Anda tidak ingin mengganti password lama Anda.</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Password Baru</label>
                            <input type="password" name="password" class="form-control py-2 px-3" placeholder="Minimal 6 karakter" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control py-2 px-3" placeholder="Ulangi password baru" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        </div>
                    </div>

                    <hr class="border-light my-4">

                    {{-- Tombol Simpan --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4 py-2 fw-semibold rounded-3 shadow-sm w-100 w-md-auto text-white transition-all">
                            <i class="bi bi-check2-circle me-2"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- SCRIPT SWEETALERT2 UNTUK POP-UP BERHASIL --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Diperbarui!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            customClass: {
                popup: 'rounded-4 shadow-sm'
            }
        });
    </script>
@endif

{{-- Tambahan Style Hover untuk Input & Tombol --}}
<style>
    .form-control:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15) !important;
        background-color: #ffffff !important;
    }
    .btn-success:hover {
        background-color: #059669 !important;
        transform: translateY(-2px);
    }
</style>
@endsection