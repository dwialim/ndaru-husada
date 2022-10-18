@extends('layouts.main')
@section('title', 'Reward')
@section('content')
    <!-- push external head elements to head -->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <div class="d-inline">
                            <h5>{{ __('Laporan Reward') }}</h5>
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
                                <a href="javascript:">{{ __('Laporan Reward') }}</a>
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
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-10 showDateRange">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <label class="col-form-label col-md-3">Tanggal</label>
                                                    <div class="col-md-9">
                                                        <input type="date" name="startDate" id="startDate" class="input-sm form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <label class="col-form-label col-md-2">s/d</label>
                                                    <div class="col-md-10">
                                                        <input type="date" name="endDate" id="endDate" class="input-sm form-control">
                                                    </div>
                                                </div>
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
                        <div class="card-header">
                            <a href="javascript:void(0)" onclick="myPrint()" id="exportButton"class="btn btn-primary hidden-print">
                                <i class="fa fa-file-export fs-14 m-r-10"></i> <b>Export Laporan</b>
                              </a>
                            {{-- <button class="btn btn-primary hidden-print" onclick="myPrint()"><i class="ik ik-printer"></i> Cetak Laporan </button> --}}
                        </div>
                        <div class="card-body">
                            <table id="datatables" class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>{{ __('No.') }}</th>
                                        <th>{{ __('Nama Pelanggan') }}</th>
                                        <th>{{ __('Total Pembelian(Dispensing)') }}</th>
                                        <th>{{ __('Total Reward') }}</th>
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

        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}"> 
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <!--server side users table script-->

        <script>
            $(document).ready(function() {
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
                        url: "{{ route('reward') }}",
                        type: "get",
                        data: {
                            'startDate': function() {return $('#startDate').val()},
                            'endDate': function() {return $('#endDate').val()},
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex',render:function(data){
                            return '<p class="text-center">'+data+'</p>';
                        },orderable: false, searchable: false },
                        {data: 'nama_pelanggan', name: 'nama_pelanggan',render:function(data){
                            return '<p class="text-center">'+data.toUpperCase()+'</p>';
                        }},
                        {data:'total',name:'total',render:function(data,type,row){
                            return '<p class="text-center">'+formatRupiah(data,"Rp. ")+'</p>'
                        }},
                        {data: 'persen', name: 'persen',render:function(data){
                            return '<p class="text-center">'+formatRupiah(data,"Rp. ")+'</p>';
                        }},
                        // {data: 'total_barang', name: 'total_barang',render:function(data){
                        //     return '<p class="text-center">'+data+'</p>';
                        // }},
                        { data: 'action', name: 'action' },
                    ],
                });

            });

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

            function myPrint() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                window.open('{{ route('printReward') }}'+`?startDate=${startDate}&endDate=${endDate}`, '_blank');
            }

            function searchData(){
                $('.btn-search').attr('disabled',true);
                var startDate = $('#startDate').val();// range awal tanggal
                var endDate = $('#endDate').val();// range akhir tanggal
                if(startDate && endDate){
                    if(startDate<=endDate){
                        $('#datatables').DataTable().ajax.reload();
                    }else{
                        Swal.fire({
                            icon: 'warning',
                            title: 'Whoops',
                            text: 'Tanggal Tidak Sesuai!',
                            showConfirmButton: false,
                            timer: 1200
                        });
                    }
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
                $('#startDate').val('');
                $('#endDate').val('');
                $('.btn-refresh').attr('disabled', false);
                $('#datatables').DataTable().ajax.reload();
            }

            function show_reward(nama_pelanggan) {
                $('#main-layer').hide();
                $.ajax({
                    type: "POST",
                    url: "{{ route('show-reward') }}",
                    data: {
                        nama_pelanggan: nama_pelanggan,
                    },
                }).done(function(data) {
                    if (data.status == 'success') {
                        $('#other-layer').html(data.content).fadeIn();
                    } else {
                        $('#main-layer').show();
                    }
                });
            }
        </script>
    @endpush
@endsection
