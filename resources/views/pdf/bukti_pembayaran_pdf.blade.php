<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $pembayaran->tagihan->pelanggan->id_pelanggan }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .container { width: 90%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #198754; } /* Warna hijau untuk pembayaran */
        .header p { margin: 5px 0; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content th, .content td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .content th { background-color: #f8f9fa; }
        .info-table { margin-bottom: 25px; }
        .info-table td { border: none; padding: 4px 0; }
        .info-label { font-weight: bold; width: 180px; }
        .payment-details { margin-top: 20px; padding: 15px; border: 1px solid #198754; background-color: #f0fff4; }
        .payment-details p { margin: 5px 0; }
        .payment-details .amount { font-weight: bold; font-size: 16px; color: #198754; }
        .footer { text-align: center; font-size: 10px; color: #777; position: fixed; bottom: 0; width: 100%; }
         .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 128, 0, 0.08); /* Watermark hijau */
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="watermark">LUNAS</div>
    <div class="container">
        <div class="header">
            <h1>BUKTI PEMBAYARAN TAGIHAN</h1>
            <p>Aplikasi PDAM Pribadi</p>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}</p>
        </div>

        <div class="content">
            <h3>Informasi Pelanggan</h3>
            <table class="info-table">
                <tr>
                    <td class="info-label">ID Pelanggan</td>
                    <td>: {{ $pembayaran->tagihan->pelanggan->id_pelanggan }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Pelanggan</td>
                    <td>: {{ $pembayaran->tagihan->pelanggan->nama_pelanggan }}</td>
                </tr>
            </table>

            <h3>Detail Tagihan yang Dibayar</h3>
            <table class="info-table">
                 <tr>
                    <td class="info-label">Nomor Tagihan (ID Sistem)</td>
                    <td>: #{{ $pembayaran->tagihan->id }}</td>
                </tr>
                <tr>
                    <td class="info-label">Bulan Tagihan</td>
                    <td>: {{ \Carbon\Carbon::parse($pembayaran->tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Volume Pemakaian</td>
                    <td>: {{ number_format($pembayaran->tagihan->pemakaianAir->volume_pemakaian, 2, ',', '.') }} m³</td>
                </tr>
                <tr>
                    <td class="info-label">Jumlah Tagihan Awal</td>
                    <td>: Rp {{ number_format($pembayaran->tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="payment-details">
                <h3 style="color: #198754; margin-top:0;">Detail Pembayaran</h3>
                <table class="info-table" style="margin-bottom: 0;">
                    <tr>
                        <td class="info-label">Nomor Pembayaran (ID Sistem)</td>
                        <td>: #{{ $pembayaran->id }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tanggal Pembayaran</td>
                        <td>: {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->isoFormat('DD MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Jumlah Dibayar</td>
                        <td>: <span class="amount">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span></td>
                    </tr>
                     <tr>
                        <td class="info-label">Status Tagihan</td>
                        <td>: <strong style="color: #198754;">LUNAS</strong></td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 40px; font-size: 11px;">
                <p>Terima kasih telah melakukan pembayaran.</p>
                <p>Simpan bukti pembayaran ini dengan baik.</p>
            </div>
        </div>

        <div class="footer">
            Dokumen ini dicetak secara otomatis oleh sistem dan sah tanpa tanda tangan.
        </div>
    </div>
</body>
</html>