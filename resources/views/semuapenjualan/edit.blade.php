@extends('layouts.main')
@section('title', 'Data Semua Penjualan')
@section('content')

<div class="d-inline">
<h5>Semua Penjualan</h5>
</div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header row">
                       
                <div class="col col-sm-5">
                    <div class="card-options text-right">
                        <div class="card-header">
				   	</a>
				</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="advanced_table" class="table">
                    <thead>
                        <tr>
                            <th> No </th>
                            <th> Tanggal Penjualan </th>
                            <th> Nama Barang </th>
                            <th> Harga </th>
                            <th> QTY </th>
							<th> Total Harga </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($semuapenjualan as $sp)
                            <tr>
                                <td id="pgId"> {{ $loop->index+1 }} </td>
                                <td> {{ $sp->tanggal_penjualan }} </td>
                                <td> {{ $sp->nama }} </td>
								<td> {{ $sp->harga_beli }} </td>
								<td> {{ $sp->qty }} </td>
                                <td>
									<button class="btn btn-success btn-simpan">Simpan</button>
                                    <a href="{{ url('/semuapenjualan', [$sp->id]) }}"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
								<div class="row">		
							</div>
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
    @endpush
@endsection