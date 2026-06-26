@extends('layouts.bendahara')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Dashboard Keuangan</h3>
        <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan kas masuk.</p>
    </div>
</div>

{{-- 4 KARTU METRIK KEUANGAN --}}
{{-- 4 KARTU METRIK KEUANGAN --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <p class="mb-1 text-white-50 small fw-bold text-uppercase">Pemasukan Hari Ini</p>
                <h4 class="fw-bold mb-0">Rp {{ number_format($pemasukanHariIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-4 bg-success bg-gradient text-white h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <p class="mb-1 text-white-50 small fw-bold text-uppercase">Pemasukan Bulan Ini</p>
                <h4 class="fw-bold mb-0">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    
    {{-- KARTU BARU: PROGRESS LUNAS BULAN INI --}}
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-4 bg-info bg-gradient text-white h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <p class="mb-1 text-white-50 small fw-bold text-uppercase">Lunas SPP ({{ $bulanSekarangStr }})</p>
                <h4 class="fw-bold mb-0">
                    {{ $siswaLunasBulanIni }} <span class="fs-6 fw-normal text-white-50">/ {{ $totalSiswaAktif }} Siswa</span>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm rounded-4 bg-danger bg-gradient text-white h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <p class="mb-1 text-white-50 small fw-bold text-uppercase">Potensi Tunggakan</p>
                <h4 class="fw-bold mb-0">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- TABEL 5 TRANSAKSI TERBARU --}}
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i>5 Transaksi Terakhir</h6>
                <a href="{{ route('riwayat.index') }}" class="btn btn-sm btn-light border text-muted">Lihat Semua</a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse ($transaksiTerbaru as $trx)
                            <tr>
                                <td>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px;">
                                        <i class="bi {{ $trx->metode_pembayaran == 'Tunai' || $trx->metode_pembayaran == 'Tunai / Manual' ? 'bi-cash' : 'bi-bank' }}"></i>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $trx->siswa->nama_lengkap ?? 'Siswa Dihapus' }}</span>
                                    <span class="small text-muted">{{ $trx->tagihan_spp->bulan ?? '-' }} • {{ \Carbon\Carbon::parse($trx->created_at)->diffForHumans() }}</span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    + Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada transaksi sama sekali.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- KARTU INFO / BANTUAN CEPAT --}}
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-light border">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                <img src="https://illustrations.popsy.co/amber/freelancer.svg" alt="Welcome" class="img-fluid mb-3 mx-auto" style="max-height: 180px;">
                <h5 class="fw-bold text-dark">Sistem Siap!</h5>
                <p class="text-muted small mb-4">Pilih menu <strong>Kasir Pembayaran</strong> untuk mulai menerima setoran uang SPP dari siswa hari ini.</p>
                <a href="{{ route('tunggakan.index') }}" class="btn btn-outline-danger w-100 rounded-pill fw-bold">
                    <i class="bi bi-exclamation-circle me-1"></i> Kejar Tunggakan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection