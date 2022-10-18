<?php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}

	function qty($data){
		$jenis = $data->jenis_penjualan;
		$qtyJual = $data->qty;
		$perBiji = $data->stok_barang->jumlah_perbox;
		if($jenis=="Dispensing (perBox)"){
			$result = ($qtyJual/$perBiji)." Box";
		}else{
			$result = $qtyJual." Pcs";
		}
		return $result;
	}

	function hargaBeli($data){
		$jenis = $data->jenis_penjualan;
		$perBiji = $data->stok_barang->jumlah_perbox;
		$harga = $data->stok_barang->harga_beli;
		if($jenis=="Dispensing (perBox)"){
			$harga = $harga;
		}else{
			$harga = round($harga/$perBiji);
		}
		return rupiah($harga);
	}

	function hargaJual($data){
		$jenis = $data->jenis_penjualan;
		$umum = $data->stok_barang->harga_umum;
		$resep = $data->stok_barang->harga_resep;
		$disBox = $data->stok_barang->harga_dispensing;
		$disBii = $data->stok_barang->harga_dispensing_perbiji;
		if($jenis=="Umum"){
			$harga = $umum;
		}else if($jenis=="Resep"){
			$harga = $resep;
		}else if($jenis=="Dispensing (perBox)"){
			$harga = $disBox;
		}else{
			$harga = $disBii;
		}
		return rupiah($harga);
	}
?>
<table id="excelLaba" class="table table-striped table-bordered" width="100%">
	<thead>
		<tr>
			<td colspan="10"><b><p style="text-align:center;">{{ $judul }}</p></b></td>
		</tr>
		<tr>
			<td colspan="10"><p style="text-align: center;">APOTEK NDARU HUSADA</p></td>
		</tr>
		<tr>
			<td colspan="10"><p style="text-align: center;">{{ $date }}</p></td>
		</tr>
		<tr>
			<th colspan="10"><p style="text-align: right; font-weight: bold;">Total = {{rupiah($totalLabaRugi)}}</p></th>
		</tr>
		<tr>
			<th><p style="font-weight:bold;text-align:center">NO</p></th>
			<th><p style="font-weight:bold;text-align:center">NAMA BARANG</p></th>
			<th><p style="font-weight:bold;text-align:center">SATUAN</p></th>
			<th><p style="font-weight:bold;text-align:center">ED</p></th>
			<th><p style="font-weight:bold;text-align:center">QTY</p></th>
			<th><p style="font-weight:bold;text-align:center">HARGA BELI</p></th>
			<th><p style="font-weight:bold;text-align:center">HARGA JUAL</p></th>
			<th><p style="font-weight:bold;text-align:center">OMSET</p></th>
			<th><p style="font-weight:bold;text-align:center">SELISIH HARGA</p></th>
			<th><p style="font-weight:bold;text-align:center">LABA / KOTOR</p></th>
			{{-- <th><p style="font-weight:bold;text-align:center">HARGA UMUM</p></th>
			<th><p style="font-weight:bold;text-align:center">HARGA RESEP</p></th>
			<th><p style="font-weight:bold;text-align:center">HARGA DISPENSING BOX</p></th>
			<th><p style="font-weight:bold;text-align:center">HARGA DISPENSING BIJI</p></th> --}}
		</tr>
	</thead>

	@php
	$no = 1;
	@endphp
	<tbody id='panelHasil'>
		@foreach ($lap as $item)
		<tr>
			<td><p style="padding: 5px;" align="center" valign="middle">{{$no}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->barang->nama}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->barang->satuan->nama ?? 'Null'}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->expired}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{qty($item)}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{hargaBeli($item)}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{hargaJual($item)}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{rupiah($item->omset)}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{rupiah($item->selisih)}}</p></td>
			<td><p style="padding: 5px;" align="center" valign="middle">{{rupiah($item->labarugi)}}</p></td>
		</tr>
		@php
		$no++;
		@endphp
		@endforeach
		@if($no == '1')
		<tr>
			<td colspan="12" style="text-align: center;padding: 5px;">Tidak Ada Data</td>
		</tr>
		@endif

	</tbody>
</table>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="{{asset('src/js/jquery.table2excel.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var date = new Date();
		var getYear = date.getFullYear();
		var getMonth = date.getMonth()+1;
		var getDate = date.getDate();
		var output = getYear+'/'+(getMonth<10 ? '0':'')+getMonth+'/'+(getDate<10 ? '0':'')+getDate;
		$('#excelLaba').table2excel({
			exclude: ".noExl",
			name: "Laporan Laba Kotor",
			filename: "Laporan Laba Kotor "+output,
			fileext: ".XLSX",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true,
			preserveColors: false
		});
		console.log('tes')
	});
</script>