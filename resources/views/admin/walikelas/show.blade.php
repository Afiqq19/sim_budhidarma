@extends('layouts.admin')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('walikelas.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Guru
        </a>
        <h3 class="fw-bold text-dark">Profil Detail Guru</h3>
        <p class="text-muted">Informasi lengkap biodata dan akun sistem.</p>
    </div>
</div>

<div class="row g-4">
    {{-- Kartu Info Utama --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
            
            {{-- 🔥 Bagian Foto Profil Dinamis Berdasarkan Jenis Kelamin 🔥 --}}
            <div class="mb-3 d-flex justify-content-center">
                @if($walikelas->jk == 'L')
                    {{-- Avatar Cowok --}}
                    <img src="{{ asset('images/username_lk.png') }}" alt="Avatar Laki-laki" 
                         class="rounded-circle shadow border border-3 border-white" 
                         style="width: 120px; height: 120px; object-fit: cover; background-color: #e9ecef;">
                @else
                    {{-- Avatar Cewek --}}
                    <img src="{{ asset('images/username_pr.png') }}" alt="Avatar Perempuan" 
                         class="rounded-circle shadow border border-3 border-white" 
                         style="width: 120px; height: 120px; object-fit: cover; background-color: #f8d7da;">
                @endif
            </div>

            <h4 class="fw-bold mb-1">{{ $walikelas->nama_lengkap }}</h4>
            <p class="text-muted mb-3 fw-medium">NRG: {{ $walikelas->nrg }}</p>
            
            <div class="mt-auto">
                @if($walikelas->kelas)
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25">
                        <span class="d-block text-success fw-bold mb-1"><i class="bi bi-star-fill me-1"></i> Sedang Menjabat</span>
                        <h5 class="text-success mb-0">Wali Kelas {{ $walikelas->kelas->nama_kelas }}</h5>
                    </div>
                @else
                    <div class="p-3 bg-secondary bg-opacity-10 rounded-3 border border-secondary border-opacity-25">
                        <span class="d-block text-secondary fw-bold mb-1"><i class="bi bi-dash-circle me-1"></i> Status Jabatan</span>
                        <h6 class="text-secondary mb-0">Belum Menjabat Wali Kelas</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kartu Detail Lengkap --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-0 border-bottom">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-card-list me-2"></i>Informasi Biodata & Akun</h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    {{-- Kolom Kiri: Biodata --}}
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Biodata Diri</h6>
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted px-0" width="40%">NRG</td>
                                    <td class="fw-bold px-0">: {{ $walikelas->nrg }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">NIP</td>
                                    <td class="fw-bold px-0">: {{ $walikelas->nip ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">Jenis Kelamin</td>
                                    <td class="fw-bold px-0">: {{ $walikelas->jk == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">No. WhatsApp</td>
                                    <td class="fw-bold px-0">: 
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $walikelas->no_hp) }}" target="_blank" class="text-success text-decoration-none">
                                            <i class="bi bi-whatsapp me-1"></i>{{ $walikelas->no_hp }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0 align-top">Alamat</td>
                                    <td class="fw-bold px-0">: {{ $walikelas->alamat }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Kolom Kanan: Akun --}}
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Data Akun Sistem</h6>
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted px-0" width="40%">Status Akun</td>
                                    <td class="fw-bold px-0">: 
                                        @if($walikelas->user)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Ada Akun</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">Email</td>
                                    <td class="fw-bold px-0">: {{ $walikelas->user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">Username</td>
                                    <td class="fw-bold text-primary px-0">: {{ $walikelas->user->username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">Role Akses</td>
                                    <td class="fw-bold px-0">: 
                                        <span class="badge bg-info text-dark">Wali Kelas</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted px-0">Password</td>
                                    <td class="fw-bold px-0 text-muted">: <i>(Terenkripsi / Rahasia)</i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection