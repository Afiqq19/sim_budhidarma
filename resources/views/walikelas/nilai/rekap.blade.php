@extends('layouts.walikelas')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-sm-center flex-column flex-sm-row gap-3">
    <div>
        <h3 class="fw-bold text-dark"><i class="bi bi-trophy-fill text-warning me-2"></i>Rekapitulasi Nilai Kelas</h3>
        <p class="text-muted mb-0">Leger nilai dan peringkat siswa kelas <strong class="badge bg-primary px-2 py-1 fs-6">{{ $waliKelas->kelas->nama_kelas ?? 'Belum Ditugaskan' }}</strong></p>
    </div>
    
    {{-- 🔥 TAHUN AJARAN AKTIF (TIDAK BISA DIUBAH-UBAH) 🔥 --}}
    <div class="mt-3 mt-sm-0 text-sm-end">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold border border-primary border-opacity-25 shadow-sm">
            <i class="bi bi-calendar-event me-1"></i> T.A: {{ $tahunAktif->tahun ?? '-' }}
        </span>
        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold border border-info border-opacity-25 ms-2 shadow-sm">
            <i class="bi bi-bookmark-star me-1"></i> Semester: {{ $tahunAktif->semester ?? '-' }}
        </span>
    </div>
</div>

{{-- 🔥 PENGAMAN JIKA GURU BELUM PUNYA KELAS 🔥 --}}
@if(empty($waliKelas->kelas_id))
    <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center p-4">
        <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px; min-width: 60px;">
            <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
        </div>
        <div>
            <h5 class="fw-bold text-dark mb-1">Akses Rekap Terkunci</h5>
            <p class="mb-0 text-dark opacity-75">Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun. Rekapitulasi nilai tidak dapat ditampilkan sebelum Anda memiliki kelas binaan.</p>
        </div>
    </div>
@else

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-columns-reverse text-primary me-2"></i>Peringkat & Total Nilai Siswa</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                    <tr>
                        <th class="text-start ps-4 py-3 border-0" width="5%">No</th>
                        <th class="text-start py-3 border-0" width="30%">Data Siswa</th>
                        <th class="py-3 border-0" width="15%">Mapel Diisi</th>
                        <th class="py-3 border-0" width="15%">Total Nilai</th>
                        <th class="py-3 border-0" width="15%">Peringkat</th>
                        <th class="py-3 border-0 pe-4" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $s)
                    <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                        <td class="text-start ps-4 text-muted fw-medium">{{ $loop->iteration }}</td>
                        
                        {{-- 🔥 AVATAR PINTAR 🔥 --}}
                        <td class="text-start py-3">
                            <div class="d-flex align-items-center">
                                @php
                                    $avatarSiswa = ($s->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                                @endphp
                                <img src="{{ asset($avatarSiswa) }}" class="rounded-circle shadow-sm me-3 border border-2 border-white" width="45" height="45" style="object-fit: cover; background: #e2e8f0;" alt="Avatar">
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $s->nama_lengkap }}</span>
                                    <span class="text-muted small font-monospace bg-light px-2 py-1 rounded">{{ $s->nisn }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @if($s->jumlah_mapel == 0)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Belum ada nilai</span>
                            @else
                                <span class="fw-bold text-primary fs-6">{{ $s->jumlah_mapel }}</span> <span class="text-muted small">Mapel</span>
                            @endif
                        </td>
                        
                        <td>
                            <span class="fs-5 fw-bold {{ $s->total_nilai > 0 ? 'text-dark' : 'text-muted opacity-50' }}">{{ $s->total_nilai }}</span>
                        </td>
                        
                        <td>
                            @if($s->peringkat === 1)
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-trophy-fill me-1"></i> Juara 1</span>
                            @elseif($s->peringkat === 2)
                                <span class="badge bg-secondary px-3 py-2 rounded-pill shadow-sm text-white"><i class="bi bi-award-fill me-1"></i> Juara 2</span>
                            @elseif($s->peringkat === 3)
                                <span class="badge text-white px-3 py-2 rounded-pill shadow-sm" style="background-color: #cd7f32;"><i class="bi bi-award-fill me-1"></i> Juara 3</span>
                            @elseif($s->peringkat === '-')
                                <span class="text-muted small">-</span>
                            @else
                                <span class="fw-bold text-muted">Ke-{{ $s->peringkat }}</span>
                            @endif
                        </td>
                        
                        <td class="pe-4">
                            <a href="{{ route('walikelas.nilai.detail', $s->id) }}" class="btn btn-sm btn-light border fw-bold text-primary rounded-pill px-4 shadow-sm transition-all hover-lift">
                                <i class="bi bi-eye-fill me-1"></i> Rapor
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="bi bi-folder-x fs-1 opacity-25"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Data Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0">Belum ada siswa yang terdaftar di kelas ini pada Tahun Ajaran Aktif.</p>
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