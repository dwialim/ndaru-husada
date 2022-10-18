<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan Expired</title>
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
        @foreach ($laporanexpired as $key => $le)
            @foreach($le->stok_barang as $p => $a)
                @php
                $tgl_sekarang = new DateTime();
                $tgl_exp      = new DateTime($a->expired);
                $selisih      = $tgl_sekarang->diff($tgl_exp);
                $selisih      = (int) $selisih->format('%R%a');
                $status       = '';
                $skipped      = false;
                if ($selisih <= 0) {
                    $status = 'Expired';
                }elseif ($selisih <= 90) { // 90 hari = 3 bulan
                    $status = 'Hampir Expired';
                }else {
                    $skipped = true;
                }
                
                @endphp

                @if (!$skipped)
                    <tr>
                        <td> {{ $le->id }} </td>
                        <td> {{ $le->nama }} </td>
                        <td> {{$le->satuan->nama ?? 'Null'}} </td>
                        <td> {{ $le->jenis }} </td>
                        <td> {{ $a->expired}} </td>
                        <td> {{ $a->jumlah}} </td>
                        <td> {{ $status }} </td>
                    </tr>
                @endif
        @endforeach
        @endforeach
    
    </tbody>
    </table>
</body>
</html>