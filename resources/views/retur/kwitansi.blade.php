@php
    if ($jenis == 'Penjualan') {
        $kata = 'Kwitansi';
        $created_at = $retur->created_at;
        $nomor = $retur->penjualan->no_kwitansi;
    } else {
        $kata = 'Faktur';
        $created_at = $retur->created_at;
        $nomor = $retur->faktur->no_faktur_pbf;
    }
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }
        
        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <table class="table table-borderless table-sm align-middle">
        <tr>
            <th colspan="4" style="text-align:center">
                <span>APOTEK NDARU HUSADA FARMA</span> <br>
                <span>Jl. Gajah Mada No.1 Blok A</span> <br>
                <span>MOJOSARI</span><br>
                <hr style="border: 1px dashed black; width:98%; text-align:center">
            </th>
        </tr>
        <tr>
            <td class="nowrap">Tanggal :</td>
            <td>{{ $created_at }}</td>
        </tr>
        <tr>
            <td class="nowrap">Nomor {{ $kata }} :</td>
            <td>{{ $nomor }}</td>
        </tr>
        <tr>
            <td colspan="4">
                Barang Yang Diretur
            </td>
        </tr>
        <tr>
            <th>No</th>
            <th>Barang</th>
            <th>QTY</th>
            <th>Satuan</th>
        </tr>
        @foreach ($retur->detail_retur as $detail_retur)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail_retur->stok_barang->barang->nama ?? 'Null' }}</td>
                <td>{{ $detail_retur->qty }}</td>
                <td>{{ $detail_retur->stok_barang->barang->satuan->nama ?? 'Null' }}</td>
            </tr>
        @endforeach
        <tr style="height:200px">
            <td colspan="4" style="text-align:right">{{ $retur->user->name }}</td>
        </tr>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
</body>

</html>