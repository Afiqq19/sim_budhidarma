@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('tu.tahun-ajaran.index') }}" class="btn btn-white border shadow-sm d-flex align-items-center justify-content-center rounded-3 hover-btn-back bg-white" style="width: 42px; height: 42px; transition: all 0.2s;" title="Kembali ke Daftar">
        <i class="bi bi-arrow-left fs-5 text-secondary"></i>
    </a>
    <div>
        <h3 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px;">Edit Tahun Ajaran</h3>
        <p class="text-secondary mb-0" style="font-size: 0.9rem;">Perbarui data periode akademik yang sudah ada.</p>
    </div>
</div>

{{-- ================= ALERT ERROR VALIDASI ================= --}}
@if($errors->any())
    <div class="alert bg-danger bg-opacity-10 border-0 border-start border-danger border-4 text-danger shadow-sm rounded-3 d-flex align-items-start p-3 mb-4" style="max-width: 800px;" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-3 fs-5 mt-1"></i>
        <div>
            <strong class="fw-bold">Gagal menyimpan perubahan!</strong>
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
<div class="card border-0 rounded-4 bg-white mb-5" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important; max-width: 800px;">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('tu.tahun-ajaran.update', $ta->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="d-flex align-items-center mb-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="bi bi-pencil-square fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Perbarui Detail Periode</h5>
            </div>

            <div class="row g-4 mb-2">
                {{-- Input Tahun Ajaran --}}
                <div class="col-12">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Tahun Ajaran <span class="text-danger">*</span></label>
                    <input type="text" name="tahun" class="form-control form-control-lg bg-light fs-6 border-light shadow-none focus-ring-primary fw-bold text-dark" value="{{ $ta->tahun }}" required>
                </div>
                
                {{-- Input Semester --}}
                <div class="col-12">
                    <label class="form-label fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.5px;">Semester <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select form-select-lg bg-light fs-6 border-light shadow-none focus-ring-primary cursor-pointer fw-bold text-dark" required>
                        <option value="Ganjil" {{ $ta->semester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                        <option value="Genap" {{ $ta->semester == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 pt-4 border-top border-light mt-5">
                <a href="{{ route('tu.tahun-ajaran.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary rounded-pill hover-btn-back">Batal & Kembali</a>
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill hover-lift">
                    <i class="bi bi-save2 me-2"></i> Simpan Perubahan
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
    
    /* Efek Hover Tombol */
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
    .hover-btn-back:hover { background-color: #f1f5f9 !important; color: #0f172a !important; }
    
    .cursor-pointer { cursor: pointer; }
</style>
@endsection