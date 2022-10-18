<?php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
?>
<table id="body_excel">
	<thead>
		<tr>
			<td colspan="5" align="center" ><b>{{ $judul }}</b></td>
		</tr>
		<tr>
			<td colspan="5" align="center">APOTEK NDARU HUSAHA</td>
		</tr>
		<tr>
			<td colspan="5"align="center">{{ $startDate }} s/d {{ $endDate }}</td>
		</tr>
	</thead>
</table>
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th style="font-weight:bold;text-align:center">NO</th>
			<th style="font-weight:bold;text-align:center">NAMA PELANGGAN</th>
			<th style="font-weight:bold;text-align:center;">Total Pembelian</th>
			<th style="font-weight:bold;text-align:center;">Total Reward</th>
		</tr>
	</thead>
	@php
		$no = 1;
	@endphp
	<tbody id='panelHasil'>
		@foreach ($lap as $item)
		<tr>
			<td style="padding: 5px;" align="center" valign="middle">{{$no}}</td>
			<td style="padding: 5px;" align="center" valign="middle">{{$item->nama_pelanggan}}</td>
			<td style="padding: 5px;" align="center" valign="middle">{{rupiah($item->total)}}</td>
			<td style="padding: 5px;" align="center" valign="middle">{{rupiah($item->persen)}}</td>
		</tr>
		@php
			$no++;
		@endphp
		@endforeach
		@if($no == '1')
		<tr>
			<td colspan="9" style="text-align: center;padding: 5px;">Tidak Ada Data</td>
		</tr>
		@endif
	</tbody>
</table>
