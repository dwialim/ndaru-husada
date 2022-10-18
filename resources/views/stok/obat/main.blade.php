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
                            <button type="button" class="btn btn-warning mx-4" data-toggle="modal" data-target="#importExcel">
                                <i class="fa fa-file-excel"></i> IMPORT EXCEL
                            </button>
                            <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="post" id="importexcelsave" enctype="multipart/form-data">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                                            </div>
                                            <div class="modal-body">

                                                    <label>Pilih file excel</label>
                                                    <div class="form-group">
                                                            <input type="file" name="file_excel" required="required">
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" id="btn-save-excel" class="btn btn-primary">Import</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
				</div>
				<div class="card-body">
					<!-- <div class="table-responsive"> -->
						<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
							<thead>
								<tr class="text-center">
									<th> No </th>
									<th> Nama Barang </th>
									<th> Satuan </th>
									<th> QR/BarCode </th>
									<th> No.Bacth </th>
									<th> Harga Beli </th>
									<th> Harga Umum </th>
									<th> Harga Resep </th>
									<th> Harga Dispensing perBox </th>
									<th> Harga Dispensing perBiji</th>
									<th> Expired </th>
									<th> Tgl Masuk </th>
									<th> Stock Awal (Biji)</th>
									<th> Sisa Stock (Biji)</th>
									<th> Status </th>
									<th> Action </th>
								</tr>
							</thead>
						</table>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
	<div class="otherPages"></div>
</div>
@endsection

@push('script')
	<script type="text/javascript">
		$('#btn-save-excel').click(function (e) {
			e.preventDefault();
			var data  = new FormData($('#importexcelsave')[0]);
			$('#btn-save-excel').text('Sending...');
			$.ajax({
				url: "{{ route('import-master-stok') }}",
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data){
				if(data.status == 'success'){
					Swal.fire("Success", "Excel Berhasil Diimport", "success");
					$('#datatables').DataTable().ajax.reload();
					$('#importExcel').modal('hide');
					$('#btn-save-excel').text('Import');
					$('#importexcelsave')[0].reset();
				};
			})
		});

			var dataTable = $('#dataTable').dataTable({
				scrollX: true,
				processing: true,
				serverSide: true,
				ajax: {
					url: "{{route('getStokObat')}}",
					type: "POST",
				},
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex'},
					{data: 'barang.nama', name: 'barang.nama'},
					{data: 'barang.satuan.nama', name: 'barang.satuan.nama'},
					{data: 'barcode', name: 'barcode', render: function(data,type,row){
						return '<p class="text-center" style="margin:0;">'+(data??"-")+'</p>'
					}},
					{data: 'no_batch', name: 'no_batch'},
					{data: 'harga_beli', name: 'harga_beli',render: function (data, type, row) {
						return formatRupiah(data,"Rp. ");}},
					{data: 'harga_umum', name: 'harga_umum',render: function (data, type, row) {
						return formatRupiah(data,"Rp. ");}},
					{data: 'harga_resep', name: 'harga_resep',render: function (data, type, row) {
						return formatRupiah(data,"Rp. ");}},
					{data: 'harga_dispensing', name: 'harga_dispensing',render: function (data, type, row) {
						return formatRupiah(data,"Rp. ");}},
						{data: 'harga_dispensing_perbiji', name: 'harga_dispensing_perbiji',render: function (data, type, row) {
						return formatRupiah(data,"Rp. ");}},
					{data: 'expired', name: 'expired'},
					{data: 'tgl_masuk', name: 'tgl_masuk'},
					{data: 'stok_awal', name: 'stok_awal'},
					{data: 'jumlah', name: 'jumlah'},
					{data: '', name: 'status',render: function(data,type,row){
						return status(row.expired,row.jumlah,row.minimal_stok);}},
					{data: 'action', name: 'action', orderable: false, searchable: false},
				]
			});

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
				// closeOnConfirm: true,
			}).then((result) => {
				if (result.isConfirmed == true){
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
				}
			});
		}

		function status(tanggal,stok,minStok){
			var stok = parseInt(stok);
			var minStok = parseInt(minStok);
			var expDate = new Date(tanggal);
			var currentDate = new Date();

			// get (only date) sekarang
			var dateCurrent = new Date(currentDate);
			var yearCurrent = dateCurrent.getFullYear();
			var monthCurrent = dateCurrent.getMonth()+1;
			var dateCurrent = dateCurrent.getDate();
			var fullDateCurrent = yearCurrent + '-' + (monthCurrent<10 ? "0":"")+monthCurrent + '-' +(dateCurrent<10?"0":"")+dateCurrent;

			// get (only date) expired
			var datesExpired = new Date(expDate);
			var yearExpired = datesExpired.getFullYear();
			var monthExpired = datesExpired.getMonth()+1;
			var dateExpired = datesExpired.getDate();
			var fullDateExpired = yearExpired + '-' + (monthExpired<10 ? "0":"")+monthExpired + '-' +(dateExpired<10?"0":"")+dateExpired;

			//(24 x 60 x 60 x 1000 == satu hari atau 24 jam) set tanggal currnetDate untuk status AMAN(tanggal expired dikurangi 180 hari)
			// tipe data masih timestamp
			var aman = currentDate.setTime(expDate.getTime()-(6*30*24*60*60*1000));

			// get (only date) from variabel aman (convert timestamp to date)
			var datesAman = new Date(aman);
			var yearAman = datesAman.getFullYear();
			var monthAman = datesAman.getMonth()+1;
			var dateAman = datesAman.getDate();
			var fullDateAman = yearAman + '-' + (monthAman<10 ? "0":"")+monthAman + '-' +(dateAman<10?"0":"")+dateAman;

			if(fullDateCurrent<=fullDateAman){
				if (stok>minStok) {
					showSt = `
						<p class="text-center" style="margin-bottom:0px;">
							<span class="badge badge-pill badge-success">Aman</span>
						</p>
					`;
				}else if(stok>0 && stok<=minStok){
					showSt = `
						<p class="text-center" style="margin-bottom:0px;">
							<span class="badge badge-pill badge-warning">Hampir Habis</span>
						</p>
					`;
				}else{
					showSt = `
						<p class="text-center" style="margin-bottom:0px;">
							<span class="badge badge-pill badge-danger">Stok Habis</span>
						</p>
					`;
				}
			}else if(fullDateCurrent<fullDateExpired){
				if (stok>=minStok) {
					showSt = `
						<p class="text-center" style="margin-bottom:0px;">
							<span class="badge badge-pill badge-warning">Hampir Expired</span>
						</p>
					`;
				}else if(stok>0 && stok<minStok){
					showSt = `
						<p class="text-center" style="margin-bottom:0px;">
							<span class="badge badge-pill badge-warning">Hampir Expired & Hampir Habis</span>
						</p>
					`;
				}else{
					showSt = `
						<p class="text-center" style="margin-bottom:0px;">
							<span class="badge badge-pill badge-danger">Hampir Expired & Stok Habis</span>
						</p>
					`;
				}
			}else{
				showSt = `
					<p class="text-center" style="margin-bottom:0px;">
						<span class="badge badge-pill badge-danger">Expired</span>
					</p>
				`;
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