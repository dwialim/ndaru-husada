<style>
	#opname {
		border-collapse: collapse;
		border-spacing: 0;
		width: 150%;
		border: 1px solid #ddd;
		white-space: nowrap;
	}
	#opname .thead{
		background-color: #f2f2f2
	}

	th, td {
		padding: 8px;
	}

	.txt{
		font-weight: 600;
	}

	.btn-txt{
		padding: 0;
		width: 6rem;
		font-size: 14px;
	}

	tr:nth-child(even){background-color: #f2f2f2}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="alert alert-primary" role="alert" style="border-radius:5px;">
			<!-- <div class="fas fa-exclamation-circle"></div> -->
			<div class="row">
				<div class="col-xs-1" style="">
					<i class="fas fa-exclamation-circle ml-2" style="margin:auto; font-size: 23px;"></i>
				</div>
				<div class="col-xs-11 ml-2">
					<span style="font-size: 16px;">Pastikan data sudah benar sebelum diposting. Setelah terposting, data tidak diperbolehkan diubah.</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<form class="form-save">
			<div class="card ">
				<div class="card-header bg-success">
					<h3 class="text-white">No. Stok Opname #{{$kode}}</h3>
					<input type="hidden" name="kodeSOP" value="{{$kode}}">
				</div>
				<div class="card-body">
					{{-- @csrf --}}
					<table width="100%" class="mb-2">
						<tr>
							<th>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="nama" style="font-size:15px">Cari No. Batch</label>
											<select class="js-data-example-ajax" name="noBatch" id="noBatch">
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<div class="row" style="margin-bottom: -7px;">
												<div class="col-md-12">
													<label for="nama" style="font-size:15px">Tanggal </label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<span id="showTanggal" style="font-size:14px; font-weight: 500;border-bottom: 1px dashed #7b852e"></span>
												</div>
											</div>
											<input type="hidden" name="tanggal" id="tanggal">
										</div>
									</div>
								</div>
							</th>
						</tr>
					</table>
					<div style="overflow-x:auto;">
						<table id="opname">
							<thead class="thead">
								<tr class="text-center" >
									<th>No Batch</th>
									<th>Nama</th>
									<th width="7%">Sisa Box (<span class="txt">Sistem</span>)</th>
									<th width="7%">Biji PerBox (<span class="txt">Sistem</span>)</th>
									<th width="7%">Sisa Biji (<span class="txt">Sistem</span>)</th>
									<th>Deskripsi</th>
									<th width="7%">Sisa Box (<span class="txt">Fisik</span>)<span class="text-red">*</span></th>
									<th width="7%">Biji perBox (<span class="txt">Fisik</span>)<span class="text-red">*</span></th>
									<th width="7%">Sisa Biji (<span class="txt">Fisik</span>)</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody class="text-center" id="tbodyId">
							</tbody>
						</table>
					</div>
					
					<div class="row">
						<div class="col-md-12 text-right mt-3">
							<button type="button" class="btn btn-secondary mx-2 btn-txt" id="btn-cancel">Kembali</button>
							<button type="button" class="btn btn-success btn-simpan btn-txt" id="btn-submit">Posting</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		getDate();
	});

	function getDate(){
		var date = new Date();
		var getYear = date.getFullYear();
		var getMonth = date.getMonth()+1;
		var getDate = date.getDate();
		var dateCurrentYMD = getYear+'-'+(getMonth<10 ? '0':'')+getMonth+'-'+(getDate<10 ? '0':'')+getDate;
		var dateCurrentDMY = (getDate<10 ? '0':'')+getDate+'-'+(getMonth<10 ? '0':'')+getMonth+'-'+getYear;
		$('#tanggal').val(dateCurrentYMD);
		$('#showTanggal').text(dateCurrentDMY);
	}
    
	// ONCHANGE_NO_BATCH
	$('#noBatch').change(() => {
		var data = $('#noBatch').select2('data');
		$.post('{{route("getStokBarang")}}',{id:data[0].id}).done(function(data){
			if(data.status == 'success'){
				var getData = data.data;
				var id = getData.id;
				var noBatch = getData.no_batch;
				var nama = getData.barang.nama;
				var boxSistem = getData.jumlah_box;
				var perBoxSistem = getData.jumlah_perbox;
				var hitung = boxSistem*perBoxSistem
				var stripSistem = parseInt(getData.jumlah - hitung);
				if(stripSistem != 0){
					stripSistem = stripSistem
				}else{
					stripSistem = '-'
				}

				var cekBatch = $(' input[name^="noBatch"]');
				var cekData = 0;
				for (var i = 0; i < cekBatch.length; i++) {
					if (noBatch == cekBatch[i].value) {
						cekData += 1;
					}
				}

				if(cekData==0){
					var row = `
						<tr style="width:100%;" id=rowItem${id}>
							<td>
								<input type="hidden" name="noBatch[]" value="${noBatch}"> ${noBatch}
							</td>
							<td>
								<input type='hidden' name='stok_barang_id[]' value='${id}'> ${nama}
							</td>
							<td>
								${boxSistem}
							</td>
							<td>
								${perBoxSistem}
							</td>
							<td>
								${stripSistem}
							</td>
							<td>
								<textarea class="form-control text-center" name="deskripsi[]" id="deskripsi" cols="25" rows="1" placeholder="Deskripsi (Bisa Kosong)"></textarea>
							</td>
							<td>
								<input type="text" class="form-control text-center" name="jumlahBox[]" id="jumlahBox" placeholder="Jumlah Box">
							</td>
							<td>
								<input type="text" class="form-control text-center" name="jumlahPerBox[]" id="jumlahPerBox" placeholder="Biji perBox">
							</td>
							<td>
								<input type="text" class="form-control text-center" name="sisaStrip[]" id="sisaStrip" placeholder="Sisa Biji">
							</td>
							<td style="text-align: center">
								<a href="javascript:;" class="" onclick="delStokopname(${id})"><i class="ik ik-x-square f-20 text-danger"></i></a>
							</td>
						</tr>
					`;
				}else{
					Swal.fire({
						title: 'Whoops',
						text: "No Batch Sudah Dipilih!",
						icon: 'warning',
					});
				}
				$('#select2-noBatch-container').html('');
				$("#noBatch").empty();
				$('#opname tbody').append(row);
			}
		});
	});
    
	@empty($data)
	$('#noBatch').select2({
		ajax: {
			url: "{{ route('getStokBarang') }}",
			dataType: 'json',
			type: 'POST',
			delay: 250,

			processResults: function (data) {
				return {
					results:  $.map(data, function (item) {
						return {
							text: `${item.barang.nama}`,
							id: item.id
						}
					})
				};
			},
			cache: true,
		}
	});
	@endempty
	$(document).on('keypress',function(e) {
		// console.log(e.which)
		if(e.which == 13) {
			// alert('You pressed enter!');
			console.log("ada")
		}else{}
	});
	
	$(document).on('select2:open', () => {
		document.querySelector('.select2-search__field').focus();
	});

	$(document).on('keyup','.select2-search__field',function(e){
		var self = $(this).val()
		if(self == '20906298C1'){
			// console.log("ada")
		}else{
			// console.log("gada")
		}
	})

	function delStokopname(id) {
		Swal.fire({
			title: 'Anda yakin?',
			text: " Ingin menghapus data ini!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Saya yakin!',
			cancelButtonText: 'Batal!',
		}).then((result) => {
			if (result.value == true) {
				$('#rowItem'+id).remove();
			}
		});
	}

	function formatNumber(angka) {
		return angka.toString().replace(/[^,\d]/g, "");
	}

	function cekRow(){
		var a = $('#opname tbody tr').length;
		return a;
	}

	$('#btn-submit').click(function(e) {
		e.preventDefault();
		if(cekRow()>0){
			$('#btn-submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').attr('disabled', true);
			var data = new FormData($('.form-save')[0]);
			$.ajax({
				url: "{{ route('storeStokOpname') }}",
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false
			}).done(function(data) {
				if (data.status == 'success') {
					Swal.fire({
						title: "Berhasil!",
						text: data.message,
						icon: "success",
						timer: 1000,
						showCancelButton: false,
						showConfirmButton: false
					});
					$('#tbodyId').empty();
					$('#btn-submit').html('Posted').attr('disabled',true);
				} else if (data.status == 'error') {
					$('#btn-submit').html('Posting').removeAttr('disabled');
					Swal.fire('Whoops !', data.message, 'warning');
				} else {
					var n = 0;
					for (key in data) {
						if (n == 0) {
							var dt0 = key.split(".");
							if(dt0[0] =='jumlahBox'){
								dt0 = 'Sisa Box';
							}else{
								dt0 = 'Isi perBox';
							}
						}
						n++;
					}
					$('#btn-submit').html('Posting').removeAttr('disabled');
					Swal.fire('Whoops !', 'Kolom ' + dt0 + ' Tidak Boleh Kosong !!', 'error');
				}
			}).fail(function() {
				Swal.fire("MAAF !", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
				$('#btn-submit').html('Posting').removeAttr('disabled');
			});
		}else{
			$('#btn-submit').attr('disabled',true);
			Swal.fire({
				title: "Whoops!",
				text: 'Tidak ada data untuk di Post!',
				icon: "warning",
				timer: 1200,
				showCancelButton: false,
				showConfirmButton: false
			});
			$('#btn-submit').attr('disabled',false);
		}
	});

	$('#btn-cancel').click(function(e) {
		e.preventDefault();
		$('.otherPages').fadeOut(function() {
			$('#dataTable').DataTable().ajax.reload();
			$('.otherPages').empty();
			$('.main-layer').fadeIn();
		});
	});
</script>
