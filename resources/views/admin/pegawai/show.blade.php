@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pegawai
    </a>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-0">Detail Profil Pegawai</h3>
            <p class="text-muted mb-0">Informasi lengkap staf Tata Usaha / Bendahara.</p>
        </div>
        
    </div>
</div>

<div class="row g-4">
    {{-- Sisi Kiri: Kartu Identitas --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            {{-- 🔥 Bagian Banner & Foto Profil Dinamis 🔥 --}}
            <div class="bg-primary bg-opacity-10 py-4 text-center border-bottom border-primary border-opacity-25">
                <div class="d-flex justify-content-center">
                    @if($pegawai->jk == 'L')
                        {{-- Avatar Cowok --}}
                        <img src="{{ asset('images/username_lk.png') }}" alt="Avatar Laki-laki" 
                             class="rounded-circle shadow border border-3 border-white" 
                             style="width: 120px; height: 120px; object-fit: cover; background-color: #e9ecef;">
                    @else
                        {{-- Avatar Cewek --}}
                        <img src="{{ asset('images/username_pr.png') }}" alt="Avatar Perempuan" 
                             class="rounded-circle shadow border border-3 border-white" 
                             style="width: 120px; height: 120px; object-fit: cover; background-color: #f8d7da;">
                    @endif
                </div>
            </div>
            
            <div class="card-body text-center pt-4">
                <h5 class="fw-bold text-dark mb-1">{{ $pegawai->nama_lengkap }}</h5>
                <p class="text-muted small mb-3">NIP: {{ $pegawai->nip ?? '-' }}</p>
                
                <div class="badge bg-success rounded-pill px-3 py-2 mb-2 shadow-sm">
                    {{ $pegawai->jabatan }}
                </div>
                
                <hr class="text-muted opacity-25">
                <div class="text-start small text-muted px-2">
                    <p class="mb-2"><i class="bi bi-envelope-fill text-primary me-2"></i> {{ $pegawai->user->email ?? 'Tidak ada email' }}</p>
                    <p class="mb-0">
                        <i class="bi bi-telephone-fill text-success me-2"></i> 
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pegawai->no_hp) }}" target="_blank" class="text-success text-decoration-none">
                            {{ $pegawai->no_hp ?? '-' }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Detail Data --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                {{-- Data Profil --}}
                <h6 class="fw-bold text-primary mb-4 pb-2 border-bottom border-primary border-opacity-10">
                    <i class="bi bi-person-lines-fill me-2"></i> Informasi Biodata
                </h6>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 text-muted">Nama Lengkap</div>
                    <div class="col-sm-8 fw-bold text-dark">{{ $pegawai->nama_lengkap }}</div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 text-muted">NIP</div>
                    <div class="col-sm-8 text-dark">{{ $pegawai->nip ?? 'Tidak ada NIP' }}</div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                    <div class="col-sm-8 text-dark">{{ $pegawai->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Alamat Lengkap</div>
                    <div class="col-sm-8 text-dark">{{ $pegawai->alamat ?? '-' }}</div>
                </div>

                {{-- Data Akun Login --}}
                <h6 class="fw-bold text-success mb-4 mt-5 pb-2 border-bottom border-success border-opacity-10">
                    <i class="bi bi-shield-lock-fill me-2"></i> Keamanan & Akun Sistem
                </h6>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 text-muted">Username Login</div>
                    <div class="col-sm-8 font-monospace text-success fw-bold">{{ $pegawai->user->username ?? '-' }}</div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 text-muted">Level Akses (Role)</div>
                    <div class="col-sm-8">
                        @if(($pegawai->user->role ?? '') == 'tu')
                            <span class="badge bg-primary px-3 py-2"><i class="bi bi-pc-display me-1"></i> Tata Usaha (TU)</span>
                        @elseif(($pegawai->user->role ?? '') == 'bendahara')
                            <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-wallet2 me-1"></i> Bendahara</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Tidak Diketahui</span>
                        @endif
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-4 text-muted">Akun Dibuat Pada</div>
                    <div class="col-sm-8 text-dark">{{ $pegawai->created_at->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection