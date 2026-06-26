@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.mapel.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <h3 class="fw-bold text-dark">Tambah Mata Pelajaran</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('tu.mapel.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kode Mapel (Singkatan)</label>
                    <input type="text" name="kode_mapel" class="form-control" placeholder="Contoh: PAI, MTK, PROD-TKJ" value="{{ old('kode_mapel') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" class="form-control" placeholder="Contoh: Pendidikan Agama Islam" value="{{ old('nama_mapel') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kelompok Kurikulum SMK</label>
                    <select name="kelompok" class="form-select" required>
                        <option value="">-- Pilih Kelompok --</option>
                        <option value="A" {{ old('kelompok') == 'A' ? 'selected' : '' }}>Kelompok A (Muatan Nasional)</option>
                        <option value="B" {{ old('kelompok') == 'B' ? 'selected' : '' }}>Kelompok B (Muatan Kewilayahan)</option>
                        <option value="C" {{ old('kelompok') == 'C' ? 'selected' : '' }}>Kelompok C (Muatan Peminatan Kejuruan)</option>
                        <option value="C2" {{ old('kelompok') == 'C2' ? 'selected' : '' }}>Kelompok C2 (Dasar Program Keahlian)</option>
                        <option value="C3" {{ old('kelompok') == 'C3' ? 'selected' : '' }}>Kelompok C3 (Kompetensi Keahlian)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nilai KKM Minimal</label>
                    <input type="number" name="kkm" class="form-control" value="{{ old('kkm', 75) }}" min="0" max="100" required>
                    <small class="text-muted">Default adalah 75</small>
                </div>
            </div>

            <div class="text-end mt-3 border-top pt-3">
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Mapel</button>
            </div>
        </form>
    </div>
</div>
@endsection