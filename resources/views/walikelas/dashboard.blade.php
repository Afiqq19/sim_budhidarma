@extends('layouts.walikelas')

@section('content')

@if($waliKelas)
    {{-- ===================================================== --}}
    {{-- TAMPILAN NORMAL (JIKA DIA TERDAFTAR SEBAGAI GURU)     --}}
    {{-- ===================================================== --}}
    
    <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Wali Kelas</h3>
            <p class="text-muted mb-0">Selamat datang, <strong class="text-primary">{{ Auth::user()->name }}</strong>. 
                Anda adalah Wali Kelas <strong class="badge bg-secondary">{{ $kelas->nama_kelas ?? 'Belum Ada Kelas' }}</strong>
            </p>
        </div>

    </div>

    {{-- 🔥 NOTIFIKASI JIKA GURU BELUM PUNYA KELAS 🔥 --}}
    @if(empty($waliKelas->kelas_id))
    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4">
        <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px; min-width: 60px;">
            <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
        </div>
        <div>
            <h5 class="fw-bold text-dark mb-1">Status: Belum Memiliki Kelas Binaan!</h5>
            <p class="mb-0 text-dark opacity-75">Akun Anda terdaftar sebagai Wali Kelas, namun saat ini belum ada kelas yang ditugaskan kepada Anda. Mohon hubungi <b>Tata Usaha</b> untuk pengaturan penempatan kelas.</p>
        </div>
    </div>
    @endif

    <div class="row mb-4">
        {{-- Kartu Total Siswa (Diubah jadi col-md-6 agar penuh karena kartu ke-3 dihapus) --}}
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white h-100" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white-50 small fw-bold text-uppercase tracking-wider">Total Anak Didik</p>
                        <h2 class="fw-bold mb-0">{{ $totalSiswa ?? 0 }} <span class="fs-6 fw-normal text-white-50">Siswa</span></h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Lunas SPP (Diubah jadi col-md-6 agar penuh) --}}
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 bg-info bg-gradient text-white h-100" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 70px; height: 70px;">
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white-50 small fw-bold text-uppercase tracking-wider">Lunas SPP ({{ $bulanSekarang ?? date('F') }})</p>
                        <h2 class="fw-bold mb-0">{{ $siswaLunasBulanIni ?? 0 }} <span class="fs-6 text-white-50 fw-normal">/ {{ $totalSiswa ?? 0 }}</span></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Tabel Pantau Pembayaran SPP Anak Kelasnya --}}
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-activity text-primary me-2"></i>Aktivitas Pembayaran Kelas Anda</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-muted small text-uppercase">Status</th>
                                    <th class="border-0 py-3 text-muted small text-uppercase">Nama Siswa</th>
                                    <th class="border-0 py-3 text-end px-4 text-muted small text-uppercase">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($pembayaranTerbaru) && count($pembayaranTerbaru) > 0)
                                    @foreach ($pembayaranTerbaru as $trx)
                                    <tr>
                                        <td class="px-4 py-3" style="width: 80px;">
                                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-success shadow-sm" style="width: 40px; height: 40px;">
                                                <i class="bi bi-check-lg fw-bold"></i>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="fw-bold text-dark d-block">{{ $trx->siswa->nama_lengkap }}</span>
                                            <span class="small text-muted"><i class="bi bi-wallet2 me-1"></i> Melunasi Bulan {{ $trx->tagihan_spp->bulan }}</span>
                                        </td>
                                        <td class="text-end px-4 py-3 small text-muted fw-medium">
                                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($trx->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="text-muted mb-2"><i class="bi bi-inbox fs-2 opacity-50"></i></div>
                                        <p class="text-muted mb-0 fw-medium">Belum ada aktivitas pembayaran dari siswa kelas ini.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gambar Ilustrasi --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex align-items-center justify-content-center p-4 bg-primary bg-opacity-10 position-relative overflow-hidden">
                {{-- Efek Bulatan di Background --}}
                <div class="position-absolute bg-white rounded-circle opacity-50" style="width: 150px; height: 150px; top: -30px; right: -30px;"></div>
                <div class="position-absolute bg-primary rounded-circle opacity-25" style="width: 100px; height: 100px; bottom: 20px; left: -20px;"></div>
                
                <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Ilustrasi" class="img-fluid position-relative z-1" style="max-height: 240px; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </div>
        </div>
    </div>

@else
    {{-- ===================================================== --}}
    {{-- TAMPILAN KOSONG (JIKA DIA BUKAN WALI KELAS SAMA SEKALI) --}}
    {{-- ===================================================== --}}
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center" style="max-width: 500px;">
            <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 100px; height: 100px;">
                <i class="bi bi-shield-lock-fill text-danger" style="font-size: 3.5rem;"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">Akses Dibatasi!</h3>
            <p class="text-muted mb-4 fs-6">
                Mohon maaf, <strong>{{ Auth::user()->name }}</strong>.<br>
                Akun Anda belum terdaftar dalam sistem sebagai Guru atau Wali Kelas.
            </p>
            <hr class="opacity-25 mb-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-sm w-100">
                    <i class="bi bi-box-arrow-left me-2"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </div>
@endif

@endsection