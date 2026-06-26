@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.jurusan.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Edit Data Jurusan</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>
            <ul class="mb-0 mt-1 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('tu.jurusan.update', $jurusan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-diagram-3 me-2 text-primary"></i>Informasi Program Keahlian</h5>

            <div class="mb-4">
                <label class="form-label fw-bold">Kode Jurusan <span class="text-danger">*</span></label>
                <input type="text" name="kode_jurusan" class="form-control" value="{{ $jurusan->kode_jurusan }}" required>
                <small class="text-muted">Contoh: TKJ</small>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Nama Jurusan <span class="text-danger">*</span></label>
                <input type="text" name="nama_jurusan" class="form-control" value="{{ $jurusan->nama_jurusan }}" required>
                <small class="text-muted">Contoh: Teknik Komputer dan Jaringan</small>
            </div>

            <div class="text-end mt-5 border-top pt-4">
                <button type="submit" class="btn btn-warning px-5 fw-bold text-dark shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection