@extends('layouts.bendahara')

@section('content')
{{-- ================= BAGIAN HEADER & FILTER ================= --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div class="d-flex align-items-center">
        
        {{-- Tombol Kembali Modern --}}
        <a href="{{ route('tunggakan.index') }}" class="btn btn-white bg-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 42px; height: 42px; border: 1px solid #e2e8f0; border-radius: 12px; transition: all 0.2s;" title="Kembali ke Daftar Tunggakan">
            <i class="bi bi-arrow-left fs-5 text-secondary"></i>
        </a>
        
        <div>
            <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Kartu Pembayaran SPP</h3>
            <p class="text-secondary mb-0" style="font-size: 0.9rem;">
                Data Tagihan Tahun Ajaran: <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1 px-2 py-1">{{ $selectedTahun ?? 'Belum disetting' }}</span>
            </p>
        </div>
    </div>
    
    {{-- Form Filter Tahun Ajaran --}}
    @if(count($listTahun) > 0)
    <form action="{{ route('transaksi.show', $siswa->id) }}" method="GET" class="d-flex align-items-center bg-white p-1 rounded-pill shadow-sm" style="border: 1px solid #e2e8f0;">
        <label for="ta" class="fw-semibold me-2 ms-3 text-muted" style="font-size: 0.85rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i>Filter T.A:</label>
        <select name="ta" id="ta" class="form-select border-0 fw-bold text-dark cursor-pointer rounded-pill py-2 pe-4 ps-3" onchange="this.form.submit()" style="outline: none; box-shadow: none; background-color: #f8fafc; font-size: 0.9rem;">
            @foreach($listTahun as $ta)
                <option value="{{ $ta }}" {{ $selectedTahun == $ta ? 'selected' : '' }}>{{ $ta }}</option>
            @endforeach
        </select>
    </form>
    @endif
</div>

{{-- ================= ALERT PESAN ================= --}}
@if(session('success'))
    <div class="alert bg-success bg-opacity-10 border-0 border-start border-success border-4 text-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> 
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert bg-danger bg-opacity-10 border-0 border-start border-danger border-4 text-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i> 
        <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ================= IDENTITAS PROFIL SISWA ================= --}}
<div class="card border-0 rounded-4 mb-4 bg-white" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
    <div class="card-body p-4 d-flex align-items-center flex-wrap gap-3">
        {{-- Avatar Inisial Lembut --}}
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 70px; height: 70px; font-size: 2rem; font-weight: 800; background-color: #eff6ff; color: #2563eb; border: 2px solid #bfdbfe;">
            {{ substr($siswa->nama_lengkap, 0, 1) }}
        </div>
        
        <div class="flex-grow-1">
            <h4 class="fw-bolder text-dark mb-2">{{ $siswa->nama_lengkap }}</h4>
            <div class="d-flex flex-wrap text-muted gap-2">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium"><i class="bi bi-person-vcard text-muted me-1"></i> NISN: <strong>{{ $siswa->nisn }}</strong></span>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium"><i class="bi bi-building text-muted me-1"></i> Kelas: <strong>{{ $siswa->kelas->nama_kelas ?? 'Belum punya kelas' }}</strong></span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Siswa {{ $siswa->status_siswa }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ================= GRID 12 BULAN KARTU SPP ================= --}}
<div class="d-flex justify-content-between align-items-end mb-3 mt-5">
    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar2-range text-primary me-2"></i> Rincian Tagihan 12 Bulan</h5>
    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-medium" style="font-size: 0.75rem;"><i class="bi bi-robot me-1"></i> Faktur Otomatis</span>
</div>

<div class="row g-4">
    @foreach($bulan_spp as $bulan)
        @php
            $tagihan = $tagihans[$bulan] ?? null;
        @endphp
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-2">
            @if($tagihan)
                @if($tagihan->status == 'Lunas')
                    {{-- [1] KARTU HIJAU: SUDAH LUNAS --}}
                    <div class="card border-0 rounded-4 bg-white h-100 position-relative overflow-hidden" style="box-shadow: 0 4px 15px rgba(16, 185, 129, 0.05); border: 1px solid #10b981 !important;">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <i class="bi bi-patch-check-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <div class="card-body p-4 text-center d-flex flex-column z-1">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 mx-auto mb-3 fw-bold border border-success border-opacity-25" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;">
                                <i class="bi bi-check2-all me-1"></i> LUNAS
                            </span>
                            <h5 class="fw-bold text-dark mb-1">{{ $bulan }}</h5>
                            <h4 class="fw-bolder text-success mb-4 mt-2">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</h4>
                            
                            {{-- Tombol Cetak Struk --}}
                            <div class="mt-auto w-100">
                                @php
                                    $pembayaran = \App\Models\Pembayaran::where('tagihan_spp_id', $tagihan->id)->first();
                                @endphp

                                @if($pembayaran)
                                    <a href="{{ route('riwayat.cetak', $pembayaran->id) }}" target="_blank" class="btn btn-light text-success border-success border-opacity-25 w-100 rounded-pill fw-semibold shadow-sm transition-all hover-success-btn">
                                        <i class="bi bi-printer-fill me-1"></i> Cetak Bukti
                                    </a>
                                @else
                                    <span class="text-danger small fw-bold"><i class="bi bi-x-circle me-1"></i> Bukti Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- [2] KARTU PUTIH/MERAH: BELUM LUNAS --}}
                    <div class="card border-0 rounded-4 bg-white h-100 position-relative" style="box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #e2e8f0 !important;">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 mx-auto mb-3 fw-bold border border-danger border-opacity-25" style="font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase;">
                                <i class="bi bi-exclamation-circle me-1"></i> MENUNGGAK
                            </span>
                            <h5 class="fw-bold text-dark mb-1">{{ $bulan }}</h5>
                            <small class="text-muted mb-3 d-block" style="font-size: 0.8rem;"><i class="bi bi-clock-history me-1"></i>Jatuh tempo: 25 {{ $bulan }}</small>
                            <h4 class="fw-bolder text-danger mb-4">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</h4>
                            
                            {{-- Tombol Pemicu Modal (Pakai warna Biru/Primary agar positif) --}}
                            <div class="mt-auto">
                                <button type="button" class="btn btn-primary w-100 fw-semibold shadow-sm rounded-pill transition-all" data-bs-toggle="modal" data-bs-target="#modalBayar-{{ $tagihan->id }}">
                                    <i class="bi bi-wallet2 me-1"></i> Proses Bayar
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 🔥 MODAL KONFIRMASI MODERN 🔥 --}}
                    <div class="modal fade" id="modalBayar-{{ $tagihan->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $tagihan->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="modal-header bg-primary bg-opacity-10 border-0 p-4 pb-3">
                                    <h5 class="modal-title fw-bold text-primary" id="modalLabel-{{ $tagihan->id }}"><i class="bi bi-wallet-fill me-2"></i>Konfirmasi Terima Dana</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4 text-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-cash-stack" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <p class="text-muted mb-1 fs-6">Terima Tunai (Cash) untuk Bulan</p>
                                    <h4 class="fw-bolder text-dark mb-2">{{ $bulan }} (T.A {{ $selectedTahun }})</h4>
                                    <h2 class="fw-bolder text-primary mb-4">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</h2>
                                    
                                    <div class="alert bg-warning bg-opacity-10 border-0 border-start border-warning border-4 text-dark text-start small p-3 rounded-3 mb-0">
                                        <i class="bi bi-shield-exclamation me-1 text-warning fs-6"></i> <strong>Penting:</strong> Pastikan uang fisik sejumlah nominal di atas sudah Anda terima. Transaksi ini akan tercatat permanen di sistem.
                                    </div>
                                </div>
                                <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-between">
                                    <button type="button" class="btn btn-white border rounded-pill px-4 fw-medium text-muted" data-bs-dismiss="modal">Batal</button>
                                    
                                    <form action="{{ route('transaksi.storeManual', $siswa->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                                        <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                            <i class="bi bi-check2-circle me-1"></i> Konfirmasi & Simpan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                {{-- [3] KARTU DASHED: BELUM DI-GENERATE --}}
                <div class="card border-0 rounded-4 h-100 bg-transparent" style="border: 2px dashed #cbd5e1 !important; background-color: #f8fafc !important;">
                    <div class="card-body text-center d-flex flex-column justify-content-center py-5">
                        <h6 class="fw-bold text-secondary mb-2">{{ $bulan }}</h6>
                        <i class="bi bi-dash-circle fs-3 text-secondary opacity-50 mb-2"></i>
                        <span class="small fw-semibold text-muted">Belum ada tagihan</span>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>

{{-- Tambahan Style Hover khusus halaman ini --}}
<style>
    .hover-success-btn:hover {
        background-color: #10b981 !important;
        color: #ffffff !important;
    }
</style>
@endsection 