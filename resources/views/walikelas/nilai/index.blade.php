@extends('layouts.walikelas')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-sm-center flex-column flex-sm-row gap-3">
    <div>
        <h3 class="fw-bold text-dark"><i class="bi bi-journal-check text-primary me-2"></i>Input Nilai E-Rapor</h3>
        <p class="text-muted mb-0">Kelola nilai akademik siswa kelas <strong class="badge bg-primary px-2 py-1 fs-6">{{ $kelas->nama_kelas ?? 'Belum Ditugaskan' }}</strong></p>
    </div>
    <div class="text-sm-end">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold border border-primary border-opacity-25 shadow-sm">
            <i class="bi bi-calendar-event me-1"></i> T.A: {{ $tahunAktif->tahun ?? '-' }}
        </span>
        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold border border-info border-opacity-25 ms-2 shadow-sm">
            <i class="bi bi-bookmark-star me-1"></i> Semester: {{ $tahunAktif->semester ?? '-' }}
        </span>
    </div>
</div>

{{-- Alert Notifikasi --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


{{-- 🔥 PENGAMAN JIKA GURU BELUM PUNYA KELAS 🔥 --}}
@if(empty($waliKelas->kelas_id))
    <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center p-4">
        <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px; min-width: 60px;">
            <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
        </div>
        <div>
            <h5 class="fw-bold text-dark mb-1">Akses Input Nilai Tidak Tersedia</h5>
            <p class="mb-0 text-dark opacity-75">Anda belum ditugaskan sebagai Wali Kelas untuk kelas manapun. Form pengisian nilai E-Rapor tidak dapat diakses sebelum Anda memiliki kelas binaan.</p>
        </div>
    </div>
@else

{{-- Bagian Atas: Filter Mata Pelajaran --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-body p-4 bg-primary bg-opacity-10">
        <form action="{{ route('walikelas.nilai.index') }}" method="GET" class="row align-items-center" id="formFilterMapel">
            <div class="col-md-7 mb-3 mb-md-0">
                <label class="form-label fw-bold text-primary small text-uppercase tracking-wider"><i class="bi bi-book-half me-2"></i>Mata Pelajaran Aktif</label>
                <select name="mapel_id" class="form-select form-select-lg border-0 shadow-sm fw-bold focus-ring focus-ring-primary" onchange="document.getElementById('formFilterMapel').submit()">
                    
                    @if($listMapel->isEmpty())
                        <option value="">-- Belum Ada Mata Pelajaran --</option>
                    @else
                        {{-- Grouping berdasarkan Kelompok --}}
                        @foreach($listMapel->groupBy('kelompok') as $kelompok => $mapels)
                            <optgroup label="Muatan Kelompok {{ $kelompok }}">
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}" {{ $selectedMapelId == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    @endif

                </select>
            </div>
            <div class="col-md-5">
                <div class="bg-white bg-opacity-50 p-3 rounded-3 border border-primary border-opacity-10">
                    <p class="text-muted small mb-0 fw-medium">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>Pilih mata pelajaran di samping. Daftar siswa di bawah akan otomatis menampilkan kolom nilai sesuai mapel yang dipilih.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Bagian Bawah: Form Pengisian Massal --}}
<form action="{{ route('walikelas.nilai.store') }}" method="POST">
    @csrf
    {{-- Lempar ID mapel yang sedang aktif agar tersimpan di database --}}
    <input type="hidden" name="mapel_id" value="{{ $selectedMapelId }}">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Lembar Penilaian Kelas</h6>
            @if($siswas->count() > 0 && $selectedMapelId)
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                        <tr>
                            <th class="text-start ps-4 py-3 border-0" width="5%">No</th>
                            <th class="text-start py-3 border-0" width="25%">Siswa</th>
                            <th class="text-center py-3 border-0" width="12%">Pengetahuan</th>
                            <th class="text-center py-3 border-0" width="12%">Keterampilan</th>
                            <th class="text-center py-3 border-0" width="12%">Nilai Akhir</th>
                            <th class="text-start py-3 border-0 pe-4" width="34%">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $s)
                        @php
                            // Cek apakah siswa ini sudah punya nilai untuk mapel yang dipilih
                            $nilai = $s->nilais->first(); 
                        @endphp
                        <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="text-start ps-4 text-muted fw-medium">{{ $loop->iteration }}</td>
                            
                            {{-- 🔥 DESAIN MODERN DENGAN AVATAR PINTAR 🔥 --}}
                            <td class="text-start py-3">
                                <div class="d-flex align-items-center">
                                    @php
                                        $avatarSiswa = ($s->jk == 'P') ? 'images/username_pr.png' : 'images/username_lk.png';
                                    @endphp
                                    <img src="{{ asset($avatarSiswa) }}" class="rounded-circle shadow-sm me-3 border border-2 border-white" width="40" height="40" style="object-fit: cover; background: #e2e8f0;" alt="Avatar">
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">{{ $s->nama_lengkap }}</span>
                                        <span class="text-muted small font-monospace">{{ $s->nisn }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Input Nilai Pengetahuan --}}
                            <td class="text-center">
                                <input type="number" min="0" max="100" name="nilai[{{ $s->id }}][nilai_pengetahuan]" 
                                       class="form-control text-center bg-light border-0 focus-ring focus-ring-primary fw-medium" 
                                       value="{{ $nilai?->nilai_pengetahuan ?? '' }}" placeholder="0-100"
                                       oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                            </td>

                            {{-- Input Nilai Keterampilan --}}
                            <td class="text-center">
                                <input type="number" min="0" max="100" name="nilai[{{ $s->id }}][nilai_keterampilan]" 
                                       class="form-control text-center bg-light border-0 focus-ring focus-ring-primary fw-medium" 
                                       value="{{ $nilai?->nilai_keterampilan ?? '' }}" placeholder="0-100"
                                       oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                            </td>

                            {{-- Input Nilai Akhir --}}
                            <td class="text-center">
                                <input type="number" min="0" max="100" name="nilai[{{ $s->id }}][nilai_akhir]" 
                                       class="form-control text-center fw-bold bg-primary bg-opacity-10 text-primary border-0 focus-ring focus-ring-primary" 
                                       value="{{ $nilai?->nilai_akhir ?? '' }}" placeholder="0-100"
                                       oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                            </td>

                            {{-- Input Catatan --}}
                            <td class="pe-4">
                                <input type="text" name="nilai[{{ $s->id }}][catatan]" 
                                       class="form-control bg-light border-0 focus-ring focus-ring-primary" 
                                       value="{{ $nilai?->catatan_wali_kelas ?? '' }}" placeholder="Ketik catatan di sini...">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-folder-x fs-1 opacity-25"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Data Tidak Tersedia</h6>
                                <p class="text-muted small mb-0">Belum ada siswa yang terdaftar di kelas ini atau mata pelajaran belum dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Tombol Simpan di Bawah (Hanya muncul jika ada siswa) --}}
        @if($siswas->count() > 0 && $selectedMapelId)
        <div class="card-footer bg-light border-top-0 p-4 text-end rounded-bottom-4">
            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-5 shadow-sm py-2 transition-all hover-lift">
                <i class="bi bi-floppy-fill me-2"></i> Simpan Nilai
            </button>
        </div>
        @endif
    </div>
</form>
@endif

@endsection