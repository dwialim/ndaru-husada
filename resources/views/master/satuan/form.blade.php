<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">

<div class="row">
    <!-- end message area-->
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header bg-{{ (!empty($data)) ? 'primary' : 'success' }}">
                @empty($data)
                    <h3 class="text-white">{{ __('Tambah Data Satuan Baru') }}</h3>
                @else
                    <h3 class="text-white">{{ __('Edit Data Satuan Baru') }}</h3>
                @endempty
            </div>
            <div class="card-body">
                <form class="form-save form-save">
                    <input type="hidden" name="id" value="{{ (!empty($data)) ? $data->id : '' }}">
                    {{-- @csrf --}}
                    <div class="row">
                        <div class="col-sm-6">

                            <div class="form-group">
                                <label for="nama">{{ __('Nama Satuan') }}<span
                                        class="text-red">*</span></label>
                                <input id="nama" type="text" class="form-control" name="nama"
                                    value="{{ (!empty($data)) ? $data->nama : '' }}" placeholder="Nama Satuan" required>
                                <div class="help-block with-errors"></div>
                                {{-- ERROR FEEDBACK --}}
                            </div>
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
    var jenis = $('#jenis').val();
    // $('#jenis').select2();

    $('#btn-submit').click(function(e) {
        e.preventDefault();
        $('#btn-submit').html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        ).attr('disabled', true);
        var data = new FormData($('.form-save')[0]);
        console.log(data);
        $.ajax({
            url: "{{ route('store-satuan') }}",
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
