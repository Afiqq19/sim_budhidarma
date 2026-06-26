@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;"><i class="bi bi-nut-fill me-2 text-primary"></i>Setting Mapel per Kelas</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Kelola dan tetapkan mata pelajaran untuk masing-masing rombongan belajar.</p>
    </div>
</div>

{{-- ================= INFO PANEL ================= --}}
<div class="alert bg-primary bg-opacity-10 border-0 border-start border-primary border-4 rounded-4 p-4 mb-4 d-flex align-items-center shadow-sm">
    <div class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0" style="width: 50px; height: 50px;">
        <i class="bi bi-info-circle-fill fs-3 text-primary"></i>
    </div>
    <div>
        <strong class="d-block mb-1 text-dark fs-6">Panduan Pengaturan:</strong>
        <span class="text-dark opacity-75" style="font-size: 0.9rem;">
            Silakan klik tombol <span class="badge bg-primary rounded-pill px-2 py-1"><i class="bi bi-sliders"></i> Atur Mapel</span> pada kelas yang ingin dikonfigurasi. Mata pelajaran yang diatur di sini akan otomatis muncul di akun <strong>Wali Kelas</strong> saat melakukan proses input nilai E-Rapor.
        </span>
    </div>
</div>

{{-- ================= KARTU & TABEL DATA ================= --}}
<div class="card border-0 shadow-sm rounded-4 bg-white mb-5" style="border: 1px solid #e2e8f0 !important;">
    
    {{-- Header Kartu + Live Search --}}
    <div class="card-header bg-transparent py-3 px-4 border-bottom border-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-3-fill me-2 text-primary opacity-50"></i>Daftar Kelas Aktif</h6>
        
        {{-- FITUR PENCARIAN LIVE KELAS --}}
        <div class="input-group shadow-sm rounded-pill overflow-hidden bg-light" style="border: 1px solid #e2e8f0; max-width: 320px;">
            <span class="input-group-text bg-transparent border-0 text-muted pe-1"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent fw-medium" placeholder="Ketik nama kelas..." style="font-size: 0.9rem;">
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-white" id="tableKelas">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-4 py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">No</th>
                        <th width="35%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nama Kelas</th>
                        <th width="35%" class="py-3 text-center text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status Konfigurasi</th>
                        <th width="25%" class="pe-4 text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Aksi Setting</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelases as $item)
                    <tr style="transition: all 0.2s;">
                        <td class="ps-4 text-secondary" style="font-size: 0.9rem;">{{ $loop->iteration }}</td>
                        
                        {{-- Nama Kelas --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                    <i class="bi bi-building"></i>
                                </div>
                                <span class="fw-bolder text-dark fs-6">{{ $item->nama_kelas }}</span>
                            </div>
                        </td>
                        
                        {{-- Status Konfigurasi Mapel --}}
                        <td class="text-center">
                            @if($item->mapels_count > 0)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold shadow-sm" style="font-size: 0.8rem;">
                                    <i class="bi bi-check2-circle me-1"></i> Sudah Diatur ({{ $item->mapels_count }} Mapel)
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-semibold shadow-sm" style="font-size: 0.8rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i> Belum Dikonfigurasi
                                </span>
                            @endif
                        </td>
                        
                        {{-- Tombol Aksi --}}
                        <td class="pe-4 text-center">
                            <a href="{{ route('tu.setting.mapel.manage', $item->id) }}" class="btn btn-primary btn-sm fw-bold rounded-pill px-4 shadow-sm transition-all hover-lift">
                                <i class="bi bi-sliders me-1"></i> Atur Mapel
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-door-closed fs-1 opacity-25"></i></div>
                            <p class="text-muted mb-0 fw-medium">Data Kelas belum tersedia.</p>
                            <small class="text-muted d-block mt-1">Silakan tambahkan Master Kelas terlebih dahulu.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= SCRIPT & STYLE TAMBAHAN ================= --}}
@if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Fitur Live Search Kelas
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#tableKelas tbody tr');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();

                tableRows.forEach(row => {
                    // Cek jika baris tersebut bukan baris "Data Kosong"
                    if(row.cells.length > 1) {
                        // Kolom ke-2 (index 1) adalah Nama Kelas
                        const text = row.cells[1].textContent.toLowerCase();
                        if(text.includes(filter)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        }

        // 2. Pop-up Sukses dengan SweetAlert2
        let flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Konfigurasi Disimpan!',
                text: flashSuccess.getAttribute('data-message'),
                showConfirmButton: false,
                timer: 2500,
                customClass: { popup: 'rounded-4' }
            });
        }
    });
</script>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
</style>
@endsection