<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi SPP - {{ $trx->order_id }}</title>
    <style>
        /* Desain Khusus Kertas Cetak A4 / A5 Portrait */
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
        
        /* Kop Surat Resmi (SUDH DIBIKIN FIXED AGAR RAPI DI TENGAH) */
        .header-table {
            width: 100%;
            table-layout: fixed; /* Jurus agar kolom tidak bergeser */
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .header-title h3 { margin: 0 0 5px 0; font-size: 16px; font-weight: bold; }
        /* Font H1 disesuaikan ke 20px agar "Indrapura" muat 1 baris lurus */
        /* Font diperkecil sedikit ke 18px dan diberi perintah nowrap agar tidak bisa turun baris */
        .header-title h1 { margin: 0 0 5px 0; font-size: 18px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; white-space: nowrap; }
        .header-title p { margin: 0; font-size: 12px; }
        
        /* Judul Dokumen */
        .judul-dokumen {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            text-decoration: underline;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        /* Tabel Info Transaksi & Siswa */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
        }

        /* Tabel Rincian Pembayaran */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 10px 15px; 
        }
        .data-table th {
            background-color: #e9ecef;
            text-align: center;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact; /* Mengatasi error kuning di teks editor */
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
            width: 180px;
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
        
        /* Tanda Lunas Watermark */
        .watermark-lunas {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: rgba(0, 128, 0, 0.1); 
            font-weight: bold;
            border: 5px solid rgba(0, 128, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            pointer-events: none;
            text-transform: uppercase;
        }

        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak Kuitansi Resmi</button>

    <div class="container" style="position: relative;">
        <div class="watermark-lunas">LUNAS</div>

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

        <div class="judul-dokumen">KUITANSI PEMBAYARAN SPP</div>

        <table class="info-table">
            <tr>
                <td width="150"><strong>No. Transaksi</strong></td>
                <td width="10">:</td>
                <td><code style="font-size: 15px;">{{ $trx->order_id }}</code></td>
                
                <td width="130"><strong>Nama Siswa</strong></td>
                <td width="10">:</td>
                <td><strong>{{ $trx->siswa->nama_lengkap ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td><strong>Tanggal Bayar</strong></td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal_bayar)->format('d F Y') }}</td>
                
                <td><strong>NISN / Kelas</strong></td>
                <td>:</td>
                <td>{{ $trx->siswa->nisn ?? '-' }} / {{ $trx->siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Kasir</strong></td>
                <td>:</td>
                <td>
                    {{ $trx->pegawai->nama_lengkap ?? ($trx->metode_pembayaran == 'Tunai' ? 'Ibu Bendahara' : 'Sistem / Midtrans') }}
                </td>
                
                <td><strong>Tahun Ajaran</strong></td>
                <td>:</td>
                <td>
                    TA {{ $trx->tagihan_spp->tahun_ajaran->tahun ?? '-' }} ({{ $trx->tagihan_spp->tahun_ajaran->semester ?? '-' }})
                </td>
            </tr>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Bulan</th>
                    <th width="20%">Metode</th>
                    <th width="30%">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>{{ $trx->tagihan_spp->bulan ?? '-' }}</td>
                    <td class="text-center">{{ $trx->metode_pembayaran }}</td>
                    <td class="text-right">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">TOTAL PEMBAYARAN</th>
                    <th class="text-right" style="font-size: 18px;">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div style="font-style: italic; font-size: 12px; margin-top: -15px; color: #555;">
            * Kuitansi ini adalah bukti pembayaran yang sah dan diterbitkan secara resmi oleh sistem keuangan sekolah.
        </div>

        <div class="signature-area">
            <p>Indrapura, {{ \Carbon\Carbon::parse($trx->tanggal_bayar)->format('d F Y') }}</p>
            <p>Bendahara Sekolah,</p>
            <div class="signature-space"></div>
            <p class="fw-bold text-decoration-underline">
                {{ $trx->pegawai->nama_lengkap ?? ($trx->metode_pembayaran == 'Tunai' ? 'Ibu Bendahara' : 'Staf Keuangan') }}
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