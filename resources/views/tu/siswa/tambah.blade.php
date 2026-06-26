@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('tu.siswa.index') }}" class="btn btn-white border shadow-sm d-flex align-items-center justify-content-center rounded-3 hover-btn-back bg-white" style="width: 42px; height: 42px; transition: all 0.2s;" title="Kembali ke Data Siswa">
        <i class="bi bi-arrow-left fs-5 text-secondary"></i>
    </a>
    <div>
        <h3 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px;">Tambah Siswa Baru</h3>
        <p class="text-secondary mb-0" style="font-size: 0.9rem;">Lengkapi biodata di bawah untuk mendaftarkan siswa ke dalam sistem.</p>
    </div>
</div>

{{-- ================= ALERT ERROR VALIDASI ================= --}}
@if($errors->any())
    <div class="alert bg-danger bg-opacity-10 border-0 border-start border-danger border-4 text-danger shadow-sm rounded-3 d-flex align-items-start p-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-3 fs-5 mt-1"></i>
        <div>
            <strong class="fw-bold">Gagal menyimpan data!</strong>
            <ul class="mb-0 mt-2 ps-3" style="font-size: 0.9rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ================= KARTU FORMULIR ================= --}}
<div class="card border-0 rounded-4 bg-white mb-5" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('tu.siswa.store') }}" method="POST">
            @csrf
            
            {{-- Info Pembuatan Akun Otomatis --}}
            <div class="alert bg-primary bg-opacity-10 border-0 border-start border-primary border-4 text-dark rounded-3 py-3 px-4 mb-4 d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                <div style="font-size: 0.9rem;">
                    Sistem akan otomatis membuatkan <strong>Akun Login</strong> untuk siswa ini.<br>
                    <span class="text-muted">Username = <strong>NISN</strong> | Password default = <code class="bg-white px-2 py-1 rounded fw-bold border text-primary">budhidarma123</code></span>
                </div>
            </div>

            {{-- SECTION 1: DATA PRIBADI --}}
            <div class="d-flex align-items-center mb-4 mt-2">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-badge fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Data Pribadi & Akademik</h5>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">NISN <span class="text-danger">*</span></label>
                    <input type="number" name="nisn" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" placeholder="Masukkan 10 Digit NISN" value="{{ old('nisn') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" placeholder="Contoh: Budi Santoso" value="{{ old('nama_lengkap') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Pilih Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select form-select-lg bg-light fs-6 border-light shadow-none focus-ring-primary cursor-pointer" required>
                        <option value="" disabled selected>-- Pilih Kelas Penempatan --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ $k->kode_kelas }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jk" class="form-select form-select-lg bg-light fs-6 border-light shadow-none focus-ring-primary cursor-pointer" required>
                        <option value="" disabled selected>-- Pilih Gender --</option>
                        <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" placeholder="Kota Lahir" value="{{ old('tempat_lahir') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" value="{{ old('tanggal_lahir') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Email Akses <span class="text-muted fw-normal text-capitalize">(Opsional)</span></label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" placeholder="siswa@gmail.com" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-success small text-uppercase" style="letter-spacing: 0.5px;"><i class="bi bi-whatsapp me-1"></i> No. WA Siswa <span class="text-muted fw-normal text-capitalize">(Opsional)</span></label>
                    <input type="text" name="no_hp_siswa" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-success" placeholder="0812xxxx" value="{{ old('no_hp_siswa') }}">
                </div>
            
                <div class="col-md-12">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" rows="2" placeholder="Tuliskan alamat domisili siswa saat ini...">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <hr class="border-light my-5">

            {{-- SECTION 2: DATA ORANG TUA --}}
            <div class="d-flex align-items-center mb-4">
                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="bi bi-telephone-fill fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Data Orang Tua / Wali</h5>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Nama Orang Tua / Wali</label>
                    <input type="text" name="nama_orang_tua" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" placeholder="Nama ayah, ibu, atau wali" value="{{ old('nama_orang_tua') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">No. Telp / WA Darurat</label>
                    <input type="text" name="no_hp_ortu" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary" placeholder="0812xxxx" value="{{ old('no_hp_ortu') }}">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-3 border-top border-light">
                <a href="{{ route('tu.siswa.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary rounded-pill hover-btn-back">Batal</a>
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill hover-lift">
                    <i class="bi bi-check2-circle me-2"></i> Simpan & Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Custom CSS Khusus Halaman Form Ini --}}
<style>
    /* Efek garis tepi (border) saat inputan diklik */
    .focus-ring-primary:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        background-color: #ffffff !important;
    }
    .focus-ring-success:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
        background-color: #ffffff !important;
    }
    
    /* Efek Hover Tombol */
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
    .hover-btn-back:hover { background-color: #f1f5f9 !important; color: #0f172a !important; }
    
    /* Style Label lebih bersih */
    .form-label { margin-bottom: 0.4rem; }
</style>
@endsection