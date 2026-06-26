<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaliKelas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WaliKelasController extends Controller
{
    public function index()
    {
        // Ambil data guru beserta akun login dan kelas yang sedang dipegang (jika ada)
        $walikelas = WaliKelas::with(['user', 'kelas'])->latest()->get();
        return view('admin.walikelas.index', compact('walikelas'));
    }

    public function create()
    {
        // Ambil data kelas untuk ditampilkan di pilihan (dropdown)
        $kelas = \App\Models\Kelas::orderBy('nama_kelas', 'asc')->get();
        return view('admin.walikelas.tambah', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 🔥 Tambahkan max:12 di sini untuk mencegah error SQL "Data too long"
            'nrg'          => 'required|string|max:12|unique:wali_kelas,nrg|unique:users,username',
            'kelas_id'     => 'nullable|exists:kelas,id',
            // 🔥 Sesuaikan max menjadi 35 sesuai batas database kita sebelumnya
            'nama_lengkap' => 'required|string|max:35',
            'jk'           => 'required|in:L,P',
            // 🔥 Tambahkan max:50 untuk email
            'email'        => 'required|email|max:50|unique:users,email',
            // Validasi username & password DIHAPUS karena otomatis
        ], [
            // --- PESAN ERROR KUSTOM ---
            'nrg.max'      => 'Pemberitahuan: Nomor NRG terlalu panjang, maksimal hanya 12 digit!', // Ini penangkal utamanya
            'nrg.unique'   => 'NRG sudah terdaftar atau sudah dipakai sebagai username.',
            'nrg.required' => 'NRG wajib diisi.',

            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'      => 'Nama lengkap terlalu panjang (maksimal 35 karakter).',

            'jk.required'  => 'Jenis kelamin wajib dipilih.',
            'jk.in'        => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',

            'email.required' => 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, gunakan yang lain.',
            'email.max'    => 'Alamat email terlalu panjang (maksimal 50 karakter).'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'username' => $request->nrg, // 🔥 Auto-set Username = NRG
                'password' => Hash::make('12345678'), // 🔥 Auto-set Password = 12345678
                'role'     => 'wali_kelas',
            ]);

            Walikelas::create([
                'user_id'      => $user->id,
                'kelas_id'     => $request->kelas_id ?: null,
                'nrg'          => $request->nrg,
                'nip'          => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk'           => $request->jk,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('walikelas.index')->with('success', 'Data Guru & Akun (Username: ' . $request->nrg . ') berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal Simpan Database: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $walikelas = Walikelas::findOrFail($id);
            $userId = $walikelas->user_id;

            $walikelas->delete(); // Hapus profil
            User::destroy($userId); // Hapus akun loginnya juga

            DB::commit();
            return redirect()->route('walikelas.index')->with('success', 'Data Guru beserta hak aksesnya berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    // TAMPILKAN DETAIL GURU
    public function show($id)
    {
        $walikelas = Walikelas::with(['user', 'kelas'])->findOrFail($id);
        return view('admin.walikelas.show', compact('walikelas'));
    }

    // TAMPILKAN FORM EDIT
    // TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $walikelas = Walikelas::with('user')->findOrFail($id);

        // 🔥 Tambahkan huruf 's' menjadi $kelases 🔥
        $kelases = \App\Models\Kelas::orderBy('nama_kelas', 'asc')->get();

        // 🔥 Tambahkan huruf 's' di dalam compact menjadi 'kelases' 🔥
        return view('admin.walikelas.edit', compact('walikelas', 'kelases'));
    }

    // PROSES UPDATE DATA KE DATABASE
    // PROSES UPDATE DATA KE DATABASE
    public function update(Request $request, $id)
    {
        $walikelas = Walikelas::findOrFail($id);
        $userId = $walikelas->user_id;

        $request->validate([
            // 🔥 Batas aman NRG: max:12, pengecualian ID wali_kelas saat update
            'nrg'          => 'required|string|max:12|unique:wali_kelas,nrg,' . $id,
            'kelas_id'     => 'nullable|exists:kelas,id',
            // 🔥 Angka sakti Bapak: max:35
            'nama_lengkap' => 'required|string|max:35',
            'jk'           => 'required|in:L,P',
            // 🔥 Angka sakti Bapak: max:50, pengecualian ID user saat update
            'email'        => 'required|email|max:50|unique:users,email,' . $userId,
            // 🔥 Username disesuaikan ke max:35, pengecualian ID user saat update
            'username'     => 'required|string|max:35|unique:users,username,' . $userId,
            'password'     => 'nullable|string|min:6', // Boleh kosong jika tidak ingin ganti password
        ], [
            // --- PESAN ERROR KUSTOM ---
            'nrg.required' => 'NRG wajib diisi.',
            'nrg.max'      => 'Nomor NRG maksimal 12 digit.',
            'nrg.unique'   => 'NRG sudah terdaftar untuk guru lain.',

            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'      => 'Nama lengkap maksimal 35 karakter.',

            'jk.required'  => 'Jenis kelamin wajib dipilih.',
            'jk.in'        => 'Jenis kelamin tidak valid.',

            'email.required' => 'Email wajib diisi.',
            'email.email'  => 'Format email tidak valid.',
            'email.unique' => 'Email sudah dipakai pengguna lain.',
            'email.max'    => 'Alamat email maksimal 50 karakter.',

            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah terdaftar, gunakan yang lain.',
            'username.max'      => 'Username maksimal 35 karakter.',

            'password.min' => 'Password minimal 6 karakter jika ingin diubah.'
        ]);

        DB::beginTransaction();
        try {
            // 1. Siapkan Data Update Akun User
            $userData = [
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'username' => $request->username, // 🔥 Update Username sesuai inputan
            ];

            // 🔥 2. Cek apakah admin mengisi password baru?
            // Jika diisi, masukkan ke array update. Jika tidak, password lama tetap aman.
            if ($request->filled('password')) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
            }

            // Eksekusi update user
            $user = User::findOrFail($userId);
            $user->update($userData);

            // 3. Update Profil Walikelas
            $walikelas->update([
                'kelas_id'     => $request->kelas_id ?: null,
                'nrg'          => $request->nrg,
                'nip'          => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jk'           => $request->jk,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('walikelas.index')->with('success', 'Data Guru & Hak Akses berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal Update Database: ' . $e->getMessage());
        }
    }
}
