@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('walikelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h3 class="fw-bold text-dark">Edit Data Guru</h3>
    <p class="text-muted">Perbarui biodata dan akses login untuk <strong>{{ $walikelas->nama_lengkap }}</strong>.</p>
    
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<form action="{{ route('walikelas.update', $walikelas->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        {{-- Sisi Kiri: Profil Guru --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-lines-fill me-2"></i>Biodata Guru</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor Registrasi Guru (NRG)</label>
                            <input type="text" name="nrg" class="form-control" value="{{ old('nrg', $walikelas->nrg) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NIP (Opsional)</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip', $walikelas->nip) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $walikelas->nama_lengkap) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Tugaskan Sebagai Wali Kelas</label>
                        <select name="kelas_id" class="form-select border-primary">
                            <option value="">-- Tidak Ada / Sedang Tidak Menjabat --</option>
                            
                            @foreach($kelases as $k)
                                @php
                                    // 🔥 LOGIKA PINTAR: Cek apakah kelas ini sudah diklaim oleh guru lain
                                    $guruLain = \App\Models\WaliKelas::where('kelas_id', $k->id)
                                                    ->where('id', '!=', $walikelas->id)
                                                    ->first();
                                @endphp

                                {{-- Jika sudah ada guru lain, tambahkan atribut 'disabled' --}}
                                <option value="{{ $k->id }}" 
                                    {{ old('kelas_id', $walikelas->kelas_id) == $k->id ? 'selected' : '' }}
                                    {{ $guruLain ? 'disabled' : '' }}
                                >
                                    {{ $k->nama_kelas }} 
                                    
                                    {{-- Label Status Kelas --}}
                                    @if($guruLain)
                                        (❌ Terisi: {{ $guruLain->nama_lengkap }})
                                    @elseif(old('kelas_id', $walikelas->kelas_id) == $k->id)
                                        (✅ Kelas Guru Ini)
                                    @else
                                        (🟢 Tersedia)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="L" {{ old('jk', $walikelas->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk', $walikelas->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $walikelas->no_hp) }}" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Alamat Rumah</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $walikelas->alamat) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Akun Sistem --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-success"><i class="bi bi-shield-check me-2"></i>Pengaturan Akun Login</h6>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-start w-100">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $walikelas->user->email ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-start w-100">Username Login <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $walikelas->user->username ?? '') }}" required>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold w-100 text-danger">Ganti Password <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ketik password baru...">
                        <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>Biarkan kosong jika tidak ingin mengubah password saat ini.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow mt-auto">
                        <i class="bi bi-save-fill me-2"></i> PERBARUI DATA
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection