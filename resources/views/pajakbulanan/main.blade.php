@extends('layouts.main')
@section('title', 'Laporan Laba Bersih')
@push('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- push external head elements to head -->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        {{-- <i class="ik ik-users bg-blue"></i> --}}
                        <div class="d-inline">
                            <h5>{{ __('Laporan Pajak Bulanan') }}</h5>
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
                                <a href="#">{{ __('Laporan Pajak Bulanan') }}</a>
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
                        <div class="card-header row justify-content-between">
                            <div class="col-4">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="padding: 5px 10px;"><i
                                                class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="datepicker" id="datepicker"
                                        value="" />
                                </div>
                                <a href="javascript:void(0)" onclick="myPrint()" id="exportButton"
                                    class="btn btn-primary hidden-print">
                                    <i class="fa fa-file-export fs-14 m-r-10"></i> <b>Export Laporan</b>
                                </a>
                            </div>
                            <div class="col-3 align-self-end">
                                <strong>
                                    <p class="mb-0">Total = <span id="total">0</span></p>
                                </strong>
                            </div>
                            {{-- <button class="btn btn-primary hidden-print" onclick="myPrint()"><i class="ik ik-printer"></i> Cetak Laporan </button> --}}
                        </div>
                        <div class="card-body">
                            <table id="datatables" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('No.') }}</th>
                                        <th>{{ __('Nama ') }}</th>
                                        <th>{{ __('Satuan ') }}</th>
                                        <th>{{ __('Harga Beli') }}</th>
                                        <th>{{ __('PPN') }}</th>
                                        <th>{{ __('QTY') }}</th>
                                        <th>{{ __('Total') }}</th>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
        <!--server side users table script-->

        <script>
            $(document).ready(function() {
                // GET CURRENT DATE
                var date = new Date();
                var bulan = `${date.getMonth()+1}-${date.getFullYear()}`;

                // DATEPICKER
                $("#datepicker").datepicker({
                    format: "mm-yyyy",
                    startView: "months",
                    minViewMode: "months",
                    autoclose: true,
                    todayBtn: true
                });
                $('#datepicker').change(() => {
                    var bulan = $('#datepicker').val();
                    var dTable $('#datatables').DataTable().ajax.reload();
                    dTable.on( 'xhr', function () {
                        var json = dTable.ajax.json();
                        $('#total').text(formatRupiah(parseInt(json.total), 'Rp.'))
                } );
                })

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
                        url: "{{ route('pajakbulanan') }}",
                        type: "get",
                        data: {
                            'bulan': function() {return $('#datepicker').val()}
                            // 'bulan': function() {return ($('#datepicker').val() != '') ? $('#datepicker').val() : bulan}
                        },
                       
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'barang',
                            name: 'barang'
                        },
                        {
                            data: 'satuan',
                            name: 'satuan'
                        },
                        {
                            data: 'harga_beli',
                            name: 'harga_beli',
                            render: function(data, type, row) {
                                return formatRupiah(data, "Rp. ");
                            }
                        },
                        {
                            data: 'nominal_pajak',
                            name: 'nominal_pajak',
                            render: function(data, type, row) {
                                return data + '%';
                            }
                        },
                        {
                            data: 'qty',
                            name: 'qty',
                        },
                        {
                            data: 'pajakbulanan',
                            name: 'pajakbulanan',
                            render: function(data, type, row) {
                                return formatRupiah(data, "Rp. ");
                            }
                        },
                    ],
                });
                dTable.on( 'xhr', function () {
                    var json = dTable.ajax.json();
                    $('#total').text(formatRupiah(parseInt(json.total), 'Rp.'))
                } );
            });

            

            function myPrint() {
                window.open("{{ route('printPajakBulanan') }}?bulan="+$('#datepicker').val(), '_blank');
            }
        </script>
    @endpush
@endsection
