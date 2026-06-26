<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pembayaran SPP - {{ $siswa->nama_lengkap }}</title>
    <style>
        /* Desain Khusus Kertas Cetak A4 / HVS */
        @page {
            size: A4 portrait;
            margin: 15mm; 
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        
        /* Kop Surat dengan Logo - DIBUAT FIXED AGAR PRESISI DI TENGAH */
        .header-table {
            width: 100%;
            table-layout: fixed; 
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .header-title h3 { margin: 0 0 5px 0; font-size: 16px; font-weight: bold; }
        /* Font disesuaikan 18px & nowrap agar tulisan Indrapura tidak turun baris */
        .header-title h1 { margin: 0 0 5px 0; font-size: 18px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; white-space: nowrap; }
        .header-title p { margin: 0; font-size: 12px; }
        
        /* Judul Dokumen */
        .judul-dokumen {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Tabel Info Siswa */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }

        /* Tabel Rincian SPP */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px 10px; 
        }
        .data-table th {
            background-color: #e9ecef;
            text-align: center;
            font-weight: bold;
            /* Anti Error Kuning di Editor */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* Area Tanda Tangan */
        .signature-area {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 20px;
        }
        .signature-space {
            height: 80px;
        }

        /* Tombol Print (Sembunyi saat dicetak) */
        .btn-print {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #0d6efd;
            color: #fff;
            text-decoration: none;
            font-family: Arial, sans-serif;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        .btn-print:hover { background-color: #0b5ed7; }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak Bukti Lunas</button>

    <div class="container">
        <table class="header-table">
            <tr>
                <td width="15%" style="text-align: center;">
                    <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMK" style="max-width: 90px; height: auto;">
                </td>
                
                <td width="70%" class="header-title" style="text-align: center;">
                    <h3>YAYASAN PENDIDIKAN</h3>
                    <h1>SMK SWASTA BUDHI DARMA INDRAPURA</h1>
                    <p>Alamat: Jl. Datuk Umar Palangki 21256, Tanah Merah, Air Putih, Batu Bara</p>
                    <p>Email: budhidarma5@gmail.com | Telp: (0622) 7231522</p>
                </td>
                
                <td width="15%"></td>
            </tr>
        </table>

        <div class="judul-dokumen">REKAPITULASI PEMBAYARAN SPP SISWA</div>

        <table class="info-table">
            <tr>
                <td width="130"><strong>Nama Lengkap</strong></td>
                <td width="10">:</td>
                <td>{{ $siswa->nama_lengkap }}</td>
                <td width="120"><strong>Tahun Ajaran</strong></td>
                <td width="10">:</td>
                <td>{{ $tahun }}</td>
            </tr>
            <tr>
                <td><strong>NISN</strong></td>
                <td>:</td>
                <td>{{ $siswa->nisn }}</td>
                <td><strong>Kelas</strong></td>
                <td>:</td>
                <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Bulan Tagihan</th>
                    <th width="20%">Tanggal Bayar</th>
                    {{-- 🔥 TAMBAHAN KOLOM METODE 🔥 --}}
                    <th width="15%">Metode</th>
                    <th width="15%">Status</th>
                    <th width="25%">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tagihanLunas as $tagihan)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>SPP Bulan {{ $tagihan->bulan }}</td>
                    
                    @php
                        // Cari data pembayaran yang sesuai
                        $bayar = \App\Models\Pembayaran::where('tagihan_spp_id', $tagihan->id)
                                    ->where('status_bayar', 'success')
                                    ->first();
                    @endphp
                    
                    {{-- Tanggal Bayar --}}
                    <td class="text-center">
                        {{ $bayar ? \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d F Y') : '-' }}
                    </td>

                    {{-- 🔥 ISI KOLOM METODE PEMBAYARAN 🔥 --}}
                    <td class="text-center">
                        {{ $bayar ? $bayar->metode_pembayaran : '-' }}
                    </td>
                    
                    <td class="text-center fw-bold">LUNAS</td>
                    <td class="text-right">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    {{-- Karena tambah 1 kolom, maka colspan dinaikkan jadi 5 --}}
                    <th colspan="5" class="text-right">TOTAL PEMBAYARAN LUNAS</th>
                    <th class="text-right" style="font-size: 16px;">Rp {{ number_format($totalBayar, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="signature-area">
            <p>Indrapura, {{ date('d F Y') }}</p>
            <p>Bendahara Sekolah,</p>
            <div class="signature-space"></div>
            <p class="fw-bold text-decoration-underline">
                {{ Auth::user()->name ?? 'Staf Keuangan' }}
            </p>
        </div>
        <div style="clear: both;"></div>

    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); 
        }
    </script>
</body>
</html>