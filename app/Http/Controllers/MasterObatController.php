<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables, Validator;

class MasterObatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = [];

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    return '<div class="table-actions">
                            <a href="#" onclick="edit_obat(' . $data['id'] . ')"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                            <a href="#" onclick="delete_obat(' . $data['id'] . ')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </div>';
                })
                ->rawColumns(['roles', 'permissions', 'action'])
                ->make(true);
        }

        return view('master.barang.main');
    }

    public function form(Request $request)
    {
        $data = [];
        $data['data'] = (!empty($request->id)) ? "" : "";
        $content = view('master.barang.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        $rules = array(
            'nama_satuan' => 'required',
            'jenis_satuan' => 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Berhasil Disimpan !!'];
            return response()->json($return);
        } else {
            return $validator->messages();
        }
    }
}
