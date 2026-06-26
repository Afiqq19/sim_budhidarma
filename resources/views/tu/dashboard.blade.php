@extends('layouts.tu')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                    <p class="mb-0 fs-5">Anda login sebagai Staf Tata Usaha. Silakan kelola data operasional dan akademik sekolah hari ini.</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="bi bi-speedometer2" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Baris 1: Statistik Kesiswaan --}}
<h5 class="fw-bold text-secondary mb-3"><i class="bi bi-people-fill me-2"></i>Statistik Peserta Didik</h5>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4 rounded-3 h-100 position-relative overflow-hidden">
            <div class="card-body">
                <h6 class="text-muted fw-bold mb-2">SISWA AKTIF</h6>
                <h2 class="fw-bold text-primary mb-0">{{ $total_siswa }} <small class="fs-6 text-muted">Anak</small></h2>
                <i class="bi bi-person-check-fill position-absolute text-primary" style="font-size: 4rem; opacity: 0.1; right: -10px; bottom: -15px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-success border-4 rounded-3 h-100 position-relative overflow-hidden">
            <div class="card-body">
                <h6 class="text-muted fw-bold mb-2">LULUSAN / ALUMNI</h6>
                <h2 class="fw-bold text-success mb-0">{{ $total_alumni }} <small class="fs-6 text-muted">Anak</small></h2>
                <i class="bi bi-mortarboard-fill position-absolute text-success" style="font-size: 4rem; opacity: 0.1; right: -10px; bottom: -15px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-danger border-4 rounded-3 h-100 position-relative overflow-hidden">
            <div class="card-body">
                <h6 class="text-muted fw-bold mb-2">PINDAH / KELUAR</h6>
                <h2 class="fw-bold text-danger mb-0">{{ $total_pindah }} <small class="fs-6 text-muted">Anak</small></h2>
                <i class="bi bi-box-arrow-right position-absolute text-danger" style="font-size: 4rem; opacity: 0.1; right: -10px; bottom: -15px;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Baris 2: Statistik Akademik --}}
<h5 class="fw-bold text-secondary mb-3 mt-2"><i class="bi bi-building me-2"></i>Statistik Akademik & Guru</h5>
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-info border-4 rounded-3 h-100 position-relative overflow-hidden bg-light">
            <div class="card-body">
                <h6 class="text-muted fw-bold mb-2">JURUSAN / PROGRAM</h6>
                <h2 class="fw-bold text-dark mb-0">{{ $total_jurusan }}</h2>
                <i class="bi bi-diagram-3-fill position-absolute text-info" style="font-size: 4rem; opacity: 0.1; right: -10px; bottom: -15px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-warning border-4 rounded-3 h-100 position-relative overflow-hidden bg-light">
            <div class="card-body">
                <h6 class="text-muted fw-bold mb-2">ROMBEL / KELAS</h6>
                <h2 class="fw-bold text-dark mb-0">{{ $total_kelas }}</h2>
                <i class="bi bi-door-open-fill position-absolute text-warning" style="font-size: 4rem; opacity: 0.1; right: -10px; bottom: -15px;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-dark border-4 rounded-3 h-100 position-relative overflow-hidden bg-light">
            <div class="card-body">
                <h6 class="text-muted fw-bold mb-2">WALI KELAS DITETAPKAN</h6>
                <h2 class="fw-bold text-dark mb-0">{{ $total_walikelas }}</h2>
                <i class="bi bi-person-video3 position-absolute text-dark" style="font-size: 4rem; opacity: 0.1; right: -10px; bottom: -15px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Kiri: Tabel Siswa Terbaru --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-plus-fill me-2"></i>Pendaftaran Siswa Terbaru</h6>
                <a href="{{ route('tu.siswa.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Lengkap</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Gender</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa_baru as $siswa)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $siswa->nama_lengkap }}</td>
                                <td><code>{{ $siswa->nisn }}</code></td>
                                <td><span class="badge bg-info text-dark">{{ $siswa->kelas->nama_kelas ?? '-' }}</span></td>
                                <td>{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Kanan: Aksi Cepat --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white" style="background: linear-gradient(180deg, #0d6efd 0%, #0a58ca 100%);">
            <div class="card-body p-4">
                <h5 class="fw-bold border-bottom border-light pb-2 mb-4"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat TU</h5>
                
                <div class="d-grid gap-3">
                    <a href="{{ route('tu.siswa.create') }}" class="btn btn-light text-primary fw-bold text-start shadow-sm py-2">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah Siswa Baru
                    </a>
                    <a href="{{ route('tu.walikelas.index') }}" class="btn btn-light text-primary fw-bold text-start shadow-sm py-2">
                        <i class="bi bi-person-video3 me-2"></i> Penempatan Wali Kelas
                    </a>
                    <a href="{{ route('tu.alumni.index') }}" class="btn btn-light text-primary fw-bold text-start shadow-sm py-2">
                        <i class="bi bi-mortarboard-fill me-2"></i> Lihat Arsip Alumni
                    </a>
                </div>
                
                <div class="mt-4 pt-3 border-top border-light text-center opacity-75">
                    <small><i class="bi bi-info-circle me-1"></i> Gunakan tombol di atas untuk bekerja lebih cepat.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection