@extends('layouts.main')
@section('title', 'PBF')
@section('content')
@push('head')
<style type="text/css">
	div.dataTables_wrapper {
		width: 95%;
		margin: 0 auto;
	}
</style>
@endpush
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<div class="container-fluid">
	<div class="page-header">
		<div class="row align-items-end">
			<div class="col-lg-8">
				<div class="page-header-title">
					<div class="d-inline">
						<h5>{{ __('Data PBF') }}</h5>
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
							<a href="javascript:void(0)">{{ __('Data PBF') }}</a>
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
				<div class="card p-3">
					<div class="card-header">
						<a href="javascript:void(0)" id="create-pbf" class="btn btn-primary">Tambah PBF Baru</a>
					</div>
					<div class="card-body">
						<table id="datatables" class="table table-striped dataTable display nowrap" style="width: 100%;">
							<thead>
								<tr class="text-center">
									<th>{{ __('No.') }}</th>
									<th>{{ __('Kode PBF') }}</th>
									<th>{{ __('Nama PBF') }}</th>
									<th>{{ __('Alamat') }}</th>
									<th>{{ __('Provinsi') }}</th>
									<th>{{ __('Kabupaten') }}</th>
									<th>{{ __('Kecamatan') }}</th>
									<th>{{ __('Email') }}</th>
									<th>{{ __('Telpon') }}</th>
									<th>{{ __('Option') }}</th>
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
<script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
<!--server side users table script-->

<script>
	$(document).ready(function() {
		// Sementara tidak digunakan

		var dTable = $('#datatables').dataTable({
			scrollX: true,
			serverSide: true,
			processing: true,
			language: {
				processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
			},
			// scroller: {
			// 	loadingIndicator: false
			// },
			// pagingType: "full_numbers",
			ajax: {
				url: "{{ route('pbf') }}",
				type: "get"
			},
			columns: [
				{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex',
					render:function(data){
						return "<p class='text-center'>"+(data??"-")+"</p>";
					},
					orderable: false,
					searchable: false
				},
				{data: 'kode',name: 'kode',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'nama',name: 'nama',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'alamat',name: 'alamat',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'provinsi.nama',name: 'provinsi.nama',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'kabupaten.nama',name: 'kabupaten.nama',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'kecamatan.nama',name: 'kecamatan.nama',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'email',name: 'email',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'no_telpon',name: 'no_telpon',render:function(data){
					return "<p class='text-center'>"+(data??"-")+"</p>";
				}},
				{data: 'action',name: 'action'}
			],
		});

		// CREATE PBF
		$('#create-pbf').click(function() {
			$('#main-layer').hide();
			$.post("{{ route('form-pbf') }}").done(function(data) {
				if (data.status == 'success') {
					$('#other-layer').html(data.content).fadeIn();
				} else {
					$('#main-layer').show();
				}
			});
		})
	});

	function edit_pbf(id) {
		$('#main-layer').hide();
		$.ajax({
			type: "POST",
			url: "{{ route('form-pbf') }}",
			data: {
				id: id,
			},
		}).done(function(data) {
			if (data.status == 'success') {
				$('#other-layer').html(data.content).fadeIn();
			} else {
				$('#main-layer').show();
			}
		});
	}

	function delete_pbf(id) {
		Swal.fire({
			title: "Apakah Anda yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan lagi.",
			type: "warning",
			showCancelButton: true,
			showCloseButton: true,
			icon: 'warning',
			cancelButtonText: 'Batal',
			confirmButtonText: 'Hapus',
		}).then((result) => {
			if (result.value) {
				Swal.fire("", "Data berhasil dihapus!", "success");
				$.post("{{ route('delete-pbf') }}", {
					id: id
				}).done(function(data) {
					if (data == 'true') {
						Swal.fire(data.status, "Data berhasil dihapus!", "success");
					}
					$('#datatables').DataTable().ajax.reload();
				}).fail(function() {
					Swal.fire("Sorry!", "Gagal menghapus data!", "error");
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				Swal.fire("Batal", "Data batal dihapus!", "error");
				$('#datatables').DataTable().ajax.reload();
			}
		});
	}
</script>
@endpush
@endsection
