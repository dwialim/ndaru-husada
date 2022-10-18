@extends('layouts.main')

@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
<style type="text/css">
	div.dataTables_wrapper {
		width: 95%;
		margin: 0 auto;
	}
</style>
@endpush

@section('title', 'Stok Opname')
@section('content')
<div class="container-fluid">
	<div class="main-layer">
		<div class="page-header">
			<div class="row align-items-end">
				<div class="col-lg-8">
					<div class="page-header-title">
						<div class="d-inline">
							<h5>Stok Opname</h5>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<nav class="breadcrumb-container" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="{{route('dashboard')}}"><i class="ik ik-home"></i></a>
							</li>
							<li class="breadcrumb-item">
								<a href="javascript:">Stok Opname</a>
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header row">
					<div class="col-lg-12">
						<div class="row mb-4 ml-2">
							<div class="col-md-10 showDateRange">
								<div class="row">
									<div class="col-sm-6">
										<div class="row">
											<label class="col-form-label col-sm-3">Tanggal</label>
											<div class="col-sm-9">
												<input type="date" name="startDate" id="startDate" class="input-sm form-control">
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="row">
											<label class="col-form-label col-sm-2">s/d</label>
											<div class="col-sm-10">
												<input type="date" name="endDate" id="endDate" class="input-sm form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-2" style="margin-top: 1px;">
								<button class="btn btn-info btn-sm btn-search" title="Cari" onclick="searchData()">
									<i class="fas fa-search"></i>
								</button>
								&nbsp;
								<button class="btn btn-success btn-sm btn-refresh" title="Refresh" onclick="refreshTable()">
									<i class="fas fa-sync"></i>
								</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<button type="button" class="btn btn-sm btn-primary btn-add">
									<i class="fa fa-plus"></i> Tambah
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
						<thead>
							<tr class="text-center">
								<th>No</th>
								<th>No. SOp</th>
								<th>No. Batch</th>
								<th>Nama</th>
								<th>Total Biji (Sistem)</th>
								<th>Total Biji (Fisik)</th>
								<th>Harga Beli perBiji</th>
								<th>Keterangan</th>
								<th>Tanggal SOp</th>
								<th>Selisih (Data Fisik)</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="otherPages"></div>
</div>
@endsection

@push('script')
	<script type="text/javascript">
		$(document).ready(function(){
			loadData();
		});

		function refreshTable(){
			$('#dataTable').DataTable().destroy();
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
					$('#dataTable').DataTable().destroy();
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

		function loadData(startDate='', endDate=''){
			var dataTable = $('#dataTable').DataTable({
				dom: "<'row'<'col-sm-12 mb-2'<'float-right'B>>>"
					+"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>"
					+"<'row'<'col-sm-12'tr>>"
					+"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				buttons: [
					{
						text: 'Excel',
						extend: 'excelHtml5',
						className: 'btn btn-primary fas fa-print',
					},
				],
				scrollX: true,
				serverSide: true,
				processing: true,
				ajax: {
					url: "{{route('getStokOpname')}}",
					type: "POST",
					data: {
						startDate: startDate,
						endDate: endDate,
					}
				},
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
					{data: 'kode_stok_opname', name: 'kode_stok_opname',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
					{data: 'batch_stok_barang', name: 'batch_stok_barang',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
					{data: 'stok_barang.barang.nama', name: 'stok_barang.barang.nama',render:function(data){
						return '<p class="text-center">'+data+'</p>';
					}},
					{data: 'stok_awal', name: 'stok_awal',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
					{data: 'jumlah_stok', name: 'jumlah_stok',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
					{data: 'hargaPerBiji', name: 'hargaPerBiji',render:function(data){
						return '<p class="text-center">'+formatRupiah(data,"Rp. ")+'</p>'
					}},
					{data: 'keterangan', name: 'keterangan',render:function(data){
						return keterangan(data);
					}},
					{data: 'tanggal', name: 'tanggal',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
					{data: 'selisih', name: 'selisih',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
				],
				drawCallback: function(){
					var hasRows = this.api().rows({filter: 'applied'}).data().length > 0;
					$('.buttons-excel')[0].style.visibility = hasRows ? 'visible':'hidden'
				},
			});
		}

		function keterangan(data){
			var text = data;
			var result = ''
			if(text){
				result = text.toUpperCase()
			}else{
				result = null;
			}
			return '<p class="text-center">'+(result??'-')+'</p>';
		}

		$('.btn-add').click(function(){
			$('.main-layer').hide();
			$.post("{{route('formStokOpname')}}").done(function(data){
				if(data.status == 'success'){
					$('.otherPages').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		});

		function updated(id) {
			var id = id;
			$('.main-layer').hide();
			$.post("{{route('formBeliObat')}}", {
				idFaktur:id
			}).done(function(data){
				if (data.status == 'success') {
					$('.otherPages').html(data.content).fadeIn();
				} else {
					$('.main-layer').show()
				}
			});
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
	</script>
@endpush