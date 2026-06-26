<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mapel;

class SettingMapelController extends Controller
{
    public function index() {
    $kelases = Kelas::withCount('mapels')->orderBy('nama_kelas', 'asc')->get();
    return view('tu.setting_mapel.index', compact('kelases'));
}

public function manage($kelas_id) {
    $kelas = Kelas::with('mapels')->findOrFail($kelas_id);
    $mapels = Mapel::orderBy('kelompok', 'asc')->get();
    
    // Ambil ID mapel yang sudah dicentang sebelumnya
    $mapel_terpilih = $kelas->mapels->pluck('id')->toArray();
    
    return view('tu.setting_mapel.manage', compact('kelas', 'mapels', 'mapel_terpilih'));
}

public function store(Request $request, $kelas_id) {
    $kelas = Kelas::findOrFail($kelas_id);
    // Fungsi sync() akan otomatis menambah yang baru diceklis dan menghapus yang di-uncheck
    $kelas->mapels()->sync($request->mapel_ids);
    
    return redirect()->route('tu.setting.mapel.index')->with('success', 'Mapping Mapel Berhasil!');
}
}
