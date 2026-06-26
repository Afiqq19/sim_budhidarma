@extends('layouts.siswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle d-inline-block mb-3">
                        <i class="bi bi-shield-lock-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Keamanan Akun</h4>
                    <p class="text-muted small">Kelola password Anda untuk melindungi data pribadi di portal ini.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 mb-4 small shadow-sm">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('siswa.profil.password.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control border-danger bg-danger bg-opacity-10 py-2" required placeholder="Masukkan password lama">
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control border-primary bg-primary bg-opacity-10 py-2" required placeholder="Minimal 8 karakter">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control border-primary bg-primary bg-opacity-10 py-2" required placeholder="Ulangi password baru">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">Simpan Password Baru</button>
                        <a href="{{ route('siswa.profil') }}" class="btn btn-light rounded-pill py-2 text-muted fw-bold">Batal & Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection