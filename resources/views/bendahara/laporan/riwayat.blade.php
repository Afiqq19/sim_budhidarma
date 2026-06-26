@extends('layouts.bendahara')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Transaksi</h3>
        <p class="text-muted mb-0">Pantau aliran dana SPP yang masuk ke sistem berdasarkan waktu dan metode pembayaran.</p>
    </div>
    
    {{-- TOMBOL DOWNLOAD EXCEL --}}
    <div>
        {{-- Mengirimkan semua request (filter pencarian & bulan) ke rute Excel agar datanya sinkron --}}
        <a href="{{ route('riwayat.export', request()->all()) }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
            <i class="bi bi-file-earmark-excel-fill me-2"></i>Download Excel
        </a>
    </div>
</div>

{{-- KARTU REKAPITULASI TUNGGAL (DINAMIS SESUAI FILTER) --}}
<div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-center">
        <div>
            <p class="mb-1 text-white-50 small fw-bold text-uppercase">{{ $judulRekap }}</p>
            <h2 class="fw-bold mb-0">Rp {{ number_format($totalRekap, 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 65px; height: 65px;">
            <i class="bi bi-wallet2 fs-2"></i>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        {{-- FORM FILTER PINTAR --}}
        <form action="{{ route('riwayat.index') }}" method="GET" class="row g-2 mb-4 bg-light p-3 rounded-4 border">
            <div class="col-md-4">
                <label class="small text-muted fw-bold mb-1">Cari Siswa</label>
                <div class="input-group shadow-sm rounded-3">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Nama atau NISN..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="small text-muted fw-bold mb-1">Pilih Bulan</label>
                <input type="month" name="bulan_filter" class="form-control shadow-sm rounded-3 text-muted" value="{{ request('bulan_filter') }}">
            </div>
            <div class="col-md-2">
                <label class="small text-muted fw-bold mb-1">Metode</label>
                <select name="metode_filter" class="form-select shadow-sm rounded-3 text-muted">
                    <option value="">Semua Metode</option>
                    <option value="tunai" {{ request('metode_filter') == 'tunai' ? 'selected' : '' }}>Tunai / Kasir</option>
                    <option value="va" {{ request('metode_filter') == 'va' ? 'selected' : '' }}>Virtual Account</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm rounded-3">Filter</button>
                <a href="{{ route('riwayat.index') }}" class="btn btn-light w-100 fw-bold shadow-sm rounded-3 text-muted border">Reset</a>
            </div>
        </form>

        {{-- Tabel Riwayat Transaksi --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-secondary small">WAKTU TRANSAKSI</th>
                        <th class="text-secondary small">ORDER ID & KASIR</th>
                        <th class="text-secondary small">SISWA & KELAS</th>
                        <th class="text-secondary small">PEMBAYARAN UNTUK</th>
                        <th class="text-secondary small text-end">NOMINAL</th>
                        <th class="text-secondary small text-center">METODE & STATUS</th>
                        <th class="text-secondary small text-center">AKSI</th> {{-- Kolom Baru untuk Tombol --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayats as $trx)
                    <tr>
                        {{-- Waktu --}}
                        <td>
                            <span class="d-block fw-bold text-dark">{{ \Carbon\Carbon::parse($trx->tanggal_bayar)->format('d M Y') }}</span>
                            <span class="small text-muted">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</span>
                        </td>
                        
                        {{-- Order ID & Kasir --}}
                        <td>
                            <span class="badge bg-light text-dark border font-monospace">{{ $trx->order_id }}</span>
                            @if($trx->metode_pembayaran == 'Tunai / Manual' || $trx->metode_pembayaran == 'Tunai')
                                <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">Kasir: <strong>{{ $trx->pegawai->nama_lengkap ?? 'Bendahara' }}</strong></small>
                            @else
                                <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">Oleh: <strong>Sistem VA</strong></small>
                            @endif
                        </td>

                        {{-- Siswa --}}
                        <td>
                            <span class="fw-bold text-primary">{{ $trx->siswa->nama_lengkap ?? 'Siswa Dihapus' }}</span>
                            <span class="d-block small text-muted">{{ $trx->siswa->kelas->nama_kelas ?? '-' }} • {{ $trx->siswa->nisn ?? '-' }}</span>
                        </td>

                        {{-- Untuk SPP Bulan Apa --}}
                        <td>
                            <span class="fw-bold text-dark">SPP {{ $trx->tagihan_spp->bulan ?? '-' }}</span>
                            <span class="d-block small text-muted">T.A. {{ $trx->tagihan_spp->tahun_ajaran->tahun ?? '-' }}</span>
                        </td>

                        {{-- Nominal --}}
                        <td class="text-end fw-bold text-success">
                            Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                        </td>

                        {{-- Metode & Status --}}
                        <td class="text-center">
                            @if($trx->metode_pembayaran == 'Tunai / Manual' || $trx->metode_pembayaran == 'Tunai')
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1 mb-1 d-inline-block">
                                    <i class="bi bi-cash-stack me-1"></i> Tunai
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-primary border border-info-subtle px-2 py-1 mb-1 d-inline-block">
                                    <i class="bi bi-bank me-1"></i> VA Midtrans
                                </span>
                            @endif
                            
                            <div class="mt-1">
                                <span class="badge bg-success small"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                            </div>
                        </td>

                        {{-- AKSI: Tombol Cetak Struk --}}
                        <td class="text-center align-middle">
                            <a href="{{ route('riwayat.cetak', $trx->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-3 shadow-sm" title="Cetak Struk">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5"> {{-- Ubah colspan jadi 7 --}}
                            <i class="bi bi-search fs-1 d-block mb-3 opacity-50"></i>
                            Tidak ada transaksi yang ditemukan untuk filter tersebut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $riwayats->links() }}
        </div>

    </div>
</div>
@endsection