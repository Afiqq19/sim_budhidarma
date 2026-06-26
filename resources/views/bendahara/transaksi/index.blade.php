@extends('layouts.bendahara')

@section('content')
<div class="mb-4 text-center">
    <h3 class="fw-bold text-dark"><i class="bi bi-cash-coin text-primary me-2"></i>Kasir Pembayaran SPP</h3>
    <p class="text-muted">Cari siswa berdasarkan Nama atau NISN untuk melihat Kartu Tagihan</p>
</div>

{{-- Kotak Pencarian --}}
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 p-2">
            <div class="card-body">
                <form action="{{ route('transaksi.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-lg border-primary me-2" placeholder="Ketik NISN atau Nama Lengkap Siswa..." value="{{ request('search') }}" required>
                    <button type="submit" class="btn btn-primary btn-lg fw-bold px-4 shadow-sm">
                        <i class="bi bi-search me-2"></i> Cari
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Hasil Pencarian --}}
@if(request('search'))
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold border-bottom pb-2 mb-3">Hasil Pencarian: <span class="text-primary">"{{ request('search') }}"</span></h5>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>NISN</th>
                            <th>Nama Lengkap Siswa</th>
                            <th>Kelas</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $siswa->nisn }}</span></td>
                            <td class="fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</td>
                            <td class="text-center">
                                <a href="{{ route('transaksi.show', $siswa->id) }}" class="btn btn-success fw-bold shadow-sm">
                                    <i class="bi bi-wallet2 me-1"></i> Buka Kartu SPP
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger py-4">
                                <i class="bi bi-exclamation-circle fs-3 d-block mb-2"></i>
                                Siswa tidak ditemukan atau statusnya sudah tidak aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection