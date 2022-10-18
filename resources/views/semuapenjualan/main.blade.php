@extends('layouts.main')
@section('title', 'Semua Penjualan')
@section('content')
<!-- push external head elements to head -->
@push('head')
@endpush
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<div class="container-fluid">
	<div class="page-header">
		<div class="row align-items-end">
			<div class="col-lg-8">
				<div class="page-header-title">
					{{-- <i class="ik ik-users bg-blue"></i> --}}
					<div class="d-inline">
						<h5>{{ __('Data Semua Penjualan') }}</h5>
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
							<a href="#">{{ __('Data Semua Penjualan') }}</a>
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
					<div class="card-header">
						<a href="{{ route('penjualan') }}" id="create-semua-penjualan" class="btn btn-primary">Tambah Penjualan Baru</a>
					</div>
					<div class="card-body">
						<table id="datatables" class="table">
							<thead class="text-center">
								<tr>
									<th>{{ __('No.') }}</th>
									<th>{{ __('No. Kwitansi') }}</th>
									<th>{{ __('Tanggal Penjualan') }}</th>
									<th>{{ __('Nama Pelanggan') }}</th>
									<th>{{ __('Nama User') }}</th>
									<th>{{ __('Level User') }}</th>
									<th>{{ __('Option') }}</th>
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
<!-- push external js -->
@push('script')
<script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
<!--server side users table script-->

<script>
	$(document).ready(function() {
		var dTable = $('#datatables').dataTable({
			responsive: true,
			serverSide: true,
			processing: true,
			scroller: {
				loadingIndicator: false
			},
			pagingType: "full_numbers",
			ajax: {
				url: "{{ route('semuaPenjualan') }}",
				type: "get"
			},
			columns: [
				{data: 'DT_RowIndex',name: 'DT_RowIndex',orderable: false,searchable: false},
				{data: 'no_kwitansi',name: 'no_kwitansi'},
				{data: 'tanggal_penjualan',name: 'tanggal_penjualan'},
				{data: 'nama_pelanggan',name: 'nama_pelanggan',render:function(data){
					return '<p class="text-center">'+data.toUpperCase()+'</p>';
				}},
				{data: 'nama_user',name: 'nama_user',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'nama_level',name: 'nama_level',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'action',name: 'action'}
			],
		});

	});

	function edit_semua_penjualan(id) {
		$(location).prop('href', "{{ route('penjualan') }}?id="+id)
	}

	function delete_semua_penjualan(id) {
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
				$.post("{{ route('delete_semua_penjualan') }}", {
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

	function cetak_kwitansi(id) {
		$.post("{{route('cetak_kwitansi') }}", {id:id}).done(function(data){
			if(data.status == 'success'){
				if (data.print.length > 0) {
					var winPrint = window.open('about:blank', '_blank');
					winPrint.document.write(data.print[0]);
					winPrint.document.close();
					setTimeout(function () {
						winPrint.print()
						winPrint.close()
					},150);
				}
			} else {
				swal("Success!", data.message, "success");
				$('.preloader').hide();
				$('.main-page').show();
			}
		});
	}
</script>
@endpush
@endsection
