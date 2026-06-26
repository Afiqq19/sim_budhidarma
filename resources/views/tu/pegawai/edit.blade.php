@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Edit Data Pegawai</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Pribadi & Jabatan</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $pegawai->nama_lengkap }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NIP (Opsional)</label>
                    <input type="text" name="nip" class="form-control" value="{{ $pegawai->nip }}">
                </div>
            </div>

            <div class="row border-top pt-3 mt-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jabatan Pegawai</label>
                    <input type="text" name="jabatan" class="form-control bg-light fw-bold text-primary" value="{{ $pegawai->jabatan }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-danger">Hak Akses Sistem (Role)</label>
                    <select name="role_akun" class="form-select bg-light fw-bold text-danger" style="pointer-events: none;">
                        <option value="{{ $pegawai->user->role }}" selected>{{ ucfirst($pegawai->user->role) }}</option>
                    </select>
                </div>
            </div>

            <h5 class="fw-bold mb-4 mt-4 border-bottom pb-2"><i class="bi bi-shield-lock me-2 text-primary"></i>Akun Login & Kontak</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Username Login</label>
                    <input type="text" name="username" class="form-control" value="{{ $pegawai->user->username }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Pribadi</label>
                    <input type="email" name="email" class="form-control" value="{{ $pegawai->user->email }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jenis Kelamin</label>
                    <select name="jk" class="form-select" required>
                        <option value="L" {{ $pegawai->jk == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $pegawai->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">No. Handphone / WA</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $pegawai->no_hp }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ $pegawai->alamat }}</textarea>
                </div>
            </div>

            <div class="text-end mt-4 border-top pt-4">
                <button type="submit" class="btn btn-warning px-5 fw-bold text-dark shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection