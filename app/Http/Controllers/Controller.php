<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\StokOpname;
use App\Models\Faktur;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
	use AuthorizesRequests;
	use DispatchesJobs;
	use ValidatesRequests;

	public function __construct(){
		date_default_timezone_set('Asia/Jakarta');
	}
    
	public function generateKode($model, $prefix){
		// GENERATE KODE
		$num = 0;
		$data = $model::select('kode')
			->where('kode', 'like', $prefix.'%')
			->orderBy('kode', 'desc')
			->first();
		if ($data) {
			$num = explode('-', $data->kode)[1];
		}
		$next_kode = $prefix.'-' . sprintf("%05d", (string)((int)$num + 1));
		return $next_kode;
	}

	public function generateKwitansi(){
		$cur_date = date('Ym');
		$num = 0;
		$data = Penjualan::select('no_kwitansi')
			->where('no_kwitansi', 'like', $cur_date.'%')
			->orderBy('no_kwitansi', 'desc')
			->first();
		if ($data) {
			$num = (int)substr($data->no_kwitansi, 8);
		}
		$next_kode = date('Ymd'.sprintf("%05d", (string)$num + 1));
		return $next_kode;
	}

	public function generateKodeSO(){
		// GENERATE KODE => RESET PER-BULAN
		$cur_date = date('Ym');
		$num = 0;
		$data = StokOpname::select('kode_stok_opname')
			->where('kode_stok_opname', 'like', '%'.$cur_date.'%')
			->orderBy('kode_stok_opname', 'desc')
			->first();
		if ($data) {
			$num = (int)substr($data->kode_stok_opname, 11);
		}
		$next_kode = 'SOP'.date('Ymd'.sprintf("%05d", (string)$num + 1));
		return $next_kode;
	}

	public function generateResep(){
		// GENERATE KODE => RESET PER-HARI
		$cur_date = date('Ymd');
		$num = 0;
		$data = Penjualan::select('nomor_resep')
			->where('nomor_resep', 'like', '%'.$cur_date.'%')
			->orderBy('nomor_resep', 'desc')
			->first();
		if ($data) {
			$num = (int)substr($data->nomor_resep, 13);
		}
		$next_kode = 'NHD-'.date('Ymd'.'-'.sprintf("%03d", (string)$num + 1));
		return $next_kode;
	}

	public function generateKodeFaktur(){
		// GENERATE KODE => RESET PER-BULAN
		$cur_date = date('Ym');
		$num = 0;
		$data = Faktur::select('no_registrasi')
			->where('no_registrasi', 'like', '%'.$cur_date.'%')
			->orderBy('no_registrasi', 'desc')
			->first();
		if ($data) {
			$num = (int)substr($data->no_registrasi, 13);
		}
		$next_kode = 'REG-'.date('Ymd'.'-'.sprintf("%05d", (string)$num + 1));
		return $next_kode;
	}
}
