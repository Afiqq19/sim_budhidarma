@extends('layouts.siswa')

@section('content')

@php
    // 🔥 PENGATURAN TEMA KELAS BERDASARKAN STATUS 🔥
    $badgeKelasClass = 'bg-primary text-white';
    $teksKelas = $siswa->kelas->nama_kelas ?? '-';
    
    if ($siswa->status_siswa == 'Alumni') {
        $badgeKelasClass = 'bg-secondary text-white';
        $teksKelas = 'Lulusan / Alumni';
    } elseif ($siswa->status_siswa == 'Pindah') {
        $badgeKelasClass = 'bg-warning text-dark';
        $teksKelas = 'Status Pindah';
    }
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-wallet2 text-primary me-2"></i>Administrasi Keuangan</h3>
        <p class="text-muted mb-0">Status Akademik: <strong class="badge {{ $badgeKelasClass }}">{{ $teksKelas }}</strong> | Periode: <strong>{{ $selectedTahun }}</strong></p>
    </div>
    
    <form action="{{ route('siswa.tagihan') }}" method="GET" class="d-flex gap-2">
        <select name="tahun" class="form-select border-primary shadow-sm fw-bold focus-ring focus-ring-primary" onchange="this.form.submit()" style="min-width: 250px;">
            @foreach($listTahun as $t)
                <option value="{{ $t }}" {{ $selectedTahun == $t ? 'selected' : '' }}>
                    Tahun Ajaran {{ $t }} {{ (isset($tahunAktifObj) && $t == $tahunAktifObj->tahun) ? '(Aktif)' : '' }}
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- Card Tunggakan Aktif --}}
@if(isset($tahunAktifObj) && $selectedTahun == $tahunAktifObj->tahun)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 {{ $totalTunggakanWajib > 0 ? 'bg-danger bg-opacity-10 border-start border-danger border-4' : 'bg-success bg-opacity-10 border-start border-success border-4' }}">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    @if($totalTunggakanWajib > 0)
                        <h6 class="text-danger fw-bold text-uppercase small mb-1">Tunggakan Jatuh Tempo (s/d {{ $bulanSekarang }})</h6>
                        <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($totalTunggakanWajib, 0, ',', '.') }}</h3>
                        <p class="mb-0 small mt-1 text-danger">Mencakup periode: {{ $teksRangeBulan }}</p>
                    @else
                        <h6 class="text-success fw-bold text-uppercase small mb-1">Status Keuangan</h6>
                        <h3 class="fw-bold text-dark mb-0">Lunas / Aman ✨</h3>
                        <p class="mb-0 small mt-1 text-success">Semua kewajiban tahun ajaran ini telah diselesaikan.</p>
                    @endif
                </div>
                <i class="bi {{ $totalTunggakanWajib > 0 ? 'bi-exclamation-circle-fill text-danger' : 'bi-check-circle-fill text-success' }} display-5"></i>
            </div>
        </div>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white pt-4 px-4 pb-3 border-bottom-0">
        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-calendar3 text-primary me-2"></i>Rincian Pembayaran 12 Bulan</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="ps-4 text-start py-3 border-0">Bulan</th>
                        <th class="py-3 border-0">Nominal</th>
                        <th class="py-3 border-0">Status</th>
                        <th class="pe-4 py-3 border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $isFirstUnpaid = true; @endphp
                    @forelse($tagihans as $t)
                        @php $idx = $urutanBulan[$t->bulan] ?? 0; @endphp
                        <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="ps-4 text-start fw-bold text-dark py-3">
                                {{ $t->bulan }}
                                @if(isset($tahunAktifObj) && $selectedTahun == $tahunAktifObj->tahun && $t->status == 'Belum Lunas' && $idx <= $indexSekarang && $isFirstUnpaid)
                                    <span class="badge bg-danger ms-2 shadow-sm" style="font-size: 0.65rem; letter-spacing: 0.5px;">JATUH TEMPO</span>
                                @endif
                            </td>
                            <td class="fw-medium text-dark">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $t->status == 'Lunas' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $t->status == 'Lunas' ? 'text-success' : 'text-danger' }} px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi {{ $t->status == 'Lunas' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i> {{ $t->status }}
                                </span>
                            </td>
                            <td class="pe-4">
                                @if($t->status == 'Belum Lunas')
                                    @if(isset($tahunAktifObj) && $selectedTahun == $tahunAktifObj->tahun)
                                        <button class="btn {{ ($idx <= $indexSekarang && $isFirstUnpaid) ? 'btn-primary' : 'btn-outline-primary' }} btn-sm rounded-pill px-4 fw-bold shadow-sm transition-all hover-lift" onclick="payNow('{{ $t->snap_token }}')">
                                            <i class="bi bi-credit-card-fill me-1"></i> {{ ($idx <= $indexSekarang && $isFirstUnpaid) ? 'Bayar Sekarang' : 'Bayar Tagihan' }}
                                        </button>
                                        @if($idx <= $indexSekarang) @php $isFirstUnpaid = false; @endphp @endif
                                    @else
                                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill"><i class="bi bi-telephone-fill me-1"></i> Hubungi Bendahara</span>
                                    @endif
                                @else
                                    
                                    {{-- 🔥 PERBAIKAN: TOMBOL CETAK BUKTI BAYAR UNTUK SISWA 🔥 --}}
                                    @php
                                        // Cari data pembayaran yang berhubungan dengan tagihan ini
                                        $pembayaran = \App\Models\Pembayaran::where('tagihan_spp_id', $t->id)->first();
                                    @endphp

                                    @if($pembayaran)
                                        <a href="{{ route('riwayat.cetak', $pembayaran->id) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="bi bi-printer me-1"></i> Cetak Bukti
                                        </a>
                                    @else
                                        {{-- Jaga-jaga kalau datanya anomali (Lunas tapi record pembayarannya hilang) --}}
                                        <i class="bi bi-patch-check-fill text-success fs-4 opacity-75"></i>
                                    @endif

                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-5 text-muted"><i class="bi bi-folder-x fs-1 opacity-25 d-block mb-2"></i>Data tagihan tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Catatan Informasi --}}
<div class="alert alert-info border-0 rounded-4 mt-4 small shadow-sm bg-primary bg-opacity-10 text-primary d-flex align-items-start p-4">
    <i class="bi bi-info-circle-fill fs-3 me-3 mt-1"></i>
    <div>
        <strong class="d-block mb-2" style="font-size: 0.95rem;">Pusat Informasi Layanan Administrasi:</strong>
        <ul class="mb-0 ps-3 text-dark opacity-75" style="line-height: 1.6;">
            <li>Sistem memprioritaskan penyelesaian tagihan dengan status <strong>Jatuh Tempo</strong> terlebih dahulu.</li>
            <li>Tagihan untuk bulan berikutnya akan otomatis terbuka saat memasuki periode bulan tersebut.</li>
            <li>Status pembayaran akan terverifikasi dan menjadi <strong>Lunas</strong> secara <em>real-time</em> setelah transaksi berhasil.</li>
            <li>Untuk pelunasan tunggakan di <strong>Tahun Ajaran sebelumnya</strong>, silakan hubungi Bendahara Sekolah secara langsung.</li>
        </ul>
    </div>
</div>

{{-- SCRIPT MIDTRANS DAN AJAX AUTO-LUNAS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    function payNow(snapToken) {
        if(!snapToken) {
            alert("Sistem sedang memproses token Virtual Account Anda. Silakan muat ulang (refresh) halaman ini.");
            return;
        }

        window.snap.pay(snapToken, {
            // Ganti bagian onSuccess Bapak dengan kode ini:
        onSuccess: function(result) {
            fetch('{{ route("siswa.tagihan.success") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    snap_token: snapToken
                })
            }).then(response => response.json())
            .then(data => {
                // HAPUS bagian alert("Terima kasih!...") yang lama
                // Ganti dengan perintah reload otomatis
                location.reload(); 
            }).catch(error => {
                location.reload();
            });
        },
            onPending: function(result) {
                alert("Status transaksi tertunda. Silakan selesaikan pembayaran Anda sebelum batas waktu habis.");
                location.reload();
            },
            onError: function(result) {
                alert("Transaksi dibatalkan atau terjadi kesalahan jaringan.");
            },
            onClose: function() {
                console.log('Pengguna menutup jendela pembayaran');
            }
        });
    }
</script>
@endsection