<?php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
?>

<div class="row">
	<!-- end message area-->
	<div class="col-md-12">
		<div class="card ">
			<div class="card-header bg-{{ ($title=='Tambah') ? 'primary' : 'success' }}">
				<h3 class="text-white">{{$title}} Data Persentase</h3>
			</div>
			<div class="card-body">
				<form class="form-save form-save">
					<input type="hidden" name="id" value="{{ (!empty($data)) ? $data->id : '' }}">
					{{-- @csrf --}}
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nama">Nama Persentase
									<span class="text-red">*</span>
								</label>
								<input id="nama" type="text" class="form-control" name="nama" value="{{(!empty($data))?$data->nama:''}}" placeholder="Nama Untuk Harga Jual" {{(!empty($data)?'readonly':'')}} required>
								<div class="help-block with-errors"></div>
								{{-- ERROR FEEDBACK --}}
							</div>
							<div class="form-group">
								<label for="persentase">Persentase (%)
									<span class="text-red">*</span>
								</label>
								<input id="persentase" type="text" class="form-control" name="persentase" value="{{(!empty($data))?$data->persentase:''}}" placeholder="Persentase" required>
								<div class="help-block with-errors"></div>
								{{-- ERROR FEEDBACK --}}
							</div>
							<div class="form-group">
								<label for="nominal">Nominal
								</label>
								<input id="nominal" type="text" onkeyup="ubahFormat(this)" class="form-control" name="nominal" value="{{(!empty($data) && !empty($data->nominal))?rupiah($data->nominal):''}}" placeholder="Kosongkan Jika Tidak Ada Nominal">
								<div class="help-block with-errors"></div>
								{{-- ERROR FEEDBACK --}}
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<a href="#" id="btn-cancel" class="btn btn-secondary">Kembali</a>
								<button type="submit" id="btn-submit"
								class="btn btn-success">Simpan</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	var jenis = $('#jenis').val();

	$('#btn-submit').click(function(e) {
		e.preventDefault();
		// $('#btn-submit').html(
		// 	'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
		// 	).attr('disabled', true);
		var data = new FormData($('.form-save')[0]);
		// console.log(data);
		$.ajax({
			url: "{{ route('storePersentase') }}",
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
					showCancelButton: false, // There won't be any cancel button
					showConfirmButton: false // There won't be any confirm button
				});
				$('#other-layer').fadeOut(function() {
					$('#other-layer').empty();
					$('#main-layer').fadeIn();
					$('#datatables').DataTable().ajax.reload();
				});
			} else if (data.status == 'error') {
				$('#btn-submit').html('Simpan').removeAttr(
					'disabled');
				Swal.fire('Whoops !', data.message, 'warning');
			} else {
				var n = 0;
				for (key in data) {
					if (n == 0) {
						var dt0 = key;
					}
					n++;
				}
				$('#btn-submit').html('Simpan').removeAttr(
					'disabled');
				Swal.fire('Whoops !', 'Kolom ' + dt0 + ' Tidak Boleh Kosong !!', 'error');
			}
		}).fail(function() {
			Swal.fire("MAAF !", "Terjadi Kesalahan, Silahkan Ulangi Kembali !!", "warning");
			$('#btn-submit').html('Simpan').removeAttr(
				'disabled');
		});
	});

	function ubahFormat(v){
		$(v).val(formatRupiah(v.value,'Rp. '));
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

	$('#btn-cancel').click(function(e) {
		e.preventDefault();
		$('#other-layer').fadeOut(function() {
			$('#other-layer').empty();
			$('#main-layer').fadeIn();
		});
	});
</script>
