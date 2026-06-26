@extends('layouts.tu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Penempatan Wali Kelas</h3>
        <p class="text-muted mb-0">Atur penugasan rombongan belajar untuk guru / wali kelas</p>
    </div>
    {{-- Tombol Tambah Dihapus: Karena hanya Yayasan yang boleh menambah Guru --}}
</div>

{{-- Alert Notifikasi Sukses --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        <div class="alert alert-info border-0 rounded-3 small mb-4">
            <i class="bi bi-info-circle-fill me-2"></i> <strong>Informasi:</strong> Staf TU hanya memiliki akses untuk melakukan penempatan kelas dan pembaruan kontak. Penambahan atau penghapusan data Wali Kelas dikelola oleh Yayasan.
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NRG / NIP</th>
                        <th>Nama Guru</th>
                        <th>Wali Kelas Untuk</th>
                        <th>L/P</th>
                        <th>Kontak</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($walikelas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="d-block fw-bold text-primary">{{ $item->nrg }}</span>
                            <small class="text-muted">{{ $item->nip ?? '-' }}</small>
                        </td>
                        <td class="fw-bold">{{ $item->nama_lengkap }}</td>
                        <td>
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                <i class="bi bi-diagram-3 me-1"></i> {{ $item->kelas->nama_kelas ?? 'Belum ada kelas' }}
                            </span>
                        </td>
                        <td>{{ $item->jk }}</td>
                        <td>
                            <a href="https://wa.me/{{ $item->no_hp }}" target="_blank" class="text-decoration-none">
                                {{ $item->no_hp }} <i class="bi bi-whatsapp text-success"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                {{-- Link diarahkan ke rute TU --}}
                                <a href="{{ route('tu.walikelas.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ route('tu.walikelas.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Atur Penempatan Kelas"><i class="bi bi-pencil-square"></i></a>
                                
                                {{-- Tombol Hapus Dihapus: Karena hanya Yayasan yang boleh memecat/menghapus Guru --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people mb-3 d-block" style="font-size: 3rem;"></i>
                            Belum ada data Wali Kelas dari Yayasan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection