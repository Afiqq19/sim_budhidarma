@extends('layouts.walikelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        {{-- ALERT SUKSES (Akan muncul hijau kalau berhasil) --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- ALERT ERROR VALIDASI (Akan muncul merah kalau password lama salah / tidak cocok) --}}
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Gagal mengubah password:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 mt-2">
            <div class="card-body p-4 p-md-5">
                
                {{-- HEADER IKON KEAMANAN --}}
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-lock-fill" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Keamanan Akun</h4>
                    <p class="text-muted small">Pastikan Anda menggunakan kombinasi password yang kuat dan mudah diingat.</p>
                </div>
                
                {{-- FORM GANTI PASSWORD --}}
                <form action="{{ route('walikelas.password.update') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengubah password Anda sekarang?');">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Password Saat Ini</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-unlock"></i></span>
                            <input type="password" name="current_password" class="form-control border-start-0 focus-ring focus-ring-light" required placeholder="Masukkan password lama...">
                        </div>
                    </div>
                    
                    <hr class="my-4 opacity-25">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-danger">Password Baru</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger border-end-0"><i class="bi bi-key-fill"></i></span>
                            <input type="password" name="password" class="form-control border-danger border-opacity-25 border-start-0 focus-ring focus-ring-danger" required placeholder="Minimal 8 karakter...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-danger">Konfirmasi Password Baru</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger border-end-0"><i class="bi bi-check-circle-fill"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-danger border-opacity-25 border-start-0 focus-ring focus-ring-danger" required placeholder="Ketik ulang password baru...">
                        </div>
                    </div>
                    
                    {{-- TOMBOL AKSI --}}
                    <div class="d-flex justify-content-between pt-3 border-top mt-4">
                        <a href="{{ route('walikelas.profil') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                            <i class="bi bi-floppy-fill me-2"></i> Perbarui Password
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection