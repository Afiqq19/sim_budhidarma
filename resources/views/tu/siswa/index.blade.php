@extends('layouts.tu')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
    <div>
        <h3 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Data Master Siswa</h3>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">Manajemen biodata, kelas, dan akses akun siswa</p>
    </div>
    
    <div class="d-flex flex-column flex-sm-row gap-2">
        {{-- 🔥 FITUR PENCARIAN (SEARCH) 🔥 --}}
        <form action="{{ route('tu.siswa.index') }}" method="GET" class="d-flex">
            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white" style="border: 1px solid #e2e8f0; min-width: 250px;">
                <span class="input-group-text bg-transparent border-0 text-muted pe-1"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent" placeholder="Cari NISN atau Nama..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                <button class="btn btn-primary px-3 fw-semibold" type="submit" style="border-radius: 0 50px 50px 0;">Cari</button>
            </div>
        </form>
        
        {{-- Tombol Tambah Siswa --}}
        <a href="{{ route('tu.siswa.create') }}" class="btn btn-primary fw-semibold shadow-sm rounded-pill d-flex align-items-center px-4 transition-all hover-lift">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Siswa
        </a>
    </div>
</div>

{{-- Alert Notifikasi Sukses --}}
@if(session('success'))
    <div class="alert bg-success bg-opacity-10 border-0 border-start border-success border-4 text-success shadow-sm rounded-3 d-flex align-items-center p-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-3 fs-5"></i>
        <div class="fw-medium">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 bg-white" style="border: 1px solid #e2e8f0 !important;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-white">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">No</th>
                        <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">NISN</th>
                        <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nama Lengkap</th>
                        <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kelas</th>
                        <th class="py-3 text-center text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">L/P</th>
                        <th class="py-3 text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kontak Ortu</th>
                        <th class="pe-4 py-3 text-center text-muted fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $item)
                    <tr style="transition: all 0.2s;">
                        <td class="ps-4 text-secondary" style="font-size: 0.9rem;">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-2 fw-semibold" style="font-size: 0.8rem; letter-spacing: 1px;">
                                {{ $item->nisn }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                {{-- Avatar Inisial --}}
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-3 fw-bold flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    {{ substr($item->nama_lengkap, 0, 1) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $item->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                                {{ $item->kelas->kode_kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->jk == 'L')
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-circle" title="Laki-laki">L</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-circle" title="Perempuan">P</span>
                            @endif
                        </td>
                        <td class="text-secondary" style="font-size: 0.85rem;">
                            <i class="bi bi-telephone text-muted me-1"></i> {{ $item->no_hp_ortu ?? '-' }}
                        </td>
                        <td class="pe-4 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Detail Elegan --}}
                                <a href="{{ route('tu.siswa.show', $item->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-3 d-flex align-items-center justify-content-center transition-all hover-btn-action" title="Lihat Profil Lengkap" style="width: 32px; height: 32px; padding: 0;">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                
                                {{-- Tombol Hapus Elegan --}}
                                <form action="{{ route('tu.siswa.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus siswa ini? Akun login juga akan terhapus!')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger border shadow-sm rounded-3 d-flex align-items-center justify-content-center transition-all hover-btn-action" title="Hapus" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-search fs-1 opacity-25"></i></div>
                            <p class="text-muted mb-0 fw-medium">Belum ada data siswa.</p>
                            @if(request('search'))
                                <small class="text-danger d-block mt-1">Pencarian untuk "<strong>{{ request('search') }}</strong>" tidak ditemukan.</small>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination Laravel (Jika Ada) --}}
    @if(method_exists($siswas, 'links') && $siswas->hasPages())
    <div class="card-footer bg-white border-top pt-3 pb-3 px-4 rounded-bottom-4">
        {{ $siswas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Custom CSS untuk Hover --}}
<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3) !important; }
    .hover-btn-action:hover { background-color: #f1f5f9 !important; transform: scale(1.05); }
</style>
@endsection