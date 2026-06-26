@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.jurusan.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Tambah Jurusan Baru</h3>
</div>

{{-- INI DIA TOA PENANGKAP ERROR-NYA --}}
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
        <form action="{{ route('tu.jurusan.store') }}" method="POST">
            @csrf

            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-diagram-3 me-2 text-primary"></i>Informasi Program Keahlian</h5>

            <div class="mb-4">
                <label class="form-label fw-bold">Kode Jurusan <span class="text-danger">*</span></label>
                <input type="text" name="kode_jurusan" class="form-control" value="{{ old('kode_jurusan') }}" placeholder="Contoh: TKJ" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Nama jurusan <span class="text-danger">*</span></label>
                <input type="text" name="nama_jurusan" class="form-control" value="{{ old('nama_jurusan') }}" placeholder="Contoh: Teknik Komputer dan Jaringan" required>
            </div>

            <div class="text-end mt-5 border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Jurusan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection