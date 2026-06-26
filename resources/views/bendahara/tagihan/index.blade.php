@extends('layouts.bendahara')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark"><i class="bi bi-receipt text-primary me-2"></i>Manajemen Data Tagihan</h3>
    <p class="text-muted">Atur Master Tarif SPP dan Generate tagihan massal untuk seluruh siswa aktif.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 border-0" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Tahun Ajaran Aktif</h5>
                        <h3 class="text-primary fw-bold mb-0">{{ $tahunAktif ? $tahunAktif->tahun . ' (' . $tahunAktif->semester . ')' : 'Belum Ada' }}</h3>
                    </div>
                    <i class="bi bi-calendar-check fs-1 text-primary opacity-25"></i>
                </div>

                <div class="row text-center mb-4">
                    <div class="col-4 border-end">
                        <p class="text-muted mb-1">Total Siswa Aktif</p>
                        <h4 class="fw-bold">{{ $jumlahSiswaAktif }} <span class="fs-6 fw-normal">Siswa</span></h4>
                    </div>
                    <div class="col-4 border-end">
                        <p class="text-muted mb-1">Tagihan Ter-generate</p>
                        <h4 class="fw-bold text-success">{{ number_format($jumlahTagihanDibuat, 0, ',', '.') }} <span class="fs-6 fw-normal">Lembar</span></h4>
                    </div>
                    <div class="col-4">
                        <p class="text-muted mb-1">Master Tarif SPP (Saat Ini)</p>
                        {{-- MENGGUNAKAN VARIABEL $nominal_tampil --}}
                        <h4 class="fw-bold text-danger">Rp {{ number_format($nominal_tampil, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <div class="alert alert-warning border-0 rounded-3 shadow-sm mb-0">
                    <i class="bi bi-info-circle-fill me-2"></i> 
                    Sistem akan otomatis membuatkan 12 bulan tagihan SPP untuk setiap Siswa berdasarkan Master Tarif SPP yang tersimpan.
                </div>
            </div>
            
            <div class="card-footer bg-light border-top p-4">
                @if($tahunAktif && $jumlahSiswaAktif > 0)
                    <div class="row align-items-center">
                        
                        {{-- KIRI: Form Save Master Nominal --}}
                        <div class="col-md-5 border-end border-2 pe-4">
                            <label class="fw-bold text-dark mb-2 small"><i class="bi bi-1-circle-fill text-primary me-1"></i>Kunci Master Tarif SPP</label>
                            <form action="{{ route('tagihan.setNominal') }}" method="POST" class="d-flex">
                                @csrf
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white fw-bold text-muted border-end-0">Rp</span>
                                    
                                    {{-- MENGGUNAKAN VARIABEL $nominal_tampil DI INPUT VALUE --}}
                                    <input type="number" name="nominal" class="form-control border-start-0 fw-bold {{ $jumlahTagihanDibuat > 0 ? 'text-secondary bg-light' : 'text-primary' }}" value="{{ $nominal_tampil }}" {{ $jumlahTagihanDibuat > 0 ? 'readonly' : 'required' }}>
                                    
                                    @if($jumlahTagihanDibuat > 0)
                                        <button type="button" class="btn btn-secondary fw-bold px-3" disabled title="Terkunci"><i class="bi bi-lock-fill"></i></button>
                                    @else
                                        <button type="submit" class="btn btn-dark fw-bold px-3">SAVE</button>
                                    @endif
                                </div>
                            </form>
                            
                            @if($jumlahTagihanDibuat > 0)
                                <small class="text-danger mt-2 d-block" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Tarif dikunci permanen karena tagihan tahun ini sudah berjalan.</small>
                            @endif
                        </div>

                        {{-- KANAN: Tombol Generate Massal --}}
                        <div class="col-md-7 ps-4">
                            <label class="fw-bold text-dark mb-2 small"><i class="bi bi-2-circle-fill text-primary me-1"></i>Eksekusi Tagihan</label>
                            <form action="{{ route('tagihan.generateMassal') }}" method="POST">
                                @csrf
                                {{-- MENGGUNAKAN VARIABEL $nominal_tampil DI NOTIFIKASI POPUP --}}
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-pill shadow-sm" onclick="return confirm(`Yakin ingin men-generate ribuan tagihan dengan tarif Rp {{ number_format($nominal_tampil, 0, ',', '.') }} sekarang?`)">
                                    <i class="bi bi-magic me-2"></i> GENERATE MASSAL
                                </button>
                            </form>
                        </div>
                        
                    </div>
                @else
                    <button class="btn btn-secondary btn-lg w-100 fw-bold rounded-pill shadow-sm" disabled>
                        <i class="bi bi-lock me-2"></i> Atur Tahun Ajaran Aktif Terlebih Dahulu
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection