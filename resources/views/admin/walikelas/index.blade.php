@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Manajemen Guru</h3>
        <p class="text-muted mb-0">Kelola data dan hak akses Guru / Wali Kelas</p>
    </div>
    <a href="{{ route('walikelas.create') }}" class="btn btn-success fw-bold shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Guru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Guru</th>
                        <th>NRG / NIP</th>
                        <th>Wali Kelas</th>
                        <th>Kontak</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 🔥 PERHATIKAN: Variabelnya sekarang $walikelas, bukan $pegawais --}}
                    @forelse ($walikelas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="d-block fw-bold text-dark">{{ $item->nama_lengkap }}</span>
                            <small class="text-muted">Username: <code>{{ $item->user->username ?? '-' }}</code></small>
                        </td>
                        <td>
                            <span class="d-block fw-bold">{{ $item->nrg }}</span>
                            <small class="text-muted">NIP: {{ $item->nip ?? '-' }}</small>
                        </td>
                        <td>
                            @if($item->kelas)
                                <span class="badge bg-success rounded-pill">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Belum Menjabat</span>
                            @endif
                        </td>
                        <td>
                            <div class="small"><i class="bi bi-telephone text-success"></i> {{ $item->no_hp ?? '-' }}</div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                {{-- Tombol Show (Detail) --}}
                                <a href="{{ route('walikelas.show', $item->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Tombol Edit --}}
                                <a href="{{ route('walikelas.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('walikelas.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $item->id }}')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-person-workspace mb-3 d-block" style="font-size: 3rem;"></i>
                            Belum ada data Guru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    </script>
@endif

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data profil dan akun login guru ini akan terhapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4 shadow-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@endsection