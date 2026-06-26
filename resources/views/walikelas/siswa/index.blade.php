@extends('layouts.walikelas')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Data Siswaku</h3>
        {{-- 🔥 PERBAIKAN ERROR NULL: Tambahkan '??' agar tidak error saat kelas belum di-set 🔥 --}}
        <p class="text-muted">Daftar siswa aktif di kelas <strong class="badge bg-primary px-2 py-1 fs-6">{{ $waliKelas->kelas->nama_kelas ?? 'Belum Ditugaskan' }}</strong></p>
    </div>
</div>

{{-- 🔥 PENGAMAN JIKA GURU BELUM PUNYA KELAS 🔥 --}}
@if(empty($waliKelas->kelas_id))
    <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center p-4">
        <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px; min-width: 60px;">
            <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
        </div>
        <div>
            <h5 class="fw-bold text-dark mb-1">Data Tidak Tersedia</h5>
            <p class="mb-0 text-dark opacity-75">Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun, sehingga daftar siswa tidak dapat ditampilkan.</p>
        </div>
    </div>
@else

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2">
        <form action="{{ route('walikelas.siswa.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 focus-ring focus-ring-light" placeholder="Cari nama atau NISN siswa..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm rounded-3">Cari Siswa</button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('walikelas.siswa.index') }}" class="btn btn-light w-100 fw-bold shadow-sm rounded-3 border">Reset</a>
            </div>
            @endif
        </form>
    </div>
    
    <div class="card-body p-0 mt-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 border-0 py-3" width="5%">No</th>
                        <th class="border-0 py-3">Data Siswa</th>
                        <th class="border-0 py-3">NISN</th>
                        <th class="border-0 py-3">Kontak Wali</th>
                        <th class="text-center pe-4 border-0 py-3" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $s)
                    <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                        <td class="ps-4 text-muted fw-medium">{{ $loop->iteration }}</td>
                        
                        {{-- 🔥 DESAIN MODERN DENGAN AVATAR PINTAR 🔥 --}}
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $avatarSiswa = ($s->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                                @endphp
                                <img src="{{ asset($avatarSiswa) }}" class="rounded-circle shadow-sm me-3 border border-2 border-white" width="45" height="45" style="object-fit: cover; background: #e2e8f0;" alt="Avatar">
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $s->nama_lengkap }}</span>
                                    <span class="badge {{ $s->jk == 'L' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $s->jk == 'L' ? 'text-primary' : 'text-danger' }} small rounded-pill mt-1">
                                        {{ $s->jk == 'L' ? 'Laki-Laki' : 'Perempuan' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span class="text-secondary fw-medium font-monospace bg-light px-2 py-1 rounded">{{ $s->nisn }}</span>
                        </td>
                        
                        <td>
                            @if($s->no_hp_ortu)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->no_hp_ortu) }}" target="_blank" class="text-decoration-none text-success fw-medium d-inline-flex align-items-center bg-success bg-opacity-10 px-3 py-1 rounded-pill transition-all" style="font-size: 0.85rem;" onmouseover="this.classList.add('shadow-sm')" onmouseout="this.classList.remove('shadow-sm')">
                                    <i class="bi bi-whatsapp me-2 fs-6"></i> {{ $s->no_hp_ortu }}
                                </a>
                            @else
                                <span class="text-muted small fst-italic">- Belum Diisi -</span>
                            @endif
                        </td>
                        
                        <td class="text-center pe-4">
                            <a href="{{ route('walikelas.siswa.show', $s->id) }}" class="btn btn-sm btn-light border fw-bold rounded-pill px-4 shadow-sm" style="color: var(--primary-color);">
                                Detail <i class="bi bi-arrow-right-short ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="bi bi-search fs-1 opacity-25"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Siswa Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0">Belum ada data siswa di kelas ini atau kata kunci tidak cocok.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($siswas->count() > 0)
    <div class="card-footer bg-light border-top-0 p-4 rounded-bottom-4">
        <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Menampilkan total <strong>{{ $siswas->count() }}</strong> siswa aktif di kelas ini.</p>
    </div>
    @endif
</div>

@endif
@endsection