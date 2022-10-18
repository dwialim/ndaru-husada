<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Hash, Auth, DataTables;
use App\Exports\PenjualanExport;
use App\Models\MstPersentase;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanController extends Controller
{
	public function print() 
    {
        return Excel::download(new PenjualanExport, 'Cetak List Penjualan.xlsx');
    }

	public function index(Request $request){
		$data['tanggal_penjualan'] = date('Y-m-d');
		// $data['barang'] = Barang::all();
		// $data['stok_barang'] = StokBarang::has('barang')->has('pbf')->with('barang.satuan')
		// 	->orderBy('barang_id', 'desc')->get();
		$data['stok_barang'] = StokBarang::has('barang')->with('barang.satuan')
			->orderBy('barang_id', 'desc')->get();
		$data['mstPersentase'] = MstPersentase::all();
		// $data['kode'] = $this->generateResep();

		// IF EDIT PENJUALAN
		if ($request->id) {
			$data['penjualan'] = Penjualan::with('detail_penjualan.stok_barang.barang')
				->with('user')->find($request->id);
		}
		return view('penjualan.main',$data);
	}

	public function getData(Request $request){
		// $data = StokBarang::where('barang_id',$request->id)->first();
		$data['data'] = StokBarang::with('barang.satuan')->find($request->id);
		$data['kode'] = $this->generateResep();
		$jumlah = $request->jumlah;
		$cekJual = $request->cekPenjualan;

		if (!empty($data['data'])) {
			if($jumlah != null || $jumlah != 0){
				if($cekJual=='Dispensing (perBox)'){
					$perBox = $data['data']->jumlah_perbox;
					$sisaBox = (int) ($data['data']->jumlah / $perBox);	// parse float to int
					if($sisaBox >= $jumlah){
						$data['sisaBox'] = $sisaBox;
						return response()->json($data, 200);
					}else{
						$return = ['status'=>'error','message'=>'Stok '.$data['data']->barang->nama.' tersisa '.$sisaBox.' Box!'];
					}
				}else{
					if ($data['data']->jumlah >= $jumlah) {
						return response()->json($data, 200);
					}elseif($data['data']->jumlah != 0){
						$return = ['status'=>'error','message'=>'Stok '.$data['data']->barang->nama.' tersisa '.$data['data']->jumlah.' Biji!'];
					}
					else{
						$return = ['status'=>'error','message'=>'Stok '.$data['data']->barang->nama.' habis!'];
					}
				}
			}else{
				$return = ['status'=>'error','message'=>'Masukkan jumlah barang dengan benar!'];
			}
		}else{
			$return = ['status'=>'error','message'=>' belum ada stok!'];
			// $return = ['status'=>'error','message'=>$data->barang->nama.' belum ada stok!'];
		}
		return response()->json($return,200);
	}

	// cari MESSAGE "Stok Kurang" dalam array, jika ada maka respone => TRUE
	public function multiSearch($array, $search){
		foreach($array as $key => $val){
			if(in_array($search, $val)){
				return true;
			}else{
				return false;
			}
		}
	}

	public function store(Request $request){
		// return $request->all();
		$arrWadah = [];
		$arrFind = [];

		foreach($request->cekData as $cek => $ceks){
			// SIMPAN PENJUALAN KEDALAM VARIABEL arrWadah
			foreach($request->id_stok_barang as $key => $p){
				$getSB = StokBarang::select('jumlah_perbox')->where('id',$p)->first();
				$arrWadah[$key]['id'] = $request->id_stok_barang[$key];
				$arrWadah[$key]['qty'] = $request->jumlah[$key];
				$arrWadah[$key]['perBox'] = $getSB->jumlah_perbox;
				$arrWadah[$key]['jenis'] = $request->jenis_penjualan[$key];
			}

			// MENGAMBIL KOLOM ARRAY TERTENTU
			$keys = array_column($arrWadah, 'id');
			$qtys = array_column($arrWadah, 'qty');
			$jenis = array_column($arrWadah, 'jenis');
			$perBoxs = array_column($arrWadah, 'perBox');
			$qty = 0;

			// MULAI MENGHITUNG QTY DARI PENJUALAN, BERDASARKAN ID DAN JENIS PENJUALAN
			foreach($keys as $k => $v){
				if($jenis[$k]=='Dispensing (perBox)'){ // JIKA PENJUALAN perBox
					$qty += $qtys[$k]*$perBoxs[$k];
				}else{ // JIKA PENJUALAN perBiji
					$qty += $qtys[$k];
				}
				$results[] = ['id'=>$v,'qty'=>$qty]; // INPUT DATA PENJUALAN YANG SUDAH DI HITUNG(berdasarkan ID dan JENIS PENJUALAN) KEDALAM array $results[]
				$qty = 0; // SET ulang QTY MENJADI NOL
			}

			// MERGE ARRAY JIKA ADA DUPLIKAT KEY(ID)
			$arrs = array_values(array_reduce($results,
				function(array $a, array $v){
					$k = $v['id'];
					if(!array_key_exists($k,$a)){ // SET ARRAY (KEY)
						$a[$k] = $v;
					}else{ // jika KEY sudah di-SET, mulai hitung semua total QTY(hanya berdasarkan ID)
						$a[$k]['qty'] += $v['qty'];
					}
					return $a;
				},
				[] // return $a dalam bentuk ARRAY
			));

			// buat array baru untuk menyimpan JUMLAH StokBarang berdasarkan ID
			foreach($arrs as $key => $val){
				$findSB = StokBarang::where('id',$val)->first();
				$arrFind[$key]['id'] = $findSB->id;
				$arrFind[$key]['jumlah'] = $findSB->jumlah;
			}

			$x = [];
			// buat pengecekan penjualan, QTY jual melebihi stok barang atau tidak, kemudian simpan ke array
			foreach($arrFind as $key => $val){
				if($arrs[$key]['qty'] > $val['jumlah']){
					$x[$key]['id'] = $val['id'];
					$x[$key]['message'] = 'Stok Kurang';
				}else{
					$x[$key]['id'] = $val['id'];
					$x[$key]['message'] = 'Stok Lebih';
				}
			}
			
			// pengecekan jika terdapat message "Stok Kurang" (true) di dalam array $x[] maka respone error
			if($this->multiSearch($x,'Stok Kurang') == true && $request->cekData[$cek] == 'dataBaru'){
				return ['status'=>'error','message'=>'Penjualan Melebihi Stok!'];
			}else{
				// return 'cek';
				$array_print = [];
				if (!empty($request->id_stok_barang)) {
					if ($request->penjualan_id != '') { // UPDATE PENJUALAN
						$penjualan = Penjualan::find($request->penjualan_id);
						// CEK DETAIL PENJUALAN YG DIHAPUS
						$detail_penjualan_deleting = DetailPenjualan::where('penjualan_id', $request->penjualan_id)
							->whereNotIn('id', $request->id_detail_penjualan);
						$detail_penjualan_deleting->delete();
					} else { // CREATE PENJUALAN
						$no_kwitansi = $this->generateKwitansi();
						$penjualan = new Penjualan;
						$penjualan->user_id = Auth::user()->id;
						$penjualan->no_kwitansi = $no_kwitansi;
					}

					if(!empty($request->namaPasien)||!empty($request->umurPasien)||!empty($request->alamatPasien)||!empty($request->namaDokter)||!empty($request->nomorResep)){
						$penjualan->nama_pasien		= strtolower($request->namaPasien);
						$penjualan->umur_pasien		= $request->umurPasien;
						$penjualan->alamat_pasien	= strtolower($request->alamatPasien);
						$penjualan->nama_dokter		= strtolower($request->namaDokter);
						$penjualan->nomor_resep		= $request->nomorResep;
						$penjualan->tanggal_resep	= date("Y-m-d",strtotime($request->tanggalResep));
					}
					$penjualan->nama_pelanggan		= strtolower($request->nama_pelanggan);
					$penjualan->jumlah_bayar		= $request->jumlah_bayar;
					$penjualan->kembalian			= $request->kembalian;
					$penjualan->tanggal_penjualan = $request->tanggal_penjualan;
					$penjualan->save();

					foreach($request->id_stok_barang as $key => $val){
						$jumlahPerBox = 0;
						if($request->cekData[$cek] == 'dataBaru'){
							$stok_barang = StokBarang::where([
								['id',$val],
								['jumlah','>=',$request->jumlah[$key]],
							])->firstOrFail();

						}else{
							$stok_barang = StokBarang::where([
								['id',$val],
							])->first();
						}
						$jumlahPerBox = $stok_barang->jumlah_perbox; //get jumlah perbox

						if ($request->id_detail_penjualan[$key] != 0) { // JIKA UPDATE DETAIL PENJUALAN
							$detail_penjualan = DetailPenjualan::find($request->id_detail_penjualan[$key]);
						} else { // CREATE NEW DETAIL PENJUALAN
							$detail_penjualan = new DetailPenjualan;
							$detail_penjualan->penjualan_id = $penjualan->id;
							$detail_penjualan->stok_barang_id = $val;
							$detail_penjualan->jenis_penjualan = $request->jenis_penjualan[$key];

						}
						if($request->jenis_penjualan[$key]=='Dispensing (perBox)'){
							$total = $jumlahPerBox*$request->jumlah[$key]; // jumlah perbox * jumlah box(yang dibeli)
							$stok_barang->jumlah = ($stok_barang->jumlah + $detail_penjualan->qty) - $total; // jumlah stok barang di tambah dengan qty lama kemudian dikurangi dengan qty baru
							$jumlahBox = (int)($stok_barang->jumlah/$jumlahPerBox); // cari sisa jumlahBox
							$stok_barang->jumlah_box = $jumlahBox;
						}else{
							$stok_barang->jumlah = ($stok_barang->jumlah + $detail_penjualan->qty) - $request->jumlah[$key];
							$jumlahBox = (int)($stok_barang->jumlah/$jumlahPerBox); // cari sisa jumlahBox
							$stok_barang->jumlah_box = $jumlahBox;
						}
						$stok_barang->save();
						
						if($request->jenis_penjualan[$key]=='Dispensing (perBox)'){
							$total = $jumlahPerBox*$request->jumlah[$key];
							$detail_penjualan->qty = $total;
						}else{
							$detail_penjualan->qty = $request->jumlah[$key];
						}
						$detail_penjualan->save();
					}

					$data['penjualan'] = $penjualan;
					$data['stok_barang'] = $request;
					$data['mstPersentase'] = MstPersentase::all();
					$print = view('penjualan.kwitansi', $data)->render();

					array_push($array_print,$print);
					$return = ['status'=>'success','code'=>200,'message'=>'Data berhasil disimpan', 'print'=>$array_print];
					return response()->json($return);
				}
			}
		}
	}

	// public function semuaPenjualan(){
	// 	$data['page'] = 'Penjualan';
	// 	return view('penjualan.semuaPenjualan.main',$data);
	// }

	public function dataTbAllPenjualan(Request $request){
	}

	public function cetak_kwitansi(Request $request){
		$array_print = [];
		$penjualan = Penjualan::has('user')
			->with('detail_penjualan')
			->with('user')
			->find($request->id);

		$data['penjualan'] = $penjualan;
		$data['mstPersentase'] = MstPersentase::all();
		$print = view('penjualan.kwitansi', $data)->render();
		array_push($array_print,$print);
		$return = ['status'=>'success','code'=>200,'message'=>'Data berhasil disimpan', 'print'=>$array_print];
		return response()->json($return);
	}

	private $total;

	// MULAI HITUNG LABA BERSIH
	public function hitungLabaRugi($harga,$value, $perBox){
		$total = 0;
		if($value->jenis_penjualan =="Dispensing (perBox)"){
			$selisih = round($harga - $value->stok_barang->harga_beli);
			$labarugi = $selisih * ($value->qty/$perBox);
			$omset = $harga*($value->qty/$perBox);
		}else{
			$selisih = round($harga - ($value->stok_barang->harga_beli/$perBox));
			$labarugi = $selisih * $value->qty;
			$omset = $harga*$value->qty;
		}

		$value->selisih = $selisih;
		$value->labarugi = $labarugi;
		$value->omset = $omset;
		$total += $labarugi;
		$this->total += $total;
	}

	// PENGECEKAN JENIS PENJUALAN
	public function cekJenisPenjualan($data){
		foreach($data as $key => $value){
			$perBox = $value->stok_barang->jumlah_perbox;
			if($value->jenis_penjualan =="Umum"){
				$this->hitungLabaRugi($value->stok_barang->harga_umum, $value, $perBox);
			}elseif($value->jenis_penjualan =="Resep"){
				$this->hitungLabaRugi($value->stok_barang->harga_resep, $value, $perBox);
			}elseif($value->jenis_penjualan =="Dispensing (perBox)"){
				$this->hitungLabaRugi($value->stok_barang->harga_dispensing, $value, $perBox);
			}elseif($value->jenis_penjualan =="Dispensing (perBiji)"){
				$this->hitungLabaRugi($value->stok_barang->harga_dispensing_perbiji, $value, $perBox);
			}
		}
	}

	// GET DATA
	public function query($startDate,$endDate){
		if(!empty($startDate) && !empty($endDate)){
			$data = DetailPenjualan::whereHas('penjualan',function($query) use($startDate,$endDate){
				$query->whereBetween('tanggal_penjualan',[$startDate,$endDate]);
			})->has('stok_barang')
			->with('stok_barang.barang.satuan')
			->get();
		}
		else{
			$data = DetailPenjualan::has('penjualan')->has('stok_barang')
			->with('stok_barang.barang.satuan')
			->get();
		}
		$this->data = $data; // SET PROPERTY DATA SUPAYA DAPAT DI AKSES DI KETIKA METHOD DIPANGGIL
	}

	public function laporan(Request $request){
		$startDate = $request->startDate;
		$endDate = $request->endDate;

		$this->query($startDate,$endDate); // GET DATA FROM FUNCTION
		$this->cekJenisPenjualan($this->data); // CEK JENIS PENJUALAN

		if ($request->ajax()) {
			
			// return $data;
			return Datatables::of($this->data)
				->addIndexColumn()
				->addColumn('total', "$this->total")
				->editColumn('barang', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
						->stok_barang
						->barang
						->nama ?? 'Null' ;
				})
				->editColumn('satuan', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->barang
					->satuan
					->nama ?? 'Null' ;
				})
				->editColumn('jenis', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->barang
					->jenis ?? 'Null' ;
				})
				->editColumn('expired', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->expired ?? 'Null' ;
				})
				->editColumn('jenis_penjualan', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->jenis_penjualan ?? 'Null' ;
				})
				->editColumn('harga_beli', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_beli ?? 'Null' ;
				})
				->editColumn('harga_umum', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_umum ?? 0 ;
				})
				->editColumn('harga_resep', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_resep ?? 'Null' ;
				})
				->editColumn('harga_dispensing', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_dispensing ?? 'Null' ;
				})
				->editColumn('harga_dispensing_perbiji', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->harga_dispensing_perbiji ?? 'Null' ;
				})
				->editColumn('jumlah_perbox', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->jumlah_perbox ?? 'Null' ;
				})
				->editColumn('batch', function (DetailPenjualan $detail_penjualan) {
					return $detail_penjualan
					->stok_barang
					->no_batch ?? 'Null' ;
				})
				->editColumn('idTRX', function (DetailPenjualan $detail_penjualan) {
					$trx = Penjualan::where('id',$detail_penjualan->penjualan_id)->first();
					// return $detail_penjualan
					// ->stok_barang
					// ->no_batch ?? 'Null' ;
					return $trx->no_kwitansi ?? "Null";
				})
				->with('total',$this->total)
				->make(true);
		}

		return view('penjualan.laporan', $data = ['total' => $this->total]);
	}

	public function printLaporan(Request $request){
		$startDate = $request->startDate;
		$endDate = $request->endDate;
		$data['totalLabaRugi'] = preg_replace("/\D+/", "", $request->totalLabaRugi);
		$date = date('Y-m-d');
		$data['date'] = $date;
		$data['judul'] = 'LAPORAN PENJUALAN';

		$this->query($startDate,$endDate); // GET DATA FROM FUNCTION
		$this->cekJenisPenjualan($this->data); // CEK JENIS PENJUALAN
		
		$data['lap'] = $this->data;
		// $data['total'] = $total;

		$date = date('Y-m-d');

		$content = view('penjualan.excel', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}
}
