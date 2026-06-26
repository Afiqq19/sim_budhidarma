@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Master Data Pegawai</h3>
        <p class="text-muted mb-0">Kelola data Bendahara, Kepala Sekolah, dan Staff lainnya.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i> Tambah Pegawai
    </button>
</div>

{{-- PENANGKAP PESAN SUKSES --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }} 
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- PENANGKAP PESAN ERROR (SANGAT PENTING) --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Gagal Menyimpan Data!</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama & NIP</th>
                        <th>Jabatan / Hak Akses</th>
                        <th>Kontak & Login</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawais as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                            <small class="text-muted">NIP: {{ $item->nip ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark mb-1">{{ $item->jabatan }}</span><br>
                            <small class="text-secondary"><i class="bi bi-key-fill"></i> Role: <b>{{ $item->user->role }}</b></small>
                        </td>
                        <td>
                            <div class="small"><i class="bi bi-telephone-fill text-success"></i> {{ $item->no_hp ?? '-' }}</div>
                            <div class="small"><i class="bi bi-person-fill text-primary"></i> {{ $item->user->username }}</div>
                        </td>
                        <td class="text-center">
                            {{-- Tombol Show/Detail --}}
                            <a href="{{ route('pegawai.show', $item->id) }}" class="btn btn-sm btn-info text-white fw-bold mb-1" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                            
                            {{-- Tombol Edit --}}
                            <a href="{{ route('pegawai.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark fw-bold mb-1" title="Edit Data"><i class="bi bi-pencil-square"></i></a>
                            
                            {{-- Tombol Hapus --}}
                            <form action="{{ route('pegawai.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pegawai ini? Akun login juga akan terhapus!')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger mb-1" title="Hapus Data"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data pegawai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pegawai Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pegawai.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 mb-4"><small><i class="bi bi-info-circle-fill me-1"></i> Password default (awal) untuk login adalah: <code>password123</code></small></div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP (Opsional)</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip') }}">
                        </div>
                    </div>

                    <div class="row border-top pt-3 mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jabatan Pegawai</label>
                            <input type="text" name="jabatan" class="form-control bg-light fw-bold text-primary" value="Bendahara Sekolah" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-danger">Hak Akses Sistem (Role)</label>
                            
                            {{-- Trik Hidden Input agar data role terkirim walau dropdown di-disable --}}
                            <input type="hidden" name="role_akun" value="bendahara">
                            
                            <select class="form-select bg-light fw-bold text-danger" disabled>
                                <option selected>Bendahara / Keuangan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row border-top pt-3 mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Pribadi <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jk" class="form-select" required>
                                <option value="">-- Pilih Gender --</option>
                                <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">No. Handphone / WA</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan & Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection