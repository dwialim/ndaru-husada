<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use App\Models\Barang;
use App\Models\Pbf;
use App\Models\LogActivity;
use App\Models\Faktur;
use App\Models\DetailFaktur;
use App\Models\Pajak;
use App\Models\Satuan;
use App\Models\MstPersentase;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StokImport;

class StokBarangController extends Controller{
	public function import(Request $request)
    {
        $this->validate($request, [
            'file_excel' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file_excel');
        Excel::import(new StokImport, $file);

        return ['status'=>'success','message' => 'Berhasil Import Excel','title' => 'Success'];
    }

	public function mainStokObat(){
		$data['page'] = 'Stok Obat';
		return view('stok.obat.main', $data);
	}

	// public function mainStokRetail(){
	// 	$data['page'] = 'Stok Retail';
	// 	return view('stok.retail.main', $data);
	// }

	public function mainBeliObat(){
		$data['page'] = 'Pembelian Obat';
		return view('pembelian.obat.main', $data);
	}

	// public function mainBeliRetail(){
	// 	$data['page'] = 'Pembelian Retail';
	// 	return view('pembelian.retail.main', $data);
	// }

	public function query($startDate,$endDate){
		if(!empty($startDate) && !empty($endDate)){
			$dateS = new Carbon($startDate);
			$dateE = new Carbon($endDate);
			$data = Faktur::with([
					'stok_barang',
					'detail_faktur',
					'pbf',
				])
			// ->whereBetween('created_at',[$dateS->format('Y-m-d')." 00:00:00", $dateE->format('Y-m-d')." 23:59:59"])->get();
			->whereBetween('created_at',[$startDate, $endDate])->get();
		}
		$this->data = $data;
	}

	public function print(Request $request){
		$date = date('Y-m-d');
		$data['date'] = $date;
		$data['judul'] = 'LAPORAN PEMBELIAN OBAT';

		$startDate = $request->startDate;
		$endDate = $request->endDate;
		
		$this->query($startDate,$endDate);

		// UNTUK AMBIL TOTAL PAJAK PER-FAKTUR
		foreach($this->data as $key => $val){
			$this->totalPajak($val);
		}

		$data['data'] = $this->data;
		$sum = 0;
		$tPajak = 0;
		foreach($this->data as $key => $val){
			if($val->status_piutang == 1){
				$sum += $val->total_pembelian;
				$tPajak += $this->totalPajak($val);
			}
		}
		$data['total'] = $tPajak;
		$data['sum'] = $sum;
		if(count($this->data)>0){
			$content = view('pembelian.obat.excel', $data)->render();
			return ['status' => 'success', 'content' => $content];
		}else{
			return ['status' => 'error', 'message' => 'Data tidak ditemukan pada tanggal tersebut!'];
		}

	}

	public function dataTbStokObat(Request $request){
		// $barang = Barang::select('id')->get();
		if(request()->ajax()) {
			// $data = StokBarang::with(array('barang'))->whereIn('barang_id',$barang)->whereNull('faktur_id')->get();

			// $data = StokBarang::with('barang')->get();
			$data = StokBarang::with(['barang' => function($q){
				$q->with('satuan');
			}])->get();
			return Datatables::of($data)
				->addIndexColumn()
				// SEMENTARA TIDAK DIGUNAKAN
					->addColumn('action',function($row){
						$btn = '<a href="javascript:;" onclick=updated('.$row->id.')><i class="ik ik-edit f-16 mr-15 text-warning"></i></a>';
					// 	$btn .= '<a href="javascript:;" onclick=deleted('.$row->id.')><i class="ik ik-trash-2 f-16 text-danger"></i></a>';
						return $btn;
					})
				->rawColumns(['action'])
				->make(true);
		}
		return response()->json($data,200);
	}

	public function totalPajak($row){
		if(empty($row->detail_faktur[0])){
			return $total = 0;
		}else{
			$cekData = $row->detail_faktur;
			$pembelian = $row->total_pembelian;

			// mulai hitung
			$total = 0;
			$persenDPP = $row->persentase_dpp; 
			// $dpp = round(($pembelian*100)/(100+$persenDPP)); // hitung DPP lama
			foreach ($cekData as $key => $val) {
				if($val->persentase!=null) {
					$total += round($persenDPP * ($val->persentase / 100));
				}else{
					$total += $val->nominal;
				}
				// $val->total = $total;
			}
			$row->total = $total;
			return $total;
		}
	}

	public function dataTbBeliObat(Request $request){
		if(request()->ajax()){
			$startDate = $request->startDate;
			$endDate = $request->endDate;
			if(!empty($startDate) && !empty($endDate)){
				// VERSI 1.0 GET FAKTUR DENGAN STATUS PIUTANG
					// $data = Faktur::with([
					// 	'stok_barang',
					// 	'detail_faktur',
					// 	'pbf',
					// ])->where('status_piutang','=',1)
					// ->whereBetween('created_at',[$startDate,$endDate])->get();

				// VERSI 1.3 GET SEMUA FAKTUR
				$data = Faktur::with([
					'stok_barang',
					'detail_faktur',
					'pbf',
				])->whereBetween('created_at',[$startDate,$endDate])->get();
			}else{
				$data = Faktur::with([
					'stok_barang',
					'detail_faktur',
					'pbf',
				])->get();
			}

			$sum = 0;
			$tPajak = 0;
			foreach($data as $key => $val){
				if($val->status_piutang == 1){
					$sum += $val->total_pembelian;
					$tPajak += $this->totalPajak($val);
				}
			}
			return Datatables::of($data)
				->addIndexColumn()
				->addColumn('pajak', function ($row) {
					return $this->totalPajak($row);
				})
				->addColumn('statusPiutang',function($row){
					if($row->status_piutang == 0){
						$showStatus = '<span class="badge badge-pill badge-success">Lunas</span>';
						// $showStatus = '<a>tes</a>';
					}else{
						$showStatus = '<span class="badge badge-pill badge-warning">Piutang</span>';
					}
					return $showStatus;
				})
				->addColumn('action',function($row){
					$btn = '<a href="javascript:;" onclick=updated('.$row->id.')><i class="ik ik-edit f-16 mr-15 text-warning"></i></a>';
					$btn .= '<a href="javascript:;" onclick=deleted('.$row->id.')><i class="ik ik-trash-2 mr-15 f-16 text-danger"></i></a>';
					if ($row->status_piutang==1) {
						$btn .= '<a href="javascript:;" onclick=uploaded('.$row->id.')><i class="ik ik-upload text-secondary"></i></a>';
					}
					return $btn;
				})
				->with('sumHarga',$sum)
				->with('sumPajak',$tPajak)
				->rawColumns(['action','statusPiutang'])
				->make(true);
		}
	}

	public function getHargaMaster(Request $request){
		// return $request->all();
		$data = Barang::find($request->id);
		return response()->json($data);
	}

	public function getHargaBarang(Request $request){
		// $data = StokBarang::has('barang')->has('pbf')
		// 	->find($request->id);
		$data = StokBarang::has('barang')
			->find($request->id);
		return response()->json($data);
	}

	public function formStokObat(Request $request){
		$faktur = Faktur::max('id');
		$time = date('dmy');
		$unik = "REG-".$time.'-'.($faktur+1);
		$data['mstPersentase'] = MstPersentase::all();

		if (isset($request->id_stok_barang) && !empty($request->id_stok_barang)) {
			// $data['getFaktur'] = Faktur::with('pbf')->where('id',$request->idFaktur)->first();
			// $data['faktur'] = Faktur::with('pbf')->where('id',$request->idFaktur)->get();
			// $data['detailFaktur'] = DetailFaktur::with('pajak')->where('faktur_id',$data['getFaktur']->id)->get();
			// $data['getStokBarang'] = StokBarang::where('faktur_id',$data['getFaktur']->id)->first();
			// $data['stokBarang'] = StokBarang::where('faktur_id',$data['getFaktur']->id)->get();

			$data['getStokBarang'] = StokBarang::find($request->id_stok_barang);
			$data['form'] = 'Stok Obat';
			$data['title']  = 'Edit';
		} else {
			$data['stokBarang'] = '';
			$data['form'] = 'Stok Obat';
			$data['title']  = 'Tambah';
		}

		$data['menu'] = 'stokObat';
		$data['text'] = 'Obat';
		$data['obat'] = Barang::with(['satuan'])->get();
		$data['pbfs'] = Pbf::all();
		$data['satuan'] = Satuan::all();

		$content = view('stok.formStok', $data)->render();
		if (!empty($data['obat'])) {
			return ['status' => 'success','message' => 'Berhasil Mengambil Data', 'content' => $content];
		} else {
			return ['status' => 'success', 'content' => $content];
		}
	}

	public function formBeliObat(Request $request){
		// generate kode Faktur versi 1.0 
			// $faktur = Faktur::max('id');
			// $time = date('dmy');
			// $data['noReg'] = "REG-".$time.'-'.($faktur+1);
		// generate kode Faktur versi 1.2 
		$data['noReg'] = $this->generateKodeFaktur();
		
		$data['mstPersentase'] = MstPersentase::all();

		if (isset($request->idFaktur) && !empty($request->idFaktur)) {
			$data['getFaktur'] = Faktur::with('pbf')->where('id',$request->idFaktur)->first();
			$data['faktur'] = Faktur::with('pbf')->where('id',$request->idFaktur)->get();
			$data['detailFaktur'] = DetailFaktur::with('pajak')->where('faktur_id',$data['getFaktur']->id)->get();
			$data['getDetailFaktur'] = DetailFaktur::where('faktur_id',$data['getFaktur']->id)->first();
			$data['getStokBarang'] = StokBarang::where('faktur_id',$data['getFaktur']->id)->first();
			$data['stokBarang'] = StokBarang::where('faktur_id',$data['getFaktur']->id)->get();

			$data['form'] = 'Pembelian Obat';
			$data['title']  = 'Edit';
		} else {
			$data['getFaktur'] = '';
			$data['faktur'] = '';
			$data['stokBarang'] = '';
			$data['form'] = 'Pembelian Obat';
			$data['title']  = 'Tambah';
		}

		$barang = Barang::select('id')->get();
		$data['data'] = StokBarang::with(
				[
					'faktur' => function($query){
						$query->with('detail_faktur');
					},
					'barang' => function($qy){
						$qy->with('satuan');
					},
					'pbf',
				]
			)->whereIn('barang_id',$barang)->where([
				['faktur_id',$request->idFaktur]
			])->get();

		$data['menu'] = 'pembelianObat';
		$data['text'] = 'Obat';
		$data['mstPajak'] = Pajak::all();
		$data['obat'] = Barang::with(['satuan'])->get();
		$data['pbfs'] = Pbf::all();
		$data['satuan'] = Satuan::all();

		$content = view('stok.formStok', $data)->render();
		if (!empty($data['obat'])) {
			return ['status' => 'success','message' => 'Berhasil Mengambil Data', 'content' => $content];
		} else {
			return ['status' => 'success', 'content' => $content];
		}
	}

	public function getAlamatPBF(Request $request){
		if($request->id != 'first'){
			$data = PBF::where('id',$request->id)->first();
		}else{
			$data = '';
		}
		return response()->json($data,200);
	}

	public function storeBukti(Request $request){
		$data = $request->file('uploadBuktiPembayaran');
		$dirInduk = 'imagesNdaru/';
		$dirPembayaran = 'buktiPembayaran';

		$faktur = Faktur::find($request->id);

		if($data!=null){
			$idGambar = $faktur->no_registrasi;
			$getNama = $data->getClientOriginalName(); 
			$data->move($dirInduk.$dirPembayaran, $idGambar.'-'.$getNama);
			$faktur->notaPembayaran	= $dirInduk.$dirPembayaran.'/'.$idGambar.'-'.$getNama;
			$faktur->status_piutang = 0;
			$faktur->save();
			return [
				'status'=>'success',
				'code'=>200,
				'message'=>'Berhasil Import Gambar',
			];
		}else{
			return [
				'status'=>'error',
				'code'=>205,
				'message'=>'Gagal Import Gambar',
			];
		}

	}

	public function storeStokBarang(Request $request){
		// return $request->all();
		$notaFaktur = $request->file('notaFaktur');
		$notaPembayaran = $request->file('notaPembayaran');
		$dirInduk = 'imagesNdaru/';
		$dirFaktur = 'faktur';
		$dirPembayaran = 'buktiPembayaran';
		// return $request->all();
		// $dt = StokBarang::all();
		// foreach($dt as $k => $p){
		// 	$sb = StokBarang::where('id',$p->id)->first();
		// 	$awal = $sb->stok_awal;
		// 	$perbox = $sb->jumlah_perbox;
		// 	// $jml = $sb->jumlah;
		// 	if($sb->stok_awal==0){
		// 		$sb->jumlah = $awal;
		// 		$sb->jumlah_box = $awal;
		// 	}else{
		// 		$mod = $awal%$perbox;
		// 		$box = ($awal-$mod)/$perbox;
		// 		$biji = $awal;
		// 		$sb->jumlah = $awal;
		// 		$sb->jumlah_box = $box;
		// 	}
		// 	// $sb->stok_awal = $jml;
		// 	$sb->save();
		// 	// return $sb;
		// }
		// return [
		// 	'status'=>'success',
		// 	'code'=>200,
		// 	'message'=>($value != 'baru') ? 'Berhasil Mengubah Data' : 'Berhasil Menambahkan Data'
		// ];
		if (isset($request->id_stok_barang)) {
			$menu = $request->menu[0];
			$arrFaktur = [];
			$arrDetailPajak = [];
			if ($menu=='pembelianObat') {
				if ($request->id_stok_barang[0]=='baru') {
					$faktur = new Faktur;
				}else{
					$faktur = Faktur::find($request->idFaktur[0]);
				}

				$faktur->no_registrasi = $request->noRegistrasi[0];
				$faktur->pbf_id = $request->pbfId[0];
				$faktur->no_faktur_pbf = $request->noFaktur[0];
				$faktur->total_pembelian = $request->fixHarga[0];
				if(isset($request->materai)){
					$faktur->materai = preg_replace("/\D+/", "", $request->materai);
				}else{
					$faktur->materai = null;
				}
				if(isset($request->persentaseDPP)){
					$faktur->persentase_dpp = $request->persentaseDPP;
				}
				$faktur->status_piutang = $request->cekPiutang[0];
				$faktur->jatuh_tempo = date("Y-m-d",strtotime($request->jatuhTempo[0]));

				// upload gambar
				$idGambar = $faktur->no_registrasi;
				if(!empty($faktur->notaPembayaran) && $faktur->status_piutang==1){
					unlink($faktur->notaPembayaran);
					$faktur->notaPembayaran = null;
				}
				if($notaFaktur!=null){
					$getNmFaktur = $notaFaktur->getClientOriginalName(); 
					if(!empty($faktur->notaFaktur) && file_exists(public_path().'/'.$faktur->notaFaktur)){
						unlink($faktur->notaFaktur);
					}
					$notaFaktur->move($dirInduk.$dirFaktur, $idGambar.'-'.$getNmFaktur);
					$faktur->notaFaktur	= $dirInduk.$dirFaktur.'/'.$idGambar.'-'.$getNmFaktur;
				}
				if($notaPembayaran!=null){
					$getNmPembayaran = $notaPembayaran->getClientOriginalName();
					if(!empty($faktur->notaPembayaran) && file_exists(public_path().'/'.$faktur->notaPembayaran)){
						unlink($faktur->notaPembayaran);
					}
					$notaPembayaran->move($dirInduk.$dirPembayaran, $idGambar.'-'.$getNmPembayaran);
					$faktur->notaPembayaran = $dirInduk.$dirPembayaran.'/'.$idGambar.'-'.$getNmPembayaran;
				}
				$faktur->save();
				array_push($arrFaktur, $faktur->id);

				if(isset($request->idDetailPajak)){
					foreach($request->idDetailPajak as $idp => $idDetail){
						if ($idDetail=='baru') {
							$detailFaktur = new DetailFaktur;
						}else{
							$detailFaktur = DetailFaktur::where('id',$idDetail)->first();
						}
						$detailFaktur->faktur_id = $faktur->id;
						$detailFaktur->pajak_id = $request->idMstPajak[$idp];
						if ($request->arrOptionPajak[$idp] == 'nominal') {
							if (!empty($detailFaktur->persentase)) {
								$detailFaktur->persentase = null;
								$detailFaktur->nominal = $request->nominalPajak[$idp];	
							}else{
								$detailFaktur->nominal = $request->nominalPajak[$idp];
							}
						}else{
							if (!empty($detailFaktur->nominal)) {
								$detailFaktur->nominal = null;
								$detailFaktur->persentase = $request->nominalPajak[$idp];
							}else{
								$detailFaktur->persentase = $request->nominalPajak[$idp];
							}
						}
						$detailFaktur->save();
						array_push($arrDetailPajak,$detailFaktur->id);
					}
				}
			}

			$arrStokBarang = [];
			foreach ($request->id_stok_barang as $key => $value) {
				if($value == 'baru'){
					$stokBarang = new stokBarang;
				}else{
					$stokBarang = StokBarang::where('id',$value)->first();
				}
				// return $request->jumlahBox[0];
				if($menu=='pembelianObat'){
					$stokBarang->faktur_id	= $faktur->id;
					$idPBF = $request->pbfId[0];
					
				}else{
					// if(isset($request->pbf[$key])){
					// 	$idPBF = $request->pbf[$key];
					// }else{
					// 	$idPBF = null;
					// }
					$idPBF = (isset($request->pbf)?$request->pbf[$key]:null);
					// $totalStok = $request->jumlahBox[0]*$request->jumlahPerBox[0];
					// return $totalStok;
				}

				// SEBELUM ADA KOLOM STOK AWAL
					// $totalStok = $request->jumlahBox[$key]*$request->jumlahPerBox[$key];
					// if($value=='baru'){ // JIKA PEMBELIAN OBAT BARU
					// 	$stokBarang->stok_awal 		= $totalStok;
					// 	$stokBarang->jumlah_box		= $request->jumlahBox[$key];
					// 	$stokBarang->jumlah_perbox	= $request->jumlahPerBox[$key];
					// 	$stokBarang->jumlah 		= $totalStok;
					// 	$stokBarang->harga_beli		= preg_replace("/\D+/", "", $request->harga_beli[$key]);
					// 	$stokBarang->harga_umum		= preg_replace("/\D+/", "", $request->harga_umum[$key]);
					// 	$stokBarang->harga_resep	= preg_replace("/\D+/", "", $request->harga_resep[$key]);
					// 	$stokBarang->harga_dispensing	= preg_replace("/\D+/", "", $request->harga_dispensing[$key]);
					// 	$stokBarang->harga_dispensing_perbiji = preg_replace("/\D+/", "", $request->harga_dispen_biji[$key]);
					// }else{
					// 	if($stokBarang->jumlah == $stokBarang->stok_awal){ // JIKA STOK MASIH UTUH ATAU BELUM ADA YANG TERJUAL
					// 		$stokBarang->stok_awal 		= $totalStok;
					// 		$stokBarang->jumlah_box		= $request->jumlahBox[$key];
					// 		$stokBarang->jumlah_perbox	= $request->jumlahPerBox[$key];
					// 		$stokBarang->jumlah 		= $totalStok;
					// 		$stokBarang->harga_beli		= preg_replace("/\D+/", "", $request->harga_beli[$key]);
					// 		$stokBarang->harga_umum		= preg_replace("/\D+/", "", $request->harga_umum[$key]);
					// 		$stokBarang->harga_resep	= preg_replace("/\D+/", "", $request->harga_resep[$key]);
					// 		$stokBarang->harga_dispensing	= preg_replace("/\D+/", "", $request->harga_dispensing[$key]);
					// 		$stokBarang->harga_dispensing_perbiji = preg_replace("/\D+/", "", $request->harga_dispen_biji[$key]);
					// 	}
					// }
				$totalStok = $request->jumlahBox[$key]*$request->jumlahPerBox[$key];
				if($value=='baru'){ // JIKA PEMBELIAN OBAT BARU
					$stokBarang->stok_awal 		= $request->stokAwal[$key];
					$stokBarang->jumlah_box		= $request->jumlahBox[$key];
					$stokBarang->jumlah_perbox	= $request->jumlahPerBox[$key];
					$stokBarang->jumlah 		= $totalStok;
					$stokBarang->harga_beli		= preg_replace("/\D+/", "", $request->harga_beli[$key]);
					$stokBarang->harga_umum		= preg_replace("/\D+/", "", $request->harga_umum[$key]);
					$stokBarang->harga_resep	= preg_replace("/\D+/", "", $request->harga_resep[$key]);
					$stokBarang->harga_dispensing	= preg_replace("/\D+/", "", $request->harga_dispensing[$key]);
					$stokBarang->harga_dispensing_perbiji = preg_replace("/\D+/", "", $request->harga_dispen_biji[$key]);
				}else{
					// if($stokBarang->jumlah == $stokBarang->stok_awal){ // JIKA STOK MASIH UTUH ATAU BELUM ADA YANG TERJUAL
						// $stokBarang->stok_awal 		= $request->stokAwal[$key];
						$stokBarang->jumlah_box		= $request->jumlahBox[$key];
						$stokBarang->jumlah_perbox	= $request->jumlahPerBox[$key];
						$stokBarang->jumlah 		= $totalStok;
						$stokBarang->harga_beli		= preg_replace("/\D+/", "", $request->harga_beli[$key]);
						$stokBarang->harga_umum		= preg_replace("/\D+/", "", $request->harga_umum[$key]);
						$stokBarang->harga_resep	= preg_replace("/\D+/", "", $request->harga_resep[$key]);
						$stokBarang->harga_dispensing	= preg_replace("/\D+/", "", $request->harga_dispensing[$key]);
						$stokBarang->harga_dispensing_perbiji = preg_replace("/\D+/", "", $request->harga_dispen_biji[$key]);
					// }
				}
				$stokBarang->barang_id		= $request->barang[$key];
				$stokBarang->no_batch		= $request->no_batch[$key];
				$stokBarang->minimal_stok	= $request->minStok[$key];
				$stokBarang->expired		= date("Y-m-d",strtotime($request->expired[$key]));
				$stokBarang->tgl_masuk		= date("Y-m-d",strtotime($request->tgl_masuk[$key]));
				if(!empty($request->barCode[$key])){
					$stokBarang->barcode = $request->barCode[$key];
				}else{
					$stokBarang->barcode = null;
				}
				if (!empty($request->diskonBeli[$key])) {
					$stokBarang->diskon = $request->diskonBeli[$key];
				}else{
					$stokBarang->diskon = null;
				}

				if(!empty($request->potongan[$key])){
					$stokBarang->potongan = $request->potongan[$key];
				}else{
					$stokBarang->potongan = null;
				}

				if(isset($request->id_stok_barang) && !empty($request->laba[$key])){
					$stokBarang->nominal_laba = $request->laba[$key];
				}else{
					$stokBarang->nominal_laba = null;
				}
				if (isset($request->id_stok_barang) && !empty($request->pajak[$key])) {
					$stokBarang->nominal_pajak = $request->pajak[$key];
				}else{
					$stokBarang->nominal_pajak = null;
				}
				$stokBarang->pbf_id = $idPBF;
				// return $idPBF;
				$stokBarang->save();
				array_push($arrStokBarang,$stokBarang->id);
			}

			if(count($arrStokBarang) > 0 && !empty($arrFaktur)){
				$stokBarangRemove = stokBarang::whereNotIn('id',$arrStokBarang)->whereIn('faktur_id',$arrFaktur)->delete();
			}

			if(count($arrDetailPajak)>0 && !empty($arrFaktur)){
				$detailFakturRemove = DetailFaktur::whereNotIn('id',$arrDetailPajak)->whereIn('faktur_id',$arrFaktur)->delete();
			}
			$data['faktur'] = Faktur::select('id');
			
			if ($stokBarang) {
				return [
					'status'=>'success',
					'code'=>200,
					'message'=>($value != 'baru') ? 'Berhasil Mengubah Data' : 'Berhasil Menambahkan Data'
				];
			} else {
				return [
					'status'=>'error',
					'code'=>250,
					'message'=>(!empty($request->id_stok_barang)) ? 'Gagal Mengubah Data' : 'Gagal Menambahkan Data'
				];
			}
		}
		// LogActivity::saveActivity($page, $aksi, $id);
	}

	public function deleteFaktur(Request $request){
		$doDelete = Faktur::find($request->id);
		if (!empty($doDelete)) {
			$doDelete->delete();
			return ['status'=>'success','message'=>'Data berhasil dihapus!','title'=>'Berhasil'];
		}else{
			return ['status'=>'error','message'=>'Data gagal dihapus!','title'=>'Gagal'];
		}
	}

	public function deleteStokBarang(Request $request){
		$doDelete = stokBarang::find($request->id);
		if (!empty($doDelete)) {
			$doDelete->delete();
			return ['status'=>'success','message'=>'Data berhasil dihapus!','title'=>'Berhasil'];
		}else{
			return ['status'=>'error','message'=>'Data gagal dihapus!','title'=>'Gagal'];
		}
	}
}
