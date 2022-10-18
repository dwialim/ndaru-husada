@extends('layouts.main')
@section('title', 'Data Laporan Pengeluaran Lain-lain')
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
					<div class="d-inline">
						<h5>Laporan Pengeluaran Lain-lain</h5>
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
							<a href="javascript:;">Laporan Pengeluaran Lain-lain</a>
						</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>

	<div class="main-layer">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header row">
					<div class="col-lg-12">
						<div class="row mb-4 ml-3">
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
								<button class="btn btn-info btn-sm btn-search" title="Cari" onclick="searchData()"><i class="fas fa-search"></i></button>&nbsp;
								<button class="btn btn-success btn-sm btn-refresh" title="Refresh" onclick="refreshTable()"><i class="fas fa-sync"></i></button>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<button type="button" class="btn btn-sm btn-primary btn-add">
									<i class="fa fa-plus"></i> Tambah Laporan
								</button>
							</div>
							<div class="col-md-6">
								<p class="float-right">Total Pengeluaran: <span id="totalPengeluaran">Rp. 0</span></p>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">

					<table id="laporanPengeluaran" class="table table-striped dataTable display nowrap" style="width: 100%;">
						<thead>
							<tr class="text-center">
								<th> No </th>
								<th> Nama </th>
								<th> Deskripsi </th>
								<th> Nominal </th>
								<th> Tanggal </th>
								<th> Action </th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="otherPages"></div>
</div>

@push('script')
	<script>
		$(document).ready(function(){
			loadData();
		});

		$('.btn-add').click(function(){
			$('.main-layer').hide();
			$.post("{{route('formPengeluaran')}}").done(function(data){
				if(data.status == 'success'){
					$('.otherPages').html(data.content).fadeIn();
				} else {
					$('.main-layer').show();
				}
			});
		});

		function updated(id){
			$('.main-layer').hide();
			$.post("{{route('formPengeluaran')}}",{id:id}).done(function(data){
				if(data.status == 'success'){
					$('.otherPages').html(data.content).fadeIn();
				}else{
					$('.main-layer').show();
				}
			});
		}

		function deleted(id){
			Swal.fire({
				title: 'Anda yakin?',
				text: "Data akan dihapus dari sistem dan tidak dapat dikembalikan!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				confirmButtonText: 'Saya yakin!',
				cancelButtonText: 'Batal!',
			}).then((result)=>{
				if(result.isConfirmed==true){
					$.post("{{ route('destroyPengeluaran') }}",{id:id}).done(function(data){
						if(data.status == 'success'){
							$('#laporanPengeluaran').DataTable().ajax.reload();
							Swal.fire({
								icon: data.status,
								text: data.message,
								showConfirmButton: false,
								timer: 1200
							});
						}else{
							Swal.fire({
								icon: data.status,
								title: 'Whoops',
								text: data.message,
								showConfirmButton: false,
								timer: 1200
							});
						}
					});
				}else{}
			});
		}

		function searchData(){
			$('.btn-search').attr('disabled',true);
			var startDate = $('#startDate').val();// range awal tanggal
			var endDate = $('#endDate').val();// range akhir tanggal
			if(startDate && endDate){
				$('#laporanPengeluaran').DataTable().destroy();
				loadData(startDate,endDate);
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

		function refreshTable(){
			$('#laporanPengeluaran').DataTable().destroy();
			$('.btn-refresh').attr('disabled', true);
			$('#startDate').val('');
			$('#endDate').val('');
			$('.btn-refresh').attr('disabled', false);
			loadData();
		}

		function loadData(startDate='',endDate=''){
			var dataTable = $('#laporanPengeluaran').DataTable({
				dom: "<'row'<'col-sm-12 mb-2'<'float-right'B>>>"
					+"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>"
					+"<'row'<'col-sm-12'tr>>"
					+"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				buttons: [{
					text: 'Excel',
					extend: 'excelHtml5',
					className: 'btn btn-primary fas fa-print',
					exportOptions : {
						modifier : {
							// DataTables core
							order : 'index', // 'current', 'applied',
							//'index', 'original'
							page : 'all', // 'all', 'current'
							search : 'none' // 'none', 'applied', 'removed'
						},
						columns: [ 0, 1, 2, 3, 4],
					}
				}],
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				scrollX: true,
				processing: true,
				serverSide: true,
				ajax: {
					url: "{{route('getPengeluaran')}}",
					type: 'post',
					data: {
						startDate: startDate,
						endDate: endDate,
					},
				},
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex',render:function(data){
						return '<p class="text-center">'+data+'</p>';
					}},
					{data: 'nama', name: 'nama',render:function(data){
						return '<p class="text-center">'+data+'</p>';
					}},
					{data: 'deskripsi', name: 'deskripsi',render:function(data){
						return deskripsi(data);
					}},
					{data: 'nominal', name: 'nominal',render:function(data){
						return '<p>'+formatRupiah(data,'Rp. ')+'</p>';
					}},
					{data: 'tanggal', name: 'tanggal',render:function(data){
						return '<p class="text-center">'+data+'</p>';
					}},
					{data: 'action', name: 'action',render:function(data){
						return '<p class="text-center">'+data+'</p>'
					}},
				],
				drawCallback: function() {
					var hasRows = this.api().rows({
						filter: 'applied'
					}).data().length > 0;
					$('.buttons-excel')[0].style.visibility = hasRows ? 'visible' : 'hidden'
				},
			});

			dataTable.on('xhr',function(){
				var totalPengeluaran = dataTable.ajax.json().sumPengeluaran;
				$('#totalPengeluaran').text(formatRupiah(totalPengeluaran,'Rp. '))
			})

		}

		function deskripsi(data){
			if(data){
				showSt = '<p class="text-center">'+data+'</p>';
			}else{
				showSt = '<p class="text-center"> - </p>';
			}
			return showSt;
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
@endsection