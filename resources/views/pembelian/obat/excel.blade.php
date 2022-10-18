<?php 
function rupiah($angka)
{
  $hasil_rupiah = "Rp. " . number_format((int)$angka);
  $hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
  return $hasil_rupiah;
}
?>
<table id="excelLaba" style="height:auto;">
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
			<th colspan="6"><p style="text-align: right; font-weight: bold;">Total Harga Piutang = {{rupiah($sum)}} </p></th>
			<th colspan="4"><p style="text-align: right; font-weight: bold;">Total Pajak Piutang = {{rupiah($total)}} </p></th>
		</tr>
		<tr>
			<th><p style="font-weight:bold;text-align:center">NO</p></th>
			<th><p style="font-weight:bold;text-align:center">NO. Registrasi</p></th>
			<th><p style="font-weight:bold;text-align:center">NO. FAKTUR</p></th>
			<th><p style="font-weight:bold;text-align:center">TOTAL HARGA</p></th>
			<th><p style="font-weight:bold;text-align:center">MATERAI</p></th>
			<th><p style="font-weight:bold;text-align:center">PAJAK WAJIB DIBAYAR</p></th>
			<th><p style="font-weight:bold;text-align:center">STATUS</p></th>
			<th><p style="font-weight:bold;text-align:center">JATUH TEMPO</p></th>
			<th><p style="font-weight:bold;text-align:center">NAMA PBF</p></th>
			<th><p style="font-weight:bold;text-align:center">ALAMAT PBF</p></th>
			<th colspan="2"><p style="font-weight:bold;text-align:center">gambar</p></th>
		</tr>
	</thead>
	@php
	$no = 1;
	@endphp
	<tbody id='panelHasil'>
		@foreach ($data as $item)
		<tr>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{$no}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{$item->no_registrasi}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{$item->no_faktur_pbf}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{rupiah($item->total_pembelian)}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{($item->materai?rupiah($item->materai):'-')}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{(!empty($item->total)?rupiah($item->total):'-')}}</p></td>
			<td style="background: {{($item->status_piutang==1?'#ff8100':'#69eb06')}};"><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{($item->status_piutang == 1 ? 'Piutang' : 'Lunas' )}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{$item->jatuh_tempo}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{$item->pbf->nama}}</p></td>
			<td><p style="padding: 5px; vertical-align: middle;" align="center" valign="middle">{{$item->pbf->alamat}}</p></td>
			<td colspan="2"><div class="row"><div class="col-md-12"><img src="{{asset($item->notaFaktur)}}" width="120" height="90"></div></div></td>
		</tr>
		@php
		$no++;
		@endphp
		@endforeach
		@if($no == '1')
		<tr>
			<td colspan="10" style="text-align: center;padding: 5px;">Tidak Ada Data</td>
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
			name: "Laporan Laba Rugi",
			filename: "Laporan Laba Rugi "+output+".xls",
			fileext: ".xls",
			exclude_img: false,
			exclude_links: false,
			exclude_inputs: true,
			preserveColors: true
		});
	});
</script>