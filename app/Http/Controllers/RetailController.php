<?php

namespace App\Http\Controllers;
use DB;
use DataTables;
use Auth;
use App\Models\Retail;
use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Http\Request;

class RetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $retail = Barang::with('stok_barang')->get();
        return view('retail.list', compact('retail'));
    }

    public function getRetailList(Request $request)
    {
        $data = Retail::get();

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if ($data->name == 'Super Admin') {
                    return '';
                }
                if (Auth::user()->can('manage_user')) {
                    return '<div class="table-actions">
                            <a href="#productView" data-toggle="modal" data-target="#productView" id="detail"><i
                            class="ik ik-eye f-16 mr-15"></i></a>
                            <a href="'.url('retail/edit/'.$data->id).'" ><i class="ik ik-edit-2 f-16 mr-15 text-green"></i></a>
                            <a href="'.url('retail/'.$data->id).'"><i class="ik ik-trash-2 f-16 text-red"></i></a>
                        </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('retail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    //     $request->validate([
    //         'nama_barang'=> 'required',
    //         'no_bacth'   => 'required',
    //         'harga_beli' => 'required',
    //         'harga_jual' => 'required',
    //         'expired'    => 'required',
    //         'tgl_masuk'  => 'required',
    //         'stock_awal' => 'required',
    //         'sisa_stock' => 'required',
    //         'supplier'   => 'required',
    //         'status'     =>  'required',
    //     ]);

    //     $retail = Retail::create($request->all());
    //     return redirect('retail')
    //         ->with('success','Product created successfully.');
    // }

    

    // public function detail($id)
    // {
    //     $retail = Retail::find($id);


    //     return response()->json([
    //         "retail" => $retail
    //     ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $retail = Retail::find($id);
        return view('retail.edit', compact('retail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'nama_barang' => 'required',
        //     'no_bacth' => 'required',
        //     'harga_beli' => 'required',
        //     'harga_jual' => 'required',
        //     'expired' => 'required',
        //     'tgl_masuk' => 'required',
        //     'stock_awal' => 'required',
        //     'sisa_stock' => 'required',
        //     'supplier' => 'required',
        //     'status' => 'required',
            
        // ]);

        // $retail = Retail::find($id);
        //     $retail->nama_barang        = $request->nama_barang;
        //     $retail->no_bacth           = $request->no_bacth;
        //     $retail->harga_beli         = $request->harga_beli;
        //     $retail->harga_jual         = $request->harga_jual;
        //     $retail->expired            = $request->expired;
        //     $retail->tgl_masuk          = $request->tgl_masuk;
        //     $retail->stock_awal         = $request->stock_awal;
        //     $retail->sisa_stock         = $request->sisa_stock;
        //     $retail->supplier           = $request->supplier;
        //     $retail->status             = $request->status;
        //     $retail->save();

        // return redirect('retail')
        //     ->with('success','Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $retail = Retail::find($id);
        $retail->delete();
        return redirect('retail');
    }
}
