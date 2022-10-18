@extends('layouts.main')
@section('title', 'Satuan')
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
                            <h5>{{ __('Data Satuan') }}</h5>
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
                                <a href="#">{{ __('Data Pengguna') }}</a>
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
                            <a href="#" id="create-satuan" class="btn btn-primary">Tambah Satuan Baru</a>
                        </div>
                        <div class="card-body">
                            <table id="datatables" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('No.') }}</th>
                                        <th>{{ __('Nama Satuan') }}</th>
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
                        url: "{{ route('satuan') }}",
                        type: "get"
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nama', name: 'nama' },
                        // { data: 'jenis', name: 'jenis' },
                        { data: 'action', name: 'action' }
                    ],
                });

                // CREATE SATUAN
                $('#create-satuan').click(function() {
                    $('#main-layer').hide();
                    $.post("{{ route('form-satuan') }}").done(function(data) {
                        if (data.status == 'success') {
                            $('#other-layer').html(data.content).fadeIn();
                        } else {
                            $('#main-layer').show();
                        }
                    });
                })
            });

            function edit_satuan(id) {
                $('#main-layer').hide();
                $.ajax({
                    type: "POST",
                    url: "{{ route('form-satuan') }}",
                    data: {
                        id: id,
                    },
                }).done(function(data) {
                    if (data.status == 'success') {
                        $('#other-layer').html(data.content).fadeIn();
                    } else {
                        $('#main-layer').show();
                    }
                });
            }

            function delete_satuan(id) {
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
                        $.post("{{ route('delete-satuan') }}",{id:id}).done(function(data) {
                        	if (data == 'true') {
                        		Swal.fire("", "Data berhasil dihapus!", "success");
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
        </script>
    @endpush
@endsection
