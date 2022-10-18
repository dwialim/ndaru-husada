<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use Illuminate\Http\Request;
use DataTables, Validator;

class PajakController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pajak::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    return '<div class="table-actions">
                            <a href="#" onclick="edit_pajak('.$data->id.')"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                            <a href="#" onclick="delete_pajak('.$data->id.')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </div>';
                })
                ->rawColumns(['roles','permissions','action'])
                ->make(true);
        }
        return view('master.pajak.main');
    }

    public function form(Request $request)
    {
        $data = [];
        $data['data'] = (!empty($request->id)) ? Pajak::find($request->id) : "";
        $content = view('master.pajak.form',$data)->render();
        return ['status'=>'success','content'=>$content];
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
                $save = Pajak::find($request->id);
            } else { //CREATE
                $save = new Pajak;
            }
            $save->nama = $request->nama;
            $save->deskripsi = $request->deskripsi;
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
        $deleted = Pajak::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }
}
