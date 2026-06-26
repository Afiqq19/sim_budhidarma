@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Tambah Pegawai (Bendahara) Baru</h3>
</div>

{{-- PENANGKAP ERROR (Wajib Ada) --}}
@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Penyimpanan Gagal!</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf
            <div class="alert alert-info border-0 mb-4 shadow-sm">
                <i class="bi bi-info-circle-fill me-2"></i> Password default akun baru adalah: <b>password123</b>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama tanpa gelar" value="{{ old('nama_lengkap') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control bg-light text-primary fw-bold" value="Bendahara Sekolah" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-danger">Akses Login (Role)</label>
                    
                    {{-- Trik Asli yang disembunyikan agar data PASTI terkirim --}}
                    <input type="hidden" name="role_akun" value="bendahara">
                    
                    {{-- Tampilan Palsu agar kelihatan terkunci --}}
                    <select class="form-select bg-light fw-bold text-danger" disabled>
                        <option selected>Bendahara / Keuangan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Pribadi <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jk" class="form-select" required>
                        <option value="">-- Pilih Gender --</option>
                        <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">No. HP/WA</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NIP (Opsional)</label>
                    <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika tidak ada" value="{{ old('nip') }}">
                </div>
            </div>

            <div class="text-end mt-4 border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Simpan & Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection