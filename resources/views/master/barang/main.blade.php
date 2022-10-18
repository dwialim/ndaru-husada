@extends('layouts.main')
@section('title', 'Master '.request()->jenis)
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        {{-- <i class="ik ik-users bg-blue"></i> --}}
                        <div class="d-inline">
                            <h5>{{ __('Data '.request()->jenis) }}</h5>
                            {{-- <span>{{ __('List of users')}}</span> --}}
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
                                <a href="#">{{ __('Data '.request()->jenis) }}</a>
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
                        <div class="card-header">
                            <a href="#" id="create-barang" class="btn btn-primary">Tambah  {{ request()->jenis }} Baru</a>
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
                            <table id="datatables" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('No.') }}</th>
                                        <th>{{ __('Kode '.request()->jenis) }}</th>
                                        <th>{{ __('Nama '.request()->jenis) }}</th>
                                        <th>{{ __('Satuan '.request()->jenis) }}</th>
                                        <th>{{ __('Option') }}</th>
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
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <!--server side users table script-->

        <script>
            $(document).ready(function() {
                // Sementara tidak digunakan
                var dTable = $('#datatables').DataTable({

                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    responsive: false,
                    serverSide: true,
                    processing: true,
                    language: {
                        processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                    },
                    scroller: {
                        loadingIndicator: false
                    },
                    pagingType: "full_numbers",
                    ajax: {
                        url: "{{ route('master-barang') }}",
                        type: "get",
                        data: function (d) {
                            d.jenis = '{{ request()->jenis }}'
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'kode', name: 'kode' },
                        { data: 'nama', name: 'nama' },
                        { data: 'satuan', name: 'satuan' },
                        { data: 'action', name: 'action' }
                    ],
                });

                // CREATE SUPPLIER
                $('#create-barang').click(function() {
                    $('#main-layer').hide();
                    $.post("{{ route('form-master-barang') }}", {jenis:'{{ request()->jenis }}'}).done(function(data) {
                        if (data.status == 'success') {
                            $('#other-layer').html(data.content).fadeIn();
                        } else {
                            $('#main-layer').show();
                        }
                    });
                })
            });

            function edit_barang(id) {
                $('#main-layer').hide();
                $.ajax({
                    type: "POST",
                    url: "{{ route('form-master-barang') }}",
                    data: {
                        id: id,
                        jenis: '{{ request()->jenis }}'
                    },
                }).done(function(data) {
                    if (data.status == 'success') {
                        $('#other-layer').html(data.content).fadeIn();
                    } else {
                        $('#main-layer').show();
                    }
                });
            }

            function delete_barang(id) {
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan lagi.",
                    type: "warning",
                    showCancelButton: true,
                    showCloseButton: true,
                    icon: 'warning',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Hapus',
                }).then((result) => {
                    if (result.value) {
                        $.post("{{ route('delete-master-barang') }}",{id:id}).done(function(data) {
                        	if (data == 'true') {
                        		Swal.fire(data.status, "Data berhasil dihapus!", "success");
                        	}
                            $('#datatables').DataTable().ajax.reload();
                        }).fail(function() {
                        	Swal.fire("Sorry!", "Gagal menghapus data!", "error");
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire("Batal", "Data batal dihapus!", "error");
                    }
                });
            }

            $('#btn-save-excel').click(function (e) {
				e.preventDefault();
				var data  = new FormData($('#importexcelsave')[0]);
				$('#btn-save-excel').text('Sending...');
				$.ajax({
						url: "{{ route('import-master-barang') }}",
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
				}
		    );
        </script>
    @endpush
@endsection
