@extends('layouts.main')

@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush

@section('title', 'Data Obat')
@section('content')
<div class="container-fluid">
	<div class="main-layer">
		<div class="page-header">
			<div class="row align-items-end">
				<div class="col-lg-8">
					<div class="page-header-title">
						{{-- <i class="ik ik-users bg-blue"></i> --}}
						<div class="d-inline">
							<h5>{{$page}}</h5>
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
								<a href="javascript:">{{$page}}</a>
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<button type="button" class="btn btn-sm btn-primary btn-add">
						<i class="fa fa-plus"></i> Tambah {{$page}}
					</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="dataTable" class="table table-striped dataTable ml-0" style="width: 80rem;">
							<thead>
								<tr class="text-center">
									<th> No </th>
									<th> No. Registrasi </th>
									<th> No. Faktur </th>
									<th> Total Harga </th>
									<th> Jatuh Tempo </th>
									<th> Nama PBF </th>
									<th> Alamat PBF </th>
									<th> Action </th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="otherPages"></div>
</div>
@endsection

@push('script')
	<script type="text/javascript">
		var dataTable = $('#dataTable').dataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "{{route('getBeliRetail')}}",
				type: "POST",
			},
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'faktur.no_registrasi', name: 'noRegistrasi',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'faktur.no_faktur_pbf', name: 'noFaktur',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'faktur.total_pembelian', name: 'totalPembelian',render:function(data){
					return '<p class="text-center">'+formatRupiah(data,"Rp. ")+'</p>'
				}},
				{data: 'faktur.jatuh_tempo', name: 'jatuhTempo',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'faktur.pbf.nama', name: 'namaPBF',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'faktur.pbf.alamat', name: 'tgl_masuk',render:function(data){
					return '<p class="text-center">'+data+'</p>'
				}},
				{data: 'action', name: 'action',render:function(data,type,row){
					return '<p class="text-center">'+data+'</p>'}, orderable: false, searchable: false},
			]
		});

		$('.btn-add').click(function(){
			$('.main-layer').hide();
			$.post("{{route('formBeliRetail')}}").done(function(data){
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
			$.post("{{route('formBeliRetail')}}", {
				idFaktur:id
			}).done(function(data){
				if (data.status == 'success') {
					$('.otherPages').html(data.content).fadeIn();
				} else {
					$('.main-layer').show()
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
				// closeOnConfirm: true,
			}).then((result) => {
				$.post("{!! route('deleteStokBarang') !!}",{id:id}).done(function(data){
					if(data.status == 'success'){
						Swal.fire({
							title: data.title,
							text: data.message,
							icon: 'success',
							showConfirmButton: false,
							timer: 1200,
						});
						$('#dataTable').DataTable().ajax.reload();
					} else{
						Swal.fire({
							title: data.title,
							text: data.message,
							icon: 'error',
							showConfirmButton: false,
							timer: 1200,
						});
						$('#dataTable').DataTable().ajax.reload();
					}
				});
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