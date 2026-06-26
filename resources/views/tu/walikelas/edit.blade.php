@extends('layouts.tu')

@section('content')
<div class="mb-4">
    <a href="{{ route('tu.walikelas.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
    </a>
    <h3 class="fw-bold text-dark">Edit Data Wali Kelas</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm">
        <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Penyimpanan Gagal!</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('tu.walikelas.update', $walikelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="username" value="{{ $walikelas->user->username ?? '' }}">

            <h5 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-person-badge me-2 text-primary"></i>Informasi Wali Kelas</h5>

            {{-- 🔥 PERBAIKAN: Form diubah menggunakan struktur Table agar rata, presisi, dan tidak berserak 🔥 --}}
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <tbody>
                        <tr>
                            <td style="width: 30%;" class="fw-bold text-secondary">Nama Lengkap 🔒</td>
                            <td>
                                <input type="text" name="nama_lengkap" class="form-control bg-light" value="{{ $walikelas->nama_lengkap }}" readonly>
                                <small class="text-danger">*Hanya Yayasan yang bisa mengubah nama.</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">NRG (Nomor Registrasi) 🔒</td>
                            <td>
                                <input type="text" name="nrg" class="form-control bg-light" value="{{ $walikelas->nrg }}" readonly>
                                <small class="text-danger">*NRG dikunci karena digunakan sebagai Username Login sistem.</small>
                            </td>
                        </tr>
                                                
                        <tr>
                            <td class="fw-bold text-secondary">NIP <span class="text-muted fw-normal">(Opsional)</span></td>
                            <td>
                                <input type="text" name="nip" class="form-control" value="{{ old('nip', $walikelas->nip) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-danger">Tugas Kelas (Rombel)</td>
                            <td>
                                <select name="kelas_id" class="form-select border-danger">
                                    <option value="">-- Tidak Ada / Sedang Tidak Menjabat --</option>
                                    @foreach($kelases as $k)
                                        @php
                                            $guruLain = \App\Models\WaliKelas::where('kelas_id', $k->id)
                                                            ->where('id', '!=', $walikelas->id)
                                                            ->first();
                                        @endphp
                                        <option value="{{ $k->id }}" 
                                            {{ old('kelas_id', $walikelas->kelas_id) == $k->id ? 'selected' : '' }}
                                            {{ $guruLain ? 'disabled' : '' }}>
                                            {{ $k->nama_kelas }} 
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
                                <small class="text-muted">Pilih "Tidak Ada" jika guru ini sedang tidak menjabat wali kelas.</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">No. Handphone / WA <span class="text-danger">*</span></td>
                            <td>
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $walikelas->no_hp) }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">Jenis Kelamin <span class="text-danger">*</span></td>
                            <td>
                                <select name="jk" class="form-select" required>
                                    <option value="L" {{ old('jk', $walikelas->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jk', $walikelas->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">Alamat Lengkap</td>
                            <td>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $walikelas->alamat) }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h5 class="fw-bold mb-4 mt-4 border-bottom pb-2"><i class="bi bi-shield-lock me-2 text-primary"></i>Akun Login & Keamanan</h5>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <tbody>
                        <tr>
                            <td style="width: 30%;" class="fw-bold text-secondary">Email <span class="text-danger">*</span></td>
                            <td>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $walikelas->user->email ?? '') }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-secondary">Password Baru <span class="text-muted fw-normal">(Opsional)</span></td>
                            <td>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                <small class="text-muted">Biarkan kosong jika tidak ingin ganti password.</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4 border-top pt-4">
                <button type="submit" class="btn btn-warning px-5 fw-bold text-dark shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection