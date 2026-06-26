@extends('layouts.siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Perbarui Profil</h4>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25">
                        <i class="bi bi-lock-fill me-1"></i> Tanda * tidak dapat diubah
                    </span>
                </div>
                
                <form action="{{ route('siswa.profil.update') }}" method="POST">
                    @csrf
                    
                    {{-- =============================================== --}}
                    {{-- 1. DATA TERKUNCI (HANYA NISN DAN NAMA) --}}
                    {{-- =============================================== --}}
                    <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">1. Data Induk Sistem (Terkunci)</h6>
                    <div class="row bg-light p-3 rounded-4 mb-4 mx-0 border border-secondary border-opacity-25">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label small fw-bold text-muted">NISN <span class="text-danger fs-6">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-25"><i class="bi bi-lock text-muted"></i></span>
                                <input type="text" class="form-control text-muted border-secondary border-opacity-25 bg-white" value="{{ $siswa->nisn }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger fs-6">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-25"><i class="bi bi-lock text-muted"></i></span>
                                <input type="text" class="form-control text-muted border-secondary border-opacity-25 bg-white" value="{{ $siswa->nama_lengkap }}" disabled>
                            </div>
                        </div>
                    </div>

                    {{-- =============================================== --}}
                    {{-- 2. DATA BIODATA (DAPAT DIUBAH) --}}
                    {{-- =============================================== --}}
                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-4">2. Biodata & Informasi Kontak (Dapat Diubah)</h6>
                    <div class="row px-2">
                        
                        {{-- Jenis Kelamin --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-select border-primary bg-primary bg-opacity-10 shadow-sm">
                                <option value="L" {{ $siswa->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ $siswa->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Tempat Lahir --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $siswa->tempat_lahir }}" placeholder="Contoh: Medan">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $siswa->tanggal_lahir }}">
                        </div>

                        {{-- No HP Siswa --}}
                        <div class="col-md-6 mb-3 mt-2">
                            <label class="form-label small fw-bold">Nomor WhatsApp Siswa</label>
                            <input type="text" name="no_hp_siswa" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $siswa->no_hp_siswa }}" placeholder="Contoh: 08123456789">
                        </div>

                        {{-- Alamat --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Alamat Lengkap Tempat Tinggal</label>
                            <textarea name="alamat" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" rows="3" placeholder="Masukkan alamat domisili saat ini...">{{ $siswa->alamat }}</textarea>
                        </div>

                        {{-- Nama Wali --}}
                        <div class="col-md-6 mb-3 mt-2">
                            <label class="form-label small fw-bold">Nama Orang Tua / Wali</label>
                            <input type="text" name="nama_orang_tua" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $siswa->nama_orang_tua }}" placeholder="Nama Ayah/Ibu/Wali">
                        </div>

                        {{-- No HP Wali --}}
                        <div class="col-md-6 mb-3 mt-2">
                            <label class="form-label small fw-bold">Nomor HP Orang Tua / Wali</label>
                            <input type="text" name="no_hp_ortu" class="form-control border-primary bg-primary bg-opacity-10 shadow-sm" value="{{ $siswa->no_hp_ortu }}" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="{{ route('siswa.profil') }}" class="btn btn-light rounded-pill px-4 text-muted fw-bold border shadow-sm">Batal</a>
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