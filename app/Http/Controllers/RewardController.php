<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Exports\RewardExport;
use App\Models\DetailPenjualan;
use Maatwebsite\Excel\Facades\Excel;

class RewardController extends Controller{
	public function print(Request $request){
		$date = date('Y-m-d');
		$data['date'] = $date;
		$data['startDate'] = $request->startDate;
		$data['endDate'] = $request->endDate;
		$data['judul'] = 'LAPORAN REWARD';
		if ($request->startDate && $request->endDate) {
			// $data['lap'] = Penjualan::selectRaw('penjualans.id, penjualans.nama_pelanggan, detail_penjualans.jenis_penjualan, SUM(detail_penjualans.qty) as total_barang, COUNT(nama_pelanggan) as jumlah_pembelian')
			// ->join('detail_penjualans', 'penjualans.id', 'detail_penjualans.penjualan_id')
			// ->join('stok_barangs as sb','sb.id','detail_penjualans.stok_barang_id')
			// ->join('barangs as br','br.id','sb.barang_id')
			// ->where([
			// 	['br.nama','NOT LIKE','%CYCLOFEM%'],
			// 	['br.nama','NOT LIKE','%TRICLOFEM%'],
			// 	['br.nama','NOT LIKE','%CYCLOGESTON%'],
			// 	['br.nama','NOT LIKE','%DEPO ANDALAN%'],
			// 	['br.nama','NOT LIKE','%ANDALAN HARMONIS%'],
			// ])
			// ->where('detail_penjualans.jenis_penjualan','LIKE', 'Dispensing%') // DISPENSING
			// ->whereBetween('tanggal_penjualan', [$request->startDate, $request->endDate])
			// ->groupBy('nama_pelanggan')
			// ->orderBy('total_barang', 'desc')
			// ->get();
			$this->query($request->startDate,$request->endDate);
			$this->queryDuplikat($request->startDate,$request->endDate);
		}else{
			// $data['lap'] = Penjualan::selectRaw('penjualans.id, penjualans.nama_pelanggan, detail_penjualans.jenis_penjualan, SUM(detail_penjualans.qty) as total_barang, COUNT(nama_pelanggan) as jumlah_pembelian')
			// ->join('detail_penjualans', 'penjualans.id', 'detail_penjualans.penjualan_id')
			// ->join('stok_barangs as sb','sb.id','detail_penjualans.stok_barang_id')
			// ->join('barangs as br','br.id','sb.barang_id')
			// ->where([
			// 	['br.nama','NOT LIKE','%CYCLOFEM%'],
			// 	['br.nama','NOT LIKE','%TRICLOFEM%'],
			// 	['br.nama','NOT LIKE','%CYCLOGESTON%'],
			// 	['br.nama','NOT LIKE','%DEPO ANDALAN%'],
			// 	['br.nama','NOT LIKE','%ANDALAN HARMONIS%'],
			// ])
			// ->where('detail_penjualans.jenis_penjualan','LIKE', 'Dispensing%') // DISPENSING
			// ->groupBy('nama_pelanggan')
			// ->orderBy('total_barang', 'desc')
			// ->get();
			$this->query();
			$this->queryDuplikat();
		}
		$total = 0;
		$persen = 0;
		foreach($this->data as $ky => $ps){ // looping data yang sudah di groupBy(nama pelanggan tidak ada duplikat) untuk pengecekan
			foreach($this->datas as $key => $p){ // looping semua data (nama pelanggan terdapat duplikat) untuk pengecekan
				foreach($p->detail_penjualan as $k => $a){
					if($ps->nama_pelanggan==$p->nama_pelanggan){
						if($a->jenis_penjualan=='Dispensing (perBiji)'){
							$qty = $a->qty;
							$getHarga = $a->stok_barang->harga_dispensing_perbiji;
						}else if($a->jenis_penjualan=='Dispensing (perBox)'){
							$perBox = $a->stok_barang->jumlah_perbox;
							$qty = $a->qty/$perBox;
							$getHarga = $a->stok_barang->harga_dispensing;
						}
						$hasil = $qty*$getHarga;
						$total += $hasil;
					}
				}
			}
			$ps->total = $total;
			$ps->persen = round($ps->total*(2/100));
			$total = 0;
		}

		$data['lap'] = $this->data;

		$date = date('Y-m-d');

		return Excel::download(new RewardExport($data), "Reward ".$date.".xlsx");
	}

	public function query($startDate="",$endDate=""){
		if(!empty($startDate) && !empty($endDate)){
			$data = Penjualan::whereHas('detail_penjualan',function($q){
				$q->where('jenis_penjualan','like','Dispensing%');
			})->whereHas('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->whereHas('stok_barang',function($q2){
					$q2->whereHas('barang',function($q3){
						$q3->where([
							['nama','NOT LIKE','%CYCLOFEM%'],
							['nama','NOT LIKE','%TRICLOFEM%'],
							['nama','NOT LIKE','%CYCLOGESTON%'],
							['nama','NOT LIKE','%DEPO ANDALAN%'],
							['nama','NOT LIKE','%ANDALAN HARMONIS%'],
						]);
					});
				});
			})->with('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->with('stok_barang',function($q2){
					$q2->with('barang');
				});
			})
			->whereBetween('tanggal_penjualan', [$startDate,$endDate])
			->groupBy('nama_pelanggan')
			->get();
		}else{
			$data = Penjualan::whereHas('detail_penjualan',function($q){
				$q->where('jenis_penjualan','like','Dispensing%');
			})->whereHas('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->whereHas('stok_barang',function($q2){
					$q2->whereHas('barang',function($q3){
						$q3->where([
							['nama','NOT LIKE','%CYCLOFEM%'],
							['nama','NOT LIKE','%TRICLOFEM%'],
							['nama','NOT LIKE','%CYCLOGESTON%'],
							['nama','NOT LIKE','%DEPO ANDALAN%'],
							['nama','NOT LIKE','%ANDALAN HARMONIS%'],
						]);
					});
				});
			})->with('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->with('stok_barang',function($q2){
					$q2->with('barang');
				});
			})
			->groupBy('nama_pelanggan')
			->get();
		}
		$this->data = $data;
	}

	public function queryDuplikat($startDate="",$endDate=""){
		if(!empty($startDate) && !empty($endDate)){
			$datas = Penjualan::whereHas('detail_penjualan',function($q){
				$q->where('jenis_penjualan','like','Dispensing%');
			})->whereHas('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->whereHas('stok_barang',function($q2){
					$q2->whereHas('barang',function($q3){
						$q3->where([
							['nama','NOT LIKE','%CYCLOFEM%'],
							['nama','NOT LIKE','%TRICLOFEM%'],
							['nama','NOT LIKE','%CYCLOGESTON%'],
							['nama','NOT LIKE','%DEPO ANDALAN%'],
							['nama','NOT LIKE','%ANDALAN HARMONIS%'],
						]);
					});
				});
			})->with('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->with('stok_barang',function($q2){
					$q2->with('barang');
				});
			})
			->whereBetween('tanggal_penjualan', [$startDate,$endDate])
			->get();
		}else{
			$datas = Penjualan::whereHas('detail_penjualan',function($q){
				$q->where('jenis_penjualan','like','Dispensing%');
			})->whereHas('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->whereHas('stok_barang',function($q2){
					$q2->whereHas('barang',function($q3){
						$q3->where([
							['nama','NOT LIKE','%CYCLOFEM%'],
							['nama','NOT LIKE','%TRICLOFEM%'],
							['nama','NOT LIKE','%CYCLOGESTON%'],
							['nama','NOT LIKE','%DEPO ANDALAN%'],
							['nama','NOT LIKE','%ANDALAN HARMONIS%'],
						]);
					});
				});
			})->with('detail_penjualan',function($q1){
				$q1->where('jenis_penjualan','like','Dispensing%')
				->with('stok_barang',function($q2){
					$q2->with('barang');
				});
			})->get();
		}
		$this->datas = $datas;
	}

	public function index(Request $request){
		if ($request->ajax()) {
			if ($request->startDate && $request->endDate) {
				// versi 1.0
					// $data = Penjualan::selectRaw('penjualans.id, penjualans.nama_pelanggan, detail_penjualans.jenis_penjualan, SUM(detail_penjualans.qty) as total_barang, COUNT(nama_pelanggan) as jumlah_pembelian')
					// ->join('detail_penjualans', 'penjualans.id', 'detail_penjualans.penjualan_id')
					// ->join('stok_barangs as sb','sb.id','detail_penjualans.stok_barang_id')
					// ->join('barangs as br','br.id','sb.barang_id')
					// ->where([
					// 	['br.nama','NOT LIKE','%CYCLOFEM%'],
					// 	['br.nama','NOT LIKE','%TRICLOFEM%'],
					// 	['br.nama','NOT LIKE','%CYCLOGESTON%'],
					// 	['br.nama','NOT LIKE','%DEPO ANDALAN%'],
					// 	['br.nama','NOT LIKE','%ANDALAN HARMONIS%'],
					// ])
					// ->where('detail_penjualans.jenis_penjualan','LIKE', 'Dispensing%') // DISPENSING
					// ->whereBetween('tanggal_penjualan', [$request->startDate, $request->endDate])
					// ->groupBy('nama_pelanggan')
					// ->orderBy('total_barang', 'desc')
					// ->get();

				// versi 1.2
					$this->query($request->startDate,$request->endDate);
					$this->queryDuplikat($request->startDate,$request->endDate);
			}else{
				// versi 1.0
					// $data = Penjualan::selectRaw('penjualans.id, penjualans.nama_pelanggan, detail_penjualans.jenis_penjualan, SUM(detail_penjualans.qty) as total_barang, COUNT(nama_pelanggan) as jumlah_pembelian')
					// ->join('detail_penjualans', 'penjualans.id', 'detail_penjualans.penjualan_id')
					// ->join('stok_barangs as sb','sb.id','detail_penjualans.stok_barang_id')
					// ->join('barangs as br','br.id','sb.barang_id')
					// ->where([
					// 	['br.nama','NOT LIKE','%CYCLOFEM%'],
					// 	['br.nama','NOT LIKE','%TRICLOFEM%'],
					// 	['br.nama','NOT LIKE','%CYCLOGESTON%'],
					// 	['br.nama','NOT LIKE','%DEPO ANDALAN%'],
					// 	['br.nama','NOT LIKE','%ANDALAN HARMONIS%'],
					// ])
					// ->where('detail_penjualans.jenis_penjualan','LIKE', 'Dispensing%') // DISPENSING
					// ->groupBy('nama_pelanggan')
					// ->orderBy('total_barang', 'desc')
					// ->get();

				// versi 1.2
					$this->query();
					$this->queryDuplikat();
			}
			$total = 0;
			$persen = 0;
			foreach($this->data as $ky => $ps){ // looping data yang sudah di groupBy(nama pelanggan tidak ada duplikat) untuk pengecekan
				foreach($this->datas as $key => $p){ // looping semua data (nama pelanggan terdapat duplikat) untuk pengecekan
					foreach($p->detail_penjualan as $k => $a){
						if($ps->nama_pelanggan==$p->nama_pelanggan){
							if($a->jenis_penjualan=='Dispensing (perBiji)'){
								$qty = $a->qty;
								$getHarga = $a->stok_barang->harga_dispensing_perbiji;
							}else if($a->jenis_penjualan=='Dispensing (perBox)'){
								$perBox = $a->stok_barang->jumlah_perbox;
								$qty = $a->qty/$perBox;
								$getHarga = $a->stok_barang->harga_dispensing;
							}
							$hasil = $qty*$getHarga;
							$total += $hasil;
						}
					}
				}
				$ps->total = $total;
				$ps->persen = round($ps->total*(2/100));
				$total = 0;
			}
            return Datatables::of($this->data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<div class="text-center">
                        <a href="javascript:" onclick="show_reward(\''.$data->nama_pelanggan.'\')"><i class="ik ik-eye f-16 mr-15 text-success"></i></a>
                    </div>';
                })
                ->make(true);
        }
        return view('reward.main');
    }

    public function show(Request $request)
    {   
        $data['nama_pelanggan'] = $request->nama_pelanggan;
        // $data['penjualan'] = Penjualan::where('nama_pelanggan', $request->nama_pelanggan)->get();

        // return $detail_penjualan;
        $content = view('reward.show', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }
    
    public function get_detail_penjualan(Request $request){
        $detail_penjualan = DetailPenjualan::with('penjualan', 'stok_barang.barang.satuan')
                ->whereRelation('penjualan', 'nama_pelanggan', $request->nama_pelanggan)
                ->join('stok_barangs as sb','sb.id','detail_penjualans.stok_barang_id')
                ->join('barangs as br','br.id','sb.barang_id')
                ->where([
                    ['br.nama','NOT LIKE','%CYCLOFEM%'],
                    ['br.nama','NOT LIKE','%TRICLOFEM%'],
                    ['br.nama','NOT LIKE','%CYCLOGESTON%'],
                    ['br.nama','NOT LIKE','%DEPO ANDALAN%'],
                    ['br.nama','NOT LIKE','%ANDALAN HARMONIS%'],
                ])
                ->orderBy('detail_penjualans.penjualan_id', 'desc')
                ->get();
            return Datatables::of($detail_penjualan)
                ->addIndexColumn()
                ->editColumn('no_kwitansi', function(DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan->penjualan->no_kwitansi;
                })
                ->editColumn('tanggal', function(DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan->penjualan->tanggal_penjualan;
                })
                ->editColumn('barang', function(DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan->stok_barang->barang->nama;
                })
                ->editColumn('no_batch', function(DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan->stok_barang->no_batch;
                })
                ->editColumn('satuan', function(DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan->stok_barang->barang->satuan->nama;
                })
                ->make(true);
    }
}
