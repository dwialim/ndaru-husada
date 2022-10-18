<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\PbfController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\MstPersentaseController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RetailController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\StokOpnameController;

use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\LabaBersihController;
use App\Http\Controllers\PalingLakuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\PajakBulananController;
use App\Http\Controllers\LaporanExpiredController;
use App\Http\Controllers\LaporanHampirhbsController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SemuaPenjualanController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RolesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () { return view('auth.login'); });

Route::post('pembelian/print',[StokBarangController::class, 'print'])->name('printPembelianObat');
Route::get('laporanexpired/print/', [LaporanExpiredController::class, 'print'])->name('printLaporanExpired');
Route::get('laporanhampirhbs/print/', [LaporanHampirhbsController::class, 'print'])->name('printLaporanHampirHbs');
Route::get('palinglaku/print/', [PalingLakuController::class, 'print'])->name('printPalingLaku');
Route::post('laporanpenjualan/print/',[PenjualanController::class, 'printLaporan'])->name('printLaporanPenjualan');
Route::post('lababersih/print/', [LabaBersihController::class, 'print'])->name('printLabaBersih');
Route::post('labarugi/print/', [LabaRugiController::class, 'print'])->name('printLabaRugi');
Route::get('pajakbulanan/print/', [PajakBulananController::class, 'print'])->name('printPajakBulanan');
Route::get('penjualan/print/', [PenjualanController::class, 'print'])->name('printPenjualan');
Route::get('reward/print/', [RewardController::class, 'print'])->name('printReward');
Route::post('import-master-stok', [StokBarangController::class, 'import'])->name('import-master-stok');


Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
Route::post('login', [LoginController::class,'login']);
Route::post('register', [RegisterController::class,'register']);


Route::get('password/forget',  function () {
	return view('pages.forgot-password');
})->name('password.forget');
Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');


Route::group(['middleware' => 'auth'], function(){
	Route::get('/logout', [LoginController::class,'logout'])->name('logout');
	Route::get('/clear-cache', [HomeController::class,'clearCache']);

	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	
	Route::group(['middleware' => 'can:manage_user'], function(){
		Route::get('/user', [UserController::class,'index'])->name('users');
		Route::get('/user/get-list', [UserController::class,'getUserList']);
		Route::get('/user/create', [UserController::class,'create'])->name('create-user');
		Route::post('/user/create', [UserController::class,'store'])->name('store-user');
		Route::get('/user/{id}', [UserController::class,'edit']);
		Route::post('/user/update', [UserController::class,'update']);
		Route::post('/user/delete', [UserController::class,'delete'])->name('delete-user');
	});
	
	// Data Satuan Route
	Route::group(['prefix' => 'satuan'], function(){
		Route::get('/', [SatuanController::class, 'index'])->name('satuan');
		Route::post('/form', [SatuanController::class,'form'])->name('form-satuan');
		Route::post('/store', [SatuanController::class,'store'])->name('store-satuan');
		Route::post('/simpanSatuan', [SatuanController::class,'simpanSatuan'])->name('simpanSatuan');
		Route::post('/delete', [SatuanController::class,'delete'])->name('delete-satuan');
	});

	// Data PBF Route
	Route::group(['prefix' => 'pbf'], function(){
		Route::get('/', [PbfController::class, 'index'])->name('pbf');
		Route::post('/form', [PbfController::class,'form'])->name('form-pbf');
		Route::post('/store', [PbfController::class,'store'])->name('store-pbf');
		Route::post('/delete', [PbfController::class,'delete'])->name('delete-pbf');
		Route::post('/getKabupaten', [PbfController::class,'getKabupaten'])->name('getKabupaten');
		Route::post('/getKecamatan', [PbfController::class,'getKecamatan'])->name('getKecamatan');
	});

	// Data Barang Route
	Route::group(['prefix' => 'master-barang'], function(){
		Route::get('/', [BarangController::class, 'index'])->name('master-barang');
		Route::post('/form', [BarangController::class,'form'])->name('form-master-barang');
		Route::post('/store', [BarangController::class,'store'])->name('store-master-barang');
		Route::post('/simpanBarang', [BarangController::class,'simpanBarang'])->name('simpanBarang');
		Route::post('/delete', [BarangController::class,'delete'])->name('delete-master-barang');
		Route::post('/import', [BarangController::class,'import'])->name('import-master-barang');
		Route::post('/saveObat', [BarangController::class, 'saveObat'])->name('saveObat');
	});

	// Data Pajak Route
	Route::group(['prefix' => 'pajak'], function(){
		Route::get('/', [PajakController::class, 'index'])->name('pajak');
		Route::post('/form', [PajakController::class,'form'])->name('form-pajak');
		Route::post('/store', [PajakController::class,'store'])->name('store-pajak');
		Route::post('/delete', [PajakController::class,'delete'])->name('delete-pajak');
	});

	// Master Persentase
	Route::group(['prefix' => 'persentase'],function(){
		Route::get('/', [MstPersentaseController::class, 'index'])->name('persentase');
		Route::post('/form', [MstPersentaseController::class, 'form'])->name('formPersentase');
		Route::post('/store', [MstPersentaseController::class, 'store'])->name('storePersentase');
		Route::post('/dataTbMstPersentase', [MstPersentaseController::class, 'dataTbMstPersentase'])->name('dataTbMstPersentase');
		Route::post('/destroy', [MstPersentaseController::class, 'destroy'])->name('destroyPersentase');
	});

	// STOK BARANG
	Route::group(['prefix' => 'stok'], function(){
		Route::get('/obat', [StokBarangController::class, 'mainStokObat'])->name('stokObat');
		Route::post('/formStokObat',[StokBarangController::class, 'formStokObat'])->name('formStokObat');
		Route::post('/getStokObat',[StokBarangController::class, 'dataTbStokObat'])->name('getStokObat');

		Route::post('/getHargaMaster',[StokBarangController::class, 'getHargaMaster'])->name('getHargaMaster');

		Route::post('/storeStokBarang',[StokBarangController::class, 'storeStokBarang'])->name('storeStokBarang');
		Route::post('/deleteStokBarang',[StokBarangController::class, 'deleteStokBarang'])->name('deleteStokBarang');

		Route::get('/retail',[StokBarangController::class,'mainStokRetail'])->name('stokRetail');
		Route::post('/formStokRetail',[StokBarangController::class, 'formStokRetail'])->name('formStokRetail');
		Route::post('/getStokRetail',[StokBarangController::class, 'dataTbStokRetail'])->name('getStokRetail');
		Route::post('/getHargaBarang',[StokBarangController::class, 'getHargaBarang'])->name('getHargaBarang');
	});

	// PENJUALAN
	Route::group(['prefix' => 'penjualan'], function(){
		Route::get('/',[PenjualanController::class,'index'])->name('penjualan');
		Route::post('/',[PenjualanController::class,'getData'])->name('getData');
		Route::post('/store',[PenjualanController::class,'store'])->name('storePenjualan');

		Route::post('/dataTbAllPenjualan',[PenjualanController::class, 'dataTbAllPenjualan'])->name('dataTbAllPenjualan');
		Route::post('cetak_kwitansi/', [PenjualanController::class, 'cetak_kwitansi'])->name('cetak_kwitansi');
	});

	Route::group(['prefix' => 'semua_penjualan'], function(){
		Route::get('/', [SemuaPenjualanController::class, 'index'])->name('semuaPenjualan');
		Route::post('/delete', [SemuaPenjualanController::class, 'delete'])->name('delete_semua_penjualan');
	});

	// RETUR
	Route::group(['prefix' => 'retur'], function(){
		Route::get('/', [ReturController::class, 'index'])->name('retur');
		Route::post('/form', [ReturController::class,'form'])->name('form-retur');
		Route::post('/store', [ReturController::class,'store'])->name('store-retur');
		Route::post('/delete_retur', [ReturController::class,'delete'])->name('delete-retur');
		Route::post('/get_kwitansi', [ReturController::class,'get_kwitansi'])->name('get_kwitansi');
		Route::post('/get_detail_penjualan', [ReturController::class,'get_detail_penjualan'])->name('get_detail_penjualan');
		Route::post('/get_stok_barang', [ReturController::class,'get_stok_barang'])->name('get_stok_barang');
		Route::post('/change_status', [ReturController::class,'change_status'])->name('change_status_retur');
		Route::post('cetak_retur/', [ReturController::class, 'cetak_retur'])->name('cetak_retur');
	});
	// Route::get('/semuapenjualan/get-list', [SemuaPenjualanController::class, 'getSemuaPenjualanList']);
	// Route::post('/semuapenjualan/tambah',[SemuaPenjualanController::class, 'store']);
	// Route::get('/semuapenjualan', [SemuaPenjualanController::class, 'index'])->name('semuaPenjualan');
	// Route::get('/semuapenjualan/{id}', [SemuaPenjualanController::class, 'destroy'])->name('penjualan');
	// Route::get('/semuapenjualan/edit/{id}', [SemuaPenjualanController::class, 'edit']);

	// PEMBELIAN
	Route::group(['prefix' => 'pembelian'], function(){
		Route::post('/storeBukti',[StokBarangController::class, 'storeBukti'])->name('storeBukti');
		Route::get('/obat', [StokBarangController::class, 'mainBeliObat'])->name('beliObat');
		Route::post('/formBeliObat',[StokBarangController::class, 'formBeliObat'])->name('formBeliObat');
		Route::post('/getBeliObat',[StokBarangController::class, 'dataTbBeliObat'])->name('getBeliObat');

		Route::post('/getAlamatPBF',[StokBarangController::class, 'getAlamatPBF'])->name('getAlamatPBF');
		Route::post('/storeBeliBarang',[StokBarangController::class, 'storeBeliBarang'])->name('storeBeliBarang');
		Route::post('/deleteBeliBarang',[StokBarangController::class, 'deleteBeliBarang'])->name('deleteBeliBarang');
		Route::post('/deleteFaktur',[StokBarangController::class, 'deleteFaktur'])->name('deleteFaktur');
		
		Route::get('/retail',[StokBarangController::class,'mainBeliRetail'])->name('beliRetail');
		Route::post('/formBeliRetail',[StokBarangController::class, 'formBeliRetail'])->name('formBeliRetail');
		Route::post('/getBeliRetail',[StokBarangController::class, 'dataTbBeliRetail'])->name('getBeliRetail');
	});

	// STOK OPNAME
	Route::group(['prefix'=>'stok-opname'],function(){
		Route::get('/',[StokOpnameController::class, 'main'])->name('mainStokOpname');
		Route::post('/form',[StokOpnameController::class, 'form'])->name('formStokOpname');
		Route::post('/getStokBarang', [StokOpnameController::class,'getStokBarang'])->name('getStokBarang');
		Route::post('/getStokOpname', [StokOpnameController::class,'getStokOpname'])->name('getStokOpname');
		Route::post('/store',[StokOpnameController::class, 'store'])->name('storeStokOpname');
	});

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:manage_role|manage_user'], function(){
		Route::get('/roles', [RolesController::class,'index']);
		Route::get('/role/get-list', [RolesController::class,'getRoleList']);
		Route::post('/role/create', [RolesController::class,'create']);
		Route::get('/role/edit/{id}', [RolesController::class,'edit']);
		Route::post('/role/update', [RolesController::class,'update']);
		Route::get('/role/delete/{id}', [RolesController::class,'delete']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user'], function(){
		Route::get('/permission', [PermissionController::class,'index']);
		Route::get('/permission/get-list', [PermissionController::class,'getPermissionList']);
		Route::post('/permission/create', [PermissionController::class,'create']);
		Route::get('/permission/update', [PermissionController::class,'update']);
		Route::get('/permission/delete/{id}', [PermissionController::class,'delete']);
	});

	// get permissions
	Route::get('get-role-permissions-badge', [PermissionController::class,'getPermissionBadgeByRole'])->name('get-role-permissions');


	// permission examples
    Route::get('/permission-example', function () {
    	return view('permission-example');
    });

	Route::post('/session_hapus_notif','DashboardController@session_hapus_notif')->name('session_hapus_notif');
    // API Documentation
    Route::get('/rest-api', function () { return view('api'); });
	
    // Editable Datatable
	Route::get('/table-datatable-edit', function () {
		return view('pages.datatable-editable');
	});

	//Retail
	Route::get('/retail/tambah', function () {
		return view('retail.create');
	});
    Route::get('/retail/get-list', [RetailController::class, 'getRetailList']);
    Route::post('/retail/tambah',[RetailController::class, 'store']);
    Route::get('/retail', [RetailController::class, 'index']);
    Route::get('/retail/detail/{id}', [RetailController::class, 'detail']);
    Route::get('/retail/edit/{id}', [RetailController::class, 'edit']);
    Route::put('/retail/edit/{id}', [RetailController::class, 'update']);
    Route::get('/retail/{id}', [RetailController::class, 'destroy']);

	Route::get('/obat/tambah', function () {
		return view('obat.create');
	});

    Route::get('/obat/get-list', [ObatController::class, 'getObatList']);
    Route::post('/obat/tambah',[ObatController::class, 'store']);
    Route::get('/obat/detail/{id}', [ObatController::class, 'detail']);
    Route::get('/obat/edit/{id}', [ObatController::class, 'edit']);
    Route::put('/obat/edit/{id}', [ObatController::class, 'update']);
    Route::get('/obat/{id}', [ObatController::class, 'destroy']);

	// START LAPORAN
	//laporanexpired
    Route::get('/laporanexpired/get-list', [LaporanExpiredController::class, 'getLaporanExpiredList'])->name('getLaporanExpiredList');
    Route::get('/laporanexpired', [LaporanExpiredController::class, 'index'])->name('laporanExpired');
    Route::get('/laporanexpired/{id}', [LaporanExpiredController::class, 'destroy']);

	//lababersih
    Route::get('/lababersih', [LabaBersihController::class, 'index'])->name('lababersih');
    Route::get('/lababersih/{id}', [LabaBersihController::class, 'destroy']);

    // penjualan
    Route::get('/laporanpenjualan',[PenjualanController::class, 'laporan'])->name('laporanPenjualan');

	//pajakbulanan
    Route::get('/pajakbulanan', [PajakBulananController::class, 'index'])->name('pajakbulanan');
    Route::get('/pajakbulanan/{id}', [PajakBulananController::class, 'destroy']);

	//labarugi
    Route::get('/labakotor', [LabaRugiController::class, 'index'])->name('labarugi');
    Route::get('/labarugi/{id}', [LabaRugiController::class, 'destroy']);

    //Route laporanhampirhbs
	Route::get('/laporanhampirhbs/get-list', [LaporanHampirhbsController::class, 'getLaporanHampirhbsList'])->name('getLaporanHampirhbsList');
    Route::get('/laporanhampirhbs', [LaporanHampirhbsController::class, 'index'])->name('laporanHabis');
    Route::get('/laporanhampirhbs/{id}', [LaporanHampirhbsController::class, 'destroy']);

	//Route paling laku
    Route::get('/palinglaku', [PalingLakuController::class, 'index'])->name('palinglaku');
    Route::get('/palinglaku/{id}', [PalingLakuController::class, 'destroy']);
	Route::get('/barang', [BarangController::class, 'index']);

	// Route Reward
	Route::group(['prefix' => 'reward'], function (){
		Route::get('/', [RewardController::class, 'index'])->name('reward');
		Route::post('/show', [RewardController::class, 'show'])->name('show-reward');
		Route::post('/get_detail_penjualan', [RewardController::class, 'get_detail_penjualan'])->name('get_detail_penjualan_reward');
	});

    // pengeluaran lain-lain
    Route::group(['prefix'=> 'pengeluaran'], function(){
	    Route::get('/', [PengeluaranController::class, 'index'])->name('pengeluaran');
	    Route::post('/form', [PengeluaranController::class, 'form'])->name('formPengeluaran');
	    Route::post('/store', [PengeluaranController::class, 'store'])->name('storePengeluaran');
	    Route::post('/destroy', [PengeluaranController::class, 'destroy'])->name('destroyPengeluaran');
	    Route::post('/getPengeluaran', [PengeluaranController::class, 'getPengeluaran'])->name('getPengeluaran');
    });
    // END LAPORAN

	Route::group(['prefix' => 'log-activity'], function () {
        Route::get('/','LogActivityController@main')->name('logActivity');
    });
	
    // Themekit demo pages
	// Route::get('/calendar', function () { return view('pages.calendar'); });
	// Route::get('/charts-amcharts', function () { return view('pages.charts-amcharts'); });
	// Route::get('/charts-chartist', function () { return view('pages.charts-chartist'); });
	// Route::get('/charts-flot', function () { return view('pages.charts-flot'); });
	// Route::get('/charts-knob', function () { return view('pages.charts-knob'); });
	// Route::get('/forgot-password', function () { return view('pages.forgot-password'); });
	// Route::get('/form-addon', function () { return view('pages.form-addon'); });
	// Route::get('/form-advance', function () { return view('pages.form-advance'); });
	// Route::get('/form-components', function () { return view('pages.form-components'); });
	// Route::get('/form-picker', function () { return view('pages.form-picker'); });
	// Route::get('/invoice', function () { return view('pages.invoice'); });
	// Route::get('/layout-edit-item', function () { return view('pages.layout-edit-item'); });
	// Route::get('/layouts', function () { return view('pages.layouts'); });

	// Route::get('/navbar', function () { return view('pages.navbar'); });
	// Route::get('/profile', function () { return view('pages.profile'); });
	// Route::get('/project', function () { return view('pages.project'); });
	// Route::get('/view', function () { return view('pages.view'); });

	// Route::get('/table-bootstrap', function () { return view('pages.table-bootstrap'); });
	// Route::get('/table-datatable', function () { return view('pages.table-datatable'); });
	// Route::get('/taskboard', function () { return view('pages.taskboard'); });
	// Route::get('/widget-chart', function () { return view('pages.widget-chart'); });
	// Route::get('/widget-data', function () { return view('pages.widget-data'); });
	// Route::get('/widget-statistic', function () { return view('pages.widget-statistic'); });
	// Route::get('/widgets', function () { return view('pages.widgets'); });

	// themekit ui pages
	// Route::get('/alerts', function () { return view('pages.ui.alerts'); });
	// Route::get('/badges', function () { return view('pages.ui.badges'); });
	// Route::get('/buttons', function () { return view('pages.ui.buttons'); });
	// Route::get('/cards', function () { return view('pages.ui.cards'); });
	// Route::get('/carousel', function () { return view('pages.ui.carousel'); });
	// Route::get('/icons', function () { return view('pages.ui.icons'); });
	// Route::get('/modals', function () { return view('pages.ui.modals'); });
	// Route::get('/navigation', function () { return view('pages.ui.navigation'); });
	// Route::get('/notifications', function () { return view('pages.ui.notifications'); });
	// Route::get('/range-slider', function () { return view('pages.ui.range-slider'); });
	// Route::get('/rating', function () { return view('pages.ui.rating'); });
	// Route::get('/session-timeout', function () { return view('pages.ui.session-timeout'); });
	// Route::get('/pricing', function () { return view('pages.pricing'); });


	// new inventory routes
	// Route::get('/inventory', function () { return view('inventory.dashboard'); });
	// Route::get('/pos', function () { return view('inventory.pos'); });
	// Route::get('/products', function () { return view('inventory.product.list'); });
	// Route::get('/products/create', function () { return view('inventory.product.create'); });
	// Route::get('/categories', function () { return view('inventory.category.index'); });
	// Route::get('/sales', function () { return view('inventory.sale.list'); });
	// Route::get('/sales/create', function () { return view('inventory.sale.create'); });
	// Route::get('/purchases', function () { return view('inventory.purchase.list'); });
	// Route::get('/purchases/create', function () { return view('inventory.purchase.create'); });
	// Route::get('/customers', function () { return view('inventory.people.customers'); });
	// Route::get('/suppliers', function () { return view('inventory.people.suppliers'); });

});

Route::post('/sessionDestroy',[DashboardController::class,'sessionDestroy'])->name('sessionDestroy');
Route::get('/register', function () { return view('pages.register'); });
Route::get('/login-1', function () { return view('pages.login'); });