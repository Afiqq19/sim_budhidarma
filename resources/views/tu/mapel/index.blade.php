@extends('layouts.tu')

@section('content')
{{-- ================= HEADER & FILTER PENCARIAN ================= --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
    <div>
        <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Master Mata Pelajaran</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Manajemen basis data mapel sesuai struktur kurikulum SMK.</p>
    </div>
    
    <div class="d-flex flex-column flex-sm-row gap-2">
        {{-- 🔥 FITUR PENCARIAN MAPEL 🔥 --}}
        <form action="{{ route('tu.mapel.index') }}" method="GET" class="d-flex">
            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white" style="border: 1px solid #e2e8f0; min-width: 260px;">
                <span class="input-group-text bg-transparent border-0 text-muted pe-1"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="Cari Kode atau Nama Mapel..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                <button class="btn btn-primary px-3 fw-semibold" type="submit" style="border-radius: 0 50px 50px 0;">Cari</button>
            </div>
        </form>

        <a href="{{ route('tu.mapel.create') }}" class="btn btn-primary fw-semibold shadow-sm rounded-pill d-flex align-items-center px-4 transition-all hover-lift">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Mapel
        </a>
    </div>
</div>

{{-- ================= TABEL DATA MAPEL ================= --}}
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-white">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="ps-4 py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">No</th>
                        <th width="15%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kode Mapel</th>
                        <th width="30%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nama Mata Pelajaran</th>
                        <th width="20%" class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kelompok Muatan</th>
                        <th width="10%" class="text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nilai KKM</th>
                        <th width="15%" class="pe-4 text-center py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mapels as $item)
                    <tr style="transition: all 0.2s;">
                        <td class="ps-4 text-secondary" style="font-size: 0.9rem;">
                            {{ $loop->iteration + ($mapels->currentPage() - 1) * $mapels->perPage() }}
                        </td>
                        
                        {{-- Kode Mapel --}}
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-2 fw-semibold font-monospace" style="font-size: 0.85rem; letter-spacing: 1px;">
                                {{ $item->kode_mapel }}
                            </span>
                        </td>
                        
                        {{-- Nama Mapel --}}
                        <td>
                            <span class="fw-bolder text-dark" style="font-size: 0.95rem;">{{ $item->nama_mapel }}</span>
                        </td>
                        
                        {{-- Kelompok / Muatan Kurikulum --}}
                        <td>
                            @if($item->kelompok == 'A')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-geo-alt-fill me-1"></i> A (Nasional)</span>
                            @elseif($item->kelompok == 'B')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-map-fill me-1"></i> B (Kewilayahan)</span>
                            @elseif($item->kelompok == 'C')
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-mortarboard-fill me-1"></i> C (Peminatan)</span>
                            @elseif($item->kelompok == 'C2')
                                <span class="badge px-3 py-1 rounded-pill fw-medium" style="background-color: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;"><i class="bi bi-laptop me-1"></i> C2 (Dasar Program)</span>
                            @elseif($item->kelompok == 'C3')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-medium"><i class="bi bi-tools me-1"></i> C3 (Kompetensi)</span>
                            @endif
                        </td>
                        
                        {{-- KKM --}}
                        <td class="text-center">
                            <span class="badge bg-dark bg-gradient px-3 py-2 rounded-3 shadow-sm fs-6 border border-secondary">{{ $item->kkm }}</span>
                        </td>
                        
                        {{-- Aksi Modern --}}
                        <td class="pe-4 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('tu.mapel.edit', $item->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-3 d-flex align-items-center justify-content-center transition-all hover-btn-action" title="Edit Mapel" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('tu.mapel.destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-light text-danger border shadow-sm rounded-3 d-flex align-items-center justify-content-center transition-all hover-btn-action" title="Hapus Mapel" onclick="confirmDelete('{{ $item->id }}')" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-journal-x fs-1 opacity-25"></i></div>
                            <p class="text-muted mb-0 fw-medium">Belum ada data Mata Pelajaran.</p>
                            @if(request('search'))
                                <small class="text-danger d-block mt-1">Pencarian untuk "<strong>{{ request('search') }}</strong>" tidak ditemukan.</small>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination dengan Padding --}}
        @if(method_exists($mapels, 'links') && $mapels->hasPages())
            <div class="px-4 py-3 border-top bg-light rounded-bottom-4 d-flex justify-content-end">
                {{ $mapels->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

{{-- ================= SCRIPT & STYLE TAMBAHAN ================= --}}
@if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

@if($errors->any())
    <div id="flash-errors" data-messages="{{ implode('<br>', $errors->all()) }}" style="display: none;"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Pop-up Sukses
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

    // Pop-up Error Validasi
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

    // Pop-up Konfirmasi Hapus Data
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data mapel akan dihapus permanen! Pastikan tidak ada data nilai yang terikat dengan mapel ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#f1f5f9',
            cancelButtonText: '<span class="text-dark fw-medium">Batal</span>',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
            customClass: { 
                popup: 'rounded-4',
                cancelButton: 'border border-secondary border-opacity-25 shadow-sm text-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important; }
    .hover-btn-action:hover { background-color: #f1f5f9 !important; transform: scale(1.05); }
</style>
@endsection