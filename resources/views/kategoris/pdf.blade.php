<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategori {{ $kategori->nama }}</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        h2 { text-align: center; color: #2c3e50; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; }
        .item-table { width: 100%; border-collapse: collapse; }
        .item-table th, .item-table td { border: 1px solid #bdc3c7; padding: 8px; text-align: left; }
        .item-table th { background-color: #f2f2f2; }
        .footer { position: fixed; bottom: 0; text-align: center; font-size: 10px; width: 100%; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <h2>Laporan Kategori Items</h2>
    <table class="info-table">
        <tr><td width="20%"><b>Nama Kategori</b></td><td>: {{ $kategori->nama }}</td></tr>
        <tr><td><b>Kode Kategori</b></td><td>: {{ $kategori->kode }}</td></tr>
    </table>
    <h3>Daftar Item:</h3>
    <table class="item-table">
        <thead>
            <tr><th>Kode</th><th>Nama Barang</th><th>Jenis</th><th>Harga Beli</th><th>Harga Jual</th></tr>
        </thead>
        <tbody>
            @forelse($kategori->masterItems as $item)
                @php $harga_jual = round($item->harga_beli + ($item->harga_beli * $item->laba / 100)); @endphp
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($harga_jual, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align: center;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">Dicetak pada tanggal: {{ $tanggal_cetak }}</div>
</body>
</html>
