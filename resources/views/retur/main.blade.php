@extends('layouts.main')

@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush

@section('title', 'Retur')
@section('content')
    <!-- push external head elements to head -->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        {{-- <i class="ik ik-users bg-blue"></i> --}}
                        <div class="d-inline">
                            @php
                                $kata = '';
                                if (request()->jenis == 'Penjualan') {
                                    $kata = 'Dari';
                                }else{
                                    $kata = 'Ke';
                                }
                            @endphp
                            <h5>{{ __("Retur $kata ".request()->jenis) }}</h5>
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
                                <a href="#">{{ __('Retur') }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div id="main-layer">
            {{-- MAIN PAGE --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card p-3">
                        <div class="card-header">
                            <a href="#" id="create-barang" class="btn btn-primary">Tambah Retur Baru</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-striped dataTable ml-0" style="width: 100%;">
                                    <thead>
                                        <tr class="text-center">
                                            <th> No </th>
                                            <th> Kode Retur </th>
                                            <th> No {{ request()->jenis == 'Penjualan' ? 'Kwitansi' : 'Faktur' }} </th>
                                            <th> Tanggal Retur </th>
                                            <th> Tanggal {{ request()->jenis == 'Penjualan' ? 'Penjualan' : 'Faktur' }} </th>
                                            <th> Nama {{ request()->jenis == 'Penjualan' ? 'Pelanggan' : 'PBF' }} </th>
                                            <th> Nama {{ request()->jenis == 'Penjualan' ? 'Penerima' : 'Pengirim' }} </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
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
                var dataTable = $('#dataTable').dataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{route('retur')}}",
                        type: "GET",
                        data: {
                            'jenis': '{{ request()->jenis }}',
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'kode', name: 'kode' },
                        { data: 'nomor', name: 'nomor' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'tanggal', name: 'tanggal' },
                        { data: 'nama_pelanggan', name: 'nama_pelanggan' },
                        { data: 'nama_penerima', name: 'nama_penerima' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ]
                });


                // CREATE SUPPLIER
                $('#create-barang').click(function() {
                    $('#main-layer').hide();
                    $.post("{{ route('form-retur') }}", {jenis:'{{ request()->jenis }}'}).done(function(data) {
                        if (data.status == 'success') {
                            $('#other-layer').html(data.content).fadeIn();
                        } else {
                            $('#main-layer').show();
                        }
                    });
                })
            });

            function show_retur(id) {
                $('#main-layer').hide();
                $.ajax({
                    type: "POST",
                    url: "{{ route('form-retur') }}",
                    data: {
                        id: id,
                        jenis: '{{ request()->jenis }}',
                    },
                }).done(function(data) {
                    if (data.status == 'success') {
                        $('#other-layer').html(data.content).fadeIn();
                    } else {
                        $('#main-layer').show();
                    }
                });
            }

            function delete_retur(id) {
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
                        $.post("{{ route('delete-retur') }}",{id:id}).done(function(data) {
                            Swal.fire(data.status, "Data berhasil dihapus!", "success");
                            $('#dataTable').DataTable().ajax.reload();
                        }).fail(function() {
                        	Swal.fire("Sorry!", "Gagal menghapus data!", "error");
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire("Batal", "Data batal dihapus!", "error");
                    }
                });
            }

            function cetak_retur(id, jenis) {
                $.post("{{route('cetak_retur') }}", {id:id, jenis:jenis}).done(function(data){
                    if(data.status == 'success'){
                        if (data.print.length > 0) {
                            var winPrint = window.open('about:blank', '_blank');
                            winPrint.document.write(data.print[0]);
                            winPrint.document.close();
                            setTimeout(function () {
                                winPrint.print();
                                winPrint.close();
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: data.message,
                                    icon: 'success',
                                });
                            },150);
                        }
                    } else {
                        swal("Success!", data.message, "success");
                        $('.preloader').hide();
                        $('.main-page').show();
                    }
                });
            }
        </script>
    @endpush
@endsection
