@extends('layouts.walikelas')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('walikelas.rekap') }}" class="btn btn-sm btn-outline-secondary mb-2 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Rekap
        </a>
        <h3 class="fw-bold text-dark">Rincian Nilai: {{ $siswa->nama_lengkap }}</h3>
        <p class="text-muted">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas }}</p>
    </div>
</div>

@foreach($nilaiGrouped as $kelompok => $dataNilai)
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-primary bg-opacity-10 border-0 pt-3 px-4">
        <h6 class="fw-bold text-primary mb-0">MUATAN KELOMPOK {{ $kelompok }}</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-uppercase">
                    <th class="ps-4">Mata Pelajaran</th>
                    <th class="text-center">Pengetahuan</th>
                    <th class="text-center">Keterampilan</th>
                    <th class="text-center">Nilai Akhir</th>
                    <th class="pe-4">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dataNilai as $n)
                <tr>
                    <td class="ps-4 fw-bold">{{ $n->mapel->nama_mapel }}</td>
                    <td class="text-center">{{ $n->nilai_pengetahuan ?? '-' }}</td>
                    <td class="text-center">{{ $n->nilai_keterampilan ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $n->nilai_akhir >= 75 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                            {{ $n->nilai_akhir }}
                        </span>
                    </td>
                    <td class="pe-4 small text-muted fst-italic">{{ $n->catatan_wali_kelas ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection