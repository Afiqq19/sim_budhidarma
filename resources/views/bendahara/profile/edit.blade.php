@extends('layouts.bendahara')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge text-primary me-2"></i>Pengaturan Profil</h3>
        <p class="text-muted mb-0">Kelola informasi data diri, akun login, dan keamanan password Anda.</p>
    </div>
</div>

{{-- Alert Notifikasi Sukses --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center" role="alert">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Alert Error Validasi --}}
@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-start">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div>
            <ul class="mb-0 pl-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('profil.update') }}" method="POST">
    @csrf
    @method('PUT')

    {{-- 🔥 JURUS RAHASIA: Kolom 'name' disembunyikan agar otomatis tersinkronisasi 🔥 --}}
    <input type="hidden" name="name" id="hiddenName" value="{{ old('name', $user->name) }}">

    <div class="row g-4">
        {{-- KOLOM KIRI: DATA PEGAWAI --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-person-lines-fill me-2"></i>Biodata Pegawai</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    
                    {{-- 🔥 LOGIKA AVATAR OTOMATIS BERDASARKAN JENIS KELAMIN 🔥 --}}
                    @php
                        $avatar = ($pegawai && $pegawai->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                    @endphp
                    
                    <div class="text-center mb-4">
                        <img src="{{ asset($avatar) }}" alt="Avatar Bendahara" class="rounded-circle shadow-sm mb-3 border border-3 border-primary bg-light" style="width: 100px; height: 100px; object-fit: cover;">
                        
                        <h5 class="fw-bold mb-1" id="displayNama">{{ $pegawai->nama_lengkap ?? $user->name }}</h5>
                        <div class="text-muted small">Jabatan: <span class="badge bg-warning text-dark text-uppercase">{{ $pegawai->jabatan ?? 'Bendahara Sekolah' }}</span></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="small fw-bold text-muted mb-1">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nip" class="form-control rounded-3" value="{{ old('nip', $pegawai->nip ?? '') }}" placeholder="Kosongkan jika tidak ada">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-1">Nama Lengkap Sesuai SK</label>
                            <input type="text" name="nama_lengkap" id="inputNamaLengkap" oninput="syncNama()" class="form-control rounded-3" value="{{ old('nama_lengkap', $pegawai->nama_lengkap ?? $user->name) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="small fw-bold text-muted mb-1">Jenis Kelamin</label>
                            <select name="jk" class="form-select rounded-3" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jk', $pegawai->jk ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk', $pegawai->jk ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-1">No. Handphone / WA</label>
                            <input type="text" name="no_hp" class="form-control rounded-3" value="{{ old('no_hp', $pegawai->no_hp ?? '') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control rounded-3" rows="2">{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DATA AKUN (USERS) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold text-warning text-darken mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Data Akun & Keamanan</h5>
                </div>
                <div class="card-body p-4 pt-0 d-flex flex-column">
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="small fw-bold text-muted mb-1">Username Login 🔒</label>
                            <input type="text" name="username" class="form-control bg-light border-warning rounded-3" value="{{ old('username', $user->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-1">Alamat Email</label>
                            <input type="email" name="email" class="form-control bg-light rounded-3" value="{{ old('email', $user->email) }}">
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 mb-4">
                    
                    <div class="alert alert-info border-0 rounded-3 small py-2 mb-4">
                        <i class="bi bi-info-circle-fill me-1"></i>Abaikan kolom di bawah jika <strong>tidak ingin</strong> mengganti password Anda.
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="small fw-bold text-muted mb-1">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ketik ulang password">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-auto pt-3">
                        <button type="reset" class="btn btn-light border shadow-sm fw-bold rounded-3 px-4">Batal</button>
                        <button type="submit" class="btn btn-primary shadow-sm fw-bold rounded-3 px-4">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Script untuk auto-sync nama --}}
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