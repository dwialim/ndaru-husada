@extends('layouts.main')
@section('titlhbs', 'Data Laporan Hampir Habis')
@push('head')
<style type="text/css">
	div.dataTables_wrapper {
		width: 95%;
		margin: 0 auto;
	}
</style>
@endpush
@section('content')

	<div class="col-md-12">
		<div class="card">
			<div class="card-header row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-4">
							<div class="row">
								<label class="col-form-label col-md-5">Kategori</label>
								<select id="kategori" class="form-control col-md-7">
									<option value="today" selected>Hari Ini</option>
									<option value="between">Antar Tanggal</option>
									<option value="month">Bulan</option>
								</select>
							</div>
						</div>

						<div class="col-sm-4 showDate">
							<div class="row">
								<label class="col-form-label col-md-3">Tanggal</label>
								<div class="col-md-9">
									<input type="date" name="dateToday" id="dateToday" class="input-sm form-control">
								</div>
							</div>
						</div>

						<div class="col-sm-6 showDateRange" style="display:none;">
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-4">Tanggal</label>
										<div class="col-md-8">
											<input type="date" name="startDate" id="startDate" class="input-sm form-control">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3">s/d</label>
										<div class="col-md-9">
											<input type="date" name="endDate" id="endDate" class="input-sm form-control">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4 showMonth" style="display:none;">
							<div class="row">
								<label class="col-form-label col-md-3">Bulan</label>
								<div class="col-md-9">
									<input type="month" name="onlyMonth" id="onlyMonth" class="input-sm form-control">
								</div>
							</div>
						</div>

						<div class="col-sm-2" style="margin-top: 2px;">
							<button class="btn btn-info btn-sm btn-search" title="Cari" onclick="searchData()"><i class="fas fa-search"></i></button>
							<button class="btn btn-success btn-sm btn-refresh" title="Refresh" onclick="refreshTable()"><i class="fas fa-sync"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table id="laporanStok" class="table table-striped dataTable display nowrap" style="width: 100%;">
					<thead>
						<tr>
							<th> No </th>
							<th> Nama Barang </th>
							<th> Satuan </th>
							<!-- <th> Jenis </th> -->
							<th> ED </th>
							<th> Sisa Stock </th>
							<th> Status </th>
						</tr>
					</thead>
					{{--<tbody>
						@foreach ($laporanhampirhbs as $key => $le)
						@foreach($le->stok_barang as $p => $a)
						<tr>
							<td id="leId"> {{ $le->id }} </td>
							<td> {{ $le->nama }} </td>
							<td> {{ $le->satuan->nama ?? 'Null'}} </td>
							<td> {{ $le->jenis }} </td>
							<td> {{ $a->expired }} </td>
							<td> {{ $a->jumlah }} </td>
							@if ($a->jumlah > 21)
							<td> Aman </td>
							@elseif ( $a->jumlah <= 0)
							<td> Kosong  </td>
							@elseif ( $a->jumlah <= 20)
							<td> Hampir Habis </td>
							@endif
						</tr>
						@endforeach
						@endforeach
					</tbody>--}}
				</table>
			</div>
		</div>
	</div>

@push('script')
	<!-- <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
	<script src="{{ asset('plugins/amcharts/gauge.js') }}"></script>
	<script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
	<script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>
	<script src="{{ asset('plugins/amcharts/animate.min.js') }}"></script>
	<script src="{{ asset('plugins/amcharts/pie.js') }}"></script>
	<script src="{{ asset('plugins/ammap3/ammap/ammap.js') }}"></script>
	<script src="{{ asset('plugins/ammap3/ammap/maps/js/usaLow.js') }}"></script>
	<script src="{{ asset('js/product.js') }}"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
	<script>
		$(document).ready(function(){
			loadData();
			$('#kategori').change(function(){
				var kategori = $('#kategori').val();
				if(kategori=='today'){
					$('.showDate').show();
					$('.showDateRange').hide();
					$('.showMonth').hide();

					$('#startDate').val('');
					$('#endDate').val('');
					$('#onlyMonth').val('');
				}else if(kategori=='between'){
					$('.showDate').hide();
					$('.showDateRange').show();
					$('.showMonth').hide();

					$('#dateToday').val('');
					$('#onlyMonth').val('');
				}else{
					$('.showDate').hide();
					$('.showDateRange').hide();
					$('.showMonth').show();

					$('#dateToday').val('');
					$('#startDate').val('');
					$('#endDate').val('');
				}
			});
		});

		function searchData(){
			$('.btn-search').attr('disabled',true);
			var kategori = $('#kategori').val();
			var dateToday = $('#dateToday').val(); //hari ini
			var startDate = $('#startDate').val();// range awal tanggal
			var endDate = $('#endDate').val();// range akhir tanggal
			var onlyMonth = $('#onlyMonth').val(); //bulan
			if(kategori=='today' && dateToday){
				$('#laporanStok').DataTable().destroy();
				loadData(kategori);
			}else if(kategori=='between' && startDate && endDate){
				$('#laporanStok').DataTable().destroy();
				loadData(kategori);
			}else if(kategori=='month' && onlyMonth){
				$('#laporanStok').DataTable().destroy();
				loadData(kategori);
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
			$('.btn-refresh').attr('disabled', true);
			$('#laporanStok').DataTable().destroy();
			loadData();
			$('#dateToday').val('');
			$('#startDate').val('');
			$('#endDate').val('');
			$('#onlyMonth').val('');
			$('.btn-refresh').attr('disabled', false);
		}

		function loadData(kategori=''){
			var dateToday = $('#dateToday').val(); //hari ini

			// range tanggal
			var startDate = $('#startDate').val();
			var endDate = $('#endDate').val();

			var onlyMonth = $('#onlyMonth').val(); //bulan
			$('#laporanStok').dataTable({
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
				columnDefs: [{
					orderable: false,
					targets: -1
				}],
				scrollX: true,
				processing: true,
				serverSide: true,
				ajax: {
					url: "{{route('getLaporanHampirhbsList')}}",
					data: {
						dateToday: dateToday,
						startDate: startDate,
						endDate: endDate,
						onlyMonth: onlyMonth,
						kategori: kategori,
					},
				},
				columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex'},
					{data: 'barang.nama', name: 'namaBarang'},
					{data: 'barang.satuan.nama', name: 'namaSatuan'},
					{data: 'expired', name: 'expired'},
					{data: 'jumlah', name: 'jumlah'},
					{data: '', name: 'status',render: function(data,type,row){
						return status(row.jumlah,row.minimal_stok);
					}},
				],
				drawCallback: function() {
					var hasRows = this.api().rows({
						filter: 'applied'
					}).data().length > 0;
					$('.buttons-excel')[0].style.visibility = hasRows ? 'visible' : 'hidden'
				},
			});
		}

		function status(jumlah,minStok){
			if(jumlah>0 && jumlah<=minStok){
				showSt = '<span class="badge badge-pill badge-warning">Hampir Habis</span>';
			}else{
				showSt = '<span class="badge badge-pill badge-danger">Habis</span>';
			}
			return showSt;
		}
	</script>
@endpush
@endsection