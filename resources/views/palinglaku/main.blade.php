@extends('layouts.main')
@section('title', 'Laporan Paling Laku')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row align-items-end">
			<div class="col-lg-8">
				<div class="page-header-title">
					<div class="d-inline">
						<h5>{{ __('Laporan Paling Laku') }}</h5>
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
							<a href="#">{{ __('Laporan Paling Laku') }}</a>
						</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div id="main-layer">
		{{-- MAIN PAGE --}}
		<div class="row">
			<!-- start message area-->
			@include('include.message')
			<!-- end message area-->
			<div class="col-md-12">
				<div class="card p-3">
					<div class="card-header row">
						<div class="col-lg-12">
							<div class="row">
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
							{{--<div class="row">
								<div class="col-md-12">
									<a href="javascript:void(0)" onclick="myPrint()" id="exportButton"class="btn btn-primary hidden-print">
										<i class="fa fa-file-export fs-14 m-r-10"></i> <b>Export Laporan</b>
									</a>
								</div>
							</div>--}}
						</div>
					</div>
					<div class="card-body">
						<table id="datatables" class="table">
							<thead>
								<tr>
									<th>{{ __('No.') }}</th>
									<th>{{ __('Nama ') }}</th>
									<th>{{ __('Satuan ') }}</th>
									<th>{{ __('QTY') }}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="other-layer"></div>
</div>
<!-- push external js -->
@push('script')

<!-- <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script> -->
<script>
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

	function refreshTable(){
		$('#datatables').DataTable().destroy();
		$('.btn-refresh').attr('disabled', true);
		$('#startDate').val('');
		$('#endDate').val('');
		$('.btn-refresh').attr('disabled', false);
		loadData();
	}

	$(document).ready(function() {
		loadData();
	});

	function loadData(startDate='',endDate=''){
		var dTable = $('#datatables').DataTable({
			dom: "<'row'<'col-sm-12 mb-2'<'float-right'B>>>"
				+"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>"
				+"<'row'<'col-sm-12'tr>>"
				+"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [{
				text: 'Excel',
				extend: 'excelHtml5',
				className: 'btn btn-primary fas fa-print',
			}],
			columnDefs: [{
				orderable: false,
				targets: -1
			}],
			serverSide: true,
			processing: true,
			ajax: {
				url: "{{ route('palinglaku') }}",
				data: {
					startDate: startDate,
					endDate: endDate,
				},
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
				{ data: 'nama_barang', name: 'nama_barang' },
				{ data: 'nama_satuan', name: 'nama_satuan' },
				{ data: 'qty', name: 'qty' }
			],
		});
	}

	function myPrint() {
		window.open("{{ route('printPalingLaku') }}", '_blank');
	}
</script>
@endpush
@endsection
