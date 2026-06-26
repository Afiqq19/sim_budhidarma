@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="mb-4">
    <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;"><i class="bi bi-archive-fill me-2 text-primary opacity-50"></i>Arsip Data Alumni</h3>
    <p class="text-secondary mb-0" style="font-size: 0.95rem;">Manajemen data siswa yang telah lulus atau pindah sekolah.</p>
</div>

@php
    $dataAlumni = $alumnis->where('status_siswa', 'Alumni');
    $dataKeluar = $alumnis->where('status_siswa', '!=', 'Alumni');
@endphp

{{-- ================= TAB NAVIGASI MODERN ================= --}}
<div class="card border-0 rounded-4 shadow-sm bg-white mb-4" style="border: 1px solid #e2e8f0 !important;">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <ul class="nav nav-pills gap-2" id="arsipTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4 py-2 rounded-pill fw-bold" id="alumni-tab" data-bs-toggle="tab" data-bs-target="#alumni-pane" type="button" role="tab" style="background-color: #f0fdf4; color: #166534;">
                    <i class="bi bi-mortarboard-fill me-2"></i> Lulusan ({{ $dataAlumni->count() }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-2 rounded-pill fw-bold" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar-pane" type="button" role="tab" style="background-color: #fef2f2; color: #991b1b;">
                    <i class="bi bi-box-arrow-right me-2"></i> Pindah / Keluar ({{ $dataKeluar->count() }})
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-4 pt-3">
        {{-- Pencarian --}}
        <div class="mb-4">
            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-light" style="border: 1px solid #e2e8f0; max-width: 400px;">
                <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                <input type="text" id="liveSearch" class="form-control border-0 shadow-none bg-transparent fw-medium" placeholder="Cari nama atau NISN di arsip...">
            </div>
        </div>

        <div class="tab-content" id="arsipTabContent">
            {{-- TAB 1: ALUMNI --}}
            <div class="tab-pane fade show active" id="alumni-pane" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableAlumni">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">No</th>
                                <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">NISN</th>
                                <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Nama Lengkap</th>
                                <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Tahun Lulus</th>
                                <th class="pe-4 text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataAlumni as $item)
                            <tr class="searchable-row">
                                <td class="ps-4 text-secondary">{{ $loop->iteration }}</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border font-monospace">{{ $item->nisn }}</span></td>
                                <td class="fw-bold text-dark">{{ $item->nama_lengkap }}</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 rounded-pill">Lulus {{ $item->tahun_lulus ?? '-' }}</span></td>
                                <td class="pe-4 text-center">
                                    <a href="{{ route('tu.alumni.show', $item->id) }}" class="btn btn-sm btn-light border text-primary shadow-sm rounded-3"><i class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: PINDAH/KELUAR --}}
            <div class="tab-pane fade" id="keluar-pane" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableKeluar">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">No</th>
                                <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">NISN</th>
                                <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Nama Lengkap</th>
                                <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Status</th>
                                <th class="pe-4 text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataKeluar as $item)
                            <tr class="searchable-row">
                                <td class="ps-4 text-secondary">{{ $loop->iteration }}</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border font-monospace">{{ $item->nisn }}</span></td>
                                <td class="fw-bold text-dark">{{ $item->nama_lengkap }}</td>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 rounded-pill">{{ $item->status_siswa }}</span></td>
                                <td class="pe-4 text-center">
                                    <a href="{{ route('tu.alumni.show', $item->id) }}" class="btn btn-sm btn-light border text-primary shadow-sm rounded-3"><i class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Data kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Live Search untuk kedua tab
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.searchable-row');
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection