@extends('layouts.main')

@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
<style type="text/css">
	div.dataTables_wrapper {
		width: 96%;
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
				<div class="card-header row">
					<div class="col-md-4">
						<button type="button" class="btn btn-sm btn-primary btn-add">
							<i class="fa fa-plus"></i> Tambah {{$page}}
						</button>
					</div>
					<div class="col-md-4">
						<p class="float-right mt-3">Total Harga Piutang: <span id="sumHarga"></span></p>
					</div>
					<div class="col-md-4">
						<p class="float-right mt-3">Total Pajak Piutang: <span id="sumPajak"></span></p>
					</div>
				</div>
				<div class="card-body">
					<div></div>
					<div class="row mb-4 ml-3">
						<div class="col-md-8 showDateRange">
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
						<div class="col-sm-2" style="display: flex; justify-content: center;">
							<div class="row">
								<div class="col-xs-12">
									<button class="btn btn-info btn-sm btn-search" title="Cari" onclick="searchData()"><i class="fas fa-search"></i></button>
									<button class="btn btn-success btn-sm btn-refresh" title="Refresh" onclick="refreshTable()"><i class="fas fa-sync"></i></button>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="row">
								<div class="col-xs-12">
									<a href="javascript:void(0)" onclick="myPrint()" id="exportButton"class="btn btn-primary hidden-print">
										<i class="fa fa-file-export fs-14 m-r-10"></i> <b>Export Laporan</b>
									</a>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="table-responsive"> -->
						<table id="dataTable" class="table table-striped dataTable display nowrap" style="width: 100%;">
							<thead>
								<tr class="text-center">
									<th> No </th>
									<th> No. Registrasi </th>
									<th> No. Faktur </th>
									<th> Total Harga </th>
									<th> Materai </th>
									<th> Pajak Wajib Dibayar</th>
									<th> Jatuh Tempo </th>
									<th> Status </th>
									<th> Nama PBF </th>
									<th> Alamat PBF </th>
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

<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Upload Bukti Pembayaran</h4>
			</div>
			<div class="modal-body">
				<form id="formUpload">
					<input type="file" name="uploadBuktiPembayaran" id="uploadBuktiPembayaran">
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default closeBukti" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary saveBukti">Simpan</button>
			</div>
		</div>
	</div>
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
				$('#dataTable').DataTable().destroy();
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

		function myPrint() {
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();
			if(startDate && endDate){
				if(startDate<=endDate){
					$.post("{!! route('printPembelianObat') !!}",{startDate:startDate,endDate:endDate},function(data){
						var newWin = window.open('', 'Print-Window');
						newWin.document.open();
						newWin.document.write('<html><head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"></head><body>'+data.content+'</body></html>');
						setTimeout(() => {
							newWin.document.close();
							newWin.close();
						}, 3000);
					});
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
			console.log(startDate);
		}

		function loadData(startDate='', endDate=''){
			var dataTable = $('#dataTable').DataTable({
				scrollX: true,
				serverSide: true,
				processing: true,
				ajax: {
					url: "{{route('getBeliObat')}}",
					type: "POST",
					data: {
						startDate: startDate,
						endDate: endDate,
					}
				},
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex',render:function(data){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'
					}},
					{data: 'no_registrasi', name: 'no_registrasi',render:function(data){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'
					}},
					{data: 'no_faktur_pbf', name: 'no_faktur_pbf',render:function(data){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'
					}},
					{data: 'total_pembelian', name: 'total_pembelian',render:function(data){
						return '<p class="text-center" style="margin:0;">'+formatRupiah(data,"Rp. ")+'</p>'
					}},
					{data: 'materai', name: 'materai',render:function(data){
						return cekMaterai(data)
					}},
					{data: 'pajak', name: 'pajak',render:function(data){
						return bayarPajak(data)
					}},
					{data: 'jatuh_tempo', name: 'jatuh_tempo',render:function(data){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'
					}},
					{data: 'statusPiutang', name: 'statusPiutang',render:function(data,type,row){
						return '<p style="margin:0;">'+data+'</p>';
					}},
					{data: 'pbf.nama', name: 'pbf.nama',render:function(data){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'
					}},
					{data: 'pbf.alamat', name: 'pbf.alamat',render:function(data){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'
					}},
					{data: 'action', name: 'action',render:function(data,type,row){
						return '<p class="text-center" style="margin:0;">'+data+'</p>'}, orderable: false, searchable: true},
				]
			});

			// var dataTable = $('#datatables').DataTable().ajax.reload();
			dataTable.on( 'xhr', function () {
				var harga = dataTable.ajax.json().sumHarga;
				var pajak = dataTable.ajax.json().sumPajak;
				$('#sumHarga').text(formatRupiah(harga,'Rp. '));
				$('#sumPajak').text(formatRupiah(pajak,'Rp. '));
			});
		}

		function cekMaterai(data){
			if(data == 0 || data == null){
				return '<p class="text-center" style="margin:0;"> - </p>'
			}else{
				return '<p class="text-center" style="margin:0;">'+formatRupiah(data,"Rp. ")+'</p>'
			}
		}

		function bayarPajak(data){
			if(data == 0){
				return '<p class="text-center" style="margin:0;"> - </p>';
			}else{
				return '<p class="text-center" style="margin:0;">'+formatRupiah(data,"Rp. ")+'</p>';
			}
		}

		// function statusPiutang(data){
		// 	var data = data;
		// 	if(data == 0){
		// 		showStatus = '<span class="badge badge-pill badge-success">Lunas</span>'
		// 	}else{
		// 		showStatus = '<span class="badge badge-pill badge-warning">Piutang</span>'
		// 	}
		// 	return showStatus;
		// }

		$('.btn-add').click(function(){
			$('.main-layer').hide();
			$.post("{{route('formBeliObat')}}").done(function(data){
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
				if(result.isConfirmed==true){
					$.post("{!! route('deleteFaktur') !!}",{id:id}).done(function(data){
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
				}else{}
			});
		}

		function uploaded(id) {
			var id = id;
			$('#myModal').modal({backdrop: 'static', keyboard: false});
			$('.closeBukti').click(function(){
				$('#uploadBuktiPembayaran').val('');
			});

			$('.saveBukti').click(function(){
				var img = $('input[name=uploadBuktiPembayaran]')[0].files[0];
				if(img==undefined){
					Swal.fire({
						title: 'whoops',
						text: 'Tidak ada gambar yang dipilih!',
						icon: 'error',
						showConfirmButton: true
					});
				}else{
					var data = new FormData($('#formUpload')[0]);
					data.append('id',id);
					$.ajax({
						url: "{{route('storeBukti')}}",
						type: "POST",
						data: data,
						async: true,
						cache: false,
						contentType: false,
						processData: false,
						success: function(data){
							Swal.fire({
								title: data.status,
								text: data.message,
								icon: 'success',
								showConfirmButton: false,
								timer: 1200,

							});
						}
					});
					$('#myModal').modal('toggle');
					$('#uploadBuktiPembayaran').val('');
					$('#dataTable').DataTable().ajax.reload();
				}
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
			var fullDateCurrent = yearCurrent + '-' + (monthCurrent<10 ? "0":"")+monthCurrent + '-' +(dateCurrent<10?"0":"")+dateCurrent;

			// get (only date) expired
			var datesExpired = new Date(expDate);
			var yearExpired = datesExpired.getFullYear();
			var monthExpired = datesExpired.getMonth()+1;
			var dateExpired = datesExpired.getDate();
			var fullDateExpired = yearExpired + '-' + (monthExpired<10 ? "0":"")+monthExpired + '-' +(dateExpired<10?"0":"")+dateExpired;

			//(24 x 60 x 60 x 1000 == satu hari atau 24 jam) set tanggal currnetDate untuk status AMAN(sudah dikurangi 90 hari)
			var aman = currentDate.setTime(expDate.getTime()-(3*30*24*60*60*1000));

			// get (only date) from variabel aman
			var datesAman = new Date(aman);
			var yearAman = datesAman.getFullYear();
			var monthAman = datesAman.getMonth()+1;
			var dateAman = datesAman.getDate();
			var fullDateAman = yearAman + '-' + (monthAman<10 ? "0":"")+monthAman + '-' +(dateAman<10?"0":"")+dateAman;

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