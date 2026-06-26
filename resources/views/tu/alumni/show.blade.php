@extends('layouts.tu')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('tu.alumni.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Buku Induk
        </a>
        <h3 class="fw-bold text-dark mb-0">Detail Profil Arsip Siswa</h3>
    </div>
    <div class="text-end">
        @if($alumni->status_siswa == 'Alumni')
            <span class="badge bg-primary fs-5 px-4 py-3 rounded-pill shadow-sm">
                <i class="bi bi-mortarboard-fill me-2"></i> LULUS TAHUN {{ $alumni->tahun_lulus }}
            </span>
        @else
            <span class="badge bg-danger fs-5 px-4 py-3 rounded-pill shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> STATUS: PINDAH / KELUAR
            </span>
        @endif
    </div>
</div>

<div class="row">
    {{-- Sisi Kiri: Foto/Informasi Utama --}}
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
            <div class="mb-3 position-relative d-inline-block mx-auto">
                <i class="bi bi-person-circle text-secondary" style="font-size: 80px;"></i>
                <div class="position-absolute bottom-0 end-0 {{ $alumni->status_siswa == 'Alumni' ? 'bg-primary' : 'bg-danger' }} text-white rounded-circle p-2 shadow">
                    <i class="bi {{ $alumni->status_siswa == 'Alumni' ? 'bi-mortarboard-fill' : 'bi-box-arrow-right' }}"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-primary">{{ $alumni->nama_lengkap }}</h4>
            <p class="text-muted mb-3">NISN: {{ $alumni->nisn }}</p>
            <span class="badge bg-secondary px-3 py-2 rounded-pill mb-3">
                Kelas Terakhir: {{ $alumni->kelas->nama_kelas ?? 'Tanpa Kelas' }}
            </span>
            <hr>
            <div class="text-start">
                <p class="small text-muted mb-1">Status Keanggotaan</p>
                <div class="d-flex align-items-center">
                    @if($alumni->status_siswa == 'Alumni')
                        <span class="badge bg-info text-dark me-2 fw-bold">Alumni</span>
                        <small>Tamat: <strong>{{ $alumni->tahun_lulus }}</strong></small>
                    @else
                        <span class="badge bg-danger text-white me-2 fw-bold">Non-Aktif (Pindah)</span>
                        <small>Akun: <strong>Dibekukan</strong></small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Biodata Lengkap --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold border-bottom pb-2 mb-4 text-primary"><i class="bi bi-clipboard-data me-2"></i>Biodata Lengkap</h5>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Email Pribadi</div>
                <div class="col-sm-8 fw-bold">{{ $alumni->user->email ?? 'Tidak ada email' }}</div>
            </div>

            {{-- TAMBAHAN: NO WA ALUMNI / SISWA PINDAH --}}
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">No. WhatsApp Siswa</div>
                <div class="col-sm-8 fw-bold">
                    @if($alumni->no_hp_siswa)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $alumni->no_hp_siswa) }}" target="_blank" class="text-decoration-none">
                            {{ $alumni->no_hp_siswa }} <i class="bi bi-whatsapp ms-1 text-success"></i>
                        </a>
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                <div class="col-sm-8 fw-bold">{{ $alumni->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Tempat, Tanggal Lahir</div>
                <div class="col-sm-8 fw-bold">
                    {{ $alumni->tempat_lahir ?? '-' }}, 
                    {{ $alumni->tanggal_lahir ? date('d F Y', strtotime($alumni->tanggal_lahir)) : '-' }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Alamat Rumah</div>
                <div class="col-sm-8 fw-bold">{{ $alumni->alamat ?? '-' }}</div>
            </div>

            <h5 class="fw-bold border-bottom pb-2 mb-4 mt-5 text-secondary"><i class="bi bi-people me-2"></i>Informasi Orang Tua / Wali</h5>
            
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">Nama Orang Tua / Wali</div>
                <div class="col-sm-8 fw-bold">{{ $alumni->nama_orang_tua ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-muted">No. Telepon / WA Ortu</div>
                <div class="col-sm-8 fw-bold">
                    @if($alumni->no_hp_ortu)
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $alumni->no_hp_ortu) }}" target="_blank" class="text-decoration-none">
                            {{ $alumni->no_hp_ortu }} <i class="bi bi-whatsapp ms-1 text-success"></i>
                        </a>
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection