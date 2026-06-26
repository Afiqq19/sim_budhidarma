@extends('layouts.tu')

@section('content')
{{-- ================= HEADER ================= --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
    <div>
        <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;"><i class="bi bi-calendar-range-fill me-2 text-primary"></i>Master Tahun Ajaran</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Manajemen periode akademik aktif dan arsip semester.</p>
    </div>
    
    <a href="{{ route('tu.tahun-ajaran.create') }}" class="btn btn-primary fw-semibold shadow-sm rounded-pill d-flex align-items-center px-4 py-2 transition-all hover-lift">
        <i class="bi bi-plus-circle-fill me-2"></i> Tambah Tahun Ajaran
    </a>
</div>

{{-- ================= NOTIFIKASI SYSTEM ================= --}}
@if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
@endif
@if($errors->any())
    <div id="flash-errors" data-messages="{{ implode('<br>', $errors->all()) }}" style="display: none;"></div>
@endif

{{-- ================= KARTU UTAMA ================= --}}
<div class="card border-0 shadow-sm rounded-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
    <div class="card-body p-4 p-md-5">
        
        {{-- ================= PAPAN PENGUMUMAN STATUS AKTIF ================= --}}
        @php
            $aktif = $tahun_ajarans->where('is_active', true)->first();
        @endphp

        @if($aktif)
            <div class="alert bg-success bg-opacity-10 border-0 border-start border-success border-4 rounded-4 p-4 mb-5 shadow-sm d-flex align-items-center">
                <div class="bg-success bg-opacity-25 rounded-circle d-flex justify-content-center align-items-center me-4 flex-shrink-0" style="width: 60px; height: 60px;">
                    <i class="bi bi-star-fill text-success fs-3"></i>
                </div>
                <div>
                    <h6 class="fw-bolder text-success mb-1" style="letter-spacing: 0.5px; text-transform: uppercase;">Tahun Ajaran Aktif Saat Ini</h6>
                    <h3 class="fw-bolder text-dark mb-1">{{ $aktif->tahun }} <span class="fs-5 text-muted fw-medium">({{ $aktif->semester }})</span></h3>
                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">Sistem sedang berjalan pada periode ini. Semua inputan nilai dan absensi akan masuk ke semester ini.</p>
                </div>
            </div>
        @else
            <div class="alert bg-danger bg-opacity-10 border-0 border-start border-danger border-4 rounded-4 p-4 mb-5 shadow-sm d-flex align-items-center">
                <div class="bg-danger bg-opacity-25 rounded-circle d-flex justify-content-center align-items-center me-4 flex-shrink-0" style="width: 60px; height: 60px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bolder text-danger mb-1">Peringatan: Belum Ada Tahun Ajaran Aktif!</h5>
                    <p class="mb-0 text-dark opacity-75" style="font-size: 0.95rem;">Sistem saat ini dalam keadaan terkunci. Guru dan Wali Kelas tidak bisa melakukan input nilai. Silakan <strong>aktifkan</strong> salah satu semester di bawah.</p>
                </div>
            </div>
        @endif
        
        {{-- ================= TABEL DATA ================= --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-white">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-4 py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">No</th>
                        <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Periode Tahun Ajaran</th>
                        <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Semester</th>
                        <th class="text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status Berjalan</th>
                        <th class="pe-4 text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Aksi & Kontrol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tahun_ajarans as $item)
                    {{-- Row highlight untuk yang sedang aktif --}}
                    <tr class="{{ $item->is_active ? 'bg-success bg-opacity-10' : '' }}" style="transition: all 0.2s;">
                        <td class="ps-4 text-secondary" style="font-size: 0.9rem;">{{ $loop->iteration }}</td>
                        
                        {{-- Tahun --}}
                        <td>
                            <span class="fw-bolder text-dark" style="font-size: 1.05rem;">{{ $item->tahun }}</span>
                        </td>
                        
                        {{-- Semester Badge --}}
                        <td>
                            @if($item->semester == 'Ganjil')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-semibold" style="letter-spacing: 0.5px;">
                                    <i class="bi bi-moon-stars-fill me-1"></i> Ganjil
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-3 py-1 rounded-pill fw-semibold" style="letter-spacing: 0.5px;">
                                    <i class="bi bi-sun-fill me-1"></i> Genap
                                </span>
                            @endif
                        </td>
                        
                        {{-- Status Saat Ini --}}
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge bg-success px-4 py-2 rounded-pill shadow-sm fw-bold" style="letter-spacing: 1px;"><i class="bi bi-check2-all me-1"></i> AKTIF</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill fw-medium">Lewat</span>
                            @endif
                        </td>
                        
                        {{-- Aksi Modern --}}
                        <td class="pe-4 text-center">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                
                                {{-- Tombol Set Aktif --}}
                                @if(!$item->is_active)
                                    <form action="{{ route('tu.tahun_ajaran.set_aktif', $item->id) }}" method="POST" id="form-aktif-{{ $item->id }}">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-success fw-bold rounded-pill px-3 shadow-sm btn-aktifkan transition-all hover-lift" 
                                            data-id="{{ $item->id }}" data-nama="{{ $item->tahun }} Semester {{ $item->semester }}">
                                            <i class="bi bi-power me-1"></i> Aktifkan
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-light text-success fw-bold rounded-pill px-3 border border-success border-opacity-25 disabled" style="opacity: 0.7;">
                                        <i class="bi bi-record-circle me-1"></i> Sedang Berjalan
                                    </button>
                                @endif

                                {{-- Divider --}}
                                <div class="vr mx-1 opacity-25"></div>

                                {{-- Tombol Edit & Hapus --}}
                                <a href="{{ route('tu.tahun-ajaran.edit', $item->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-3 d-flex align-items-center justify-content-center transition-all hover-btn-action" title="Edit Data" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('tu.tahun-ajaran.destroy', $item->id) }}" method="POST" id="form-hapus-{{ $item->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light text-danger border shadow-sm rounded-3 d-flex align-items-center justify-content-center transition-all hover-btn-action btn-hapus" title="Hapus" data-id="{{ $item->id }}" {{ $item->is_active ? 'disabled' : '' }} style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-calendar-x fs-1 opacity-25"></i></div>
                            <p class="text-muted mb-0 fw-medium">Belum ada data Tahun Ajaran.</p>
                            <a href="{{ route('tu.tahun-ajaran.create') }}" class="btn btn-sm btn-outline-primary mt-3 rounded-pill fw-semibold">Buat Baru Sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= SCRIPT & STYLE TAMBAHAN ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Pop-up Sukses
        let flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: flashSuccess.getAttribute('data-message'),
                showConfirmButton: false,
                timer: 2000,
                customClass: { popup: 'rounded-4' }
            });
        }

        // 2. Pop-up Error Validasi
        let flashErrors = document.getElementById('flash-errors');
        if (flashErrors) {
            let errHtml = '<ul class="text-start mb-0"><li>' + flashErrors.getAttribute('data-messages').split('<br>').join('</li><li>') + '</li></ul>';
            Swal.fire({
                icon: 'error',
                title: 'Oops! Ada Kesalahan',
                html: errHtml,
                confirmButtonColor: '#4f46e5',
                customClass: { popup: 'rounded-4' }
            });
        }

        // 3. SweetAlert untuk tombol "Jadikan Aktif"
        const btnAktifkan = document.querySelectorAll('.btn-aktifkan');
        btnAktifkan.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Aktifkan Semester Ini?',
                    text: `Sistem akan berpindah dan berjalan pada ${nama}. Semester yang lama akan otomatis diarsipkan.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981', 
                    cancelButtonColor: '#f1f5f9', 
                    confirmButtonText: '<i class="bi bi-power me-1"></i> Ya, Aktifkan!',
                    cancelButtonText: '<span class="text-dark fw-medium">Batal</span>',
                    reverseButtons: true,
                    customClass: { 
                        popup: 'rounded-4',
                        cancelButton: 'border border-secondary border-opacity-25 shadow-sm text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-aktif-' + id).submit();
                    }
                });
            });
        });

        // 4. SweetAlert untuk tombol "Hapus"
        const btnHapus = document.querySelectorAll('.btn-hapus');
        btnHapus.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Hapus Tahun Ajaran?',
                    text: "Data yang dihapus tidak dapat dikembalikan! Pastikan tidak ada data yang terikat dengan semester ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', 
                    cancelButtonColor: '#f1f5f9', 
                    confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: '<span class="text-dark fw-medium">Batal</span>',
                    reverseButtons: true,
                    customClass: { 
                        popup: 'rounded-4',
                        cancelButton: 'border border-secondary border-opacity-25 shadow-sm text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });
        
    });
</script>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
    .hover-btn-action:hover { background-color: #f1f5f9 !important; transform: scale(1.05); }
</style>
@endsection