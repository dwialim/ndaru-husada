<?php

namespace App\Http\Controllers;

use App\Imports\BarangImport;
use DB, Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Barang;
use App\Models\Satuan;
use DataTables, Validator;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::with(['satuan',])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('satuan', function (Barang $barang) {
                    return $barang->satuan->nama ?? 'Null' ;
                })
                ->addColumn('action', function ($data) {
                    if ($data->name == 'Super Admin') {
                        return '';
                    }
                    return '<div class="table-actions">
                        <a href="#" onclick="edit_barang(' . $data['id'] . ')"><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                        <a href="#" onclick="delete_barang(' . $data['id'] . ')"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                    </div>';
                })
                ->make(true);
                // ->toJson();
        }

        return view('master.barang.main',);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function form(Request $request)
    {
        $data = [];
        $data['data'] = (!empty($request->id)) ? Barang::with('satuan')->find($request->id) : "";
        $data['satuan'] = Satuan::all();
        $data['jenis'] = $request->jenis;
        $content = view('master.barang.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function simpanBarang(Request $request){
        if(!empty($request->namaObat) && $request->mstSatuan!='first'){
            $kode = "OBT";
            $barang = new Barang;

            $next_kode = $this->generateKode($barang, $kode); // generate kode
            $barang->kode = $next_kode;
            $barang->nama = strtolower($request->namaObat);
            $barang->satuan_id = $request->mstSatuan;
            $barang->save();

            $satuan = Satuan::where('id',$barang->satuan_id)->first();
            $data = [
                'barang' => $barang,
                'satuan' => $satuan,
            ];

            return ['status'=>'success','message'=>'Data Berhasil Disimpan!','data'=>$data];
        }else{
            return ['status'=>'warning','message'=>'Masukkan data dengan benar!'];
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'nama' => 'required',
            'satuan' => 'required',
        );

        $messages = array(
            'required'  => 'Kolom Harus Diisi',
        );

        switch ($request->jenis) {
            case 'Obat':
                $prefix = 'OBT';
                break;

            case 'Retail':
                $prefix = 'RTL';
                break;
            
            default:
                $prefix = 'XXX';
                break;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if (!$validator->fails()) {
            if ($request->id != '') { // UPDATE
                $save = Barang::find($request->id);
            } else { //CREATE
                $save = new Barang;

                // generate kode
                $next_kode = $this->generateKode($save, $prefix);
                $save->kode = $next_kode;
            }
            $save->nama = $request->nama;
            $save->satuan_id = $request->satuan;
            $save->save();

            $return = ['status' => 'success', 'code' => '200', 'message' => 'Data Berhasil Disimpan !!'];
            return response()->json($return);
        } else {
            return $validator->messages();
        }
    }

    public function delete(Request $request)
    {
        // DELETE BARANG
        $deleted = Barang::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file_excel' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file_excel');
        Excel::import(new BarangImport, $file);

        return ['status'=>'success','message' => 'Berhasil Import Excel','title' => 'Success'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        //
    }
}
