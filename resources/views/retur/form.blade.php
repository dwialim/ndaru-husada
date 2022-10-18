@empty(!$data)
    @php
        if ($jenis == 'Penjualan') {
            $id = $data->penjualan->id;
            $nomor = $data->penjualan->no_kwitansi;
            $nama_pelanggan = $data->penjualan->nama_pelanggan;
            $created_at = $data->penjualan->created_at;
        } else {
            $id = $data->faktur->id;
            $nomor = $data->faktur->no_faktur_pbf;
            $nama_pelanggan = $data->faktur->pbf->nama;
            $created_at = $data->faktur->created_at;
        }
    @endphp
@endempty
<div class="row">
    <!-- end message area-->
    <div class="col-md-12">
        <form class="form-save">
            <input type="hidden" name="jenis" id="" value="{{ $jenis }}">
            <div class="card ">
                <div class="card-header bg-{{ (!empty($data)) ? 'primary' : 'success' }}">
                    @php
                        $kata = '';
                        if ($jenis == 'Penjualan') {
                            $kata = 'Dari';
                        }else{
                            $kata = 'Ke';
                        }
                    @endphp
                    @empty($data)
                        <h3 class="text-white">{{ "Tambah Data Retur $kata $jenis" }}</h3>
                        @else
                        <h3 class="text-white">{{ "Retur $kata $jenis" }}</h3>
                    @endempty
                </div>
                <div class="card-body">
                    {{-- @csrf --}}
                    @empty($data)
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="nama">{{ 'Cari No ' . ($jenis == 'Penjualan' ? 'Kwitansi' : 'Faktur') }}</label>
                                    <select class="js-data-example-ajax" name="nomor" id="nomor">
                                    </select>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="nomor" id="nomor" value="{{ $id }}">
                    @endempty
                    
                    <div class="{{ (empty($data)) ? 'hide' : '' }}" id="detail_penjualan">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">No. {{ $jenis == 'Penjualan' ? 'Kwitansi' : 'Faktur' }} :</label>
                            <div class="col-md-5">
                                <strong><input type="text" class="form-control border-0 bg-white" id="no_kwitansi_text" name="no_kwitansi_text" value="{{ $nomor ?? '' }}" readonly></strong>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nama {{ $jenis == 'Penjualan' ? 'Pelanggan' : 'PBF' }} :</label>
                            <div class="col-md-5">
                                <strong><input type="text" class="form-control border-0 bg-white" id="nama_pelanggan" name="nama_pelanggan" value="{{ $nama_pelanggan ?? '' }}" readonly></strong>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Tanggal {{ $jenis == 'Penjualan' ? 'Penjualan' : 'Faktur' }} :</label>
                            <div class="col-md-5">
                                <strong><input type="text" class="form-control border-0 bg-white" id="tanggal_penjualan" name="tanggal_penjualan" value="{{ $created_at ?? '' }}" readonly></strong>
                            </div>
                        </div>

                        @empty(!$data)
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Tanggal Retur :</label>
                            <div class="col-md-5">
                                <strong><input type="text" class="form-control border-0 bg-white" id="tanggal_penjualan" name="tanggal_penjualan" value="{{ $data->created_at ?? '' }}" readonly></strong>
                            </div>
                        </div>
                        @endempty

                        <div class="row my-4">
                            <div class="col-md-12">
                                <h5 class="mb-2">Tabel Detail {{ $jenis == 'Penjualan' ? 'Penjualan' : 'Faktur' }}</h5>
                                <div class="table-responsive">
                                    <table id="datatables" class="table ml-0" style="width: 100%;">
                                        <thead>
                                            <tr class="text-center">
                                                <th> No </th>
                                                <th> Obat </th>
                                                <th> No. Batch </th>
                                                @if ($jenis == 'Penjualan')
                                                <th> Jenis Penjualan </th>
                                                @endif
                                                <th> QTY </th>
                                                <th> Satuan </th>
                                                <th> Isi PerBox </th>
                                                <th> Aksi </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>

            {{-- BARANG_YANG_INGIN_DIRETUR --}}
            <div class="card {{ (empty($data)) ? 'hide' : '' }}" id="barang_retur">
                <div class="card-header bg-white">
                    <h3 class="">{{ __('Tabel Barang Retur') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table_retur" class="table ml-0" style="width: 100%;">
                                    <thead>
                                        <tr class="text-center">
                                            <th> No </th>
                                            <th> Obat </th>
                                            <th> No. Batch </th>
                                            @if ($jenis == 'Penjualan')
                                            <th> Jenis Penjualan </th>
                                            @endif
                                            <th> QTY </th>
                                            <th> Satuan </th>
                                            <th> Deskripsi </th>
                                            @empty($data)
                                                <th> Aksi </th>
                                            @else
                                                <th>Status</th>
                                            @endempty
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->detail_retur ?? [] as $detail_retur)
                                            <tr>
                                                <td style="text-align: center" class='iter'>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $detail_retur->stok_barang->barang->nama }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $detail_retur->stok_barang->no_batch }}
                                                </td>
                                                @if ($jenis == 'Penjualan')
                                                <td style="text-align: center">
                                                    {{ $detail_retur->detail_penjualan->jenis_penjualan }}
                                                </td>
                                                @endif
                                                <td style="text-align: center">
                                                    {{ $detail_retur->qty }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $detail_retur->stok_barang->barang->satuan->nama ?? 'Null' }}
                                                </td>
                                                <td style="text-align: center">
                                                    {{ $detail_retur->deskripsi ?? '-' }}
                                                </td>
                                                @if ($jenis == 'Penjualan')
                                                <td style="text-align: center" id="status_col{{ $detail_retur->id }}">
                                                    @if ($detail_retur->status == Null)
                                                        <a href="javascript:;" class="badge badge-success mr-2" onclick="change_status({{ $detail_retur->id }}, 1)"><i class="ik ik-check-square"></i> Terima<ia>
                                                        <a href="javascript:;" class="badge badge-danger" onclick="change_status({{ $detail_retur->id }}, 0)"><i class="ik ik-x-square"></i> Tolak</a>
                                                    @elseif ($detail_retur->status == 1)
                                                        <span class="badge badge-success mr-2"><i class="ik ik-check-square"></i> Diterima</span>
                                                    @else
                                                        <span class="badge badge-danger mr-2"><i class="ik ik-x-square"></i> Ditolak</span>    
                                                    @endif
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right mt-3">
                            <button type="button" class="btn btn-secondary mx-2" id="btn-cancel">{{ (empty($data)) ? 'Batal' : 'Kembali' }} </button>
                            @empty($data)
                                <button type="button" class="btn btn-success btn-simpan" id="btn-submit">Simpan</button>
                            @endempty
                        </div>		
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    var is_show = {{ $data->id ?? 0 }};
    var url = "{{ $jenis == 'Penjualan' ? route('get_detail_penjualan'): route('get_stok_barang') }}";
    console.log('{{ $jenis }}');
    var dataTable = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "POST",
            data: {
                'id': function() {return $('#nomor').val()},
                'is_show': is_show,
            },
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'barang', name: 'barang' },
            { data: 'no_batch', name: 'no_batch' },
            @if ($jenis == 'Penjualan')
            { data: 'jenis_penjualan', name: 'jenis_penjualan' },
            @endif
            { data: 'qty', name: 'qty' },
            { data: 'satuan', name: 'satuan' },
            { data: 'jumlah_perbox', name: 'jumlah_perbox' },
            { data: 'action', name: 'action', orderable: false, searchable: false  },
        ]
    });
    
    // ONCHANGE_NO_KWITANSI
    $('#nomor').change(() => {
        var dataTable = $('#datatables').DataTable().ajax.reload();

        dataTable.on( 'xhr', function () {
            var data = dataTable.ajax.json().data_parent;
            console.log(data);
            $('#no_kwitansi_text').val(data.nomor);
            $('#nama_pelanggan').val(data.nama);
            $('#tanggal_penjualan').val(data.tanggal);
        } );
        
        $('#detail_penjualan').removeClass('hide');
        $('#barang_retur').removeClass('hide');
        $('#table_retur tbody').empty();
    })
    
    @empty($data)
    $('#nomor').select2({
        ajax: {
            url: "{{ route('get_kwitansi') }}",
            dataType: 'json',
            type: 'POST',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                    jenis: '{{ $jenis }}'
                }
                return query;
            },

            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nomor,
                            id: item.id
                        }
                    })
                };
            },
            cache: true,
        }
    });
    @endempty
    
    // TAMBAH BARANG RETUR
    function tambah_retur(id) {
        var stok_barang_id = $('#stok_barang_id'+id).val();
        var qty = $('#qty_penjualan'+id).val();
        var nama_barang = $('#nama_barang'+id).val();
        var no_batch = $('#no_batch'+id).val();
        var jenis_penjualan = $('#jenis_penjualan'+id).val();
        var detail_penjualan_id = $('#detail_penjualan_id'+id).val();
        var satuan_barang = $('#satuan_barang'+id).val();
        var row = `
            <tr id=rowItem${id}>
                <td style="text-align: center" class='iter'>#</td>
                <td style="text-align: center"> <input type='hidden' name='stok_barang_id[]' value='${stok_barang_id}'> ${nama_barang}</td>
                <td style="text-align: center"> ${no_batch}</td>
                @if ($jenis == 'Penjualan')
                <td style="text-align: center"> <input type='hidden' name='detail_penjualan_id[]' value='${detail_penjualan_id}'> ${jenis_penjualan}</td>
                @endif
                <td style="text-align: center">
                    <div class='input-group mt-1' style='margin-bottom: 4px; width: 87%; margin-left: auto; margin-right: auto;'>
                        <div class='input-group-prepend'>
                            <button class='btn btn-outline-secondary' onclick='decrease_qty(${id})' type='button'>-</button>
                        </div>
                            <input type='text' class='form-control w-25 text-center' name='jumlah[]' id='qty' onkeyup='ubahFormatNumber(this),f_qty(this,${id})' value='${qty}' placeholder='' aria-label='' aria-describedby='basic-addon1' autocomplete='off'>
                        <div class='input-group-append'>
                            <button class='btn btn-outline-secondary btn-plus' onclick='increase_qty(${id})' type='button'>+</button>
                        </div>
                    </div>
                </td>
                <td style="text-align: center">${satuan_barang}</td>
                <td style="text-align: center">
                    <textarea name="deskripsi[]" id="deskripsi" cols="30" rows="2"></textarea>
                </td>
                <td style="text-align: center">
                    <a href="javascript:;" class="" onclick="hapus_retur(${id})"><i class="ik ik-x-square f-20 text-danger"></i></a>
                </td>
            </tr>
        `;
        $('#table_retur tbody').append(row);

        // NO TABLE
        $('.iter').each(function(index) {
            $(this).text(index+1);
        });
        
        // DISABLED TOMBOL TAMBAH
        $('#btn_tambah_retur'+id).attr('disabled', true);
    }
    
    function hapus_retur(id) {
        $('#rowItem'+id).remove();
        $('#btn_tambah_retur'+id).attr('disabled', false);

    }

    function formatNumber(angka) {
        return angka.toString().replace(/[^,\d]/g, "");
    }

    function decrease_qty(kode){
        var qty = parseInt($('#rowItem'+kode+' #qty').val());
        var jumlah = $('#qty_penjualan'+kode).val();
        if(qty > 1 && qty <= jumlah){
            qty = parseInt(qty)-1;
            $('#rowItem'+kode+' #qty').val(qty);
        }else{
            $('#rowItem'+kode+' #qty').val(1)
            Swal.fire({
                title: 'Whoops',
                text: "Tidak bisa kurang lagi",
                icon: 'warning',
            });
        }
    }

    function increase_qty(kode){
        var qty = parseInt($('#rowItem'+kode+' #qty').val());
        var jumlah = $('#qty_penjualan'+kode).val();
        
        qty = qty+1;
        $('#rowItem'+kode+' #qty').val(qty);
        
        if (qty <= jumlah) {
            // $('#rowItem'+kode+' #total').html(formatRupiah(total(kode, qty), "Rp. "));
            // $('#rowItem'+kode+' #total_harga').val(total(kode, qty));
            // total_harga(kode)
        } else {
            $('#rowItem'+kode+' #qty').val(jumlah);
            Swal.fire({
                title: 'Whoops',
                text: "Max jumlah "+jumlah,
                icon: 'warning',
            })
        }
    }

    function f_qty(v,kode) {
        if (v.value[0] != 0) {
            $(v).val(formatNumber(v.value));

            var qty = parseInt(formatNumber(v.value));
            var jumlah = parseInt($('#qty_penjualan'+id).val());
            if (qty <= jumlah) {
                // pass
            } else {
                $('#rowItem'+kode+' #qty').val(1)
                Swal.fire({
                    title: 'Whoops',
                    text: "Max jumlah "+jumlah,
                    icon: 'warning',
                });
            }
        } else {
            $(v).val(1);
            Swal.fire({
                title: 'Whoops',
                text: "Input tidak boleh 0",
                icon: 'warning',
            });
        }

    }

    function change_status(id, status) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: (status == 1 ? "Terima" : "Tolak") + " retur!" ,
            type: "warning",
            showCancelButton: true,
            showCloseButton: true,
            icon: 'warning',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Yakin',
        }).then((result) => {
            if (result.value) {
                $.post("{{ route('change_status_retur') }}",{
                    id:id, 
                    status:status
                }).done(function(data) {
                    var status_name = status == 1 ? 'Diterima' : 'Ditolak';
                    var badge_status = '';
                    if (status == 1) {
                        badge_status = `<span class="badge badge-success mr-2"><i class="ik ik-check-square"></i> Diterima</span>`;
                    }else{
                        badge_status = `<span class="badge badge-danger mr-2"><i class="ik ik-x-square"></i> Ditolak</span>`;
                    }
                    $('#status_col'+id).html(badge_status);
                }).fail(function() {
                    Swal.fire("Sorry!", "Gagal menghapus data!", "error");
                });
            }
        });
    }

    function store() {
        $('#btn-submit').html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        ).attr('disabled', true);
        var data = new FormData($('.form-save')[0]);
        console.log(data);

        $.ajax({
            url: "{{ route('store-retur') }}",
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
                // $('#detail_penjualan').addClass('hide');
                // $('#barang_retur').addClass('hide');
                // $('#table_retur tbody').empty();

                $('#other-layer').fadeOut(function() {
                    $('#other-layer').empty();
                    $('#main-layer').fadeIn();
                    $('#dataTable').DataTable().ajax.reload();
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
    }

    var jenis = '{{ $jenis }}';

    $('#btn-submit').click(function(e) {
        e.preventDefault();
        
        if (jenis == 'PBF') {
            Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Stok barang yang diretur ke PBF akan berkurang.",
                    type: "warning",
                    showCancelButton: true,
                    showCloseButton: true,
                    icon: 'warning',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Lanjutkan',
                }).then((result) => {
                    if (result.value) {
                        store()
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire("Batal", "Batal Diretur", "success");
                    }
                });
        }else{
            store();
        }
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
