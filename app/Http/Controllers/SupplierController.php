<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables, Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return view('master.pbf.main');
    }

    public function form(Request $request)
    {
        $data = [];
        $data['data'] = (!empty($request->id)) ? "" : "";
        $content = view('master.pbf.form',$data)->render();
        return ['status'=>'success','content'=>$content];
    }

    public function store(Request $request)
    {
        $rules = array(
            'nama_supplier'=> 'required',
            'alamat'=> 'required',
            'provinsi'=> 'required',
            'kabupaten'=> 'required',
            'kecamatan'=> 'required',
            'email'=> 'required',
            'no_telpon'=> 'required',
            'jenis_supplier'=> 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $return = ['status'=>'success', 'code'=>'200', 'message'=>'Data Berhasil Disimpan !!'];
            return response()->json($return);
        }else {
            return $validator->messages();
        }
    }
}
