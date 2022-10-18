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
			margin: auto;
			padding: 0;
			background-color: #FAFAFA;
			font: 9pt "Tahoma";
		}

		.nowrap {
			white-space: nowrap;
		}
	</style>
</head>
{{-- {{ dd($stok_barang->all()) }} --}}
@php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
		return $hasil_rupiah;
	}

	function getHarga($detail_penjualan){
		switch($detail_penjualan->jenis_penjualan){
			case 'Umum':
				$harga = $detail_penjualan->stok_barang->harga_umum;
				break;
			case 'Resep':
				$harga = $detail_penjualan->stok_barang->harga_resep;
				break;
			case 'Dispensing (perBox)':
				$harga = $detail_penjualan->stok_barang->harga_dispensing;
				break;
			case 'Dispensing (perBiji)':
				$harga = $detail_penjualan->stok_barang->harga_dispensing_perbiji;
				break;

			default:
				$harga = 0;
				break;
		}
		return $harga;
	}

		$total_harga = 0;
        $hrgNominal = 0;
        $arrResep = [];
		foreach ($penjualan->detail_penjualan as $key => $detail_penjualan) {
			if($detail_penjualan->jenis_penjualan=='Dispensing (perBox)'){
				$jumlahPerBox = $detail_penjualan->stok_barang->jumlah_perbox;
				$total = $detail_penjualan->qty / $jumlahPerBox;
			}else{
				$total = $detail_penjualan->qty;
			}
            if($detail_penjualan->jenis_penjualan=='Resep'){
                array_push($arrResep,1);
                foreach($mstPersentase as $persen => $nominal){
                    if($nominal->id == 2){
                        $hrgNominal = $nominal->nominal??0;
                    }
                }
            }
			$harga = getHarga($detail_penjualan);
			$total_hargastok = $total * $harga;
			$total_harga += $total_hargastok;
		}
        $total_harga = $total_harga+$hrgNominal;
        $first = substr($total_harga,0,-3);
        $last = substr($total_harga, -3);
        $resLast = (string)((ceil($last/100))*100);
        if(strlen($resLast)>3){
            $resLast = '000';
            $first = (int)($first+1);
        }else{
            $last = $last;
        }
        $result = $first.$resLast;
@endphp
<body>
    <table class="table table-borderless table-sm align-middle">
        <tr>
            <th colspan="{{(count($arrResep)==0?3:'')}}" style="text-align:center">
                <span>APOTEK NDARU HUSADA FARMA<br>
                Jl. Gajah Mada No.1 Blok A<br>
                MOJOSARI</span>
            </th>
            <th><img src="{{asset('favicon.png')}}"></th>
        </tr>
        <tr>
            <th colspan="{{(count($arrResep)==0?4:2)}}">
                <hr style="border: 1px dashed black; width:99%; text-align:center">
            </th>
        </tr>
        <tr>
            <td colspan="{{(count($arrResep)==0?4:2)}}">
                <table>
                    <tr>
                        <td>Tanggal : {{ $penjualan->tanggal_penjualan }}</td>
                        <td>Nomor : {{ $penjualan->no_kwitansi }}</td>
                    </tr>
                    <tr>
                        <td>Jam : {{ $penjualan->created_at->format('H:i:s') }}</td>
                        <td>Kasir : {{ $penjualan->user->name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="{{(count($arrResep)==0?4:2)}}">
                <hr style="border: 1px dashed black; width:98%; text-align:center">
            </td>
        </tr>
        <tr>
            {{-- <th>Batch</th> --}}
            <th>Nama</th>
            <th>QTY</th>
            {{-- <th>Exp</th> --}}
            @if(count($arrResep)==0)
            <th>Harga</th>
            <th>Jumlah</th>
            @endif
        </tr>
        @foreach ($penjualan->detail_penjualan as $detail_penjualan)
        @php
            if($detail_penjualan->jenis_penjualan=='Dispensing (perBox)'){
                $jumlahPerBox = $detail_penjualan->stok_barang->jumlah_perbox;
                $total = $detail_penjualan->qty / $jumlahPerBox;
                $satuan = "Box";
            }else{
                $total = $detail_penjualan->qty;
                $satuan = "Pcs";
            }
        @endphp
            <tr>
                {{-- <td>{{ $detail_penjualan->stok_barang['no_batch'] }}</td> --}}
                <td>{{ $detail_penjualan->stok_barang->barang->nama }}</td>
                <td>{{ $total }} {{$satuan}}</td>
                {{-- <td>{{ $detail_penjualan->stok_barang->expired }}</td> --}}
                @if(count($arrResep) == 0)
                <td>{{ rupiah(getHarga($detail_penjualan)) }}</td>
                <td>{{ rupiah(getHarga($detail_penjualan) * $total) }}</td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td colspan="{{(count($arrResep)==0?4:2)}}">
                <hr style="border: 1px dashed black; width:99%; text-align:center">
            </td>
        </tr>
        {{-- <tr>
            <td colspan="{{(count($arrResep)==0?4:2)}}">
                <table width="100%">
                    <tr>
                        <td>Total : </td>
                        <td>{{ rupiah($result) }}</td>
                    </tr>
                </table>
            </td>
        </tr> --}}
        <tr>
            {{-- <td colspan="{{(count($arrResep)==0?2:'')}}">&nbsp</td> --}}
            <td colspan="{{(count($arrResep)==0?3:'')}}" style="text-align:right;">Total &nbsp; &nbsp;&nbsp;</td>
            <td class="nowrap">{{ rupiah($result) }}</td>
        </tr>
        <tr>
            {{-- <td colspan="{{(count($arrResep)==0?2:'')}}">&nbsp</td> --}}
            <td colspan="{{(count($arrResep)==0?3:'')}}" style="text-align:right;">Bayar &nbsp; &nbsp;</td>
            <td class="nowrap">{{ $penjualan->jumlah_bayar }}</td>
        </tr>
        <tr>
            {{-- <td colspan="{{(count($arrResep)==0?4:'')}}">&nbsp</td> --}}
            <td colspan="{{(count($arrResep)==0?3:'')}}" style="text-align:right;">Kembali&nbsp; </td>
            <td class="nowrap">{{ $penjualan->kembalian }}</td>
        </tr>
        <tr>
            <td colspan="{{(count($arrResep)==0?4:2)}}" style="text-align:center;margin:0px;">
                <hr style="border: 1px dashed black; width:99%; text-align:center">
                <span><b>Barang yang sudah dibeli tidak bisa dikembalikan</b></span><br>
                <span>TERIMA KASIH ATAS KUNJUNGAN ANDA</span>
            </td>
        </tr>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>
</body>

</html>