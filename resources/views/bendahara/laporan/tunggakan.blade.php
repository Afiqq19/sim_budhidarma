@extends('layouts.bendahara')

@section('content')
{{-- Ganti bagian Judul & Download --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark"><i class="bi bi-exclamation-octagon text-danger me-2"></i>Melihat Tunggakan</h3>
        <p class="text-muted mb-0">Senarai siswa yang belum menjelaskan SPP mengikut filter.</p>
    </div>
    <a href="{{ route('tunggakan.export', request()->all()) }}" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
        <i class="bi bi-file-earmark-excel-fill me-2"></i>Download Excel
    </a>
</div>

{{-- Tambahkan Form Filter di atas Tabel --}}
{{-- FORM PENCARIAN & FILTER --}}
        <form action="{{ route('tunggakan.index') }}" method="GET" class="row g-2 mb-4 bg-light p-3 rounded-4 border">
            {{-- Filter Tahun Ajaran --}}
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Tahun Ajaran</label>
                <select name="tahun_filter" class="form-select shadow-sm rounded-3">
                    <option value="">-- Tahun Aktif Saat Ini --</option>
                    @foreach($listTahun as $t)
                        <option value="{{ $t }}" {{ request('tahun_filter') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kelas --}}
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Kelas</label>
                <select name="kelas_filter" class="form-select shadow-sm rounded-3">
                    <option value="">Semua Kelas</option>
                    @foreach($listKelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_filter') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pencarian --}}
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-1">Cari Nama/NISN</label>
                <input type="text" name="search" class="form-control shadow-sm rounded-3" placeholder="Ketik nama siswa..." value="{{ request('search') }}">
            </div>

            {{-- Tombol --}}
            <div class="col-md-2 d-flex align-items-end gap-1">
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm rounded-3">Cari</button>
                <a href="{{ route('tunggakan.index') }}" class="btn btn-light border shadow-sm px-3 rounded-3" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
        </form>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        {{-- Form Pencarian --}}
        <form action="{{ route('tunggakan.index') }}" method="GET" class="row g-2 mb-4">
            <div class="col-md-9">
                <div class="input-group shadow-sm rounded-3">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari Nama Siswa, NISN, atau Kelas..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm rounded-3">Cari</button>
                <a href="{{ route('tunggakan.index') }}" class="btn btn-light w-100 fw-bold shadow-sm rounded-3 text-muted border">Reset</a>
            </div>
        </form>

        {{-- Tabel Tunggakan --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-secondary small" width="5%">NO</th>
                        <th class="text-secondary small" width="25%">SISWA & KELAS</th>
                        <th class="text-secondary small" width="35%">BULAN TERTUNGGAK</th>
                        <th class="text-secondary small text-end" width="15%">TOTAL TAGIHAN</th>
                        <th class="text-secondary small text-center" width="20%">AKSI (PENAGIHAN)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tunggakanPerSiswa as $siswaId => $tagihans)
                        @php 
                            $siswa = $tagihans->first()->siswa;
                            $totalTagihanSiswa = $tagihans->sum('nominal');
                            
                            // Siapkan teks untuk WhatsApp
                            $listBulan = $tagihans->pluck('bulan')->implode(', ');
                            $teksWA = "Halo Bapak/Ibu Wali dari *{$siswa->nama_lengkap}*.\n\nKami dari Tata Usaha SMK Budhi Darma menginformasikan bahwa terdapat tagihan SPP yang belum diselesaikan untuk bulan:\n*{$listBulan}*\n\nTotal Tagihan: *Rp " . number_format($totalTagihanSiswa, 0, ',', '.') . "*.\n\nMohon kerjasamanya untuk segera melakukan pelunasan. Terima kasih. 🙏";
                        @endphp
                    <tr>
                        <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                        
                        {{-- Identitas Siswa --}}
                        <td>
                            <span class="fw-bold text-dark d-block">{{ $siswa->nama_lengkap ?? 'Siswa Dihapus' }}</span>
                            <span class="small text-muted">{{ $siswa->kelas->nama_kelas ?? '-' }} • NISN: {{ $siswa->nisn ?? '-' }}</span>
                        </td>

                        {{-- Rincian Bulan --}}
                        <td>
                            @foreach($tagihans as $t)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 mb-1 me-1">
                                    {{ $t->bulan }}
                                </span>
                            @endforeach
                            <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">Total: {{ $tagihans->count() }} Bulan</small>
                        </td>

                        {{-- Total Uang --}}
                        <td class="text-end fw-bold text-danger fs-5">
                            Rp {{ number_format($totalTagihanSiswa, 0, ',', '.') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="text-center">
                            {{-- Tombol Link ke Halaman Bayar --}}
                            <a href="{{ route('transaksi.show', $siswa->id) }}" class="btn btn-sm btn-outline-primary rounded-3 shadow-sm me-1" title="Bayar Sekarang">
                                <i class="bi bi-wallet2"></i> Bayar
                            </a>

                            {{-- Tombol Kirim Tagihan WhatsApp --}}
                            @if(!empty($siswa->no_hp_ortu))
                                @php 
                                    // Bersihkan nomor HP (ubah awalan 0 atau +62 jadi format internasional standar 62)
                                    $noHpClean = preg_replace('/[^0-9]/', '', $siswa->no_hp_ortu);
                                    if(str_starts_with($noHpClean, '0')) {
                                        $noHpClean = '62' . substr($noHpClean, 1);
                                    }
                                @endphp
                                <!-- <a href="https://wa.me/{{ $noHpClean }}?text={{ urlencode($teksWA) }}" target="_blank" class="btn btn-sm btn-success rounded-3 shadow-sm" title="Kirim Tagihan via WA">
                                    <i class="bi bi-whatsapp"></i> Kirim WA
                                </a> -->
                            @else
                                <button class="btn btn-sm btn-secondary rounded-3 shadow-sm opacity-50" disabled title="Nomor HP Ortu Kosong">
                                    <i class="bi bi-whatsapp"></i> No HP Null
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-check-circle fs-1 d-block mb-3 text-success opacity-50"></i>
                            Alhamdulillah! Tidak ada siswa yang menunggak SPP hingga bulan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection