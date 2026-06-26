@extends('layouts.admin')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pegawai
        </a>
        <h3 class="fw-bold text-dark mb-0">Detail Profil Pegawai</h3>
    </div>
    <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning fw-bold shadow-sm">
        <i class="bi bi-pencil-square me-2"></i> Edit Data Pegawai
    </a>
</div>

<div class="row">
    {{-- Sisi Kiri: Foto & Info Utama --}}
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
            <div class="mb-3">
                <i class="bi bi-person-badge text-secondary" style="font-size: 80px;"></i>
            </div>
            <h4 class="fw-bold mb-1 text-primary">{{ $pegawai->nama_lengkap }}</h4>
            <p class="text-muted mb-3">NIP: {{ $pegawai->nip ?? 'Tidak ada NIP' }}</p>
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill mb-3">
                Jabatan: {{ $pegawai->jabatan }}
            </span>
            <hr>
            <div class="text-start">
                <p class="small text-muted mb-1">Informasi Akun Login</p>
                <div class="mb-2"><i class="bi bi-person-fill text-primary me-2"></i> <strong>{{ $pegawai->user->username }}</strong></div>
                <div class="mb-2"><i class="bi bi-envelope-fill text-danger me-2"></i> {{ $pegawai->user->email }}</div>
                <div><i class="bi bi-key-fill text-warning me-2"></i> Role: <span class="badge bg-secondary text-uppercase">{{ $pegawai->user->role }}</span></div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Biodata Lengkap --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold border-bottom pb-2 mb-4 text-primary"><i class="bi bi-clipboard-data me-2"></i>Biodata Lengkap</h5>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                <div class="col-sm-8 fw-bold">{{ $pegawai->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">No. Handphone / WA</div>
                <div class="col-sm-8 fw-bold">
                    @if($pegawai->no_hp)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pegawai->no_hp) }}" target="_blank" class="text-decoration-none">
                            {{ $pegawai->no_hp }} <i class="bi bi-whatsapp ms-1 text-success"></i>
                        </a>
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Alamat Lengkap</div>
                <div class="col-sm-8 fw-bold">{{ $pegawai->alamat ?? 'Belum diisi' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Tanggal Bergabung</div>
                <div class="col-sm-8 fw-bold">{{ $pegawai->created_at->format('d F Y') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection