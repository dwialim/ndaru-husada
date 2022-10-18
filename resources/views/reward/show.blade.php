<div class="row">
    <!-- end message area-->
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header ">
                <h3 class="">Detail Pembelian</h3>
            </div>
            <div class="card-body">
                {{-- @csrf --}}
                <div class="row">
                    <div class="col-sm-2">
                        <span style="font-size: 14px;">Nama Pelanggan / Bidan:</span>
                    </div>
                    <div class="col-sm-auto">
                        @php
                        $nama = strtoupper($nama_pelanggan);
                        @endphp
                        {{ $nama }}
                    </div>
                </div>

            </div>
        
            <div class="card-header bg-white">
                <h3 class="">{{ __('Riwayat Pembelian Pelanggan / Bidan') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table_penjualan" class="table ml-0" style="width: 100%;">
                                <thead>
                                    <tr class="text-center">
                                        <th> No </th>
                                        <th> No Kwitansi </th>
                                        <th> Tanggal </th>
                                        <th> Barang </th>
                                        <th> No Batch </th>
                                        <th> Satuan </th>
                                        <th> QTY </th>
                                        <th> Jenis Penjualan </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right mt-3">
                        <button type="button" class="btn btn-secondary mx-2" id="btn-cancel">Kembali</button>
                    </div>		
                </div>
            </div>
        </div>
    </div>
</div>

<script>    
    var dataTable = $('#table_penjualan').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('get_detail_penjualan_reward') }}",
            type: "POST",
            data: {
                'nama_pelanggan': '{{ $nama_pelanggan }}',
            },
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'no_kwitansi', name: 'no_kwitansi', render:function(data){
						return '<div class="text-center">'+data+'</div>';
					}},
            { data: 'tanggal', name: 'tanggal', render:function(data){
						return '<div class="text-center">'+data+'</div>';
					}},
            { data: 'barang', name: 'barang' },
            { data: 'no_batch', name: 'no_batch' },
            { data: 'satuan', name: 'satuan' },
            { data: 'qty', name: 'qty' },
            { data: 'jenis_penjualan', name: 'jenis_penjualan' },
        ],
        rowsGroup: [
            'no_kwitansi:name',
            'tanggal:name',
        ]
    });

    $('#btn-cancel').click(function(e) {
        e.preventDefault();
        $('#other-layer').fadeOut(function() {
            $('#other-layer').empty();
            $('#main-layer').fadeIn();
            // $('#table_penjualan').DataTable().ajax.reload();
        });
    });
</script>
