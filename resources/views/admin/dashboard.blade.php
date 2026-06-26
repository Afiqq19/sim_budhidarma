@extends('layouts.admin')

@section('content')
{{-- Header Dashboard --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
    <div>
        <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Dashboard Eksekutif</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Pantau ringkasan sumber daya dan keuangan sekolah T.A <span class="fw-semibold text-dark">{{ $tahunAktif ? $tahunAktif->tahun : 'Belum Diatur' }}</span></p>
    </div>
</div>

{{-- ================= BARIS 1: KARTU STATISTIK ================= --}}
<div class="row g-4 mb-4">
    {{-- Kartu Kas Masuk --}}
    <div class="col-md-4">
        <div class="card rounded-4 bg-white h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Kas Masuk ({{ $bulanSekarang }})</h6>
                </div>
                <h2 class="fw-bolder text-dark mb-2">Rp {{ number_format($uangMasukBulanIni, 0, ',', '.') }}</h2>
                <div class="d-flex align-items-center mt-3 pt-3 border-top border-light">
                    <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i> Total pembayaran lunas bulan ini.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Siswa Lunas --}}
    <div class="col-md-4">
        <div class="card rounded-4 bg-white h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Siswa Lunas</h6>
                </div>
                <div class="d-flex align-items-baseline mb-2">
                    <h2 class="fw-bolder text-dark mb-0 me-2">{{ $sudahBayarCount }}</h2>
                    <span class="text-muted fw-medium">Siswa</span>
                </div>
                <div class="d-flex align-items-center mt-3 pt-3 border-top border-light">
                    <small class="text-muted"><i class="bi bi-shield-check text-primary me-1"></i> Kewajiban telah diselesaikan.</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Menunggak --}}
    <div class="col-md-4">
        <div class="card rounded-4 bg-white h-100" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-4 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-exclamation-octagon-fill fs-4"></i>
                    </div>
                    <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Menunggak</h6>
                </div>
                <div class="d-flex align-items-baseline mb-2">
                    <h2 class="fw-bolder text-dark mb-0 me-2">{{ $belumBayarCount }}</h2>
                    <span class="text-muted fw-medium">Siswa</span>
                </div>
                <div class="d-flex align-items-center mt-3 pt-3 border-top border-light">
                    <small class="text-muted"><i class="bi bi-clock-history text-danger me-1"></i> Butuh tindak lanjut segera.</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= BARIS 2: SDM & TRANSAKSI ================= --}}
<div class="row g-4">
    {{-- Ringkasan SDM --}}
    <div class="col-md-4">
        <div class="card rounded-4 h-100 bg-white" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
            <div class="card-header bg-transparent py-4 border-0 pb-2">
                <h6 class="fw-bold text-dark mb-0">Ringkasan SDM</h6>
            </div>
            <div class="card-body px-4 pb-4 pt-2">
                <div class="d-flex flex-column gap-3">
                    
                    {{-- Item SDM 1 --}}
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <span class="fw-medium text-dark" style="font-size: 0.9rem;">Pegawai/TU</span>
                        </div>
                        <span class="badge bg-white text-dark border shadow-sm rounded-pill px-3 py-2">{{ $totalPegawai }}</span>
                    </div>

                    {{-- Item SDM 2 --}}
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <span class="fw-medium text-dark" style="font-size: 0.9rem;">Wali Kelas</span>
                        </div>
                        <span class="badge bg-white text-dark border shadow-sm rounded-pill px-3 py-2">{{ $totalWaliKelas }}</span>
                    </div>

                    {{-- Item SDM 3 --}}
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                <i class="bi bi-person-heart"></i>
                            </div>
                            <span class="fw-medium text-dark" style="font-size: 0.9rem;">Total Murid</span>
                        </div>
                        <span class="badge bg-white text-dark border shadow-sm rounded-pill px-3 py-2">{{ $totalSiswa }}</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terakhir --}}
    <div class="col-md-8">
        <div class="card rounded-4 h-100 bg-white" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
            <div class="card-header bg-transparent py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">Transaksi Terbaru <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill ms-2 fw-normal px-2">Real-time</span></h6>
                <a href="{{ route('admin.riwayat.index') }}" class="text-success text-decoration-none small fw-semibold">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                
                {{-- Area Tabel Scroll --}}
                <div class="table-responsive table-scroll" style="max-height: 330px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0 border-white">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-4 text-muted fw-semibold py-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Siswa</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Metode</th>
                                <th class="text-muted fw-semibold py-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Nominal</th>
                                <th class="pe-4 text-muted fw-semibold py-3 text-end" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerakhir as $trx)
                            <tr style="cursor: pointer; transition: all 0.2s;">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex justify-content-center align-items-center me-3 fw-bold" style="width: 35px; height: 35px; font-size: 0.85rem;">
                                            {{ substr($trx->siswa->nama_lengkap ?? 'A', 0, 1) }}
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $trx->siswa->nama_lengkap ?? 'Tanpa Nama' }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-2 fw-medium">
                                        <i class="bi bi-credit-card-2-front me-1 text-muted"></i> {{ $trx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="py-3 fw-bold text-success">
                                    Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="pe-4 py-3 small text-muted text-end fw-medium">
                                    {{ $trx->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-light"></i>
                                        Belum ada transaksi hari ini.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection