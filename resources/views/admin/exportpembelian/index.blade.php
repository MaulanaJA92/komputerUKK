<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Beli</th>
                <th>Supplier</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->tanggal_beli }}</td>
                    <td>{{ $p->supplier->nama_supplier }}</td>
                    <td>
                        <ul>
                            @foreach ($p->details as $detail)
                                <li>{{ $detail->barang->nama_barang }} - {{ $detail->jumlah_barang }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $p->details->sum('jumlah_barang') }}</td>
                    <td>Rp{{ number_format($p->details->sum('sub_total'), 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($p->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
