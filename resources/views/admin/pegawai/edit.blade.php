@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pegawai
    </a>
    <h3 class="fw-bold text-dark">Edit Data Pegawai</h3>
    <p class="text-muted">Perbarui data profil atau kredensial akses milik <strong>{{ $pegawai->nama_lengkap }}</strong>.</p>
    
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- Sisi Kiri: Profil Pegawai --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge-fill me-2"></i>Biodata Pegawai</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger fw-bold fs-5">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $pegawai->nama_lengkap) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP (Opsional)</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip', $pegawai->nip) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger fw-bold fs-5">*</span></label>
                            <select name="jk" class="form-select" required>
                                <option value="L" {{ old('jk', $pegawai->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk', $pegawai->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor HP / WA <span class="text-danger fw-bold fs-5">*</span></label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pegawai->no_hp) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jabatan dalam Sekolah <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $pegawai->jabatan) }}" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger fw-bold fs-5">*</span></label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $pegawai->alamat) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Akun Sistem --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-success"><i class="bi bi-shield-lock-fill me-2"></i>Pengaturan Akun Login</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Email <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $pegawai->user->email ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-success">Username Login 🔒 <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="text" name="username" class="form-control border-success" value="{{ old('username', $pegawai->user->username ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Login</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                        <small class="text-muted">Isi hanya jika Bapak ingin mereset password staf ini.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Level Hak Akses (Role) <span class="text-danger fw-bold fs-5">*</span></label>
                        <select name="role" class="form-select border-primary" required>
                            <option value="tu" {{ old('role', $pegawai->user->role ?? '') == 'tu' ? 'selected' : '' }}>Tata Usaha </option>
                            <option value="bendahara" {{ old('role', $pegawai->user->role ?? '') == 'bendahara' ? 'selected' : '' }}>Bendahara </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark w-100 py-3 fw-bold rounded-3 shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i> PERBARUI DATA PEGAWAI
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection