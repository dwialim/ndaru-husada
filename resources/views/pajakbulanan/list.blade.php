@extends('layouts.main')
@section('title', 'Data Laba Rugi')
@section('content')

    <div class="col-md-12">
        <div class="card">
            <div class="card-header row">
                <div class="col col-sm-1">
                    <div class="card-options d-inline-block">
                        <div class="dropdown d-inline-block">
                            <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ik ik-more-horizontal"></i></a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="moreDropdown">
                                <a class="dropdown-item" href="#">Delete</a>
                                <a class="dropdown-item" href="#">More Action</a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col col-sm-6">
                    <div class="card-search with-adv-search dropdown">
                        <form action="">
                            <input type="text" class="form-control global_filter" id="global_filter" placeholder="Search.."
                                required="">
                            <button type="submit" class="btn btn-icon"><i class="ik ik-search"></i></button>
                            <button type="button" id="adv_wrap_toggler_1" class="adv-btn ik ik-chevron-down dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="adv-search-wrap dropdown-menu dropdown-menu-right"
                                aria-labelledby="adv_wrap_toggler_1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control column_filter" id="col0_filter"
                                                placeholder="Title" data-column="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control column_filter" id="col1_filter"
                                                placeholder="Price" data-column="1">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control column_filter" id="col2_filter"
                                                placeholder="SKU" data-column="2">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control column_filter" id="col3_filter"
                                                placeholder="Qty" data-column="3">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control column_filter" id="col4_filter"
                                                placeholder="Category" data-column="4">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control column_filter" id="col5_filter"
                                                placeholder="Tag" data-column="5">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-theme">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                <button class="btn btn-warning hidden-print" onclick="myPrint()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Cetak Laporan </button>
            </div>
            <div class="card-body">
                <table id="advanced_table" class="table">
                    <thead>
                        <tr>
                            <th> No </th>
                            <th> Nama Barang </th>
                            <th> Satuan </th>
                            <th> Jenis </th>
                            <th> ED </th>
                            <th> Selisih Harga </th>
                            <th> QTY </th>
                            <th> Laba/Rugi </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pajakbulanan as $pb)
                            <tr>
                                <td id="pbId"> {{ $pb->id }} </td>
                                <td> {{ $pb->nama_barang }} </td>
                                <td> {{ $pb->satuan }} </td>
                                <td> {{ $pb->jenis }} </td>
                                <td> {{$pb->expired_date }} </td>
                                <td> {{$pb->selisi_harga }} </td>
                                <td> {{$pb->qty }} </td>
                                <td> {{$pb->laba_rugi }}
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                
            </div>
            
        </div>
      
    </div>

    @push('script')
        <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/gauge.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/animate.min.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/pie.js') }}"></script>
        <script src="{{ asset('plugins/ammap3/ammap/ammap.js') }}"></script>
        <script src="{{ asset('plugins/ammap3/ammap/maps/js/usaLow.js') }}"></script>
        <script src="{{ asset('js/product.js') }}"></script>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script>
            function myPrint() {
                window.open("{{ route('printPajakBulanan') }}", '_blank');
            }
        </script>
    @endpush
@endsection