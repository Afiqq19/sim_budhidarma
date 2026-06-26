@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('walikelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Guru
    </a>
    <h3 class="fw-bold text-dark">Registrasi Guru Baru</h3>
    <p class="text-muted">Masukkan biodata tenaga pendidik dan sistem akan otomatis membuatkan akses login.</p>
    
    {{-- Tambahkan ini untuk melihat error validasi form --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🔥 TAMBAHKAN INI UNTUK MENAMPILKAN ERROR DATABASE 🔥 --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm small mb-4 rounded-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Sistem Error:</strong> {{ session('error') }}
        </div>
    @endif
</div>

<form action="{{ route('walikelas.store') }}" method="POST">
    @csrf
    
    <div class="alert alert-info border-0 shadow-sm small mb-4">
        <i class="bi bi-info-circle-fill me-2"></i> Kolom dengan tanda bintang merah (<span class="text-danger fw-bold fs-5">*</span>) <strong>wajib diisi</strong>.
    </div>

    <div class="row g-4">
        {{-- Sisi Kiri: Profil Guru --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-video3 me-2"></i>Biodata Guru</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Registrasi Guru (NRG) <span class="text-danger fw-bold fs-5">*</span></label>
                            <input type="text" name="nrg" class="form-control @error('nrg') is-invalid @enderror" value="{{ old('nrg') }}" placeholder="Contoh: 12345678" required>
                            <small class="text-muted" style="font-size: 0.75rem;">*NRG ini akan dijadikan Username Default</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP (Opsional)</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" placeholder="Boleh dikosongkan">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" placeholder="Contoh: Siti Aminah, S.Pd., M.Pd." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Tugaskan Sebagai Wali Kelas <small class="text-muted fw-normal">(Bisa dilewati)</small></label>
                        <select name="kelas_id" class="form-select border-primary">
                            <option value="">-- Belum Menjabat Wali Kelas --</option>
                            
                            @foreach($kelas as $k)
                                @php
                                    // 🔥 LOGIKA PINTAR UNTUK HALAMAN TAMBAH BARU 🔥
                                    // Langsung cek apakah kelas ini sudah dipakai oleh guru siapa pun
                                    $guruLain = \App\Models\WaliKelas::where('kelas_id', $k->id)->first();
                                @endphp

                                {{-- Tambahkan atribut 'disabled' jika kelas sudah terisi --}}
                                <option value="{{ $k->id }}" 
                                    {{ old('kelas_id') == $k->id ? 'selected' : '' }}
                                    {{ $guruLain ? 'disabled' : '' }}
                                >
                                    {{ $k->nama_kelas }}
                                    
                                    {{-- Penambahan Label Status Kelas --}}
                                    @if($guruLain)
                                        (❌ Terisi: {{ $guruLain->nama_lengkap }})
                                    @else
                                        (🟢 Tersedia)
                                    @endif
                                </option>
                            @endforeach
                            
                        </select>
                        <small class="text-muted">Biarkan kosong jika penempatan kelas akan diatur nanti oleh Tata Usaha.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger fw-bold fs-5">*</span></label>
                            <select name="jk" class="form-select" required>
                                <option value="">- Pilih Jenis Kelamin -</option>
                                <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp <span class="text-danger fw-bold fs-5">*</span></label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Alamat Rumah <span class="text-danger fw-bold fs-5">*</span></label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat domisili saat ini" required>{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Akun Sistem --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-success"><i class="bi bi-shield-lock-fill me-2"></i>Pengaturan Akun Login</h6>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-start w-100">Alamat Email <span class="text-danger fw-bold fs-5">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="guru@sekolah.com" required>
                    </div>

                    <div class="alert alert-success border-0 small mb-4 text-start">
                        <h6 class="fw-bold"><i class="bi bi-check-circle-fill me-2"></i>Sistem Auto-Generate:</h6>
                        <ul class="mb-0 ps-3 mt-2">
                            <li><strong>Username:</strong> Akan disamakan dengan <span class="text-decoration-underline">Nomor NRG</span>.</li>
                            <li><strong>Password:</strong> Akan diatur *default* menjadi <strong>"12345678"</strong>.</li>
                        </ul>
                        <p class="mt-2 mb-0 text-muted" style="font-size: 0.8rem;">(Wali Kelas dapat mengganti password ini setelah berhasil login pertama kali).</p>
                    </div>

                    <div class="alert alert-warning border-0 small mt-auto text-start">
                        <i class="bi bi-info-circle-fill me-1"></i> <strong>Informasi:</strong> Akun ini akan otomatis diberikan hak akses sistem sebagai <strong>Wali Kelas</strong>.
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow mt-2">
                        <i class="bi bi-save-fill me-2"></i> SIMPAN DATA GURU
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection