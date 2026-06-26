@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.kelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Edit Data Kelas</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center rounded-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
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
        <form action="{{ route('tu.kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-house-door me-2 text-primary"></i>Informasi Kelas</h5>

            <div class="mb-4">
                <label class="form-label fw-bold">Pilih Jurusan <span class="text-danger">*</span></label>
                <select name="jurusan_id" class="form-select" required>
                    <option value="">-- Pilih Program Keahlian --</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ $kelas->jurusan_id == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Kode Kelas <span class="text-danger">*</span></label>
                <input type="text" name="kode_kelas" class="form-control" value="{{ $kelas->kode_kelas }}" required>
                <small class="text-muted">Contoh: X-TKJ-1</small>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Nama Kelas Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_kelas" class="form-control" value="{{ $kelas->nama_kelas }}" required>
                <small class="text-muted">Contoh: Kelas X Teknik Komputer Jaringan 1</small>
            </div>

            {{-- 🔥 PENAMBAHAN: FITUR PENETAPAN WALI KELAS DI FORM EDIT KELAS 🔥 --}}
            <div class="mb-4 border-top pt-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-check me-2 text-success"></i>Penetapan Wali Kelas</h5>
                <label class="form-label fw-bold">Pilih Wali Kelas <span class="text-muted fw-normal">(Opsional)</span></label>
                <select name="walikelas_id" class="form-select border-success">
                    <option value="">-- Kosongkan (Cabut Wali Kelas) --</option>
                    
                    @if(isset($walikelases))
                        @foreach($walikelases as $wk)
                            @php
                                // Logika mendeteksi status guru
                                $isCurrentWali = $wk->kelas_id == $kelas->id;
                                $isOtherWali = !empty($wk->kelas_id) && $wk->kelas_id != $kelas->id;
                            @endphp
                            <option value="{{ $wk->id }}" 
                                {{ $isCurrentWali ? 'selected' : '' }}
                                {{ $isOtherWali ? 'disabled' : '' }}>
                                {{ $wk->nama_lengkap }} 
                                
                                @if($isCurrentWali)
                                    (✅)
                                @elseif($isOtherWali)
                                    (❌)
                                @else
                                    (🟢)
                                @endif
                            </option>
                        @endforeach
                    @endif
                    
                </select>
                <small class="text-muted">Pilih <b>"Kosongkan"</b> jika ingin mencabut jabatan wali kelas dari kelas ini.</small>
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