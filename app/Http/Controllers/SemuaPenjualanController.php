<?php

namespace App\Http\Controllers;
use App\Models\SemuaPenjualan;
use App\Models\Penjualan;
use App\Models\StokBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Auth;
use App\Exports\SemuaPenjualanExport;
use App\Models\DetailPenjualan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class SemuaPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // VERSI 1.2
            $data = Penjualan::with('user')
                ->with('detail_penjualan')
                ->join('model_has_roles as mhr', 'mhr.model_id', '=', 'penjualans.user_id')
                ->join('roles', 'roles.id', '=', 'mhr.role_id')
                ->selectRaw('penjualans.*, roles.name as nama_level' )
                ->orderBy('penjualans.tanggal_penjualan', 'desc')
                ->orderBy('penjualans.created_at', 'desc')
                ->get();

            // VERSI 1.0
                // $data = Penjualan::with('user')
                //     ->with('detail_penjualan')
                //     ->join('model_has_roles as mhr', 'mhr.model_id', '=', 'penjualans.user_id')
                //     ->join('roles', 'roles.id', '=', 'mhr.model_id')
                //     ->selectRaw('penjualans.*, roles.name as nama_level' )
                //     ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('nama_user', function (Penjualan $penjualan) {
                    return $penjualan->user->name ?? 'Null' ;
                })
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    return '<div class="table-actions text-center">
                        <a href="#" data-toggle="tooltip" data-placement="top" title="Cetak Ulang Kwitansi" onclick="cetak_kwitansi(' . $data['id'] . ')"><i class="ik ik-printer f-16 mr-15 text-yellow"></i></a>
                        <a href="#" data-toggle="tooltip" data-placement="top" title="Edit" onclick="edit_semua_penjualan(' . $data['id'] . ')"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                        <a href="#" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="delete_semua_penjualan(' . $data['id'] . ')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                    </div>';
                })
                ->make(true);
                // ->toJson();
        }

        return view('semuapenjualan.main');
    }

    public function getSemuaPenjualanList(Request $request)
    {
        $data = SemuaPenjualan::get();

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if ($data->name == 'Super Admin') {
                    return '';
                }
                if (Auth::user()->can('manage_user')) {
                    return '<div class="table-actions">
                            <a href="#productView" data-toggle="modal" data-target="#productView" id="detail"><i
                            class="ik ik-eye f-16 mr-15"></i></a>
                            <a href="'.url('semuapenjualan/'.$data->id).'"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    

    public function edit($id)
    {
        $semuapenjualan = DB::table('penjualans')
        
        ->join('users', 'penjualans.user_id', '=', 'users.id')
        ->join('model_has_roles as b', 'b.model_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'b.model_id')
        ->join('barangs', 'barangs.id', '=', 'b.model_id')
        ->join('stok_barangs', 'stok_barangs.id', '=', 'b.model_id')
        ->join('detail_penjualans', 'detail_penjualans.id', '=', 'b.model_id')
        ->selectRaw('penjualans.id, penjualans.tanggal_penjualan,  users.name, roles.name as nama_level, barangs.nama, stok_barangs.harga_beli, detail_penjualans.qty', )
        ->get();

        return view('semuapenjualan.edit', compact('semuapenjualan'));
    }

    public function show($id)
    {
        
    }

    public function delete(Request $request)
    {
        // return $request->id;
        $deleted_penjualan = Penjualan::find($request->id)->delete();
        $deleted_detail_penjualan = DetailPenjualan::where('penjualan_id', $request->id)->delete();

        if ($deleted_penjualan && $deleted_detail_penjualan) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

}