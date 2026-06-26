@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('walikelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Tambah Wali Kelas Baru</h3>
</div>

{{-- Alert Error --}}
@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>
            <strong>Gagal menyimpan data!</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        <div class="alert alert-info border-0 py-2 mb-4 d-inline-block">
            <small><i class="bi bi-info-circle-fill me-1"></i> Akun Login guru akan otomatis terbuat. <strong>Username = NRG</strong> dan <strong>Password = <code>guru123</code></strong></small>
        </div>

        <form action="{{ route('walikelas.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Registrasi Guru (NRG) <span class="text-danger">*</span></label>
                    <input type="number" name="nrg" class="form-control" placeholder="Wajib Diisi (Untuk Login)" value="{{ old('nrg') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NIP Guru</label>
                    <input type="number" name="nip" class="form-control" placeholder="Opsional (Jika ada)" value="{{ old('nip') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Lengkap Guru <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom" value="{{ old('nama_lengkap') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Pribadi <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Contoh: guru@gmail.com" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tugaskan Sebagai Wali Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jk" class="form-select" required>
                        <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="row border-bottom pb-4 mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label fw-bold">No. WhatsApp Aktif <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 0812xxxx" value="{{ old('no_hp') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Alamat Tempat Tinggal</label>
                    <textarea name="alamat" class="form-control" rows="1" placeholder="Opsional">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-1"></i> Simpan & Tugaskan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection