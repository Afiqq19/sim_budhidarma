@extends('layouts.siswa')

@section('content')

@php
    // 🔥 LOGIKA TEMA BANNER MENGGUNAKAN CLASS 🔥
    $bannerClass = 'banner-aktif'; 
    $textColorBadge = 'text-teal';
    $iconBanner = 'bi-mortarboard';
    $isAktif = true; 
    
    if ($siswa->status_siswa == 'Alumni') {
        $bannerClass = 'banner-alumni';
        $textColorBadge = 'text-secondary';
        $iconBanner = 'bi-award';
        $isAktif = false;
    } elseif ($siswa->status_siswa == 'Pindah') {
        $bannerClass = 'banner-pindah';
        $textColorBadge = 'text-warning';
        $iconBanner = 'bi-door-open';
        $isAktif = false;
    }
@endphp

{{-- ================= BANNER SELAMAT DATANG ================= --}}
<div class="row mb-4">
    <div class="col-12">
        {{-- Panggil variabel class-nya di sini, jadi tidak ada style inline yang bikin error editor --}}
        <div class="card border-0 rounded-4 overflow-hidden {{ $bannerClass }}" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div class="card-body p-4 p-md-5 position-relative">
                {{-- Efek Watermark Ikon di Latar Belakang --}}
                <div class="position-absolute" style="right: -20px; top: -30px; opacity: 0.15; transform: rotate(-15deg);">
                    <i class="bi {{ $iconBanner }}" style="font-size: 180px;"></i>
                </div>
                
                <div class="position-relative z-1">
                    <h3 class="fw-bolder mb-2" style="letter-spacing: -0.5px;">Selamat Datang, {{ $siswa->nama_lengkap }}! 👋</h3>
                    
                    {{-- Info Kelas / Status dan Tahun Ajaran Dinamis --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                        @if(!$isAktif && $siswa->status_siswa == 'Alumni')
                            <span class="badge bg-white text-secondary px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.8rem;">
                                <i class="bi bi-mortarboard-fill me-1"></i> Lulusan / Alumni
                            </span> 
                            <span class="fw-medium opacity-75" style="font-size: 0.9rem;">Terima kasih telah menjadi bagian dari sejarah SMK Budhi Darma.</span>
                        
                        @elseif(!$isAktif && $siswa->status_siswa == 'Pindah')
                            <span class="badge bg-white text-warning px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 0.8rem;">
                                <i class="bi bi-box-arrow-right me-1"></i> Pindah
                            </span> 
                            <span class="fw-medium opacity-75" style="font-size: 0.9rem;">Status akademik Anda saat ini dibekukan karena kepindahan.</span>
                        
                        @else
                            <span class="badge bg-white px-3 py-2 rounded-pill fw-bold shadow-sm" style="color: #0d9488; font-size: 0.8rem;">
                                <i class="bi bi-building me-1"></i> Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}
                            </span> 
                            <span class="badge px-3 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #f0fdfa; color: #0d9488; border: 1px solid #ccfbf1; font-size: 0.8rem;">
                                T.A {{ $tahunAktif ? $tahunAktif->tahun : 'Belum Diatur' }} ({{ $tahunAktif ? $tahunAktif->semester : '-' }})
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= KARTU MENU UTAMA ================= --}}
<div class="row g-4">
    
    {{-- KARTU 1: E-RAPOR --}}
    <div class="col-md-6">
        <div class="card bg-white border-0 rounded-4 h-100 hover-card-modern" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="{{ $isAktif ? 'bg-warning text-warning' : 'bg-secondary text-secondary' }} bg-opacity-10 d-flex justify-content-center align-items-center rounded-4" style="width: 56px; height: 56px;">
                        <i class="bi bi-trophy-fill fs-3"></i>
                    </div>
                    <span class="badge bg-light text-muted border px-2 py-1">E-Rapor</span>
                </div>
                
                @if($isAktif)
                    {{-- Tampilan untuk Siswa Aktif --}}
                    <h6 class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px; font-size: 0.75rem;">Peringkat Kelas</h6>
                    @if($jumlahMapel > 0)
                        <h2 class="fw-bolder mb-2 text-dark" style="font-size: 2.2rem;">Ke-{{ $myRank }}</h2>
                    @else
                        <h2 class="fw-bolder mb-2 text-muted">-</h2>
                    @endif
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                        Total Nilai: <strong class="text-dark">{{ $totalNilai }}</strong> 
                        <span class="opacity-75">(Dari {{ $jumlahMapel }} Mapel)</span>
                    </p>
                @else
                    {{-- Tampilan untuk Alumni / Pindah --}}
                    <h6 class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px; font-size: 0.75rem;">Riwayat Akademik</h6>
                    <h2 class="fw-bolder mb-2 text-secondary" style="font-size: 2.2rem;">Arsip Nilai</h2>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Akses seluruh riwayat nilai dan E-Rapor Anda selama bersekolah di sini.</p>
                @endif

                <div class="mt-auto pt-4">
                    <hr class="border-light mt-0 mb-3">
                    <a href="{{ route('siswa.rapor') }}" class="{{ $isAktif ? 'text-warning' : 'text-secondary' }} text-decoration-none fw-bold d-inline-flex align-items-center transition-all hover-link">
                        Lihat Detail Rapor <i class="bi bi-arrow-right ms-2 fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU 2: KEUANGAN SPP --}}
    <div class="col-md-6">
        @php
            // Logika Warna Kartu SPP (Berdasarkan Aktif & Status Lunas)
            if (!$isAktif) {
                $sppBg = 'secondary';
                $sppText = 'Belum Dibuat / Arsip';
            } else {
                $sppBg = $statusSppBulanIni == 'Lunas' ? 'success' : 'danger';
            }
        @endphp

        <div class="card bg-white border-0 rounded-4 h-100 hover-card-modern" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="bg-{{ $sppBg }} bg-opacity-10 text-{{ $sppBg }} d-flex justify-content-center align-items-center rounded-4" style="width: 56px; height: 56px;">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <span class="badge bg-light text-muted border px-2 py-1">Keuangan</span>
                </div>
                
                @if($isAktif)
                    {{-- Tampilan untuk Siswa Aktif --}}
                    <h6 class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px; font-size: 0.75rem;">SPP Bulan {{ $bulanSekarang }}</h6>
                    
                    @if($statusSppBulanIni == 'Belum Lunas')
                        <h2 class="fw-bolder mb-2 text-danger" style="font-size: 2.2rem;">Belum Lunas</h2>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">
                            @if($totalBulanNunggak > 1)
                                Anda memiliki total <strong class="text-danger">{{ $totalBulanNunggak }} bulan</strong> tunggakan.
                            @else
                                Segera lunasi tagihan bulan ini agar tenang belajar.
                            @endif
                        </p>
                        <div class="mt-auto pt-4">
                            <hr class="border-light mt-0 mb-3">
                            <a href="{{ route('siswa.tagihan') }}" class="text-danger text-decoration-none fw-bold d-inline-flex align-items-center transition-all hover-link">
                                Bayar Sekarang <i class="bi bi-arrow-right ms-2 fs-5"></i>
                            </a>
                        </div>
                    
                    @elseif($statusSppBulanIni == 'Lunas')
                        <h2 class="fw-bolder mb-2 text-success" style="font-size: 2.2rem;">Lunas ✨</h2>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Terima kasih, tagihan bulan <strong class="text-dark">{{ $bulanSekarang }}</strong> sudah selesai.</p>
                        <div class="mt-auto pt-4">
                            <hr class="border-light mt-0 mb-3">
                            <a href="{{ route('siswa.tagihan') }}" class="text-success text-decoration-none fw-bold d-inline-flex align-items-center transition-all hover-link">
                                Lihat Riwayat Bayar <i class="bi bi-arrow-right ms-2 fs-5"></i>
                            </a>
                        </div>
                    
                    @else
                        <h2 class="fw-bolder mb-2 text-secondary" style="font-size: 2.2rem;">Belum Diterbitkan</h2>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Data tagihan bulan ini belum diterbitkan oleh bendahara sekolah.</p>
                        <div class="mt-auto pt-4">
                            <hr class="border-light mt-0 mb-3">
                            <a href="{{ route('siswa.tagihan') }}" class="text-secondary text-decoration-none fw-bold d-inline-flex align-items-center transition-all hover-link">
                                Cek Administrasi <i class="bi bi-arrow-right ms-2 fs-5"></i>
                            </a>
                        </div>
                    @endif
                
                @else
                    {{-- Tampilan untuk Alumni / Pindah --}}
                    <h6 class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px; font-size: 0.75rem;">Riwayat Keuangan</h6>
                    <h2 class="fw-bolder mb-2 text-secondary" style="font-size: 2.2rem;">Arsip Tagihan</h2>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Lihat seluruh riwayat administrasi dan pembayaran SPP Anda di masa lalu.</p>
                    <div class="mt-auto pt-4">
                        <hr class="border-light mt-0 mb-3">
                        <a href="{{ route('siswa.tagihan') }}" class="text-secondary text-decoration-none fw-bold d-inline-flex align-items-center transition-all hover-link">
                            Cek Riwayat SPP <i class="bi bi-arrow-right ms-2 fs-5"></i>
                        </a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

{{-- ================= CUSTOM CSS HOVER ================= --}}
<style>
    /* Efek hover kartu naik ke atas */
    .hover-card-modern {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.06) !important;
    }
    
    /* Efek panah bergeser pada link */
    .hover-link i {
        transition: transform 0.2s ease;
    }
    .hover-link:hover i {
        transform: translateX(5px);
    }
    
    /* Custom warna teks khusus untuk Teal */
    .text-teal {
        color: #0d9488 !important;
    }
</style>
@endsection