@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
    <div>
        <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;"><i class="bi bi-capslock-fill me-2 text-primary opacity-75"></i>Dashboard Kenaikan Kelas</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Sistem pintar pemetaan kelas massal (Tingkat X &rarr; XI &rarr; XII &rarr; Lulus).</p>
    </div>
</div>

{{-- NOTIFIKASI BERHASIL --}}
@if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

{{-- ================= CEK APAKAH ADA TAHUN AJARAN AKTIF ================= --}}
@if(!$ta_aktif)
    <div class="card border-0 rounded-4 shadow-sm bg-white text-center p-5 mb-5" style="border: 1px solid #fecdd3 !important; max-width: 600px; margin: 40px auto;">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
            <i class="bi bi-exclamation-triangle-fill fs-1"></i>
        </div>
        <h4 class="fw-bolder text-dark mb-2">Tahun Ajaran Aktif Tidak Ditemukan!</h4>
        <p class="text-muted mb-4" style="font-size: 0.95rem;">Sistem pembagian kelas massal terkunci karena belum ada periode akademik berjalan.</p>
        <a href="{{ route('tu.tahun-ajaran.index') }}" class="btn btn-primary rounded-pill fw-semibold px-4 py-2 hover-lift">
            <i class="bi bi-calendar-range-fill me-2"></i> Buka Master Tahun Ajaran
        </a>
    </div>
@else

    {{-- ================= LOGIKA PENGUNCI SEMESTER (HANYA BISA DI GANJIL) ================= --}}
    @if($ta_aktif->semester == 'Genap')
        <div class="alert bg-warning bg-opacity-10 border-0 border-start border-warning border-4 text-dark rounded-4 p-4 mb-4 shadow-sm d-flex align-items-start">
            <div class="bg-warning bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0" style="width: 56px; height: 56px;">
                <i class="bi bi-lock-fill fs-3 text-warning"></i>
            </div>
            <div>
                <h5 class="fw-bolder text-dark mb-1">Sistem Kenaikan Kelas Terkunci</h5>
                <p class="mb-0 text-muted" style="font-size: 0.95rem;">Saat ini Anda berada di <strong class="text-dark">Semester Genap</strong>. Kenaikan kelas massal hanya diizinkan untuk dieksekusi pada awal <strong class="text-dark">Semester Ganjil</strong>.</p>
            </div>
        </div>

    {{-- ================= LOGIKA PENGUNCI GANDA: JIKA SUDAH SELESAI ================= --}}
    @elseif($ta_aktif->is_kenaikan_selesai == true)
        <div class="alert bg-success bg-opacity-10 border-0 border-start border-success border-4 text-dark rounded-4 p-4 mb-4 shadow-sm d-flex align-items-start">
            <div class="bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0" style="width: 56px; height: 56px;">
                <i class="bi bi-shield-lock-fill fs-3 text-success"></i>
            </div>
            <div>
                <h5 class="fw-bolder text-success mb-1">Proses Kenaikan Kelas Telah Selesai!</h5>
                <p class="mb-0 text-muted" style="font-size: 0.95rem;">Kenaikan Kelas untuk Tahun Ajaran <strong class="text-dark">{{ $ta_aktif->tahun }} (Semester {{ $ta_aktif->semester }})</strong> sudah berhasil dieksekusi. Sistem kini <strong class="text-success">DIGEMBOK PERMANEN</strong> untuk mencegah duplikasi data siswa.</p>
            </div>
        </div>
        
        <div class="text-center py-5 opacity-50 my-4">
            <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px;">
                <i class="bi bi-rocket-takeoff-fill text-success fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark">Misi Selesai. Semua siswa sudah berada di kelas barunya.</h5>
        </div>

    {{-- ================= TAMPILKAN FORM KENAIKAN KELAS JIKA AMAN ================= --}}
    @else
        <div class="alert bg-primary bg-opacity-10 border-0 border-start border-primary border-4 text-dark rounded-4 p-4 mb-4 shadow-sm d-flex align-items-center">
            <div class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0" style="width: 50px; height: 50px;">
                <i class="bi bi-info-circle-fill fs-4 text-primary"></i>
            </div>
            <div style="font-size: 0.9rem;">
                Sekarang siswa berada di semester baru: <strong class="text-primary">{{ $ta_aktif->tahun }} - Semester {{ $ta_aktif->semester }}</strong>.<br>
                <span class="text-muted">Anda sudah bisa melakukan proses kenaikan kelas serentak.</span>
            </div>
        </div>

        <form action="{{ route('tu.kenaikan-kelas.proses') }}" method="POST" id="formProsesMassal">
            @csrf
            
            <div class="card border-0 rounded-4 mb-4 bg-white overflow-hidden" style="box-shadow: 0 4px 24px rgba(0,0,0,0.03); border: 1px solid #e2e8f0 !important;">
                
                {{-- Header Mode Pengecualian --}}
                <div class="bg-danger bg-opacity-10 border-bottom border-danger border-opacity-25 p-3 px-4 d-flex align-items-center">
                    <i class="bi bi-person-fill-exclamation text-danger fs-4 me-3"></i>
                    <h5 class="fw-bold text-danger mb-0" style="letter-spacing: -0.5px;">Mode Pengecualian (Siswa Tinggal Kelas)</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Input Pencarian Live --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small text-uppercase mb-2" style="letter-spacing: 0.5px;">Cari & Tandai Siswa yang Tinggal Kelas:</label>
                        <div class="input-group shadow-sm rounded-pill overflow-hidden bg-light" style="border: 1px solid #e2e8f0; max-width: 600px;">
                            <span class="input-group-text bg-transparent border-0 text-muted pe-1 ps-4"><i class="bi bi-search"></i></span>
                            <input type="text" id="liveSearch" class="form-control border-0 shadow-none bg-transparent py-2 fs-6 fw-medium" placeholder="Ketik Nama Siswa atau Nama Kelas (Cth: X TKJ 1)...">
                        </div>
                        <small class="text-muted mt-2 d-block" style="font-size: 0.8rem;">
                            <i class="bi bi-lightbulb-fill text-warning me-1"></i> 
                            <strong>Tips:</strong> Centang kotak di samping nama siswa jika siswa tersebut <strong>TIDAK NAIK KELAS</strong> (Tertahan).
                        </small>
                    </div>

                    {{-- TABEL SISWA SCROLLABLE --}}
                    <div class="table-responsive bg-white border rounded-3 shadow-sm" style="max-height: 450px; overflow-y: auto; border-color: #e2e8f0 !important;">
                        <table class="table table-hover align-middle mb-0 border-white" id="tabelSiswa">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th width="8%" class="text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;"><i class="bi bi-check2-square fs-6"></i></th>
                                    <th width="20%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">NISN</th>
                                    <th width="45%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Nama Lengkap Siswa</th>
                                    <th width="27%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem;">Kelas Asal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelas_dengan_siswa as $kelas)
                                    @foreach($kelas->siswas as $siswa)
                                        <tr class="baris-siswa text-dark" style="transition: all 0.2s;">
                                            <td class="text-center py-3">
                                                <input class="form-check-input checkbox-tertahan mt-0 fs-5 flex-shrink-0" type="checkbox" name="siswa_tertahan_ids[]" value="{{ $siswa->id }}" id="siswa_{{ $siswa->id }}" data-nama="{{ $siswa->nama_lengkap }}" data-kelas="{{ $kelas->nama_kelas }}" style="cursor:pointer; border-color: #cbd5e1;">
                                            </td>
                                            <td>
                                                <label id="lbl_nisn_{{ $siswa->id }}" for="siswa_{{ $siswa->id }}" style="cursor:pointer;" class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-2 fw-semibold font-monospace">{{ $siswa->nisn }}</label>
                                            </td>
                                            <td>
                                                <label id="lbl_nama_{{ $siswa->id }}" for="siswa_{{ $siswa->id }}" style="cursor:pointer;" class="fw-bold text-dark fs-6">{{ $siswa->nama_lengkap }}</label>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-semibold font-monospace text-uppercase" style="font-size: 0.75rem;">{{ $kelas->nama_kelas }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TOMBOL EKSEKUSI UTAMA DENGAN Z-INDEX AMAN --}}
            <div class="text-center pt-3 mb-5 position-relative" style="z-index: 50;">
                <button type="button" id="btnEksekusi" class="btn btn-primary btn-lg fw-bold px-5 py-3 shadow rounded-pill hover-lift d-inline-flex align-items-center text-uppercase" style="letter-spacing: 0.5px; font-size: 1rem;">
                    <i class="bi bi-rocket-takeoff-fill me-3 fs-4"></i> PROSES KENAIKAN KELAS SEKARANG
                </button>
            </div>
        </form>
    @endif
@endif

{{-- ================= SCRIPT INTERAKSI MODEREN ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Fitur Live Search Siswa Massal
        const searchInput = document.getElementById('liveSearch');
        if(searchInput) {
            searchInput.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('.baris-siswa');

                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    if(text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // 2. Deteksi Checklist Tinggal Kelas (Ubah Background Baris Jadi Merah Pastel)
        const checkboxes = document.querySelectorAll('.checkbox-tertahan');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const parentRow = this.closest('.baris-siswa');
                const lblNisn = document.getElementById('lbl_nisn_' + this.value);
                const lblNama = document.getElementById('lbl_nama_' + this.value);

                if(this.checked) {
                    parentRow.classList.add('selected-danger-bg');
                    if(lblNama) lblNama.className = 'fw-bold text-danger fs-6';
                    if(lblNisn) lblNisn.className = 'badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-2 fw-semibold font-monospace';
                } else {
                    parentRow.classList.remove('selected-danger-bg');
                    if(lblNama) lblNama.className = 'fw-bold text-dark fs-6';
                    if(lblNisn) lblNisn.className = 'badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-2 fw-semibold font-monospace';
                }
            });
        });

        // 3. EVENT LISTENER UNTUK TOMBOL EKSEKUSI (Lebih Aman dari onclick biasa)
        const btnEksekusi = document.getElementById('btnEksekusi');
        if(btnEksekusi) {
            btnEksekusi.addEventListener('click', function(e) {
                e.preventDefault(); // Cegah klik ganda / auto submit
                
                const checkedBoxes = document.querySelectorAll('.checkbox-tertahan:checked');
                const tertahan = checkedBoxes.length;
                
                let rincianSiswa = '';
                if (tertahan > 0) {
                    rincianSiswa = '<div class="text-start mt-3 p-3 border rounded-3 bg-light" style="max-height: 160px; overflow-y: auto; font-size: 0.85rem; border-color: #fed7aa !important;">';
                    rincianSiswa += '<ul class="mb-0 text-danger ps-3 fw-medium">';
                    checkedBoxes.forEach(cb => {
                        let nama = cb.getAttribute('data-nama');
                        let kelas = cb.getAttribute('data-kelas');
                        rincianSiswa += `<li><b>${nama}</b> <span class="text-muted">(${kelas})</span></li>`;
                    });
                    rincianSiswa += '</ul></div>';
                }

                let pesanTertahan = tertahan > 0 
                    ? `<div class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-2 fs-6 fw-bold w-100 text-start"><i class="bi bi-exclamation-circle-fill me-2"></i>Terdeteksi ${tertahan} Siswa Tinggal Kelas:</div> ${rincianSiswa}`
                    : `<div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-2 fs-6 fw-bold w-100 text-start"><i class="bi bi-check2-all me-2"></i> Bersih: 100% Siswa Dipromosikan Naik / Lulus</div>`;

                Swal.fire({
                    title: 'Konfirmasi Kenaikan Kelas',
                    html: `<div class="text-start text-dark opacity-75" style="font-size: 0.95rem; line-height: 1.5;">Sistem akan memproses peningkatan tingkat kelas seluruh siswa aktif secara otomatis.<br><br> ${pesanTertahan} <br>Tindakan ini bersifat permanen. Lanjutkan?</div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5', // Indigo
                    cancelButtonColor: '#f1f5f9', // Light gray
                    cancelButtonText: '<span class="text-dark fw-medium">Batal</span>',
                    confirmButtonText: '<i class="bi bi-rocket-takeoff-fill me-1"></i> Ya, Eksekusi Sekarang!',
                    reverseButtons: true,
                    customClass: { 
                        popup: 'rounded-4 p-4',
                        cancelButton: 'border border-secondary border-opacity-25 shadow-sm text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses Migrasi Kelas...',
                            text: 'Mohon tunggu dan jangan menutup browser.',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
                        });
                        // Submit formnya
                        document.getElementById('formProsesMassal').submit();
                    }
                });
            });
        }

        // 4. Pop-up Sukses dari Server
        let flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Eksekusi Berhasil!',
                text: flashSuccess.getAttribute('data-message'),
                showConfirmButton: false,
                timer: 3500,
                customClass: { popup: 'rounded-4' }
            });
        }
    });
</script>

{{-- ================= HOOK DESIGN STYLE ================= --}}
<style>
    /* Hover Lift Effect untuk Tombol Utama */
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3) !important; }
    
    /* Custom Scrollbar untuk Box Tabel List Siswa */
    #tabelSiswa::-webkit-scrollbar { width: 6px; }
    #tabelSiswa::-webkit-scrollbar-track { background: transparent; }
    #tabelSiswa::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    /* Highlight Khusus untuk Baris Siswa yang Ditandai Tinggal Kelas */
    .baris-siswa.selected-danger-bg { background-color: #fef2f2 !important; border-bottom-color: #fee2e2 !important; }
</style>
@endsection