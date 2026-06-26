@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.kelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Tambah Kelas Baru</h3>
</div>

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
        {{-- 🔥 PERBAIKAN: ACTION KE KELAS STORE 🔥 --}}
        <form action="{{ route('tu.kelas.store') }}" method="POST">
            @csrf
            
            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-house-door me-2 text-primary"></i>Informasi Kelas</h5>

            <div class="mb-4">
                <label class="form-label fw-bold">Pilih Jurusan <span class="text-danger">*</span></label>
                <select name="jurusan_id" class="form-select" required>
                    <option value="">-- Pilih Program Keahlian --</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Kode Kelas <span class="text-danger">*</span></label>
                <input type="text" name="kode_kelas" class="form-control" value="{{ old('kode_kelas') }}" placeholder="Contoh: X-TKJ-1" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Nama Kelas Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas') }}" placeholder="Contoh: Kelas X Teknik Komputer Jaringan 1" required>
            </div>

            {{-- 🔥 FITUR PENETAPAN WALI KELAS 🔥 --}}
            <div class="mb-4 border-top pt-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-check me-2 text-success"></i>Penetapan Wali Kelas</h5>
                <label class="form-label fw-bold">Pilih Wali Kelas <span class="text-muted fw-normal">(Opsional)</span></label>
                <select name="walikelas_id" class="form-select border-success">
                    <option value="">-- Kosongkan (Bisa ditetapkan nanti) --</option>
                    
                    @if(isset($walikelases))
                        @foreach($walikelases as $wk)
                            @php
                                $sudahPunyaKelas = !empty($wk->kelas_id);
                            @endphp
                            <option value="{{ $wk->id }}" 
                                {{ old('walikelas_id') == $wk->id ? 'selected' : '' }}
                                {{ $sudahPunyaKelas ? 'disabled' : '' }}>
                                {{ $wk->nama_lengkap }} 
                                
                                {{ $sudahPunyaKelas ? '(❌)' : '(🟢)' }}
                            </option>
                        @endforeach
                    @endif
                    
                </select>
                <small class="text-muted">Guru yang statusnya <b>Tersedia</b> dapat ditugaskan untuk memimpin kelas ini.</small>
            </div>

            <div class="text-end mt-5 border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection