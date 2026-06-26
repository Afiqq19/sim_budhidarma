<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// --- IMPORT CONTROLLER ADMIN (YAYASAN) ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\WaliKelasController as AdminWaliKelas;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;

// --- IMPORT CONTROLLER TU (TATA USAHA) ---
use App\Http\Controllers\TU\DashboardController;
use App\Http\Controllers\TU\JurusanController;
use App\Http\Controllers\TU\KelasController;
use App\Http\Controllers\TU\SiswaController;
use App\Http\Controllers\TU\WaliKelasController as TuWaliKelas;
use App\Http\Controllers\TU\MapelController;
use App\Http\Controllers\TU\SettingMapelController;
use App\Http\Controllers\TU\TahunAjaranController;
use App\Http\Controllers\TU\KenaikanKelasController;
use App\Http\Controllers\TU\AlumniController;

// --- IMPORT CONTROLLER BENDAHARA ---
use App\Http\Controllers\Bendahara\TransaksiController;
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboard;
use App\Http\Controllers\WaliKelas\ProfilController;


// Halaman Form Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk memproses data login saat tombol ditekan
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    
    // ==========================================
    // RUTE KHUSUS ADMIN (YAYASAN)
    // ==========================================
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        
        // Yayasan berhak penuh atas data Wali Kelas & Pegawai (TU/Bendahara)
                
        Route::resource('/pegawai', \App\Http\Controllers\Admin\PegawaiController::class);
        Route::resource('/walikelas', \App\Http\Controllers\Admin\WaliKelasController::class);

        // Rute Monitoring Riwayat Pembayaran (Admin Yayasan)
        Route::get('/riwayat-transaksi', [\App\Http\Controllers\Admin\RiwayatTransaksiController::class, 'index'])->name('admin.riwayat.index');
        Route::get('/riwayat-transaksi/export', [\App\Http\Controllers\Admin\RiwayatTransaksiController::class, 'exportExcel'])->name('admin.riwayat.export');
        Route::get('/riwayat-transaksi/cetak/{id}', [\App\Http\Controllers\Admin\RiwayatTransaksiController::class, 'cetak'])->name('admin.riwayat.cetak');

        // Di dalam grup rute Admin:
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('admin.profile');
        Route::put('/profile/update', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('admin.profile.update');
    });


    // ==========================================
    // RUTE KHUSUS TU (TATA USAHA)
    // ==========================================
    Route::middleware(['auth', 'role:tu'])->prefix('tu')->name('tu.')->group(function () {
        // Operasional Akademik & Siswa dipindah ke sini
        // 🔥 TAMBAHKAN RUTE DASHBOARD INI 🔥
        Route::get('/dashboard', [\App\Http\Controllers\TU\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/jurusan', JurusanController::class)->names('jurusan');
        Route::resource('/kelas', KelasController::class)->names('kelas');
        Route::resource('/siswa', SiswaController::class)->names('siswa');
        Route::resource('/mapel', MapelController::class)->names('mapel');
        
        Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
        Route::post('/kenaikan-kelas/proses-serentak', [KenaikanKelasController::class, 'prosesSerentak'])->name('kenaikan-kelas.proses');

        Route::get('/setting-mapel', [SettingMapelController::class, 'index'])->name('setting.mapel.index');
        Route::get('/setting-mapel/{id}', [SettingMapelController::class, 'manage'])->name('setting.mapel.manage');
        Route::post('/setting-mapel/{id}', [SettingMapelController::class, 'store'])->name('setting.mapel.store');
        
        Route::resource('/tahun-ajaran', TahunAjaranController::class)->names('tahun-ajaran')->except(['show']);
        Route::post('/tahun-ajaran/set-aktif/{id}', [TahunAjaranController::class, 'setAktif'])->name('tahun_ajaran.set_aktif');

        Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
        Route::get('/alumni/{id}', [AlumniController::class, 'show'])->name('alumni.show');

        // TU Hanya punya akses Terbatas untuk Wali Kelas (Lihat & Edit Kelas/Kontak saja)
        Route::get('/walikelas', [TuWaliKelas::class, 'index'])->name('walikelas.index');
        Route::get('/walikelas/{id}', [TuWaliKelas::class, 'show'])->name('walikelas.show');
        Route::get('/walikelas/{id}/edit', [TuWaliKelas::class, 'edit'])->name('walikelas.edit');
        Route::put('/walikelas/{id}', [TuWaliKelas::class, 'update'])->name('walikelas.update');
        // Rute untuk halaman Profil TU
        Route::get('/profile', [App\Http\Controllers\TU\ProfileController::class, 'index'])->name('profile.index');
        
        // Rute untuk MENYIMPAN perubahan Profil TU
        Route::put('/profile/update', [App\Http\Controllers\TU\ProfileController::class, 'update'])->name('profile.update');
    });


    // ==========================================
    // RUTE KHUSUS BENDAHARA
    // ==========================================
    Route::prefix('bendahara')->group(function () {
        
        // Rute Dashboard Bendahara
        Route::get('/dashboard', [BendaharaDashboard::class, 'index'])->name('bendahara.dashboard');

        // Rute Kasir Transaksi SPP
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{id}/kartu', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::post('/transaksi/{id}/bayar-manual', [TransaksiController::class, 'storeManual'])->name('transaksi.storeManual');
        // 🔥 TAMBAHKAN ->where('tahun', '.*') DI BAGIAN BELAKANGNYA 🔥
        Route::get('/transaksi/{siswa_id}/cetak-rekap/{tahun}', [TransaksiController::class, 'cetakRekap'])->where('tahun', '.*')->name('transaksi.cetakRekap');
        
        // Rute Data Tagihan SPP (Generate Massal)
        Route::get('/tagihan', [\App\Http\Controllers\Bendahara\TagihanSppController::class, 'index'])->name('tagihan.index');
        Route::post('/tagihan/generate-massal', [\App\Http\Controllers\Bendahara\TagihanSppController::class, 'generateMassal'])->name('tagihan.generateMassal');
        Route::post('/tagihan/set-nominal', [\App\Http\Controllers\Bendahara\TagihanSppController::class, 'setNominal'])->name('tagihan.setNominal');

        // Rute Laporan / Riwayat Transaksi
        Route::get('/riwayat-transaksi', [\App\Http\Controllers\Bendahara\RiwayatTransaksiController::class, 'index'])->name('riwayat.index');
        Route::get('/riwayat-transaksi/cetak/{id}', [\App\Http\Controllers\Bendahara\RiwayatTransaksiController::class, 'cetak'])->name('riwayat.cetak');
        Route::get('/riwayat-transaksi/export-excel', [\App\Http\Controllers\Bendahara\RiwayatTransaksiController::class, 'exportExcel'])->name('riwayat.export');
        

        // Rute Laporan Tunggakan SPP
        Route::get('/laporan-tunggakan', [\App\Http\Controllers\Bendahara\LaporanTunggakanController::class, 'index'])->name('tunggakan.index');
        Route::get('/laporan-tunggakan/export', [\App\Http\Controllers\Bendahara\LaporanTunggakanController::class, 'exportExcel'])->name('tunggakan.export');
    
        // Rute Edit Profil Bendahara
        Route::get('/profil', [\App\Http\Controllers\Bendahara\ProfileController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [\App\Http\Controllers\Bendahara\ProfileController::class, 'update'])->name('profil.update');
    });

   // ==========================================
    // RUTE KHUSUS WALI KELAS
    // ==========================================
    Route::prefix('walikelas')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\WaliKelas\DashboardController::class, 'index'])->name('walikelas.dashboard');
        
        // --- RUTE DATA SISWAKU ---
        Route::get('/siswa', [\App\Http\Controllers\WaliKelas\SiswaController::class, 'index'])->name('walikelas.siswa.index');
        Route::get('/siswa/{id}', [\App\Http\Controllers\WaliKelas\SiswaController::class, 'show'])->name('walikelas.siswa.show');
        
        // --- RUTE E-RAPOR & NILAI ---
        Route::get('/input-nilai', [\App\Http\Controllers\WaliKelas\NilaiController::class, 'index'])->name('walikelas.nilai.index');
        Route::post('/input-nilai', [\App\Http\Controllers\WaliKelas\NilaiController::class, 'store'])->name('walikelas.nilai.store');
        
        // Rute untuk melihat Rekap/Leger Nilai Kelas (SUDAH DIPERBAIKI NAMANYA)
        Route::get('/rekap-nilai', [\App\Http\Controllers\WaliKelas\NilaiController::class, 'rekap'])->name('walikelas.rekap');
        Route::get('/nilai/detail/{id}', [\App\Http\Controllers\WaliKelas\NilaiController::class, 'detail'])->name('walikelas.nilai.detail');
        
        // --- RUTE PROFIL GURU ---
        Route::get('/profil', [\App\Http\Controllers\WaliKelas\ProfilController::class, 'index'])->name('walikelas.profil');
        Route::get('/profil/edit', [\App\Http\Controllers\WaliKelas\ProfilController::class, 'edit'])->name('walikelas.profil.edit');
        Route::put('/profil/update', [\App\Http\Controllers\WaliKelas\ProfilController::class, 'update'])->name('walikelas.profil.update');

        Route::get('/password', [\App\Http\Controllers\WaliKelas\ProfilController::class, 'editPassword'])->name('walikelas.password.edit');
        Route::put('/password/update', [\App\Http\Controllers\WaliKelas\ProfilController::class, 'updatePassword'])->name('walikelas.password.update');
    });

    // ==========================================
    // RUTE KHUSUS SISWA
    // ==========================================
    Route::prefix('siswa')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('siswa.dashboard');
        
        // E-Rapor
        Route::get('/rapor', [\App\Http\Controllers\Siswa\RaporController::class, 'index'])->name('siswa.rapor');
        
        // Keuangan / SPP
        Route::get('/tagihan', [\App\Http\Controllers\Siswa\PembayaranController::class, 'index'])->name('siswa.tagihan');
        Route::post('/tagihan/success', [\App\Http\Controllers\Siswa\PembayaranController::class, 'updateStatus'])->name('siswa.tagihan.success');
        
        // ==========================================
        // ROUTE PROFIL SISWA
        // ==========================================
        Route::get('/profil', [App\Http\Controllers\Siswa\ProfilController::class, 'index'])->name('siswa.profil');
        

        // Route untuk Edit Profil
        Route::get('/profil/edit', [App\Http\Controllers\Siswa\ProfilController::class, 'edit'])->name('siswa.profil.edit');
        Route::post('/profil/update', [App\Http\Controllers\Siswa\ProfilController::class, 'update'])->name('siswa.profil.update');

        // Route untuk Ganti Password
        Route::get('/profil/password', [App\Http\Controllers\Siswa\ProfilController::class, 'password'])->name('siswa.profil.password');
        Route::post('/profil/password', [App\Http\Controllers\Siswa\ProfilController::class, 'updatePassword'])->name('siswa.profil.password.update');
    });

});