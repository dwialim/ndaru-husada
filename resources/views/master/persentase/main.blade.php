@extends('layouts.main')
@section('title', 'Persentase')
@push('head')
<style type="text/css">
	/*div.dataTables_wrapper {
		width: 95%;
		margin: 0 auto;
	}*/
</style>
@endpush
@section('content')
	<div class="container-fluid">
		<div class="page-header">
			<div class="row align-items-end">
				<div class="col-lg-8">
					<div class="page-header-title">
						<div class="d-inline">
							<h5>Data Persentase</h5>
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
								<a href="javascript:void(0)">Persentase</a>
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
						<!-- <div class="card-header">
							<a href="javascript:void(0)" id="addPersentase" class="btn btn-primary">Tambah Persentase Baru</a>
						</div> -->
						<div class="card-body">
							<table id="datatables" class="table table-striped dataTable display nowrap" style="width: 100%;">
								<thead>
									<tr class="text-center">
										<th>No.</th>
										<th>Nama</th>
										<th>Persentase (%)</th>
										<th>Nominal</th>
										<th>Option</th>
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
			// Sementara tidak digunakan
				var dTable = $('#datatables').DataTable({
					scrollX: true,
					serverSide: true,
					processing: true,
					ajax: {
						type: 'post',
						url: "{{ route('dataTbMstPersentase') }}",
					},
					columns: [
						{ data: 'DT_RowIndex', name: 'DT_RowIndex'},
						{ data: 'nama', name: 'nama'},
						{ data: 'persentase', name: 'persentase',render:function(data){
							return '<p class="text-center">'+data+'%</p>';
						}},
						{ data: 'nominal', name: 'nominal',render:function(data){
							return nominal(data);
						}},
						{ data: 'action', name: 'action',render:function(data){
							return '<p class="text-center">'+data+'</p>';
						}, orderable: false, searchable: false },
					],
				});

			// CREATE PERSENTASE
			$('#addPersentase').click(function(){
				$('#main-layer').hide();
				$.post("{{ route('formPersentase') }}").done(function(data){
					if (data.status == 'success') {
						$('#other-layer').html(data.content).fadeIn();
					} else {
						$('#main-layer').show();
					}
				});
			});
		});

		function nominal(data){
			if(data){
				showNominal = '<p class="text-center">'+formatRupiah(data,"Rp. ")+'</p>'
			}else{
				showNominal = '<p class="text-center">-</p>'
			}
			return showNominal;
		}

		function updated(id){
			$('#main-layer').hide();
			$.ajax({
				type: "POST",
				url: "{{ route('formPersentase') }}",
				data: {
					id: id,
				},
			}).done(function(data){
				if (data.status == 'success') {
					$('#other-layer').html(data.content).fadeIn();
				} else {
					$('#main-layer').show();
				}
			});
		}

		function deleted(id){
			Swal.fire({
				title: "Apakah Anda yakin?",
				text: "Data yang dihapus tidak dapat dikembalikan lagi, Dan dapat mempengaruhi data yang bersangutan lainnya.",
				type: "warning",
				showCancelButton: true,
				showCloseButton: true,
				icon: 'warning',
				cancelButtonText: 'Batal',
				confirmButtonText: 'Hapus',
			}).then((result) => {
				if (result.value) {
					Swal.fire("", "Data berhasil dihapus!", "success");
					$.post("{{ route('delete-pajak') }}",{id:id}).done(function(data) {
						if (data == 'true') {
							Swal.fire("", "Data berhasil dihapus!", "success");
						}
						// $('#datatables').DataTable().ajax.reload();
					}).fail(function() {
						Swal.fire("Sorry!", "Gagal menghapus data!", "error");
					});
				} else if (result.dismiss === Swal.DismissReason.cancel) {
					Swal.fire("Batal", "Data batal dihapus!", "error");
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
@endsection
