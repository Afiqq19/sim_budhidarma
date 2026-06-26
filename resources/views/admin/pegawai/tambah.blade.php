@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
    <h3 class="fw-bold text-dark">Registrasi Pegawai Baru</h3>
    <p class="text-muted">Lengkapi data profil dan tentukan hak akses login untuk staf sekolah.</p>
</div>

<form action="{{ route('pegawai.store') }}" method="POST">
    @csrf
    
    <div class="alert alert-info border-0 shadow-sm small mb-4">
        <i class="bi bi-info-circle-fill me-2"></i> Kolom dengan tanda bintang merah (<span class="text-danger fw-bold fs-5">*</span>) <strong>wajib diisi</strong>.
    </div>

    <div class="row g-4">
        {{-- BAGIAN 1: PROFIL BIODATA --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge-fill me-2"></i>Biodata Pegawai</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Lengkap Pegawai <span class="text-danger fw-bold fs-5">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Budi Sudarsono, S.Kom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP (Opsional)</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" placeholder="Masukkan NIP jika ada">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger fw-bold fs-5">*</span></label>
                            <select name="jk" class="form-select" required>
                                <option value="">- Pilih Jenis Kelamin -</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp/HP <span class="text-danger fw-bold fs-5">*</span></label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jabatan Struktural <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="Contoh: Kepala Tata Usaha / Staf Keuangan" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Alamat Rumah <span class="text-danger fw-bold fs-5">*</span></label>
                        <textarea name="alamat" class="form-control" rows="4" placeholder="Alamat lengkap tempat tinggal sekarang" required>{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: KREDENSIAL LOGIN --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-success"><i class="bi bi-shield-lock-fill me-2"></i>Pengaturan Akun Login</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Email <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@sekolah.com" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-success">Username 🔒 <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="text" name="username" class="form-control border-success @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Username unik" required>
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Login <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Level Hak Akses (Role) <span class="text-danger fw-bold fs-5">*</span></label>
                        <select name="role" class="form-select border-primary fw-bold text-primary" required>
                            <option value="">- Pilih Role -</option>
                            <option value="tu">TATA USAHA (Akses Akademik)</option>
                            <option value="bendahara">BENDAHARA (Akses Keuangan)</option>
                        </select>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow">
                        <i class="bi bi-save-fill me-2"></i> SIMPAN DATA & AKTIFKAN AKUN
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection