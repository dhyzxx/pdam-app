<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Pelanggan - {{ $tagihan->pelanggan->id_pelanggan }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .container { width: 90%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #007bff; }
        .header p { margin: 5px 0; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content th, .content td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .content th { background-color: #f8f9fa; }
        .total-section { text-align: right; margin-top: 10px; } /* Mengurangi margin-top sedikit */
        .total-section p { margin: 3px 0; font-size: 13px; } /* Mengurangi margin dan font-size */
        .total-section .amount { font-weight: bold; font-size: 16px; }
        .footer { text-align: center; font-size: 10px; color: #777; position: fixed; bottom: 0; width: 100%; }
        .info-table { margin-bottom: 25px; }
        .info-table td { border: none; padding: 3px 0; }
        .info-label { font-weight: bold; width: 150px; }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.08);
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
        }
        .rincian-item td { padding: 6px 8px; } /* Padding untuk rincian */
        .rincian-item.subtotal td { border-top: 1px solid #ccc; font-weight: bold; }
        .rincian-item.grand-total td { border-top: 2px solid #333; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="watermark">PDAM Pribadi</div>
    <div class="container">
        <div class="header">
            <h1>TAGIHAN PEMAKAIAN AIR</h1>
            <p>Aplikasi PDAM Pribadi</p>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}</p>
        </div>

        <div class="content">
            <h3>Informasi Pelanggan</h3>
            <table class="info-table">
                <tr>
                    <td class="info-label">ID Pelanggan</td>
                    <td>: {{ $tagihan->pelanggan->id_pelanggan }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Pelanggan</td>
                    <td>: {{ $tagihan->pelanggan->nama_pelanggan }}</td>
                </tr>
            </table>

            <h3>Detail Tagihan</h3>
            <table class="info-table">
                <tr>
                    <td class="info-label">Bulan Tagihan</td>
                    <td>: {{ \Carbon\Carbon::parse($tagihan->bulan_tagihan . '-01')->isoFormat('MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Status</td>
                    <td>: {{ $tagihan->status_pembayaran }}</td>
                </tr>
                 @if($tagihan->pembayaran && $tagihan->status_pembayaran == 'Lunas')
                <tr>
                    <td class="info-label">Tanggal Bayar</td>
                    <td>: {{ \Carbon\Carbon::parse($tagihan->pembayaran->tanggal_pembayaran)->isoFormat('DD MMMM YYYY') }}</td>
                </tr>
                @endif
            </table>

            <h3>Rincian Pemakaian & Biaya</h3>
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th style="text-align:right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="rincian-item">
                        <td>Pemakaian Air ({{ number_format($tagihan->pemakaianAir->volume_pemakaian, 2, ',', '.') }} m³ x Rp {{ number_format(1500, 0, ',', '.') }})</td>
                        <td style="text-align:right;">Rp {{ number_format($tagihan->pemakaianAir->volume_pemakaian * 1500, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="rincian-item">
                        <td>Pajak Tetap</td>
                        <td style="text-align:right;">Rp {{ number_format($tagihan->jumlah_pajak, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="rincian-item grand-total">
                        <td>TOTAL TAGIHAN</td>
                        <td style="text-align:right;">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 40px; font-size: 11px;">
                <p>Catatan:</p>
                <ul>
                    <li>Mohon lakukan pembayaran tepat waktu jika status masih "Belum Lunas".</li>
                    <li>Ini adalah tagihan yang dicetak melalui sistem Aplikasi PDAM Pribadi.</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            Dokumen ini dicetak secara otomatis oleh sistem dan sah tanpa tanda tangan.
        </div>
    </div>
</body>
</html>