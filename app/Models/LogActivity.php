<?php
use App\Models\Users;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\Penjualan;
use App\Models\Satuan;
use App\Models\LogActivity;
use App\Http\Libraries\Datagrid;
use Illuminate\Database\Eloquent\Model;

//use Auth;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'log_activities';
	protected $primaryKey = 'id_log_activity';
	public $timestamps = false;

	public static function saveActivity($page, $aksi, $id)
    {
		if ($page == 'Data Pengguna') {
			$user = Users::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data ('.$user->email.') Pada Halaman '.$page;
		} else if ($page == 'Data Supplier') {
			$supplier = Supplier::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data ('.$supplier->nama_supplier.') Pada Halaman '.$page;
		} else if ($page == 'Data Satuan') {
			$supplier = Satuan::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data ('.$supplier->nama_satuan.') Pada Halaman '.$page;
		} else if ($page == 'Data Obat') {
			$barang = Barang::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data ('.$barang->kode_barang.') Pada Halaman '.$page;
		} else if ($page == 'Data Retail') {
			$barang = Barang::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data ('.$barang->kode_barang.') Pada Halaman '.$page;
		} else if ($page == 'Stok Obat') {
			$stok_barang = StokBarang::find($id);
			$barang = Barang::find($stok_barang->barang_id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data stok ('.$barang->kode_barang.') Pada Halaman '.$page;
		} else if ($page == 'Stok Retail') {
			$stok_barang = StokBarang::find($id);
			$barang = Barang::find($stok_barang->barang_id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data stok ('.$barang->kode_barang.') Pada Halaman '.$page;
		} else if ($page == 'Penjualan') {
			$penjualan = Penjualan::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data pada tanggal ('.$penjualan->tanggal_penjualan.') Pada Halaman '.$page;
		} else if ($page == 'Semua Penjualan') {
			$penjualan = Penjualan::find($id);
			$activity = Auth::user()->name.' Melakukan '.$aksi.' data pada tanggal ('.$penjualan->tanggal_penjualan.') Pada Halaman '.$page;
		}

		$log_activity = new LogActivity;
		$log_activity->activity = $activity;
		$log_activity->tanggal = date('Y-m-d H:i:s');
		$log_activity->save();
		
	}
    
}