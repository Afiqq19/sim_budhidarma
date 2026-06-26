@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.walikelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Wali Kelas
    </a>
    <h3 class="fw-bold text-dark">Detail Profil Wali Kelas</h3>
</div>

<div class="row">
    {{-- Sisi Kiri: Foto & Identitas Utama --}}
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4">
            <div class="mb-3">
                <i class="bi bi-person-badge text-primary" style="font-size: 80px;"></i>
            </div>
            <h4 class="fw-bold mb-1">{{ $walikelas->nama_lengkap }}</h4>
            <p class="text-muted mb-3">NRG: <strong>{{ $walikelas->nrg }}</strong></p>
            
            <div class="mb-3">
                <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-diagram-3 me-1"></i> Wali Kelas: {{ $walikelas->kelas->nama_kelas ?? 'Belum ada kelas' }}
                </span>
            </div>
            
            <hr>
            
            <div class="text-start">
                <p class="small text-muted mb-1">Status Akun Login</p>
                <div class="d-flex align-items-center bg-light p-2 rounded">
                    <span class="badge bg-success me-2">Aktif</span>
                    <small>Username: <code class="fw-bold text-dark">{{ $walikelas->user->username ?? '-' }}</code></small>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Biodata Lengkap Guru --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold border-bottom pb-2 mb-4"><i class="bi bi-person-lines-fill me-2"></i>Informasi Pribadi & Kontak</h5>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted"><i class="bi bi-credit-card-2-front me-2"></i>Nomor Induk Pegawai (NIP)</div>
                <div class="col-sm-8 fw-bold">{{ $walikelas->nip ?? '-' }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted"><i class="bi bi-envelope me-2"></i>Email Pribadi</div>
                <div class="col-sm-8 fw-bold">{{ $walikelas->user->email ?? 'Tidak ada email' }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted"><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</div>
                <div class="col-sm-8 fw-bold">{{ $walikelas->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
            
            <div class="row mb-3 align-items-center">
                <div class="col-sm-4 text-muted"><i class="bi bi-telephone me-2"></i>No. WhatsApp Aktif</div>
                <div class="col-sm-8 fw-bold">
                    <a href="https://wa.me/{{ $walikelas->no_hp }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill">
                        <i class="bi bi-whatsapp me-1"></i> {{ $walikelas->no_hp }}
                    </a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted"><i class="bi bi-geo-alt me-2"></i>Alamat Lengkap</div>
                <div class="col-sm-8 fw-bold">{{ $walikelas->alamat ?? 'Belum diisi' }}</div>
            </div>
            
            <div class="row mb-0">
                <div class="col-sm-4 text-muted"><i class="bi bi-calendar-check me-2"></i>Tanggal Bergabung</div>
                <div class="col-sm-8 fw-bold">{{ $walikelas->created_at->format('d F Y') }}</div>
            </div>
            
        </div>
    </div>
</div>
@endsection