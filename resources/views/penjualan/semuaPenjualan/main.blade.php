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
							<h5>Semua {{$page}}</h5>
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
								<a href="javascript:">Semua {{$page}}</a>
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<a class="btn btn-sm btn-primary" href="{{route('penjualan')}}">
						<i class="fa fa-plus"></i> Tambah {{$page}}
					</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="dataTable" class="table table-striped dataTable ml-0" style="width: 70rem;">
							<thead>
								<tr class="text-center">
									<th> No </th>
									<th> Tanggal Penjualan </th>
									<th> Nama User </th>
									<th> Level User </th>
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
	// var dataTable = $('#dataTable').dataTable({
	// 	processing: true,
	// 	serverSide: true,
	// 	ajax: {
	// 		url: "{{route('dataTbAllPenjualan')}}",
	// 		type: "POST",
	// 	},
	// 	columns: [
	// 		{data: 'DT_RowIndex', name: 'DT_RowIndex'},
	// 		{data: 'barang.nama', name: 'nama'},
	// 		{data: 'no_batch', name: 'no_batch'},
	// 		{data: 'harga_beli', name: 'harga_beli',render: function (data, type, row) {
	// 			return formatRupiah(data,"Rp. ");}},
	// 		{data: 'action', name: 'action', orderable: false, searchable: false},
	// 	]
	// });

	$('.btn-add').click(function(){
		$('.main-layer').hide();
		$.post("{{route('formStokObat')}}").done(function(data){
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
		$.post("{{route('formStokObat')}}", {
			id_stok_barang:id
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

	function status(tanggal,stok){
		var stok = stok;
		var expDate = new Date(tanggal);
		var currentDate = new Date();

		// get (only date) sekarang
		var dateCurrent = new Date(currentDate);
		var yearCurrent = dateCurrent.getFullYear();
		var monthCurrent = dateCurrent.getMonth()+1;
		var dateCurrent = dateCurrent.getDate();
		// split tanggal format (Y-m-d)
		var fullDateCurrent = yearCurrent+'-'+(monthCurrent<10?"0":"")+monthCurrent+'-'+(dateCurrent<10?"0":"")+dateCurrent;

		// get (only date) expired
		var datesExpired = new Date(expDate);
		var yearExpired = datesExpired.getFullYear();
		var monthExpired = datesExpired.getMonth()+1;
		var dateExpired = datesExpired.getDate();
		// split tanggal format (Y-m-d)
		var fullDateExpired = yearExpired+'-'+(monthExpired<10?"0":"")+monthExpired+'-'+(dateExpired<10?"0":"")+dateExpired;

		//(24 x 60 x 60 x 1000 == satu hari atau 24 jam) set tanggal currnetDate untuk status AMAN(tgl expired dikurangi 90 hari)
		var aman = currentDate.setTime(expDate.getTime()-(3*30*24*60*60*1000));

		// get (only date) from variabel aman
		var datesAman = new Date(aman);
		var yearAman = datesAman.getFullYear();
		var monthAman = datesAman.getMonth()+1;
		var dateAman = datesAman.getDate();
		// split tanggal format (Y-m-d)
		var fullDateAman = yearAman+'-'+(monthAman<10?"0":"")+monthAman+'-'+(dateAman<10?"0":"")+dateAman;

		if(fullDateCurrent<=fullDateAman){
			if (stok>20) {
				showSt = '<span class="badge badge-pill badge-success">Aman</span>';
			}else if(stok<=20){
				showSt = '<span class="badge badge-pill badge-warning">Hampir Habis</span>';
			}else{
				showSt = '<span class="badge badge-pill badge-danger">Stok Habis</span>';
			}
		}else if(fullDateCurrent<fullDateExpired){
			if (stok>20) {
				showSt = '<span class="badge badge-pill badge-warning">Hampir Expired</span>';
			}else if(stok<=20){
				showSt = '<span class="badge badge-pill badge-warning">Hampir Expired & Hampir Habis</span>';
			}else{
				showSt = '<span class="badge badge-pill badge-danger">Hampir Expired & Stok Habis</span>';
			}
		}else{
			showSt = '<span class="badge badge-pill badge-danger">Expired</span>';
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