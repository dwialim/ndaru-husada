<div class="row">
    <!-- end message area-->
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header bg-{{ (!empty($data)) ? 'primary' : 'success' }}">
                @empty($data)
                    <h3 class="text-white">{{ __('Tambah Data PBF Baru') }}</h3>
                @else
                    <h3 class="text-white">{{ __('Edit Data PBF') }}</h3>
                @endempty
            </div>
            <div class="card-body">
                <form class="form-save form-save">
                    {{-- @csrf --}}
                    <input type="hidden" name="id" value="{{ (!empty($data)) ? $data->id : '' }}">
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="form-group">
                                <label for="nama">{{ __('Nama PBF') }}</label>
                                <input id="nama" type="text" class="form-control"
                                    name="nama" value="{{ (!empty($data)) ? $data->nama : '' }}" placeholder="Nama PBF" required>
                                <div class="help-block with-errors"></div>
                                {{-- ERROR FEEDBACK --}}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="alamat">{{ __('Alamat Lengkap') }}</label>
                                <input id="alamat" type="text" class="form-control" name="alamat" value="{{ (!empty($data)) ? $data->alamat : '' }}"
                                    placeholder="Alamat Lengkap" required>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="provinsi">{{ __('Provinsi') }}</label>
                                <select class="form-control select2" id="provinsi" name="provinsi">
                                    <option value="">{{ __('--Pilih Provinsi--') }}</option>
                                    @foreach ($provinsi as $p)
                                        @empty(!$data)
                                        @if($data->provinsi_id != null || $data->provinsi_id !="")
                                            @if ($data->provinsi->id == $p->id)
                                                <option value="{{ $p->id }}" selected>{{ $p->nama }}</option>
                                            @else
                                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                            @endif
                                        @endif
                                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                        @endempty
                                    @endforeach
                                </select>
                                <div class="help-block with-errors"></div>
                                {{-- ERROR FEEDBACK --}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="kabupaten">{{ __('Kabupaten / Kota') }}</label>
                                <select class="form-control select2" id="kabupaten" name="kabupaten" {{ (empty($data)) ? 'disabled' : '' }}>
                                    <option value="">{{ __('--Pilih Kab. / Kota--') }}</option>
                                    @empty(!$data)
                                        @foreach ($kabupaten as $p)
                                            @if($data->kabupaten_id != null || $data->kabupaten_id != "")
                                                @if ($data->kabupaten->id == $p->id)
                                                    <option value="{{ $p->id }}" selected>{{ $p->nama }}</option>
                                                @else
                                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endempty
                                </select>
                                <div class="help-block with-errors"></div>
                                {{-- ERROR FEEDBACK --}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="kecamatan">{{ __('Kecamatan') }}</label>
                                <select class="form-control select2" id="kecamatan" name="kecamatan" {{ (empty($data)) ? 'disabled' : '' }}>
                                    <option value="">{{ __('--Pilih Kecamatan--') }}</option>
                                    @empty(!$data)
                                        @foreach ($kecamatan as $p)
                                        @if($data->kecamatan_id != null || $data->kecamatan_id != "")
                                            @if ($data->kecamatan->id == $p->id)
                                                <option value="{{ $p->id }}" selected>{{ $p->nama }}</option>
                                            @else
                                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                            @endif
                                        @endif
                                        @endforeach
                                    @endempty
                                </select>
                                <div class="help-block with-errors"></div>
                                {{-- ERROR FEEDBACK --}}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="email">{{ __('Email')}}</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ (!empty($data)) ? $data->email : '' }}" placeholder="Alamat Email" required>
                                <div class="help-block with-errors" ></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="no_telpon">{{ __('Nomor Telepon') }}</label>
                                <input id="no_telpon" type="text" class="form-control" name="no_telpon" value="{{ (!empty($data)) ? $data->no_telpon : '' }}"
                                    placeholder="Nomor Telepon" required>
                                <div class="help-block with-errors"></div>
                                {{-- ERROR FEEDBACK --}}

                            </div>
                        </div>
                        <div class="col-sm-6">
                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="#" id="btn-cancel" class="btn btn-secondary">Kembali</a>
                                <button type="submit" id="btn-submit"
                                    class="btn btn-success">{{ __('Simpan') }}</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#provinsi').select2();
		$('#jenis').select2();
        @empty(!$data)
		    $('#kabupaten').select2();
		    $('#kecamatan').select2();
        
        @endempty
	});

    // TRIGGER KABUPATEN
    $('#provinsi').change(function () {
		var id = $('#provinsi').val();
		$.post("{!! route('getKabupaten') !!}",{id:id}).done(function(data){
			if(data.length > 0){
				var kabupaten = '<option>--Pilih Kabupaten--</option>';
				$.each(data, function(k,v){
					kabupaten += '<option value="'+v.id+'">'+v.nama+'</option>';
				});

				$('#kabupaten').html(kabupaten);
				$('#kabupaten').removeAttr('disabled');
				$('#kabupaten').select2();
			}
		});
	});
    // TRIGGER KECAMATAN
    $('#kabupaten').change(function () {
		var id = $('#kabupaten').val();
		$.post("{!! route('getKecamatan') !!}",{id:id}).done(function(data){
			if(data.length > 0){
				var kecamatan = '<option>--Pilih Kecamatan--</option>';
				$.each(data, function(k,v){
					kecamatan += '<option value="'+v.id+'">'+v.nama+'</option>';
				});

				$('#kecamatan').html(kecamatan);
				$('#kecamatan').removeAttr('disabled');
				$('#kecamatan').select2();
			}
		});
	});

    // TOMBOL SUBMIT
    $('#btn-submit').click(function(e) {
        e.preventDefault();
        $('#btn-submit').html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        ).attr('disabled', true);
        var data = new FormData($('.form-save')[0]);
        console.log(data);
        $.ajax({
            url: "{{ route('store-pbf') }}",
            type: 'POST',
            data: data,
            async: true,
            cache: false,
            contentType: false,
            processData: false
        }).done(function(data) {
            // $('.form-save').validate(data, 'has-error');
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

    $('#btn-cancel').click(function(e) {
        e.preventDefault();
        $('#other-layer').fadeOut(function() {
            $('#other-layer').empty();
            $('#main-layer').fadeIn();
            // $('#datatables').DataTable().ajax.reload();
        });
    });
</script>
