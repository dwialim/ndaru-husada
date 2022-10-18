<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan Hampir Habis</title>
</head>
<body>
    
<table id="advanced_table" class="table">
    <thead>
        <tr>
            <th> No </th>
            <th> Nama Barang </th>
            <th> Satuan </th>
            <th> Jenis </th>
            <th> ED </th>
            <th> Sisa Stock </th>
            <th> Status </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporanhampirhbs as $key => $le)
            @foreach($le->stok_barang as $p => $a)
            <tr>
                <td> {{ $le->id }} </td>
                <td> {{ $le->nama }} </td>
                <td> {{ $le->satuan->nama ?? 'Null'}} </td>
                <td> {{ $le->jenis }} </td>
                <td> {{ $a->expired }} </td>
                <td> {{ $a->jumlah }} </td>
                @if ($a->jumlah > 21)
                <td> Aman </td>
                @elseif ( $a->jumlah <= 0)
                <td> Kosong  </td>
                @elseif ( $a->jumlah <= 20)
                <td> Hampir Habis </td>
               @endif
            </tr>
        @endforeach
        @endforeach
    
    </tbody>
    </table>
</body>
</html>