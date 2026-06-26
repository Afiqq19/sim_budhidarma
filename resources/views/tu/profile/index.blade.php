@extends('layouts.tu')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark mb-0">Pengaturan Profil TU</h3>
        <p class="text-muted mb-0">Kelola informasi biodata, akun login, dan keamanan Anda.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-start" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div>
            <ul class="mb-0 pl-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('tu.profile.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    {{-- 🔥 JURUS SINKRONISASI: Kolom 'name' disembunyikan agar otomatis sama dengan 'nama_lengkap' 🔥 --}}
    <input type="hidden" name="name" id="hiddenName" value="{{ old('name', $user->name) }}">

    <div class="row g-4">
        {{-- KOLOM KIRI: Biodata Pegawai --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-vcard me-2"></i>Biodata Pegawai</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="text-center mb-4">
                        {{-- 🔥 GAMBAR AVATAR OTOMATIS BERUBAH 🔥 --}}
                        <img src="{{ asset($avatar) }}" alt="Avatar Pegawai" class="rounded-circle shadow-sm mb-3 border border-3 border-white bg-light" style="width: 100px; height: 100px; object-fit: cover;">
                        
                        <h5 class="fw-bold mb-1" id="displayNama">{{ $pegawai->nama_lengkap ?? $user->name }}</h5>
                        <div class="text-muted small">Jabatan: <span class="badge bg-secondary text-uppercase">{{ $pegawai->jabatan ?? 'Staf Tata Usaha' }}</span></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold small text-muted">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nip" class="form-control rounded-3" value="{{ old('nip', $pegawai->nip ?? '') }}" placeholder="Kosongkan jika tidak ada">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap (Sesuai SK)</label>
                            <input type="text" name="nama_lengkap" id="inputNamaLengkap" oninput="syncNama()" class="form-control rounded-3" value="{{ old('nama_lengkap', $pegawai->nama_lengkap ?? $user->name) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold small text-muted">Jenis Kelamin</label>
                            <select name="jk" class="form-select rounded-3" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jk', $pegawai->jk ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk', $pegawai->jk ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">No. Handphone / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control rounded-3" value="{{ old('no_hp', $pegawai->no_hp ?? '') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control rounded-3" rows="2">{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Akun & Keamanan --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-shield-lock-fill me-2"></i>Akun & Keamanan</h5>
                </div>
                <div class="card-body p-4 pt-0 d-flex flex-column">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Username Login</label>
                        <input type="text" name="username" class="form-control bg-light border-primary rounded-3" value="{{ old('username', $user->username) }}" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Email Pribadi</label>
                        <input type="email" name="email" class="form-control bg-light rounded-3" value="{{ old('email', $user->email) }}">
                    </div>

                    <hr class="text-muted opacity-25 mb-4">

                    <div class="alert alert-warning border-0 shadow-sm mb-4 small py-2">
                        <i class="bi bi-info-circle-fill me-1"></i> Biarkan kosong jika <strong>tidak ingin</strong> mengubah password.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Password Baru</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Ulangi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ketik ulang password baru">
                    </div>

                    <div class="d-grid mt-auto pt-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm rounded-3">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Script Otomatis Ganti Nama di Layar --}}
<script>
    function syncNama() {
        let namaLengkap = document.getElementById('inputNamaLengkap').value;
        document.getElementById('hiddenName').value = namaLengkap;
        if(namaLengkap.trim() !== '') {
            document.getElementById('displayNama').innerText = namaLengkap;
        } else {
            document.getElementById('displayNama').innerText = 'Mengetik nama...';
        }
    }
</script>
@endsection