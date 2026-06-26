@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('tu.siswa.index') }}" class="btn btn-white border shadow-sm d-flex align-items-center justify-content-center rounded-3 hover-btn-back bg-white" style="width: 42px; height: 42px; transition: all 0.2s;" title="Kembali ke Daftar Siswa">
        <i class="bi bi-arrow-left fs-5 text-secondary"></i>
    </a>
    <div>
        <h3 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px;">Detail Profil Siswa</h3>
        <p class="text-secondary mb-0" style="font-size: 0.9rem;">Informasi lengkap biodata dan status akun siswa.</p>
    </div>
</div>

<div class="row g-4 mb-5">
    {{-- ================= SISI KIRI: KARTU PROFIL UTAMA ================= --}}
    <div class="col-md-4">
        <div class="card border-0 rounded-4 bg-white h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4 text-center d-flex flex-column">
                
                {{-- Avatar Inisial --}}
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 border border-primary border-opacity-25" style="width: 110px; height: 110px; font-size: 3rem; font-weight: 800;">
                    {{ substr($siswa->nama_lengkap, 0, 1) }}
                </div>
                
                <h4 class="fw-bolder text-dark mb-1">{{ $siswa->nama_lengkap }}</h4>
                <p class="text-muted mb-3" style="font-size: 0.95rem;">NISN: <strong class="text-dark">{{ $siswa->nisn }}</strong></p>
                
                <div class="d-flex justify-content-center mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-4 py-2 rounded-pill fw-semibold" style="font-size: 0.85rem;">
                        <i class="bi bi-building me-1"></i> Kelas {{ $siswa->kelas->nama_kelas ?? 'Belum Ada Kelas' }}
                    </span>
                </div>

                <hr class="border-light w-100 mt-0 mb-4">

                {{-- Status Akun --}}
                <div class="bg-light rounded-4 p-3 text-start mb-4 border" style="border-color: #f1f5f9 !important;">
                    <span class="d-block text-muted fw-bold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Status Akses Sistem</span>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-bold" style="font-size: 0.95rem;">{{ $siswa->user->username ?? '-' }}</span>
                            <span class="text-muted" style="font-size: 0.75rem;">Username Login</span>
                        </div>
                        
                        {{-- Logika Warna Status --}}
                        @php
                            $statusColor = 'success';
                            $statusText = $siswa->status_siswa ?? 'Aktif';
                            if($statusText == 'Pindah') $statusColor = 'danger';
                            if($statusText == 'Alumni') $statusColor = 'secondary';
                        @endphp
                        <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} border-opacity-25 px-2 py-1 rounded-2 fw-semibold">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> {{ $statusText }}
                        </span>
                    </div>
                </div>

                {{-- Tombol Edit --}}
                <div class="mt-auto">
                    <a href="{{ route('tu.siswa.edit', $siswa->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm hover-lift py-2">
                        <i class="bi bi-pencil-square me-2"></i> Edit Biodata Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= SISI KANAN: BIODATA LENGKAP ================= --}}
    <div class="col-md-8">
        <div class="card border-0 rounded-4 bg-white h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
            
            <div class="card-header bg-transparent py-4 px-4 p-md-5 pb-md-3 border-bottom border-light d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="bi bi-clipboard-data-fill fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Informasi Biodata Lengkap</h5>
            </div>
            
            <div class="card-body p-4 p-md-5 pt-md-4">
                
                <div class="row g-4">
                    <div class="col-sm-6">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Alamat Email</p>
                        <p class="fw-bold text-dark mb-0 fs-6">{{ $siswa->user->email ?? 'Tidak ada email' }}</p>
                    </div>
                    
                    <div class="col-sm-6">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">No. WhatsApp Siswa</p>
                        @if($siswa->no_hp_siswa)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $siswa->no_hp_siswa) }}" target="_blank" class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 text-decoration-none rounded-pill fw-bold d-inline-flex align-items-center hover-wa">
                                <i class="bi bi-whatsapp me-2 fs-6"></i> {{ $siswa->no_hp_siswa }}
                            </a>
                        @else
                            <p class="fw-bold text-dark mb-0 fs-6">-</p>
                        @endif
                    </div>
                    
                    <div class="col-sm-6">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Jenis Kelamin</p>
                        <p class="fw-bold text-dark mb-0 fs-6">
                            @if($siswa->jk == 'L')
                                <i class="bi bi-gender-male text-primary me-1"></i> Laki-laki
                            @else
                                <i class="bi bi-gender-female text-danger me-1"></i> Perempuan
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-sm-6">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Tempat, Tanggal Lahir</p>
                        <p class="fw-bold text-dark mb-0 fs-6">
                            {{ $siswa->tempat_lahir ?? '-' }}, 
                            {{ $siswa->tanggal_lahir ? date('d F Y', strtotime($siswa->tanggal_lahir)) : '-' }}
                        </p>
                    </div>
                    
                    <div class="col-12">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Alamat Rumah</p>
                        <p class="fw-bold text-dark mb-0 fs-6 lh-base">{{ $siswa->alamat ?? '-' }}</p>
                    </div>
                </div>
                
                <hr class="border-light my-5">
                
                {{-- Data Orang Tua --}}
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">Informasi Orang Tua / Wali</h5>
                </div>
                
                <div class="row g-4">
                    <div class="col-sm-6">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Nama Orang Tua / Wali</p>
                        <p class="fw-bold text-dark mb-0 fs-6">{{ $siswa->nama_orang_tua ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">No. WhatsApp Orang Tua</p>
                        @if($siswa->no_hp_ortu)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $siswa->no_hp_ortu) }}" target="_blank" class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 text-decoration-none rounded-pill fw-bold d-inline-flex align-items-center hover-wa">
                                <i class="bi bi-whatsapp me-2 fs-6"></i> {{ $siswa->no_hp_ortu }}
                            </a>
                        @else
                            <p class="fw-bold text-dark mb-0 fs-6">-</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Custom CSS untuk Hover --}}
<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
    .hover-btn-back:hover { background-color: #f1f5f9 !important; color: #0f172a !important; }
    .hover-wa { transition: all 0.2s ease; }
    .hover-wa:hover { background-color: #10b981 !important; color: #ffffff !important; transform: translateY(-2px); }
</style>
@endsection