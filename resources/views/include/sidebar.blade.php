<div class="app-sidebar colored">
	<div class="sidebar-header">
		<a class="header-brand" href="{{route('dashboard')}}">
			<div class="logo-img">
				<img height="70", width="80" src="{{ asset('img\image-removebg-preview.png')}}" class="header-brand-img">
			</div>
			N.H.F
		</a>
		<button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
	</div>

	@php
		$segment1 = request()->segment(1);
		$segment2 = request()->segment(2);
		$jenis = request()->jenis;
	@endphp

	<div class="sidebar-content">
		<div class="nav-container">
			<nav id="main-menu-navigation" class="navigation-main">
				<div class="nav-item {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
					<a href="{{route('dashboard')}}"><i class="ik ik-home"></i><span>{{ __('Dashboard')}}</span></a>
				</div>

				@can('superadmin')
				<div class="nav-item {{ ($segment1 == 'user' || $segment1 == 'satuan' || $segment1 == 'pbf' || $segment1 == 'master-barang' || $segment1 == 'pajak' || $segment1 == 'persentase') ? 'active open' : '' }} has-sub">
					<a href="javascript:"><i class="ik ik-menu"></i><span> Data Master </span></a>
					<div class="submenu-content">
						<a href="{{ route('users') }}" class="menu-item {{ ($segment1 == 'user') ? 'active' : '' }}"> Pengguna </a>
						<a href="{{ route('satuan') }}" class="menu-item {{ ($segment1 == 'satuan') ? 'active' : '' }}" > Satuan </a>
						<a href="{{ route('pbf') }}" class="menu-item {{ ($segment1 == 'pbf') ? 'active' : '' }}" >PBF </a>
						<a href="{{ route('master-barang', ['jenis' => 'Obat']) }}" class="menu-item {{ ($jenis == 'Obat') ? 'active' : '' }}" >Obat </a>
						<a href="{{ route('pajak') }}" class="menu-item {{ ($segment1 == 'pajak') ? 'active' : '' }}" >Pajak </a>
						<a href="{{ route('persentase') }}" class="menu-item {{ ($segment1 == 'persentase') ? 'active' : '' }}" >Persentase </a>
					</div>
				</div>
				@endcan

				@can('admin')
				<div class="nav-item {{ ($segment1 == 'stok' || $segment1 == 'pembelian' || $segment1 == 'stok-opname') ? 'active open' : '' }} has-sub">
					<a href="javascript:"><i class="fa fa-dolly-flatbed" style='font-size: 1rem;'></i><span> Inventory </span></a>
					<div class="submenu-content">
						<a href="{{route('beliObat')}}" class="menu-item {{ ($segment1 == 'pembelian') ? 'active' : '' }}">
							<span> Pembelian Obat</span>
						</a>
						<a href="{{route('stokObat')}}" class="menu-item {{ ($segment1 == 'stok') ? 'active' : '' }}">
							<span> Stock Opname </span>
						</a>
						@can('superadmin')
						<a href="{{route('mainStokOpname')}}" class="menu-item {{ ($segment1 == 'stok-opname') ? 'active' : '' }}">
							<span> Stock Adjustment </span>
						</a>
						@endcan
					</div>
				</div>
				@endcan

				<div class="nav-item {{ ($segment1 == 'penjualan' && !$segment2 == 'semua-penjualan') ? 'active' : '' }}">
					<a href="{{route('penjualan')}}"><i class="ik ik-shopping-cart"></i><span> Penjualan </span></a>
				</div>

				@can('superadmin')
				<div class="nav-item {{ $segment1 == 'semua_penjualan' ? 'active' : '' }}">
					<a href="{{route('semuaPenjualan')}}"><i class="ik ik-shopping-cart"></i><span> Semua Penjualan </span></a>
				</div>

				<div class="nav-item has-sub {{ $segment1 == 'retur' ? 'active open' : '' }}">
					<a href="javascript:"><i class="ik ik-refresh-cw"></i> Retur </a>
					<div class="submenu-content">
						<a href="{{ route('retur', ['jenis' => 'Penjualan']) }}" class="menu-item {{ ($segment1 == 'retur' && $jenis == 'Penjualan') ? 'active' : '' }}" > Dari Penjualan </a>
						<a href="{{ route('retur', ['jenis' => 'PBF']) }}" class="menu-item {{ ($segment1 == 'retur' && $jenis == 'PBF') ? 'active' : '' }}" > Ke PBF </a>
					</div>
				</div>
				@endcan

				<div class="nav-item  has-sub {{ ($segment1 == 'laporanpenjualan' || $segment1 == 'labakotor' || $segment1 == 'lababersih' || $segment1 == 'pajakbulanan' || $segment1 == 'laporanexpired' || $segment1 == 'laporanhampirhbs' || $segment1 == 'palinglaku' || $segment1 == 'reward' || $segment1 == 'pengeluaran') ? 'active open' : '' }}">
					<a href="javascript:"><i class="ik ik-book"></i><span> Laporan </span></a>
					<div class="submenu-content">
						@can('superadmin')
						{{-- <a href="{{route('labarugi')}}" class="menu-item {{ ($segment1 == 'labakotor') ? 'active' : '' }}" > Laba/Kotor </a>
						<a href="{{url('lababersih')}}" class="menu-item {{ ($segment1 == 'lababersih') ? 'active' : '' }}" > Laba/Bersih </a> --}}

						<a href="{{route('laporanPenjualan')}}" class="menu-item {{ ($segment1 == 'laporanpenjualan') ? 'active' : '' }}" > Laporan Penjualan </a>

						{{--<a href="{{url('pajakbulanan')}}" class="menu-item {{ ($segment1 == 'pajakbulanan') ? 'active' : '' }}" > Pajak Bulanan </a>--}}
						<a href="{{url('laporanexpired')}}" class="menu-item {{ ($segment1 == 'laporanexpired') ? 'active' : '' }}" > Hampir Expired </a>
						<a href="{{url('laporanhampirhbs')}}" class="menu-item {{ ($segment1 == 'laporanhampirhbs') ? 'active' : '' }}" > Hampir Habis </a>
						<a href="{{route('palinglaku')}}" class="menu-item {{ ($segment1 == 'palinglaku') ? 'active' : '' }}" > Paling Laku </a>
						<a href="{{route('reward')}}" class="menu-item {{ ($segment1 == 'reward') ? 'active' : '' }}" > Reward </a>
						@endcan
						<a href="{{route('pengeluaran')}}" class="menu-item {{ ($segment1 == 'pengeluaran') ? 'active' : '' }}" > Pengeluaran Lain-lain </a>
					</div>
				</div>

				@can('superadmin')
				<div class="nav-item {{ ($segment1 == 'log-activity') ? 'active' : '' }}">
					<a href="{{url('log-activity')}}"><i class="ik ik-user"></i><span> Log.Activity </span></a>
				</div>
				@endcan
			</nav>
		</div>
	</div>
</div>
