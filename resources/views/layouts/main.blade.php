<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
<title>Ndaru | @yield('title','')</title>
<!-- initiate head with meta tags, css and script -->
@include('include.head')

</head>
<body id="app" >

<?php
session_start();

use App\Models\StokBarang;

$tanggal_sekarang = date('Y-m-d');
$tanggal_sekarang_plus = date('Y-m-d', strtotime("+180 days", strtotime($tanggal_sekarang)));

// hampir expired
$h_expired_barang_obat = StokBarang::with(['barang'])->where([
	['expired','>' , $tanggal_sekarang], 
	['expired','<',$tanggal_sekarang_plus]
])->get();

// expired
$expired_barang_obat = StokBarang::with('barang')
->where([
	['expired','<=',$tanggal_sekarang]
])->get();

// hampir habis
$h_stok_barang_obat = StokBarang::with(['barang'])
->where('jumlah','>=',1)
->whereColumn([
	['jumlah','<=','minimal_stok']
])->get();

// habis
$stok_barang_obat = StokBarang::with('barang')
->where([
	['jumlah','<=',0]
])->get();

// echo "<script type='text/javascript'>alert('$h_stok_barang_obat');</script>";

// $h_expired_barang_retail = StokBarang::with(array('barang' => function($query) {
// 	$query->where('jenis', '=', 'Retail');
// }))->where([
// 	['expired', '>' , $tanggal_sekarang], 
// 	['expired','<=' , $tanggal_sekarang_plus]
// ])->get();

// $h_stok_barang_retail = StokBarang::with(array('barang' => function($query) {
// 		$query->where('jenis', '=', 'Retail');
// 	}))->where([
// 		['jumlah','>=',1], 
// 		['jumlah','<=',20]
// ])->get();

// $expired_barang_retail = StokBarang::with(array('barang' => function($query) {
// 		$query->where('jenis', '=', 'Retail');
// 	}))->where([
// 		['expired','<=',$tanggal_sekarang]
// ])->get();

// $stok_barang_retail = StokBarang::with(array('barang' => function($query) {
// 		$query->where('jenis', '=', 'Retail');
// 	}))->where([
// 		['jumlah','<=',0]
// ])->get();


?>

<div style="position: fixed;z-index: 999;right: 10px;top: 70px;">
	@if($expired_barang_obat->count() > 0)
	@if(!isset($_SESSION["expired_barang_obat"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ff0000;color: #fff;">
		<a href="{{route('laporanExpired')}}" class="text-white" style="text-decoration: none;">
			{{$expired_barang_obat->count()}} Obat dinyatakan Expired
		</a>
		<button type="button" onclick="session_hapus(`expired_barang_obat`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif

	@if($stok_barang_obat->count() > 0)
	@if(!isset($_SESSION["stok_barang_obat"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ff0000;color: #fff;">
		<a href="{{route('laporanHabis')}}" class="text-white" style="text-decoration: none;">
			{{$stok_barang_obat->count()}} Obat dinyatakan Habis
		</a>
		<button type="button" onclick="session_hapus(`stok_barang_obat`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif 

	{{--@if($expired_barang_retail->count() > 0)
	@if(!isset($_SESSION["expired_barang_retail"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ff0000;color: #fff;">
		<a href="{{route('stokRetail')}}" class="text-white" style="text-decoration: none;">
			{{$expired_barang_retail->count()}} Retail dinyatakan Expired
		</a>
		<button type="button" onclick="session_hapus(`expired_barang_retail`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif

	@if($stok_barang_retail->count() > 0)
	@if(!isset($_SESSION["stok_barang_retail"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ff0000;color: #fff;">
		<a href="{{route('stokRetail')}}" class="text-white" style="text-decoration: none;">
			{{$stok_barang_retail->count()}} Retail dinyatakan Habis
		</a>
		<button type="button" onclick="session_hapus(`stok_barang_retail`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif--}}

	@if($h_expired_barang_obat->count() > 0)
	@if(!isset($_SESSION["h_expired_barang_obat"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ffc100;color: #fff;">
		<a href="{{route('laporanExpired')}}" class="text-white" style="text-decoration: none;">
			{{$h_expired_barang_obat->count()}} Obat dinyatakan Hampir Expired
		</a>
		<button type="button" onclick="session_hapus(`h_expired_barang_obat`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif

	@if($h_stok_barang_obat->count() > 0)
	@if(!isset($_SESSION["h_stok_barang_obat"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ffc100;color: #fff;">
		<a href="{{route('laporanHabis')}}" class="text-white" style="text-decoration: none;">
			{{$h_stok_barang_obat->count()}} Obat dinyatakan Hampir Habis
		</a>
		<button type="button" onclick="session_hapus(`h_stok_barang_obat`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif

	{{--@if($h_expired_barang_retail->count() > 0)
	@if(!isset($_SESSION["h_expired_barang_retail"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ffc100;color: #fff;">
		<a href="{{route('stokRetail')}}" class="text-white" style="text-decoration: none;">
			{{$h_expired_barang_retail->count()}} Retail dinyatakan Hampir Expired
		</a>
		<button type="button" onclick="session_hapus(`h_expired_barang_retail`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif

	@if($h_stok_barang_retail->count() > 0)
	@if(!isset($_SESSION["h_stok_barang_retail"]))
	<div class="alert mb-0 mt-1 alert-dismissible fade show" role="alert" style="background-color: #ffc100;color: #fff;">
		<a href="{{route('stokRetail')}}" class="text-white" style="text-decoration: none;">
			{{$h_stok_barang_retail->count()}} Retail dinyatakan Hampir Habis
		</a>
		<button type="button" onclick="session_hapus(`h_stok_barang_retail`)" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
	</div>
	@endif
	@endif--}}
</div>
<!-- akhir notifikasi -->

<div class="wrapper">
	<!-- initiate header-->
	@include('include.header')
	<div class="page-wrap">
		<!-- initiate sidebar-->
		@include('include.sidebar')

		<div class="main-content">
			<!-- yeild contents here -->
			@yield('content')
		</div>

		<!-- initiate chat section-->
		@include('include.chat')


		<!-- initiate footer section-->
		@include('include.footer')

	</div>
</div>

<!-- initiate modal menu section-->
@include('include.modalmenu')

<!-- initiate scripts-->
@include('include.script')	
<script>
        function session_hapus(nama) {
            $.post("{{route('session_hapus_notif')}}", {nama:nama}).done((data) => {

            })
        }
    </script>
</body>
</html>