<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\DetailRetur;
use App\Models\Faktur;
use App\Models\Penjualan;
use DataTables, Validator, Auth;
use App\Models\Retur;
use App\Models\StokBarang;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->jenis == 'Penjualan') {
                return $this->penjualan_dataTables($request);
            }elseif ($request->jenis == 'PBF') {
                return $this->faktur_dataTables($request);
            }
        }

        return view('retur.main',);
    }

    public function penjualan_dataTables($request)
    {
        $data = Retur::with(['penjualan', 'user'])
                ->where('jenis_retur', $request->jenis)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('nomor', function (Retur $retur) {
                    return $retur
                        ->penjualan
                        ->no_kwitansi ?? 'Null' ;
                })
                ->editColumn('created_at', function (Retur $retur) {
                    return $retur
                        ->created_at
                        ->format('Y-m-d H:i:s') ?? 'Null' ;
                })
                ->editColumn('tanggal', function (Retur $retur) {
                    return $retur
                        ->penjualan
                        ->created_at
                        ->format('Y-m-d H:i:s') ?? 'Null' ;
                })
                ->editColumn('nama_pelanggan', function (Retur $retur) {
                    return $retur
                        ->penjualan
                        ->nama_pelanggan ?? 'Null' ;
                })
                ->editColumn('nama_penerima', function (Retur $retur) {
                    return $retur
                        ->user
                        ->name ?? 'Null' ;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="table-actions">
                        <a href="#" data-toggle="tooltip" data-placement="top" title="Cetak Ulang Kwitansi" onclick="cetak_retur(' . $data['id'] . ',\''."Penjualan".'\')"><i class="ik ik-printer f-16 mr-15 text-yellow"></i></a>
                        <a href="#" onclick="show_retur(' . $data['id'] . ')"><i class="ik ik-eye f-16 mr-15 text-green"></i></a>
                        <a href="#" onclick="delete_retur(' . $data['id'] . ')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                    </div>';
                })
                ->make(true);
    }

    public function faktur_dataTables($request)
    {
        $data = Retur::with(['faktur', 'user'])
                ->where('jenis_retur', $request->jenis)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('nomor', function (Retur $retur) {
                    return $retur
                        ->faktur
                        ->no_faktur_pbf ?? 'Null' ;
                })
                ->editColumn('created_at', function (Retur $retur) {
                    return $retur
                        ->created_at
                        ->format('Y-m-d H:i:s') ?? 'Null' ;
                })
                ->editColumn('tanggal', function (Retur $retur) {
                    return $retur
                        ->faktur
                        ->created_at
                        ->format('Y-m-d H:i:s') ?? 'Null' ;
                })
                ->editColumn('nama_pelanggan', function (Retur $retur) {
                    return $retur
                        ->faktur
                        ->pbf
                        ->nama ?? 'Null' ;
                })
                ->editColumn('nama_penerima', function (Retur $retur) {
                    return $retur
                        ->user
                        ->name ?? 'Null' ;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="table-actions">
                        <a href="#" data-toggle="tooltip" data-placement="top" title="Cetak Ulang Kwitansi" onclick="cetak_retur(' . $data['id'] . ', \''."PBF".'\')"><i class="ik ik-printer f-16 mr-15 text-yellow"></i></a>
                        <a href="#" onclick="show_retur(' . $data['id'] . ')"><i class="ik ik-eye f-16 mr-15 text-green"></i></a>
                        <a href="#" onclick="delete_retur(' . $data['id'] . ')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                    </div>';
                })
                ->make(true);
    }

    public function form(Request $request)
    {
        if ($request->jenis == 'Penjualan') {
            $relation = 'penjualan.detail_penjualan';
        }elseif ($request->jenis == 'PBF') {
            $relation = 'faktur.detail_faktur';
        }else{
            $relation = '';
        }
        $data = [];
        $data['data'] = (!empty($request->id)) ? Retur::with(['detail_retur.stok_barang.barang.satuan', 'user', $relation])->find($request->id) : "";
        $data['jenis'] = $request->jenis;
        $content = view('retur.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function get_kwitansi(Request $request)
    {
        $jenis = $request->jenis;
        $query = $request->q ?? '-';
        if ($jenis == 'Penjualan'){
            $data = Penjualan::selectRaw('id ,no_kwitansi as nomor, nama_pelanggan')
                ->where('no_kwitansi', 'like', "%$query%")
                ->get();
        }elseif($jenis == 'PBF'){
            $data = Faktur::selectRaw('id, no_faktur_pbf as nomor')
                ->where('no_faktur_pbf', 'like', "%$query%")
                ->get();
        }else{
            $data = [];
        }
        return response()->json($data, 200);
    }

    public function get_detail_penjualan(Request $request)
    {
        $id = $request->id ?? 0;
        $detail_penjualan = DetailPenjualan::with([
                'penjualan' => function($query){
                    $query->selectRaw('penjualans.*, penjualans.no_kwitansi as nomor, penjualans.nama_pelanggan as nama, penjualans.tanggal_penjualan as tanggal');
                }, 
                'stok_barang.barang.satuan'
            ])
            ->where('penjualan_id', $id)
            ->get();
        // return $detail_penjualan;
        return Datatables::of($detail_penjualan)
            ->addIndexColumn()
            ->editColumn('barang', function (DetailPenjualan $detail_penjualan) {
                return $detail_penjualan
                    ->stok_barang
                    ->barang
                    ->nama ?? 'Null' ;
            })
            ->editColumn('no_batch', function (DetailPenjualan $detail_penjualan) {
                return $detail_penjualan
                    ->stok_barang
                    ->no_batch ?? 'Null' ;
            })
            ->editColumn('satuan', function (DetailPenjualan $detail_penjualan) {
                return $detail_penjualan
                    ->stok_barang
                    ->barang
                    ->satuan
                    ->nama ?? 'Null' ;
            })
            ->editColumn('jumlah_perbox', function (DetailPenjualan $detail_penjualan) {
                return $detail_penjualan
                    ->stok_barang
                    ->jumlah_perbox ?? 'Null' ;
            })
            ->addColumn('action', function ($data) use($request) {
                if ($request->is_show) {
                    return '';
                }
                    return '
                        <input type="hidden" id="stok_barang_id'.$data['id'].'" value="'.$data->stok_barang->id.'">
                        <input type="hidden" id="qty_penjualan'.$data['id'].'" value="'.$data->qty.'">
                        <input type="hidden" id="nama_barang'.$data['id'].'" value="'.$data->stok_barang->barang->nama.'">
                        <input type="hidden" id="no_batch'.$data['id'].'" value="'.$data->stok_barang->no_batch.'">
                        <input type="hidden" id="jenis_penjualan'.$data['id'].'" value="'.$data->jenis_penjualan.'">
                        <input type="hidden" id="detail_penjualan_id'.$data['id'].'" value="'.$data->id.'">
                        <input type="hidden" id="satuan_barang'.$data['id'].'" value="'.$data->stok_barang->barang->satuan->nama.'">
                        <button class="btn btn-sm p-0 bg-white" type="button" id="btn_tambah_retur'.$data['id'].'" onclick="tambah_retur(' . $data['id'] . ')"><i class="ik ik-plus-square f-20 text-success m-0"></i></button>
                    ';
            })
            ->with('data_parent', $detail_penjualan[0]->penjualan ?? 'Null')
            ->make(true);
    }

    public function get_stok_barang(Request $request)
    {
        $id = $request->id ?? 0;
        $stok_barang = StokBarang::with([
                'faktur' => function($query){
                    $query->join('pbfs', 'fakturs.pbf_id', 'pbfs.id')
                        ->selectRaw('fakturs.*, fakturs.no_faktur_pbf as nomor, pbfs.nama, fakturs.jatuh_tempo as tanggal');
                }, 
                'barang.satuan'
            ])
            ->selectRaw('stok_barangs.*, stok_barangs.jumlah as qty')
            ->where('faktur_id', $id)
            ->get();
        // return $stok_barang[0]->faktur;
        return Datatables::of($stok_barang)
            ->addIndexColumn()
            ->editColumn('barang', function (StokBarang $stok_barang) {
                return $stok_barang
                    ->barang
                    ->nama ?? 'Null' ;
            })
            ->editColumn('satuan', function (StokBarang $stok_barang) {
                return $stok_barang
                    ->barang
                    ->satuan
                    ->nama ?? 'Null' ;
            })
            ->addColumn('action', function ($data) use($request) {
                if ($request->is_show) {
                    return '';
                }
                    return '
                        <input type="hidden" id="stok_barang_id'.$data->id.'" value="'.$data->id.'">
                        <input type="hidden" id="qty_penjualan'.$data->id.'" value="'.$data->qty.'">
                        <input type="hidden" id="no_batch'.$data['id'].'" value="'.$data->no_batch.'">
                        <input type="hidden" id="nama_barang'.$data->id.'" value="'.$data->barang->nama.'">
                        <input type="hidden" id="satuan_barang'.$data->id.'" value="'.$data->barang->satuan->nama.'">
                        <button class="btn btn-sm p-0 bg-white" type="button" id="btn_tambah_retur'.$data->id.'" onclick="tambah_retur(' . $data->id . ')"><i class="ik ik-plus-square f-20 text-success m-0"></i></button>
                    ';
            })
            ->with('data_parent', $stok_barang[0]->faktur ?? 'Null')
            ->make(true);
    }

    public function store(Request $request)
    {
        // return $request->all();
        $jenis = $request->jenis;
        $rules = array(
            'stok_barang_id' => 'required',
            'jumlah' => 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $save = new Retur;
            
            if ($jenis == 'Penjualan') {
                $next_kode = $this->generateKode($save, 'RTA');
                $save->penjualan_id = $request->nomor; // nomor isinya id.
            } elseif ($jenis == 'PBF') {
                $next_kode = $this->generateKode($save, 'RTB');
                $save->faktur_id = $request->nomor; // nomor isinya id.
            }else{
                $return = ['status' => 'error', 'code' => '500', 'message' => 'Terjadi kesalahan'];
                return response()->json($return);
            }
            // generate kode
            $save->kode = $next_kode;
            $save->user_id = Auth::user()->id;
            $save->jenis_retur = $jenis;
            $save->save();

            foreach ($request->stok_barang_id as $key => $value) {
                $detail_retur = new DetailRetur;
                $detail_retur->retur_id = $save->id;
                $detail_retur->stok_barang_id = $value;
                $detail_retur->detail_penjualan_id = $request->detail_penjualan_id[$key] ?? null;
                $detail_retur->deskripsi = $request->deskripsi[$key];
                $detail_retur->qty = $request->jumlah[$key];
                $detail_retur->save();
                
                // MENGURANGI STOK JIKA JENIS RETUR PBF 
                if ($jenis == 'PBF') {
                    $stok_barang = StokBarang::find($value);
                    $stok_barang->jumlah -= $request->jumlah[$key];
                    $stok_barang->save();
                }
            }

            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Berhasil Disimpan !!'];
            return response()->json($return);
        } else {
            return $validator->messages();
        }
    }

    public function change_status(Request $request)
    {
        $detail_retur = DetailRetur::with('stok_barang')->find($request->id);
        $return = [];
        if ($detail_retur) {
            // UBAH STATUS
            $detail_retur->status = $request->status;
            $detail_retur->save();
            
            // KEMBALIKAN JUMLAH STOK BARANG JIKA DITERIMA
            if ($request->status == 1) {
                $stok_barang = StokBarang::find($detail_retur->stok_barang_id);
                $stok_barang->jumlah += $detail_retur->qty;
                $stok_barang->save();
            }


            $return = ['status' => 'success', 'code' => '200', 'message' => 'Status Diubah !!'];
        }
        return response()->json($return);
    }

    public function delete(Request $request)
    {
        // DELETE RETUR
        $deleted = Retur::find($request->id)->delete();
        
        // DELETE DETAIL RETUR
        
        if ($deleted) {
            $detail_retur = DetailRetur::where('retur_id', $request->id)
                ->delete();
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    public function cetak_retur(Request $request)
	{
        if ($request->jenis == 'Penjualan') {
            $relation = 'penjualan.detail_penjualan';
        }elseif ($request->jenis == 'PBF') {
            $relation = 'faktur.detail_faktur';
        }else{
            $relation = '';
        }
		$array_print = [];
		$retur = Retur::with(['detail_retur', 'user', $relation])->find($request->id);
		// return $retur;

		$data['retur'] = $retur;
		$data['jenis'] = $request->jenis;
		$print = view('retur.kwitansi', $data)->render();
		array_push($array_print,$print);
		$return = ['status'=>'success','code'=>200,'message'=>'Data berhasil dicetak', 'print'=>$array_print];
		return response()->json($return);
	}
}
