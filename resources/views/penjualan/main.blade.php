@extends('layouts.main')

@push('head')
	<style type="text/css">
		.cardbd{
			border-radius: 8px 8px 0px 0px !important;
		}
		.card .cardbd{
			height: 2rem;
		}
		.btn:focus{
			box-shadow: 0 0 0 0.2rem rgb(0 0 0 / 0%) !important;
		}
		.swal2-styled.swal2-confirm:focus{
			box-shadow: 0 0 0 0.2rem rgb(0 0 0 / 0%) !important;
		}
		.swal2-styled.swal2-default-outline:focus{
			box-shadow: 0 0 0 0.2rem rgb(0 0 0 / 0%) !important;
		}
	</style>
@endpush

@section('title', 'Penjualan Obat')
@section('content')
<?php
function rupiah($angka)
{
  $hasil_rupiah = "Rp. " . number_format((int)$angka);
  $hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
  return $hasil_rupiah;
}
?>
{{-- HITUNG TOTAL HARGA BERDASATKAN JENIS PENJUALAN --}}
@isset($penjualan) 
	@php
		function getHarga($detail_penjualan)
		{
			switch ($detail_penjualan->jenis_penjualan) {
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
		foreach ($penjualan->detail_penjualan as $key => $detail_penjualan) {
			if($detail_penjualan->jenis_penjualan =='Dispensing (perBox)'){
				$jumlahPerBox = $detail_penjualan->stok_barang->jumlah_perbox;
				$total = $detail_penjualan->qty / $jumlahPerBox;
			}else{
				$total = $detail_penjualan->qty;
			}
			if($detail_penjualan->jenis_penjualan=='Resep'){
				foreach ($mstPersentase as $persen => $nominal) {
					if($nominal->id == 2){
						$hrgNominal = $nominal->nominal ?? 0;
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
@endisset
<div class="container-fluid">
	<div class="page-header">
		<div class="row align-items-end">
			<div class="col-lg-8">
				<div class="page-header-title">
					<div class="d-inline">
						<h5>{{ __('Penjualan')}}</h5>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<nav class="breadcrumb-container" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{url('dashboard')}}"><i class="ik ik-home"></i></a>
						</li>
						<li class="breadcrumb-item">
							<a href="#">{{ __('Penjualan')}}</a>
						</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div class="row">

		<!-- start message area-->
		@include('include.message')
		<!-- end message area-->

		<div class="col-md-4">
			<div class="card cardbd">
				<div class="card-header bg-primary cardbd">
					<h6 class="text-white" style="margin: unset;">{{ __('Form Penjualan')}}</h6>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cari_barang">Temukan barang</label>
								<select class="form-control select2" id="cari_barang" name="cari_barang">
									<option value="first">--Cari Barang--</option>
									@foreach($stok_barang as $key => $val)
										<option value="{{$val->id}}">{{$val->no_batch}} | {{$val->barang->nama}} | QTY {{ $val->jumlah }} {{ $val->barang->satuan->nama ?? 'Null' }} | ED {{ $val->expired }} {{(!empty($val->barcode)? '|' :'')}} {{$val->barcode ?? ''}}</option>
									@endforeach
								</select>
							</div>
							@foreach($mstPersentase as $key => $val)
								@if($val->id==2)
									<input type="hidden" name="mstResepNominal" id="mstResepNominal" value="{{$val->nominal}}">
								@endif
							@endforeach
							<div class="form-radio my-4 ">
								<div class="radio radio-disable">
									<label>
										<input type="radio" id="harga_umum" value="" name="harga" disabled>
										<i class="helper"></i>{{ __('Umum :')}} <span id="nominal_umum"></span>
									</label>
								</div>
								<div class="radio radio-disable">
									<label>
										<input type="radio" id="harga_resep" value="" name="harga" disabled>
										<i class="helper"></i>{{ __('Resep :')}} <span id="nominal_resep"></span>
									</label>
								</div>
								<div class="radio radio-disable">
									<label>
										<input type="radio" disabled id="harga_dispensing" value="" name="harga">
										<i class="helper"></i>{{ __('Dispensing (perBox) :')}} <span id="nominal_dispensing"></span>
									</label>
								</div>
								<div class="radio radio-disable">
									<label>
										<input type="radio" disabled id="harga_dispensing_perbiji" value="" name="harga">
										<i class="helper"></i>{{ __('Dispensing (perBiji) :')}} <span id="nominal_dispensing_perbiji"></span>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label for="jumlah">Jumlah barang</label>
								<input class="form-control" type="number" name="jumlah" id="jumlah">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center mt-3">
							<button class="btn btn-primary" id="btn-list" onclick="addlist()" style="width: 90%; padding: 0;">Tambahkan ke list penjualan</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-8">
			<div class="card cardbd">
				<div class="card-header bg-secondary cardbd">
					<h6 class="text-white" style="margin: unset;">{{ __('List Penjualan')}}</h6>
				</div>
				<div class="card-body">
					<form class="form-list-barang">
						<input type="hidden" name="penjualan_id" value="{{ $penjualan->id ?? '' }}">
						<div class="row">
							<div class="col-sm">
								<div class="form-group">
									<label for="nama_pelanggan">{{ __('Nama Pelanggan') }}</label>
									<input id="nama_pelanggan" type="text" class="form-control" name="nama_pelanggan" value="{{ $penjualan->nama_pelanggan ?? '' }}" {{(!empty($penjualan->nama_pelanggan)? 'readonly':'')}} placeholder="" required>
								</div>
							</div>
							<div class="col-sm">
								<div class="form-group">
									@php
										$cur_date = date('Y-m-d');
									@endphp
									<label for="tanggalPenjualan">{{ __('Tanggal Penjualan') }}</label>
									<input id="tanggalPenjualan" type="date" class="form-control" name="tanggal_penjualan" value="{{ $penjualan->tanggal_penjualan ?? $cur_date }}" {{ empty($penjualan) ? 'readonly' : '' }}>
								</div>
							</div>
							@isset($penjualan)
								<div class="col-sm">
									<div class="form-group">
										<label for="no_kwitansi">{{ __('No. Kwitansi') }}</label>
										<input id="no_kwitansi" type="text" class="form-control" name="no_kwitansi" value="{{ $penjualan->no_kwitansi }}" placeholder="" required readonly>
									</div>
								</div>
							@endisset
						</div>
						<div class="row" style="margin-bottom: -10px;">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="jumlah_bayar">{{ __('Jumlah Bayar') }}</label>
									<input id="jumlah_bayar" type="text" class="form-control" name="jumlah_bayar" value="{{ $penjualan->jumlah_bayar ?? '' }}" {{(!empty($penjualan->jumlah_bayar)?'readonly':'')}} placeholder="" required onkeyup="input_pembayaran()">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="kembalian">{{ __('Kembalian') }}</label>
									<input id="kembalian" type="text" class="form-control"
										name="kembalian" value="{{ $penjualan->kembalian ?? '' }}" placeholder="" required readonly>
								</div>
							</div>
						</div>
						{{-- <div class="text-center mt-4" style="margin-bottom:-10px;">
							<label for="kembalian">{{ __('HANYA UNTUK PEMBELIAN RESEP') }}</label>
						</div> --}}
						<div class="mt-3" id="formResep" style="display: none;">
							<hr style="border-top: 1px dashed #e77979; margin-top: 0; margin-bottom: 3px;">
							<div class="row mb-2">
								<div class="col-md-4">
									<label for="namaPasien">{{ __('Nama Pasien') }}</label>
									<input id="namaPasien" type="text" class="form-control" name="namaPasien" value="{{ $penjualan->nama_pasien ?? '' }}" readonly>
								</div>
								<div class="col-md-4">
									<label for="umurPasien">{{ __('Umur Pasien') }}</label>
									<input id="umurPasien" type="text" class="form-control" name="umurPasien" value="{{ $penjualan->umur_pasien ?? '' }}" readonly>
								</div>
								<div class="col-md-4">
									<label for="namaDokter">{{ __('Nama Dokter') }}</label>
									<input id="namaDokter" type="text" class="form-control" name="namaDokter" value="{{ $penjualan->nama_dokter ?? '' }}" readonly>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<label for="alamatPasien">{{ __('Alamat Pasien') }}</label>
									<input id="alamatPasien" type="text" class="form-control" name="alamatPasien" value="{{ $penjualan->alamat_pasien ?? '' }}" readonly>
								</div>
								<div class="col-md-4">
									<label for="tanggalResep">{{ __('Tanggal Resep') }}</label>
									<input id="tanggalResep" type="date" class="form-control" name="tanggalResep" value="{{ $penjualan->tanggal_resep ?? '' }}" readonly>
								</div>
								<div class="col-md-4">
									<label for="nomorResep">{{ __('Nomor Resep') }}</label>
									<input id="nomorResep" type="text" class="form-control" name="nomorResep" value="{{ $penjualan->nomor_resep ?? '' }}" placeholder="" readonly>
								</div>
							</div>
							<hr style="border-top: 1px dashed #e77979; margin-top:10px;">
						</div>

						<div class="row mt-3">
							<div class="col-md-12 text-right">
								<b><span>Total Semua Harga:&nbsp;</span><span class="mr-4" id="total_semua_harga">{{ rupiah($result ?? 0) }}</span></b>
								<input type="hidden" name="total_semua_harga" id="input_total_semua_harga" value="">
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-md-12">
								{{-- <input type="hidden" name="tanggal_penjualan" value="{{$tanggal_penjualan}}"> --}}
								<table width="100%;" id="tb-penjualan">
									<thead style="border-top: 1px solid; border-bottom: 1px solid;">
										<tr class="text-center">
											<td>No</td>
											<td>Nama Barang</td>
											<td>Jenis Penjualan</td>
											<td>Harga</td>
											<td>QTY</td>
											<td>Total Harga</td>
											<td>Opsi</td>
										</tr>
									</thead>
									<tbody>
										@foreach ($penjualan->detail_penjualan ?? [] as $detail_penjualan)
										@php
											if($detail_penjualan->jenis_penjualan =='Dispensing (perBox)'){
												$jumlahPerBox = $detail_penjualan->stok_barang->jumlah_perbox;
												$total = $detail_penjualan->qty / $jumlahPerBox;
											}else{
												$total = $detail_penjualan->qty;
											}
										@endphp
										<tr class='rowItem' id='rowItem{{ $loop->iteration }}'>
											<td><span>{{ $loop->iteration }}</span></td>
											<td><input type='hidden' name='nama_barang[]' id='nama_barang' value='{{ $detail_penjualan->stok_barang->barang->nama }}'>{{ $detail_penjualan->stok_barang->barang->nama }}</td>
											<td><input type='hidden' name='jenis_penjualan[]' id='jenis_penjualan' value='{{ $detail_penjualan->jenis_penjualan }}'>{{ $detail_penjualan->jenis_penjualan }}</td>
											<td><input type='hidden' name='harga[]' id='harga' value='{{ getHarga($detail_penjualan) }}'>{{ rupiah(getHarga($detail_penjualan)) }}</td>
											<td style='width: 23%;'>
												<div class='input-group mt-1' style='margin-bottom: 4px; width: 87%; margin-left: auto; margin-right: auto;'>
													<div class='input-group-prepend'>
														<button class='mins btn btn-outline-secondary' disabled onclick='decrease_qty({{ $loop->iteration }})' type='button'>-</button>
													</div>
														<input readonly type='text' class='form-control qtys w-25 text-center' name='jumlah[]' id='qty' onkeyup='ubahFormatNumber(this),f_qty(this,{{ $loop->iteration }})' value='{{$total}}' placeholder='' aria-label='' aria-describedby='basic-addon1' autocomplete='off'>
													<div class='input-group-append'>
														<button class='plus btn btn-outline-secondary btn-plus' disabled onclick='increase_qty({{ $loop->iteration }})' type='button'>+</button>
													</div>
												</div>
											</td>
											<td>
												<input type="hidden" name="cekData[]" value="dataLama">
												<input type="hidden" name="id_detail_penjualan[]" value="{{ $detail_penjualan->id }}">
												<input type='hidden' name='id_stok_barang[]' id='id_stok_barang' value='{{ $detail_penjualan->stok_barang->id }}'>
												<input type='hidden' name='total_jumlah[]' id='total_jumlah' value='{{ getHarga($detail_penjualan) * $total }}'>
												<input type='hidden' name='total_harga[]' id='total_harga' value='{{ getHarga($detail_penjualan) * $total }}'>
												<p id='total'>{{ rupiah(getHarga($detail_penjualan) * $total) }}</p>
											</td>
											{{-- <td><button type="button" id='removeItem-{{ $loop->iteration }}' class='btn btn-link btn-remove' data-block='{{ $loop->iteration }}' disabled><i class='fa fa-trash-alt text-secondary'></i></button></td> --}}
											<td><a href='javascript:;' id='removeItem-{{ $loop->iteration }}' class='btn btn-remove disabled' data-block='{{ $loop->iteration }}'><i class='fa fa-trash-alt text-danger'></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right mt-3">
								<div id="sesiAwal">
								@isset($penjualan)
								<a href="{{ route('semuaPenjualan') }}" class="btn btn-secondary mx-2">Kembali</a>
								@endisset
								<button class="btn btn-success btn-pembayaran" type="button" style="display:; padding: 0px 7px;">{{ empty($penjualan) ? 'Pembayaran' : 'Edit' }}</button>
								</div>
								<div id="sesiAkhir" style="display: none;">
									<button class="btn btn-warning btn-ubahTransaksi" type="button" style="padding: 0px 7px;">{{ empty($penjualan) ? 'Ubah Transaksi' : 'Batal' }}</button>
									<button class="btn btn-success btn-simpan" type="submit" style="padding: 0px 7px;">Simpan</button>
								</div>
							</div>		
						</div>
					</form>
					{{-- FORM --}}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('script') 
	<script type="text/javascript">
		$('.btn-pembayaran').on('click',function(){
			var jml = $('#jumlah_bayar').val()
			var kmb = $('#kembalian').val()
			var ttl = $('#total_semua_harga').text()

			var data = getFilter()
			var nama = $('#nama_pelanggan').val()
			var pasien = $('#namaPasien').val()
			var umur = $('#umurPasien').val()
			var dokter = $('#namaDokter').val()
			var alamat = $('#alamatPasien').val()



			$('.btn-pembayaran').attr('disabled',true)
			if(data == 0){
				Swal.fire({
					icon: 'error',
					title: 'Pembayaran Gagal!',
					text: 'Tidak ada barang untuk dibayar!',
				})
			}else{
				if(jml){
					jml = jml.replace(/[^,\d]/g, '')
					kmb = kmb.replace(/[^,\d]/g, '')
					ttl = ttl.replace(/[^,\d]/g, '')
					var jmls = parseInt(jml)
					var kmbs = parseInt(kmb)
					var ttls = parseInt(ttl)
					var result =  jmls - ttls
				}else{
					result = 0
					kmbs = 1
				}
				if(result == kmbs){}
				else{
					$('#jumlah_bayar').val('')
					$('#kembalian').val('')
				}

				if(!nama){
					$('#nama_pelanggan').focus()
				}else if(!jml){
					$('#jumlah_bayar').focus()
				}else if(!pasien){
					$('#namaPasien').focus()
				}else if(!umur){
					$('#umurPasien').focus()
				}else if(!dokter){
					$('#namaDokter').focus()
				}else if(!alamat){
					$('#alamatPasien').focus()
				}

				$('#sesiAwal').hide()
				$('#sesiAkhir').show()

				$('#cari_barang').attr('disabled',true)
				$('#jumlah').attr('disabled',true)
				$('#jumlah').val('')
				$('#btn-list').attr('disabled',true)

				$('#nama_pelanggan').attr('readonly',false)
				$('#jumlah_bayar').attr('readonly',false)

				$('#namaPasien').attr('readonly',false)
				$('#umurPasien').attr('readonly',false)
				$('#alamatPasien').attr('readonly',false)
				$('#namaDokter').attr('readonly',false)
				$('#tanggalResep').attr('readonly',false)

				@if(empty($penjualan))
					$('.mins').attr('disabled',true)
					$('.plus').attr('disabled',true)
					$('.qtys').attr('readonly',true)
					$('.btn-remove').addClass('disabled')
				@else	
					$('.mins').attr('disabled',false)
					$('.plus').attr('disabled',false)
					$('.qtys').attr('readonly',false)
					$('.btn-remove').removeClass('disabled')
				@endif
			}
			$('.btn-pembayaran').attr('disabled',false)
		})

		$('.btn-ubahTransaksi').on('click',function(){
			$('.btn-ubahTransaksi').attr('disabled',true)
			$('#sesiAwal').show()
			$('#sesiAkhir').hide()

			$('#cari_barang').attr('disabled',false)
			$('#jumlah').attr('disabled',false)
			$('#btn-list').attr('disabled',false)

			$('#nama_pelanggan').attr('readonly',true)
			$('#jumlah_bayar').attr('readonly',true)
			

			$('#namaPasien').attr('readonly',true)
			$('#umurPasien').attr('readonly',true)
			$('#alamatPasien').attr('readonly',true)
			$('#namaDokter').attr('readonly',true)
			$('#tanggalResep').attr('readonly',true)

			// $('#namaPasien').val('')
			// $('#umurPasien').val('')
			// $('#alamatPasien').val('')
			// $('#namaDokter').val('')
			// $('.btn-remove').removeClass('disabled')
			@if(empty($penjualan))
				$('.mins').attr('disabled',false)
				$('.plus').attr('disabled',false)
				$('.qtys').attr('readonly',false)
				$('.btn-remove').removeClass('disabled')
			@else	
				$('.mins').attr('disabled',true)
				$('.plus').attr('disabled',true)
				$('.qtys').attr('readonly',true)
				$('.btn-remove').addClass('disabled')
			@endif
			$('.btn-ubahTransaksi').attr('disabled',false)
		})

		var getFilter = ()=>{
			var filterData = $('#tb-penjualan .rowItem').length
			return filterData
		}

		var funds = (data)=>{
			if(data == 0){
				$('#nama_pelanggan').prop('readonly',true)
				$('#jumlah_bayar').prop('readonly',true)
				$('#nama_pelanggan').val('')
				$('#jumlah_bayar').val('')
				$('#kembalian').val('')
			}
		}

		$('#cari_barang').on('select2:open',()=>{
			document.querySelector('.select2-search__field').focus()
		})

		function generate_id(n) {
			var text = "";
			var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

			for (var i = 0; i < n; i++) {
				text += possible.charAt(Math.floor(Math.random() * possible.length));
			}
			return text;
		}

		$(document).ready(function(){
			$('input[name="harga"]').click(function(){
				$('#jumlah').val('')
				$('#jumlah').focus()
			})

			funds(getFilter())

			var resep = $('#nomorResep').val()
			$('#tb-penjualan .rowItem').each(function(){
				var data = $(this).find('#jenis_penjualan')
				data.each(function(i,v){
					objResep.push($(this).val())
					if(arrResep.length == 0){
						if($(this).val() == "Resep"){
							arrResep.push(resep)
						}
					}
				})
			})
			var ceks = objResep.includes("Resep");
			if(ceks){
				$('#formResep').show()
			}
		})

		function formatNumber(angka) {
			return angka.toString().replace(/[^,\d]/g, "");
		}

		function ubahFormatNumber(v) {
			$(v).val(formatNumber(v.value));
		}

		function total(kode, qty) {
			var harga = $('#rowItem'+kode+' #harga').val();
			return (qty * harga);
		}

		function formatRupiah(angka, prefix) {
			var number_string = angka.toString().replace(/[^,\d]/g, "");
			split = number_string.split(",");
			sisa = split[0].length % 3;
			rupiah = split[0].substr(0, sisa);
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if (ribuan) {
				separator = sisa ? "." : "";
				rupiah += separator + ribuan.join(".");
			}

			rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
			return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
		}

		var objUmum = []
		function findUmum(data,key){
			var ceks = data.includes(key)
			var nama = $('#nama_pelanggan').val()
			if(ceks){
				if(!nama){
					$('#nama_pelanggan').val('pelanggan umum')
				}
			}else{
				if(!nama || nama == 'pelanggan umum'){
					$('#nama_pelanggan').val('')
				}
			}
		}

		var objResep = []
		var arrResep = []
		function findResep(data,key){
			var ceks = data.includes(key)
			
			var hargaResep = parseInt($('#mstResepNominal').val())
			if(ceks){
				var crDate = new Date()
				var getDate = crDate.getDate()
				var getMonth = crDate.getMonth()+1
				var getYear = crDate.getFullYear()
				var fullDate = getYear+'-'+(getMonth<10?0:'')+getMonth+'-'+(getDate<10?0:'')+getDate
				$('#tanggalResep').val(fullDate)
				$('#nomorResep').val(arrResep[0]);
				$('#formResep').show()
				hitungHarga(hargaResep,key)
			}else{
				hitungHarga()
				arrResep.splice(0,1)
				$('#formResep').hide()
				$('#namaPasien').val('')
				$('#umurPasien').val('')
				$('#alamatPasien').val('')
				$('#namaDokter').val('')
				$('#nomorResep').val('')
			}
		}

		function hitungHarga(getHarga='',key=''){
			var harga = $(' input[name^="total_harga"]')
			var total_harga = 0;
			for (var i = 0; i < harga.length; i++) {
				total_harga += parseInt(harga[i].value)
			}
			if(key){
				total_harga = total_harga+getHarga
			}else{
				total_harga = total_harga
			}

			// pembulatan rupiah keatas, misal (15.109 jadi 15.200)
			total_harga = total_harga.toString()
			var getLength = total_harga.length
			var first = total_harga.substring(0,getLength -3)
			// var last = (total_harga.substring(getLength -3))/100 // koding lama 21-09-2022
			var last = (total_harga.substring(getLength -3))
			if(last!='000'){
				last = last/100
				last = ((Math.ceil(last))*100).toString()
			}else{
				last = '000'
			}
			if(last.length>3){
				last = '000'
				first = parseInt(first)+1
			}else{
				last = last
			}
			var result = first+last
			total_harga = parseInt(result)

			$('#total_semua_harga').html(formatRupiah(total_harga, "Rp. "))
			$('#input_total_semua_harga').val(total_harga)
		}

		function destroyUmum(index){
			objUmum.splice(index,1)
			findUmum(objUmum,"Umum")
		}

		function destroyResep(index){
			objResep.splice(index,1)
			findResep(objResep,"Resep");
		}

		// TAMBAHKAN PENJUALAN KE TABLE
		function addlist(){
			var kode = generate_id(5)
			var id_stok_barang = $('#cari_barang').val(); // id id_stok_barang
			var jumlah = $('#jumlah').val();
			var id = $(' input[name^="id_stok_barang"]');

			// SEMENTARA TIDAK DIGUNAKAN (CEK DUPLIKAN LIST PENJUALAN)
				// var cek_data = 0;
				// for (var i = 0; i < id.length; i++) {
				// 	if (id_stok_barang == id[i].value) {
				// 		cek_data += 1;
				// 	}
				// }

			if($('#harga_dispensing').is(':checked')){
				cekPenjualan = "Dispensing (perBox)";
			}else{
				cekPenjualan = "";
			}

			if(id_stok_barang == "first"){
				Swal.fire({
					title: 'Whoops',
					text: "Silahkan pilih Obat / Barang!",
					icon: 'warning',
				});
			}else if(id_stok_barang != "first"){
				$.post("{{route('getData')}}",{id:id_stok_barang,jumlah:jumlah,cekPenjualan:cekPenjualan}).done(function(data){
					var no = ($('.rowItem').length+1);
					var harga = parseInt($('input[name=harga]:checked').val());
					var total_harga = harga*jumlah;
					var html = "";
					
					// CEK RADIO BUTTON
					var jenis_penjualan = '';
					if ($('#harga_umum').is(':checked')) {
						jenis_penjualan = 'Umum';
					}else if ($('#harga_resep').is(':checked')) {
						jenis_penjualan = 'Resep';
					}else if ($('#harga_dispensing').is(':checked')) {
						jenis_penjualan = 'Dispensing (perBox)';
					}else if($('#harga_dispensing_perbiji').is(':checked')){
						jenis_penjualan = 'Dispensing (perBiji)';
					}

					if(data.status == 'error'){
						Swal.fire({
							title: 'Whoops',
							text: data.message,
							icon: 'warning',
						});
						$('#jumlah').val('');
					}else if(jenis_penjualan==""){
						Swal.fire({
							title: 'Whoops',
							text: "Silahkan pilih harga jual!",
							icon: 'warning',
						});
					}else if (jumlah >0) {
						// arrayResep(kode,jenis_penjualan)
						objResep.push(jenis_penjualan);
						objUmum.push(jenis_penjualan);
						if(arrResep.length == 0){
							if(jenis_penjualan=='Resep'){
								arrResep.push(data.kode)
							}
						}
						html += "<tr class='rowItem' id='rowItem"+kode+"'>";
						html += "<td><span>"+no+"</span></td>";
						html += "<td><input type='hidden' name='nama_barang[]' id='nama_barang' value='"+data.data.barang.nama+"'>"+data.data.barang.nama+"</td>";
						html += "<td><input type='hidden' name='jenis_penjualan[]' id='jenis_penjualan' value='"+jenis_penjualan+"'>"+jenis_penjualan+"</td>";
						html += "<td><input type='hidden' name='harga[]' id='harga' value='"+harga+"'>"+formatRupiah(harga, 'Rp. ')+"</td>";
						html += "<td style='width: 23%;'>";
						html += "<div class='input-group mt-1' style='margin-bottom: 4px; width: 87%; margin-left: auto; margin-right: auto;'>";
						html += "<div class='input-group-prepend'><button class='mins btn btn-outline-secondary' onclick='decrease_qty(`"+kode+"`)' type='button'>-</button></div>";
						html += "<input type='text' class='form-control qtys w-25 text-center' name='jumlah[]' id='qty' onkeyup='ubahFormatNumber(this),f_qty(this,`"+kode+"`)' value='"+jumlah+"' placeholder='' aria-label='' aria-describedby='basic-addon1' autocomplete='off'>";
						html += "<div class='input-group-append'><button class='plus btn btn-outline-secondary btn-plus' onclick='increase_qty(`"+kode+"`)' type='button'>+</button></div>";
						html += "</div>";
						html += "</td>";
						html += "<td>";
						html += "<input type='hidden' name='cekData[]' value='dataBaru'>";
						html += "<input type='hidden' name='id_detail_penjualan[]' value='0'>";
						html += "<input type='hidden' name='id_stok_barang[]' id='id_stok_barang' value='"+data.data.id+"'>";
						html += "<input type='hidden' name='total_jumlah[]' id='total_jumlah' value='"+data.data.jumlah+"'>";
						html += "<input type='hidden' name='sisa_box[]' id='sisa_box' value='"+data.sisaBox+"'>";
						html += "<input type='hidden' name='total_harga[]' id='total_harga' value='"+total_harga+"'><p id='total'>"+formatRupiah(total_harga, 'Rp. ')+"</p>";
						html += "</td>";
						html += "<td><a href='javascript:;' id='removeItem-"+no+"' class='btn btn-remove' data-block='"+no+"'><i class='fa fa-trash-alt' style='color:red;'></i></a></td>";
						html += "</tr>";
						$('#tb-penjualan').append(html);
						$('#cari_barang').val('first').trigger('change');
						$('#jumlah').val('');
						setRadioHarga();

						// if(jenis_penjualan == 'Resep'){
						findResep(objResep,"Resep",data.kode);
						// }else{
						// 	findResep(objResep,"Resep");
						// }
						findUmum(objUmum,"Umum")
						$("#cari_barang").select2('open')
					}else if(jumlah == "" || jumlah == 0){
						Swal.fire({
							title: 'Whoops',
							text: "Masukkan jumlah barang dengan benar!",
							icon: 'warning',
						});
					}else{
						Swal.fire({
							title: 'Whoops',
							text: "Obat / Retail sudah masuk ke List!",
							icon: 'warning',
						});
						$('#cari_barang').val('first').trigger('change')
						$('#jumlah').val('')
						setRadioHarga()
					}

					funds(getFilter())
				});
			}
		}

		function setRadioHarga(){
			$('.radio').addClass('radio-disable');
			$('input[name="harga"]').prop('checked',false);
			// DISABLE RADIO FOR HARGA JUAL
			$('#harga_umum').prop('disabled', true);
			$('#harga_resep').prop('disabled', true);
			$('#harga_dispensing').prop('disabled', true);
			$('#harga_dispensing_perbiji').prop('disabled', true);

			// display harga set to empty
			$('#nominal_umum').text('');
			$('#nominal_resep').text('');
			$('#nominal_dispensing').text('');
			$('#nominal_dispensing_perbiji').text('');
		}

		$(document).on('click','.btn-remove',function(){
			Swal.fire({
				title: 'Anda yakin?',
				text: " Ingin menghapus data ini!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				confirmButtonText: 'Saya yakin!',
				cancelButtonText: 'Batal!',
			}).then((result) => {
				// $('.btn-remove').addClass('disabled')
				if (result.value == true) {
					var hargaResep = parseInt($('#mstResepNominal').val())
					$(this).closest('.rowItem').remove(); //remove row item

					var getIndex = $(this).data('block')-1 //get index untuk hapus array objResep berdasarkan index-ke

					destroyResep(getIndex)
					destroyUmum(getIndex)

					// reset index number
					$('.rowItem').each(function(i){
						$(this).find('span').html('' + (i+1))
						$(this).find('a').attr('id','removeItem-' + (i+1))
						$(this).find('a').attr('data-block',i+1)
					})
					funds(getFilter())
					var ceks = objResep.includes("Resep")
					if(ceks){
						hitungHarga(hargaResep,"Resep")
					}

				}
				// $('.btn-remove').removeClass('disabled')
			})
		})

		// KURANGI QTY PENJUALAN
		function decrease_qty(kode){
			var qty = parseInt($('#rowItem'+kode+' #qty').val());
			var jumlah = $('#rowItem'+kode+' #total_jumlah').val();
			if(qty > 1 && qty <= jumlah){
				qty = parseInt(qty)-1;
				$('#rowItem'+kode+' #qty').val(qty);
				$('#rowItem'+kode+' #total').html(formatRupiah(total(kode,qty),'Rp. '));
				$('#rowItem'+kode+' #total_harga').val(total(kode,qty))
				total_harga(kode);
			}else{
				$('#rowItem'+kode+' #qty').val(1)
				$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, 1), "Rp. "));
				$('#rowItem'+kode+' #total_harga').val(total(kode, 1));
				Swal.fire({
					title: 'Whoops',
					text: "Tidak bisa kurang lagi",
					icon: 'warning',
				});
				total_harga(kode)
			}
		}

		// TAMBAH QTY PENJUALAN
		function increase_qty(kode){
			var qty = parseInt($('#rowItem'+kode+' #qty').val());
			var jumlah = $('#rowItem'+kode+' #total_jumlah').val();
			var sisaBox = $('#rowItem'+kode+' #sisa_box').val();
			var cekJual = $('#rowItem'+kode+' #jenis_penjualan').val();
			
			qty = qty+1;
			$('#rowItem'+kode+' #qty').val(qty);
			if(cekJual == 'Dispensing (perBox)'){ // UNTUK PENJUALAN DISPENSING PER-BOX
				if(qty <= sisaBox){
					$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, qty), "Rp. "));
					$('#rowItem'+kode+' #total_harga').val(total(kode, qty));
					total_harga(kode)
				}else{
					$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, sisaBox), "Rp. "));
					$('#rowItem'+kode+' #total_harga').val(total(kode, sisaBox));
					$('#rowItem'+kode+' #qty').val(sisaBox);
					Swal.fire({
						title: 'Whoops',
						text: "Max "+sisaBox+' Box!',
						icon: 'warning',
					})
					total_harga(kode)
				}
			}else{ // UNTUK PENJUALAN PER-BIJI
				if (qty <= jumlah) {
					$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, qty), "Rp. "));
					$('#rowItem'+kode+' #total_harga').val(total(kode, qty));
					total_harga(kode)
				} else {
					$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, jumlah), "Rp. "));
					$('#rowItem'+kode+' #total_harga').val(total(kode, jumlah));
					$('#rowItem'+kode+' #qty').val(jumlah);
					Swal.fire({
						title: 'Whoops',
						text: "Max jumlah "+jumlah,
						icon: 'warning',
					})
					total_harga(kode)
				}
			}
		}

		// ONKEYUP QTY PENJUALAN
		function f_qty(v,kode) {
			if (v.value[0] != 0) {
				$(v).val(formatNumber(v.value));

				var qty = parseInt(formatNumber(v.value));
				var jumlah = parseInt($('#rowItem'+kode+' #total_jumlah').val());
				var cekJual = $('#rowItem'+kode+' #jenis_penjualan').val();
				var sisaBox = $('#rowItem'+kode+' #sisa_box').val();

				if(cekJual == 'Dispensing (perBox)'){ // UNTUK PENJUALAN DISPENSING PER-BOX
					if(qty <= sisaBox){
						$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, qty), "Rp. "));
						$('#rowItem'+kode+' #total_harga').val(total(kode, qty));
						total_harga(kode)
					}else{
						$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, sisaBox), "Rp. "));
						$('#rowItem'+kode+' #total_harga').val(total(kode, sisaBox));
						$('#rowItem'+kode+' #qty').val(sisaBox);
						Swal.fire({
							title: 'Whoops',
							text: "Max "+sisaBox+' Box!',
							icon: 'warning',
						})
						total_harga(kode)
					}
				}else{ // PENJUALAN PER-BIJI
					if (qty <= jumlah) {
						$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, qty), "Rp. "));
						$('#rowItem'+kode+' #total_harga').val(total(kode, qty));
						total_harga(kode);
					} else {
						$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, 1), "Rp. "));
						$('#rowItem'+kode+' #total_harga').val(total(kode, 1));
						$('#rowItem'+kode+' #qty').val(1)
						Swal.fire({
							title: 'Whoops',
							text: "Max jumlah "+jumlah,
							icon: 'warning',
						});
						total_harga(kode);
					}
				}
			} else {
				$(v).val(1);
				$('#rowItem'+kode+' #total').html(formatRupiah(total(kode, 1), "Rp. "));
				$('#rowItem'+kode+' #total_harga').val(total(kode, 1));
				Swal.fire({
					title: 'Whoops',
					text: "Input tidak boleh 0",
					icon: 'warning',
				});
				total_harga(kode)
			}
		}

		function total_harga(kode) {
			findResep(objResep,"Resep");
			// var harga = $(' input[name^="total_harga"]');
			// var total_harga = 0;
			// for (var i = 0; i < harga.length; i++) {
			// 	total_harga += parseInt(harga[i].value);
			// }
			// $('#total_semua_harga').html(formatRupiah(total_harga, "Rp. "));
			// $('#input_total_semua_harga').val(total_harga);
		}

		function storePenjualan(data){
			$.ajax({
				url: "{{route('storePenjualan')}}",
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data) {
					if (data.status == 'success') {
						var winPrint = window.open('about:blank', '_blank');

						winPrint.document.write(data.print[0]);
						winPrint.document.close();
						setTimeout(function () {
							winPrint.print();
							winPrint.close();
							Swal.fire({
								title: 'Berhasil',
								text: data.message,
								icon: 'success',
								showConfirmButton: false,
								timer: 1300
							})
							funds(getFilter())
							location.reload();
						},150)
						$('#formResep').hide()
						$('#tb-penjualan > tbody').empty();
						$('#total_semua_harga').html('Rp. 0');
						$('#jumlah_bayar').val('');
						$('#kembalian').val('');
						$('#nama_pelanggan').val('');
						$('#input_total_semua_harga').val(0);
						$('#namaPasien').val('');
						$('#umurPasien').val('');
						$('#alamatPasien').val('');
						$('#namaDokter').val('');
						$('#nomorResep').val('');
					} else {
						Swal.fire({
							title: 'Gagal',
							text: data.message,
							icon: data.status,
							showConfirmButton: false,
							timer: 1500
						})
					}
					$('.btn-simpan').html('Simpan').attr('disabled', false);
				}
			})
		}

		function validStore(data){
			Swal.fire({
				title: 'Yakin?',
				text: 'Apakah data sudah benar?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#2dce89',
				cancelButtonColor: '#f41e48',
				cancelButtonText: 'Cek data',
				confirmButtonText: 'Ya, Simpan!',
				reverseButtons: true,
			}).then((result)=>{
				if(result.isConfirmed){
					storePenjualan(data)
				}
			})
		}

		$('.btn-simpan').click(function(e) {
			e.preventDefault();
			// $('.btn-simpan').html('Proses Simpan').attr('disabled', true);
			var data = new FormData($('.form-list-barang')[0]);
			var id_stok_barang = $('#id_stok_barang').val();
			var nama_pelanggan = $('#nama_pelanggan').val();
			var jumlah_bayar = $('#jumlah_bayar').val();
			var kembalian = $('#kembalian').val();
			var namapasien = $('#namaPasien').val()
			var umurPasien = $('#umurPasien').val()
			var alamatPasien = $('#alamatPasien').val()
			var namaDokter = $('#namaDokter').val()

			if (id_stok_barang != null && nama_pelanggan != '' && jumlah_bayar != '' && kembalian != 'Kurang') {
				if(arrResep.length!=0){
					if(namaPasien!='' && umurPasien!='' && alamatPasien!='' && namaDokter!=''){
						validStore(data)
					}else{
						Swal.fire({
							title: 'Whoops',
							text: 'Lengkapi inputan terlebih dahulu!',
							icon: 'warning',
							showConfirmButton: false,
							timer: 1500
						})
						$('.btn-simpan').html('Simpan').attr('disabled', false)
					}
				}else{
					validStore(data)
				}
			}else{
				Swal.fire({
					title: 'Whoops',
					text: 'Lengkapi inputan terlebih dahulu!',
					icon: 'warning',
					showConfirmButton: false,
					timer: 1500
				});
				$('.btn-simpan').html('Simpan').attr('disabled', false);
			}
		})

		function cetak_kwitansi(id) {
			$.post("{{route('cetak_kwitansi') }}", {id:id}).done(function(data){
				if(data.status == 'success'){
					// swal("Success!", data.message, "success");
					if (data.print.length > 0) {
						var winPrint = window.open('about:blank', '_blank');
						winPrint.document.write(data.print[0]);
						winPrint.document.close();
						setTimeout(function () {
							winPrint.print();
							winPrint.close();
							Swal.fire({
								title: 'Berhasil',
								text: data.message,
								icon: 'success',
							});
						},150);
					}
				} else {
					swal("Success!", data.message, "success");
					$('.preloader').hide();
					$('.main-page').show();
				}
			});
		}

		// OBAT TRIGGER
		$('#cari_barang').change(function () {
			var id = $('#cari_barang').val();
			if (id != 'first') {
				$.post("{!! route('getHargaBarang') !!}",{id:id}).done(function(data){
					// REMOVE DISABLE ALL RADIO CLASS
					$('.radio').removeClass('radio-disable');
	
					// REMOVE DISABLE ALL RADIO INPUT
					$('#harga_umum').prop('disabled', false);
					$('#harga_resep').prop('disabled', false);
					$('#harga_dispensing').prop('disabled', false);
					$('#harga_dispensing_perbiji').prop('disabled', false);
	
					// ADD HARGA EACH RADIO
					$('#nominal_umum').text(formatRupiah(data.harga_umum, 'Rp.'));
					$('#nominal_resep').text(formatRupiah(data.harga_resep, 'Rp.'));
					$('#nominal_dispensing').text(formatRupiah(data.harga_dispensing, 'Rp.'));
					$('#nominal_dispensing_perbiji').text(formatRupiah(data.harga_dispensing_perbiji, 'Rp. '));
	
					// ADD VALUE HARGA TO INPUT RADIO
					$('#harga_umum').val(data.harga_umum);
					$('#harga_resep').val(data.harga_resep);
					$('#harga_dispensing').val(data.harga_dispensing);
					$('#harga_dispensing_perbiji').val(data.harga_dispensing_perbiji);
					$('input[name="harga"]').prop('checked',false);

					$('#harga_umum').prop('checked',true)
					$('#jumlah').val('')
					$('#jumlah').focus()
				});
			}
		});

		function input_pembayaran() {
			var jumlah_bayar = $('#jumlah_bayar').val();
			jumlah_bayar = jumlah_bayar.replace(/[^,\d]/g, '').toString();
			var total_semua_harga = $('#total_semua_harga').text();
			total_semua_harga = total_semua_harga.replace(/[^,\d]/g, '').toString();
			total_semua_harga = parseInt(total_semua_harga);
			jumlah_bayar = parseInt(jumlah_bayar);
			$('#jumlah_bayar').val(formatRupiah(jumlah_bayar.toString(),'Rp. '));
			var hitung = jumlah_bayar - total_semua_harga;
			var kembalian = ((jumlah_bayar > total_semua_harga || jumlah_bayar == total_semua_harga) ? formatRupiah(hitung.toString(),"Rp. ") : 'Kurang');
			$('#kembalian').val(kembalian);
		}
	</script>
	<script>
        function myPrint() {
          window.open("{{ route('printPenjualan') }}", '_blank');
        }
    </script>
@endpush