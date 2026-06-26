@extends('layouts.walikelas')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark"><i class="bi bi-person-vcard text-primary me-2"></i>Detail Biodata Siswa</h3>
        <p class="text-muted">Informasi lengkap mengenai data diri dan kontak orang tua siswa.</p>
    </div>
    <a href="{{ route('walikelas.siswa.index') }}" class="btn btn-light border shadow-sm rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    {{-- KOLOM KIRI: DATA PRIBADI --}}
    <div class="col-md-7 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h6 class="fw-bold text-primary mb-0"><i class="bi bi-person-fill me-2"></i>Informasi Pribadi</h6>
            </div>
            <div class="card-body p-4">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nama Lengkap</div>
                    <div class="col-sm-8 fw-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">NISN</div>
                    <div class="col-sm-8"><span class="badge bg-primary bg-opacity-10 text-primary px-3">{{ $siswa->nisn }}</span></div>
                </div>
                {{-- TAMBAHKAN BAGIAN EMAIL DI SINI --}}
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Email Siswa</div>
                    <div class="col-sm-8 text-primary">{{ $siswa->user->email ?? 'Email belum diatur' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Jenis Kelamin</div>
                    <div class="col-sm-8">{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Tempat, Tgl Lahir</div>
                    <div class="col-sm-8">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">No. HP Siswa</div>
                    <div class="col-sm-8">{{ $siswa->no_hp_siswa ?? 'Tidak Ada Data' }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted small fw-bold text-uppercase">Alamat Lengkap</div>
                    <div class="col-sm-8 text-muted italic">{{ $siswa->alamat ?? 'Alamat belum diisi' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: DATA ORANG TUA --}}
    <div class="col-md-5 mb-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h6 class="fw-bold text-success mb-0"><i class="bi bi-people-fill me-2"></i>Kontak Orang Tua / Wali</h6>
            </div>
            <div class="card-body p-4 text-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-telephone-outbound-fill text-success fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $siswa->nama_orang_tua ?? 'Nama Wali Tidak Ada' }}</h5>
                <p class="text-muted small mb-4">Orang Tua / Wali Murid</p>

                <div class="d-grid gap-2">
                    @if($siswa->no_hp_ortu)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->no_hp_ortu) }}" target="_blank" class="btn btn-success fw-bold rounded-3 shadow-sm py-2">
                        <i class="bi bi-whatsapp me-2"></i> Chat WhatsApp
                    </a>
                    <a href="tel:{{ $siswa->no_hp_ortu }}" class="btn btn-outline-dark fw-bold rounded-3 py-2">
                        <i class="bi bi-telephone me-2"></i> Panggilan Telepon
                    </a>
                    <div class="mt-2 text-center">
                        <span class="small text-muted">Nomor: <strong>{{ $siswa->no_hp_ortu }}</strong></span>
                    </div>
                    @else
                    <div class="alert alert-warning small rounded-3 border-0">
                        <i class="bi bi-exclamation-circle me-1"></i> Nomor kontak orang tua belum tersedia.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-mortarboard-fill me-2"></i>Status Akademik</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 small opacity-75">Status Keaktifan</p>
                    <span class="badge bg-white text-primary fw-bold">{{ $siswa->status_siswa }}</span>
                </div>
                <hr class="my-2 opacity-25">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 small opacity-75">Kelas Saat Ini</p>
                    <span class="fw-bold">{{ $siswa->kelas->nama_kelas }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection