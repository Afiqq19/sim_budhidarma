@extends('layouts.walikelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10"> {{-- Diperlebar jadi col-md-10 agar sama seperti siswa --}}
        
        {{-- ALERT ERROR VALIDASI --}}
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Gagal menyimpan perubahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Perbarui Profil</h4>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25">
                        <i class="bi bi-lock-fill me-1"></i> Tanda * tidak dapat diubah
                    </span>
                </div>
                
                <form action="{{ route('walikelas.profil.update') }}" method="POST" onsubmit="return confirm('Simpan perubahan kontak Anda? Pastikan nomor HP dan Email sudah benar agar tidak terlewat informasi penting.');">
                    @csrf
                    @method('PUT')

                    {{-- =============================================== --}}
                    {{-- 1. DATA TERKUNCI (NRG, NAMA, USERNAME) --}}
                    {{-- =============================================== --}}
                    <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">1. Data Induk Sistem (Terkunci)</h6>
                    <div class="row bg-light p-3 rounded-4 mb-4 mx-0 border border-secondary border-opacity-25">
                        
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold small text-muted">NRG <span class="text-danger fs-6">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-25"><i class="bi bi-lock text-muted"></i></span>
                                <input type="text" class="form-control text-muted border-secondary border-opacity-25 bg-white" value="{{ $waliKelas->nrg }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap <span class="text-danger fs-6">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-25"><i class="bi bi-lock text-muted"></i></span>
                                <input type="text" class="form-control text-muted border-secondary border-opacity-25 bg-white" value="{{ $waliKelas->nama_lengkap }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Username Login <span class="text-danger fs-6">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-25"><i class="bi bi-lock text-muted"></i></span>
                                <input type="text" class="form-control text-muted border-secondary border-opacity-25 bg-white" value="{{ $user->username }}" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- =============================================== --}}
                    {{-- 2. DATA KONTAK (DAPAT DIUBAH) --}}
                    {{-- =============================================== --}}
                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">2. Biodata & Informasi Kontak (Dapat Diubah)</h6>
                    <div class="row px-2">
                        
                        {{-- Jenis Kelamin (Dibuka Kuncinya) --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-select border-primary bg-primary bg-opacity-10 shadow-sm">
                                <option value="L" {{ $waliKelas->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ $waliKelas->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Email Sistem</label>
                            <input type="email" name="email" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $user->email }}" required>
                        </div>
                        
                        {{-- Nomor HP --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold small">Nomor HP / WA</label>
                            <input type="text" name="no_hp" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $waliKelas->no_hp }}" required>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="col-md-12 mb-3 mt-2">
                            <label class="form-label fw-bold small">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" rows="3" placeholder="Masukkan alamat domisili saat ini...">{{ $waliKelas->alamat }}</textarea>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="mt-4 d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="{{ route('walikelas.profil') }}" class="btn btn-light rounded-pill px-4 text-muted fw-bold border shadow-sm">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="bi bi-save-fill me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection