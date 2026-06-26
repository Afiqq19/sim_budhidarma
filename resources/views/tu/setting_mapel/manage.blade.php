@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('tu.setting.mapel.index') }}" class="btn btn-white border shadow-sm d-flex align-items-center justify-content-center rounded-3 hover-btn-back bg-white flex-shrink-0" style="width: 42px; height: 42px; transition: all 0.2s;" title="Kembali ke Daftar">
        <i class="bi bi-arrow-left fs-5 text-secondary"></i>
    </a>
    <div>
        <h3 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px;">Konfigurasi Mata Pelajaran</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Pilih mata pelajaran yang diajarkan pada kelas <strong class="text-primary">{{ $kelas->nama_kelas }}</strong>.</p>
    </div>
</div>

{{-- ================= INFO PANDUAN ================= --}}
<div class="alert bg-info bg-opacity-10 border-0 border-start border-info border-4 rounded-4 p-4 mb-4 d-flex align-items-center shadow-sm">
    <div class="bg-info bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0" style="width: 50px; height: 50px;">
        <i class="bi bi-lightbulb-fill fs-3 text-info"></i>
    </div>
    <div>
        <strong class="d-block mb-1 text-dark fs-6">Panduan Checklist:</strong>
        <span class="text-dark opacity-75" style="font-size: 0.9rem;">
            Silakan <strong>centang</strong> kotak pada mata pelajaran yang diajarkan di kelas ini. Mapel yang dicentang akan menjadi struktur kurikulum resmi untuk kelas <span class="badge bg-white text-dark border px-2">{{ $kelas->nama_kelas }}</span> pada E-Rapor.
        </span>
    </div>
</div>

{{-- ================= FORM LIST MAPEL ================= --}}
<form action="{{ route('tu.setting.mapel.store', $kelas->id) }}" method="POST">
    @csrf
    
    @php
        $current_kelompok = '';
        $kelompok_names = [
            'A'  => ['nama' => 'Kelompok A (Muatan Nasional)', 'color' => 'primary', 'icon' => 'bi-globe-asia-australia'],
            'B'  => ['nama' => 'Kelompok B (Muatan Kewilayahan)', 'color' => 'info', 'icon' => 'bi-map-fill'],
            'C'  => ['nama' => 'Kelompok C (Muatan Peminatan Kejuruan)', 'color' => 'secondary', 'icon' => 'bi-mortarboard-fill'],
            'C2' => ['nama' => 'Kelompok C2 (Dasar Program Keahlian)', 'color' => 'warning', 'icon' => 'bi-laptop'],
            'C3' => ['nama' => 'Kelompok C3 (Kompetensi Keahlian)', 'color' => 'danger', 'icon' => 'bi-tools'],
        ];
    @endphp

    <div class="row g-4 pb-5">
        @foreach($mapels as $m)
            
            {{-- PEMISAH HEADER KELOMPOK MAPEL --}}
            @if($current_kelompok != $m->kelompok)
                @if($current_kelompok != '')
                    </div></div></div></div> 
                @endif
                @php $current_kelompok = $m->kelompok; @endphp
                
                @php 
                    $k_info = $kelompok_names[$m->kelompok] ?? ['nama' => 'Kelompok ' . $m->kelompok, 'color' => 'dark', 'icon' => 'bi-book-half'];
                @endphp

                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                        
                        {{-- Header Kelompok Estetik --}}
                        <div class="bg-{{ $k_info['color'] }} bg-opacity-10 border-start border-{{ $k_info['color'] }} border-4 p-3 d-flex align-items-center">
                            <i class="bi {{ $k_info['icon'] }} text-{{ $k_info['color'] }} fs-4 me-3 ms-2"></i>
                            <h5 class="fw-bold text-{{ $k_info['color'] }} mb-0" style="letter-spacing: -0.5px;">{{ $k_info['nama'] }}</h5>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
            @endif

            {{-- ITEM MAPEL (CHECKBOX MODERN) --}}
            <label class="list-group-item list-group-item-action d-flex align-items-center p-3 px-4 border-bottom mapel-item {{ in_array($m->id, $mapel_terpilih) ? 'selected-bg' : '' }}" style="cursor: pointer; transition: all 0.2s;">
                
                {{-- Checkbox Asli (Disembunyikan tapi berfungsi) --}}
                <input class="form-check-input mapel-checkbox me-3 mt-0 fs-4 flex-shrink-0" type="checkbox" name="mapel_ids[]" value="{{ $m->id }}" {{ in_array($m->id, $mapel_terpilih) ? 'checked' : '' }}>
                
                {{-- Konten Teks --}}
                <div class="d-flex flex-column flex-md-row justify-content-md-between w-100 align-items-md-center">
                    <div>
                        <span class="badge bg-light text-secondary border px-2 py-1 me-2 font-monospace">{{ $m->kode_mapel }}</span>
                        <span class="fw-bolder text-dark fs-6">{{ $m->nama_mapel }}</span>
                    </div>
                    <div class="mt-2 mt-md-0 d-flex align-items-center">
                        <span class="text-muted fw-semibold small me-2">Nilai KKM:</span>
                        <span class="badge bg-dark px-2 py-1 rounded-2 shadow-sm fs-6">{{ $m->kkm }}</span>
                    </div>
                </div>
            </label>

        @endforeach
        
        {{-- Menutup tag div jika loop sudah selesai --}}
        @if($current_kelompok != '')
            </div></div></div></div>
        @endif
    </div>

    {{-- ================= ACTION BAR STICKY (MELAYANG) ================= --}}
    <div class="position-sticky bottom-0 pb-4 pt-2" style="z-index: 1000;">
        <div class="card border-0 shadow-lg rounded-pill" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid #e2e8f0 !important;">
            <div class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-center py-2 px-4 gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-shield-check fs-5"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">Simpan Konfigurasi</span>
                        <span class="text-muted" style="font-size: 0.75rem;">Pastikan mapel yang dicentang sudah sesuai kurikulum.</span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm hover-lift w-100 w-sm-auto">
                    <i class="bi bi-save2 me-2"></i> Simpan Pilihan Mapel
                </button>
            </div>
        </div>
    </div>
</form>

{{-- ================= SCRIPT & STYLE TAMBAHAN ================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efek Highlight Background saat checkbox diklik
        const checkboxes = document.querySelectorAll('.mapel-checkbox');
        
        checkboxes.forEach(box => {
            box.addEventListener('change', function() {
                const parentRow = this.closest('.mapel-item');
                if(this.checked) {
                    parentRow.classList.add('selected-bg');
                } else {
                    parentRow.classList.remove('selected-bg');
                }
            });
        });
    });
</script>

<style>
    /* Efek Hover dan Interaksi Tombol */
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
    .hover-btn-back:hover { background-color: #f1f5f9 !important; color: #0f172a !important; }
    
    /* Highlight Background untuk Mapel yang Dicentang */
    .mapel-item.selected-bg { background-color: #f0fdf4 !important; border-color: #dcfce7 !important; }
    .mapel-item:hover:not(.selected-bg) { background-color: #f8fafc !important; }
</style>
@endsection