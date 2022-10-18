<?php

namespace App\Http\Controllers;
use App\Models\Obat;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;

class ObatController extends Controller
{
    public function index()
    {
        $obat = Obat::all();
        return view('obat.list', compact('obat'));
    }

    public function getObatList(Request $request)
    {
        $data = Obat::get();

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if ($data->name == 'Super Admin') {
                    return '';
                }
                if (Auth::user()->can('manage_user')) {
                    return '<div class="table-actions">
                            <a href="#productView" data-toggle="modal" data-target="#productView" id="detail"><i
                            class="ik ik-eye f-16 mr-15"></i></a>
                            <a href="'.url('obat/edit/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                            <a href="'.url('obat/'.$data->id).'"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat'       => 'required',
            'no_bacth'        => 'required',
            'jumlah'          => 'required',
            'supplier'        => 'required',
            'harga_beli'      => 'required',
            'harga_jual'      => 'required',
            'tanggal_masuk'   => 'required',
            'tanggal_expired' => 'required',
            'laba'            => 'required',
            'pajak'           => 'required',
            'status'          => 'required',
            'stock_awal'      => 'required',
            'sisa_stock'      => 'required',
        ]);

        $obat = Obat::create($request->all());
        return redirect('obat')
            ->with('success','Obat created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $obat = Obat::find($id);
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat'       => 'required',
            'no_bacth'        => 'required',
            'jumlah'          => 'required',
            'supplier'        => 'required',
            'harga_beli'      => 'required',
            'harga_jual'      => 'required',
            'tanggal_masuk'   => 'required',
            'tanggal_expired' => 'required',
            'laba'            => 'required',
            'pajak'           => 'required',
            'status'          => 'status',
            'stock_awal'      => 'stock_awal',
            'sisa_stock'      => 'sisa_stock',
        ]);

        $obat = Obat::find($id);
        $obat->nama_obat        = $request->nama_obat;
        $obat->no_bacth         = $request->no_bacth;
        $obat->jumlah           = $request->jumlah;
        $obat->supplier         = $request->supplier;
        $obat->harga_beli       = $request->harga_beli;
        $obat->harga_jual       = $request->harga_jual;
        $obat->tanggal_masuk    = $request->tanggal_masuk;
        $obat->tanggal_expired  = $request->tanggal_expired;
        $obat->laba             = $request->laba;
        $obat->pajak            = $request->pajak;
        $obat->status           = $request->status;
        $obat->stock_awal       = $request->stock_awal;
        $obat->sisa_stock       = $request->stock_awal;

        $obat->save();

        return redirect('obat')
        ->with('success','Update successfully');
    }

    public function destroy($id)
    {
        $obat = Obat::find($id);
        $obat->delete();
        return redirect('obat');
    }

}