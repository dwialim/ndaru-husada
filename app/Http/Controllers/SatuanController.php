<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use DataTables, Validator;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Satuan::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    return '<div class="table-actions">
                            <a href="#" onclick="edit_satuan('.$data->id.')"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                            <a href="#" onclick="delete_satuan('.$data->id.')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </div>';
                })
                ->rawColumns(['roles','permissions','action'])
                ->make(true);
        }
        return view('master.satuan.main');
    }

    public function form(Request $request)
    {
        $data = [];
        $data['data'] = (!empty($request->id)) ? Satuan::find($request->id) : "";
        $content = view('master.satuan.form',$data)->render();
        return ['status'=>'success','content'=>$content];
    }

    public function simpanSatuan(Request $request){
        // return $request->nama;
        if($request->nama!='first'){
            $data = new Satuan;
            $data->nama = $request->nama;
            $data->save();
            return ['status'=>'success','message'=>'Satuan '.$request->nama.' berhasil ditambahkan','data'=>$data];
        }else{
            return ['status'=>'warning','message'=>'Satuan sudah ada!'];
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'nama'=> 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            if ($request->id != '') { // UPDATE
                $save = Satuan::find($request->id);
            } else { //CREATE
                $save = new Satuan;
            }
            $save->nama = $request->nama;
            $save->save();
            $return = ['status'=>'success', 'code'=>'200', 'message'=>'Data Berhasil Disimpan !!'];
            return response()->json($return);
        }else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        // DELETE BARANG
        $deleted = Satuan::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }
}
