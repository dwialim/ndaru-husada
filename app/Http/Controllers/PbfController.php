<?php

namespace App\Http\Controllers;

use App\Models\Pbf;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use DataTables, Validator;
use Illuminate\Http\Request;
use App\Imports\PbfImport;
use Maatwebsite\Excel\Facades\Excel;

class PbfController extends Controller{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Pbf::with(['provinsi', 'kabupaten', 'kecamatan'])->get();
            // return $data;
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    return '<div class="table-actions">
                        <p class="text-center">
                            <a href="#" onclick="edit_pbf(' . $data['id'] . ')"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                            <a href="#" onclick="delete_pbf(' . $data['id'] . ')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </p>
                    </div>';
                })
                ->make(true);
                // ->toJson();
        }
        return view('master.pbf.main');
    }

    public function form(Request $request)
    {
        $data = [];
        $data['data'] = '';
        $data['provinsi'] = Provinsi::all();

        if (!empty($request->id)) {
            $data['data'] = Pbf::with(['provinsi', 'kabupaten', 'kecamatan'])->find($request->id);
            $data['kabupaten'] = Kabupaten::where('provinsi_id', $data['data']->provinsi_id)->get();
            $data['kecamatan'] = Kecamatan::where('kabupaten_id', $data['data']->kabupaten_id)->get();
        }
        // return $data;
        $content = view('master.pbf.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function create(){
    }

    public function store(Request $request)
    {
        $rules = array(
            'nama' => 'required',
            // 'alamat' => 'required',
            // 'provinsi' => 'required',
            // 'kabupaten' => 'required',
            // 'kecamatan' => 'required',
            // 'email' => 'required',
            // 'no_telpon' => 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );
// return $request->all();
        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            if ($request->id != '') {
                $save = Pbf::find($request->id);
            } else {
                $save = new Pbf;

                // generate kode
                $next_kode = $this->generateKode($save, 'PBF');
                $save->kode = $next_kode;
            }
            $save->nama = strtoupper($request->nama);
            if(isset($request->alamat)){
                $save->alamat = strtoupper($request->alamat);
            }else{$save->alamat = null;}

            if(isset($request->provinsi)){
                $save->provinsi_id = $request->provinsi;
            }else{$save->provinsi_id = null;}

            if(isset($request->kabupaten)){
                $save->kabupaten_id = $request->kabupaten;
            }else{$save->kabupaten_id = null;}

            if(isset($request->kecamatan)){
                $save->kecamatan_id = $request->kecamatan;
            }else{$save->kecamatan_id = null;}

            if(isset($request->email)){
                $save->email = $request->email;
            }else{$save->email = null;}

            if(isset($request->no_telpon)){
                $save->no_telpon = $request->no_telpon;
            }else{$save->no_telpon = null;}
            $save->save();

            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Berhasil Disimpan !!'];
            return response()->json($return);
        } else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        // DELETE PBF
        $deleted = Pbf::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    public function show(Pbf $pbf){
    }

    public function edit(Pbf $pbf){
    }

    public function update(Request $request, Pbf $pbf){
    }

    public function destroy(Pbf $pbf){
    }

    public function getKabupaten(Request $request){
        $data = Kabupaten::where('provinsi_id', $request->id)->get();
        return response()->json($data);
    }

    public function getKecamatan(Request $request){
        $data = Kecamatan::where('kabupaten_id', $request->id)->get();
        return response()->json($data);
    }

    public function import(Request $req){
        $this->validate($req, [
            'file_excel' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $req->file('file_excel');
        Excel::import(new PbfImport, $file);

        return ['status'=>'success','message' => 'Berhasil Import Excel','title' => 'Success'];
    }
}
