@extends('layouts.main')
@section('title', 'Laporan Penjualan')
@push('head')
<style type="text/css">
	div.dataTables_wrapper {
		width: 95%;
		margin: 0 auto;
	}
</style>
@endpush
@section('content')
	<div class="container-fluid">
		<div class="page-header">
			<div class="row align-items-end">
				<div class="col-lg-8">
					<div class="page-header-title">
						{{-- <i class="ik ik-users bg-blue"></i> --}}
						<div class="d-inline">
							<h5>{{ __('Laporan Penjualan') }}</h5>
							{{-- <span>{{ __('List of users')}}</span> --}}
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<nav class="breadcrumb-container" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
							</li>
							<li class="breadcrumb-item">
								<a href="#">{{ __('Laporan Penjualan') }}</a>
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>

		<div id="main-layer">
			<div class="row">
				<!-- start message area-->
				@include('include.message')
				<!-- end message area-->
				<div class="col-md-12">
					<div class="card">
						<div class="card-header row">
							<div class="col-lg-12">
								<div class="row mb-4">
									<div class="col-md-12">
										<div class="row">
											<div class="col-sm-10 showDateRange">
												<div class="row">
													<div class="col-md-6">
														<div class="row">
															<label class="col-form-label col-md-3">Tanggal</label>
															<div class="col-md-9">
																<input type="date" name="startDate" id="startDate" class="input-sm form-control">
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<label class="col-form-label col-md-2">s/d</label>
															<div class="col-md-10">
																<input type="date" name="endDate" id="endDate" class="input-sm form-control">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="col-sm-2" style="margin-top: 2px;">
												<button class="btn btn-info btn-sm btn-search" title="Cari" onclick="searchData()"><i class="fas fa-search"></i></button>&nbsp;
												<button class="btn btn-success btn-sm btn-refresh" title="Refresh" onclick="refreshTable()"><i class="fas fa-sync"></i></button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<a href="javascript:void(0)" onclick="myPrint()" id="exportButton"class="btn btn-primary hidden-print">
											<i class="fa fa-file-export fs-14 m-r-10"></i> <b>Export Laporan</b>
										</a>
									</div>
									<div class="col-md-6">
										<input type="hidden" name="" id="tHarga" value="{{$total}}">
										<strong><p class="float-right mt-1">Total Laba : <span id="total">Rp. 0</span></p> </strong>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body">
							<table id="datatables" class="table table-striped dataTable display nowrap" style="width: 100%;">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama</th>
										<th>Satuan</th>
										<th>Jenis Penjualan</th>
										<th>QTY</th>
										<th>Harga Beli</th>
										<th>Harga Jual</th>
										<th>Omset</th>
										{{-- <th>Seleisih Harga</th> --}}
										<th>Laba</th>
										<th>ED</th>
										<th>Batch</th>
										<th>ID TRX</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="other-layer"></div>
	</div>
@push('script')
	<script>
		$(document).ready(function() {
			loadData();

			// FORMAT RUPIAH TOTAL
			var total = $('#total');
			total.text(formatRupiah(parseInt(total.text()), 'Rp.'))

		});

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

		function loadData(startDate='',endDate=''){
			var table = $('#datatables').DataTable({
				scrollX: true,
				serverSide: true,
				processing: true,
				ajax: {
					url: "{{ route('laporanPenjualan') }}",
					data: {
						startDate: startDate,
						endDate: endDate,
					},
				},
				columns: [
				{data: 'DT_RowIndex',name: 'DT_RowIndex',orderable: false,searchable: false},
				{data: 'barang',name: 'barang'},
				{data: 'satuan',name: 'nama_satuan'},
				{data: 'jenis_penjualan',name: 'jenis_penjualan'},
				{data: 'qty',name: 'qty',render:function(data,type,row){
					return qty(data,row)
				}},
				{data: 'harga_beli',name: 'harga_beli',render: function(data, type, row) {
					return hargaBeli(data,row)
				}},
				{data: '', name: '',render: function(data, type, row){
					return hargaJual(row)
				}},
				{data: 'omset',name: 'omset',render:function(data,type,row){
					return formatRupiah(data,"Rp. ")
				}},
				// {data: 'selisih',name: 'selisih',render: function(data, type, row){
				// 	return formatRupiah(data, "Rp. ")
				// }},
				{data: 'labarugi',name: 'labarugi',render: function(data, type, row){
					return formatRupiah(data, "Rp. ")
				}},
				{data: 'expired',name: 'expired'},
				{data: 'batch',name: 'batch'},
				{data: 'idTRX',name: 'idTRX'},
				],
			});

			table.on('xhr',function(){
				var totalLabaRugi = table.ajax.json().total;
				$('#total').text(formatRupiah(totalLabaRugi??0,'Rp. '));
			})
		}

		function qty(data,row){
			var jenis = row.jenis_penjualan
			var perBiji = row.jumlah_perbox
			if(jenis=="Dispensing (perBox)"){
				data = (data/perBiji)+" Box"
			}else{
				data = data+" Pcs"
			}
			return data
		}

		function hargaBeli(data,row){
			var jenis = row.jenis_penjualan
			var perBiji = row.jumlah_perbox
			var harga = data
			if(jenis=="Dispensing (perBox)"){
				harga = harga
			}else{
				harga = Math.round(harga/perBiji)
			}
			return formatRupiah(harga,"Rp. ")
		}

		function hargaJual(data){
			var jenis = data.jenis_penjualan
			var umum = data.harga_umum
			var resep = data.harga_resep
			var disBox = data.harga_dispensing
			var disBii = data.harga_dispensing_perbiji
			var harga = 0;
			if(jenis=="Umum"){
				harga = umum
			}else if(jenis=="Resep"){
				harga = resep
			}else if(jenis=="Dispensing (perBox)"){
				harga = disBox
			}else{
				harga = disBii
			}
			return formatRupiah(harga,"Rp. ")
		}

		function refreshTable(){
			$('#datatables').DataTable().destroy();
			$('.btn-refresh').attr('disabled', true);
			$('#startDate').val('');
			$('#endDate').val('');
			$('.btn-refresh').attr('disabled', false);
			loadData();
		}

		function searchData(){
			$('.btn-search').attr('disabled',true);
			var startDate = $('#startDate').val();// range awal tanggal
			var endDate = $('#endDate').val();// range akhir tanggal
			if(startDate && endDate){
				if(startDate<=endDate){
					$('#datatables').DataTable().destroy();
					loadData(startDate,endDate);
				}else{
					Swal.fire({
						icon: 'warning',
						title: 'Whoops',
						text: 'Tanggal Tidak Sesuai!',
						showConfirmButton: false,
						timer: 1200
					});
				}
			}else{
				Swal.fire({
					icon: 'warning',
					title: 'Whoops',
					text: 'Tanggal Belum Dipilih!',
					showConfirmButton: false,
					timer: 1200
				});
			}
			$('.btn-search').attr('disabled',false);
		}

		function myPrint() {
			var totalLabaRugi = $('#total').text();
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();
			$.post("{!! route('printLaporanPenjualan') !!}",{totalLabaRugi:totalLabaRugi,startDate:startDate,endDate:endDate},function(data){
				var newWin = window.open('', 'Print-Window');
				newWin.document.open();
				newWin.document.write('<html><head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"></head><body>'+data.content+'</body></html>');
				setTimeout(() => {
					newWin.document.close();
					newWin.close();
				}, 3000);
			});
		}
	</script>

@endpush
@endsection
