@extends('layouts.siswa')

@section('content')

@php
    // 🔥 PENGATURAN TEMA KELAS BERDASARKAN STATUS 🔥
    $badgeKelasClass = 'bg-primary text-white';
    $teksKelas = $siswa->kelas->nama_kelas ?? '-';
    
    if ($siswa->status_siswa == 'Alumni') {
        $badgeKelasClass = 'bg-secondary text-white';
        $teksKelas = 'Lulusan / Alumni';
    } elseif ($siswa->status_siswa == 'Pindah') {
        $badgeKelasClass = 'bg-warning text-dark';
        $teksKelas = 'Status Pindah';
    }
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-journal-bookmark-fill text-warning me-2"></i>E-Rapor Akademik</h3>
        <p class="text-muted mb-0">Status Akademik: <strong class="badge {{ $badgeKelasClass }}">{{ $teksKelas }}</strong></p>
    </div>
    
    <form action="{{ route('siswa.rapor') }}" method="GET" class="d-flex gap-2 d-print-none">
        <select name="tahun_ajaran_id" class="form-select border-warning shadow-sm fw-bold focus-ring focus-ring-warning" onchange="this.form.submit()" style="min-width: 250px;">
            @forelse($listTahunAjaran as $ta)
                <option value="{{ $ta->id }}" {{ $selectedTahunId == $ta->id ? 'selected' : '' }}>
                    T.A {{ $ta->tahun }} - Semester {{ ucfirst($ta->semester) }} {{ (isset($tahunAktifObj) && $ta->id == $tahunAktifObj->id) ? '(Aktif)' : '' }}
                </option>
            @empty
                <option value="">Tidak ada data Tahun Ajaran</option>
            @endforelse
        </select>
    </form>
</div>

{{-- 3 Kartu Ringkasan Statistik --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-warning bg-opacity-10 border-start border-warning border-4 transition-all" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-white text-warning p-3 rounded-circle shadow-sm me-3"><i class="bi bi-trophy-fill fs-3"></i></div>
                <div>
                    <h6 class="text-warning fw-bold text-uppercase small mb-1">Peringkat Kelas</h6>
                    @if($jumlahMapel > 0)
                        <h3 class="fw-bold text-dark mb-0">Ke-{{ $myRank }} <span class="fs-6 text-muted fw-normal">dari {{ $totalSiswa }} Siswa</span></h3>
                    @else
                        <h4 class="fw-bold text-dark mb-0">-</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary bg-opacity-10 border-start border-primary border-4 transition-all" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-white text-primary p-3 rounded-circle shadow-sm me-3"><i class="bi bi-calculator-fill fs-3"></i></div>
                <div>
                    <h6 class="text-primary fw-bold text-uppercase small mb-1">Rata-Rata Nilai</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $rataRata }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-success bg-opacity-10 border-start border-success border-4 transition-all" style="transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-white text-success p-3 rounded-circle shadow-sm me-3"><i class="bi bi-bar-chart-line-fill fs-3"></i></div>
                <div>
                    <h6 class="text-success fw-bold text-uppercase small mb-1">Total Nilai</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalNilai }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Detail Nilai --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white pt-4 px-4 pb-3 border-bottom-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-columns-reverse text-warning me-2"></i>Daftar Nilai (Semester {{ $tahunDilihat ? ucfirst($tahunDilihat->semester) : '-' }})</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small fw-bold text-muted tracking-wider">
                    <tr>
                        <th class="ps-4 text-center py-3 border-0" width="5%">No</th>
                        <th class="py-3 border-0" width="35%">Mata Pelajaran</th>
                        <th class="text-center py-3 border-0" width="8%">KKM</th>
                        <th class="text-center py-3 border-0" width="10%">PENG</th>
                        <th class="text-center py-3 border-0" width="10%">KTR</th>
                        <th class="text-center py-3 border-0" width="10%">Akhir</th>
                        <th class="text-center py-3 border-0" width="10%">Predikat</th>
                        <th class="pe-4 text-center py-3 border-0" width="12%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $kumpulanCatatan = []; 
                    @endphp

                    @forelse($nilaiGrouped as $kelompok => $kumpulanNilai)
                        <tr class="bg-light bg-opacity-50">
                            <td colspan="8" class="ps-4 fw-bold text-primary py-2 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                @if(str_contains(strtoupper($kelompok), 'A')) Kelompok A (Muatan Nasional)
                                @elseif(str_contains(strtoupper($kelompok), 'B')) Kelompok B (Muatan Kewilayahan)
                                @elseif(str_contains(strtoupper($kelompok), 'C')) Kelompok {{ strtoupper($kelompok) }} (Muatan Peminatan Kejuruan)
                                @else Kelompok {{ $kelompok }} @endif
                            </td>
                        </tr>

                        @foreach($kumpulanNilai as $n)
                        @php 
                            if($n->catatan_wali_kelas) {
                                $kumpulanCatatan[] = [
                                    'mapel' => $n->mapel->nama_mapel ?? 'Mata Pelajaran',
                                    'teks' => $n->catatan_wali_kelas
                                ];
                            }
                        @endphp
                        <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="ps-4 text-center text-muted fw-medium">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $n->mapel->nama_mapel ?? 'Nama Mapel Tidak Ditemukan' }}</td>
                            <td class="text-center text-muted fw-medium">{{ $n->mapel->kkm ?? 75 }}</td> 
                            <td class="text-center text-dark">{{ $n->nilai_pengetahuan ?? '-' }}</td>
                            <td class="text-center text-dark">{{ $n->nilai_keterampilan ?? '-' }}</td>
                            <td class="text-center">
                                <span class="fs-6 fw-bold {{ $n->nilai_akhir >= ($n->mapel->kkm ?? 75) ? 'text-success' : 'text-danger' }}">
                                    {{ $n->nilai_akhir }}
                                </span>
                            </td>
                            <td class="text-center fw-bold fs-6">
                                @if($n->nilai_akhir >= 90) <span class="text-primary">A</span>
                                @elseif($n->nilai_akhir >= 80) <span class="text-success">B</span>
                                @elseif($n->nilai_akhir >= ($n->mapel->kkm ?? 75)) <span class="text-warning">C</span>
                                @else <span class="text-danger">D</span> @endif
                            </td>
                            <td class="pe-4 text-center">
                                @if($n->nilai_akhir >= ($n->mapel->kkm ?? 75))
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill border border-success border-opacity-25 shadow-sm">Tuntas</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1 rounded-pill border border-danger border-opacity-25 shadow-sm">Remedial</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-file-earmark-x fs-1 opacity-25"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Nilai Belum Tersedia</h6>
                                <p class="text-muted small mb-0">Belum ada nilai yang diinput pada semester ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bagian Catatan Per Mata Pelajaran --}}
<div class="card border-0 shadow-sm rounded-4 bg-light border-start border-primary border-4 mb-4 overflow-hidden">
    <div class="card-body p-4">
        <h6 class="fw-bold text-primary text-uppercase small mb-3"><i class="bi bi-chat-left-dots-fill me-2"></i>Catatan Evaluasi Per Mata Pelajaran</h6>
        <div class="bg-white p-4 rounded-3 border border-primary border-opacity-10 shadow-sm">
            @if(count($kumpulanCatatan) > 0)
                <div class="list-group list-group-flush">
                    @foreach($kumpulanCatatan as $index => $c)
                        <div class="list-group-item bg-transparent border-0 px-0 py-2">
                            <div class="d-flex gap-2 align-items-start">
                                <span class="fw-bold text-primary bg-primary bg-opacity-10 rounded-circle d-flex justify-content-center align-items-center" style="width: 25px; height: 25px; font-size: 0.8rem;">{{ $index + 1 }}</span>
                                <div>
                                    <span class="fw-bold text-dark d-block mb-1" style="font-size: 0.95rem;">{{ $c['mapel'] }}</span>
                                    <p class="mb-0 text-muted fst-italic ps-2 border-start border-2 border-secondary border-opacity-25 py-1">"{{ $c['teks'] }}"</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-3">
                    <i class="bi bi-chat-square-text text-muted fs-3 opacity-50 mb-2 d-block"></i>
                    <p class="mb-0 text-muted small">Belum ada catatan evaluasi khusus untuk mata pelajaran di semester ini.</p>
                </div>
            @endif
        </div>
        
        {{-- 🔥 UPDATE: BAGIAN CETAK DAN TANDA TANGAN (TTD KOSONG KEMBALI) 🔥 --}}
        <div class="mt-4 d-flex justify-content-between align-items-end border-top border-secondary border-opacity-10 pt-4">

        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: #fff; padding: 20px; }
        .navbar, .sidebar, form, button, .alert, .top-navbar, .d-print-none { display: none !important; }
        #sidebar, #content-wrapper { margin-left: 0 !important; width: 100% !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 20px !important; }
        .bg-light { 
            background-color: #f8f9fa !important; 
            print-color-adjust: exact; 
            -webkit-print-color-adjust: exact; 
        }
    }
</style>
@endsection