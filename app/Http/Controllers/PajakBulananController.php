<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\PajakBulanan;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Exports\PajakBulananExport;
use Maatwebsite\Excel\Facades\Excel;

class PajakBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function print(Request $request)
    {
        $date = date('Y-m-d');
        $data['date'] = $request->bulan;
        $data['judul'] = 'LAPORAN PAJAK BULANAN';

        $bulantahun = explode('-' ,$request->bulan ?? date('m-Y'));
        $bulan = $bulantahun[0];
        $tahun = $bulantahun[1];
        $temp = DetailPenjualan::has('penjualan')->has('stok_barang')
            ->with('stok_barang.barang.satuan')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();
        
        $total = 0;
        foreach ($temp as $key => $value) {
            $pajak = $value->stok_barang->nominal_pajak / 100;
            $pajakbulanan = ($value->stok_barang->harga_beli * $pajak) * $value->qty;
            $value->pajakbulanan = $pajakbulanan;
            $total += $pajakbulanan;
        }
        $data['lap'] = $temp;
        $data['total'] = $total;
        
        return Excel::download(new PajakBulananExport($data), "Laporan Pajak Bulanan($bulan-$tahun) ".$date.".xlsx");
    }

    public function index(Request $request)
    {
        if ($request->ajax()) { 
            $bulantahun = explode('-' ,$request->bulan ?? date('m-Y'));
            $bulan = $bulantahun[0];
            $tahun = $bulantahun[1];
            $data = DetailPenjualan::has('penjualan')->has('stok_barang')
                ->with('stok_barang.barang.satuan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->get();
            
            $total = 0;
            foreach ($data as $key => $value) {
                $pajak = $value->stok_barang->nominal_pajak / 100;
                $pajakbulanan = ($value->stok_barang->harga_beli * $pajak) * $value->qty;
                $value->pajakbulanan = $pajakbulanan;
                $total += $pajakbulanan;
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('barang', function (DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan
                        ->stok_barang
                        ->barang
                        ->nama ?? 'Null' ;
                })
                ->editColumn('satuan', function (DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan
                        ->stok_barang
                        ->barang
                        ->satuan
                        ->nama ?? 'Null' ;
                })
                ->editColumn('harga_beli', function (DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan
                        ->stok_barang
                        ->harga_beli ?? 'Null' ;
                })
                ->editColumn('nominal_pajak', function (DetailPenjualan $detail_penjualan) {
                    return $detail_penjualan
                        ->stok_barang
                        ->nominal_pajak ?? 0 ;
                })
                ->with('total', $total)
                ->make(true);
        }
        return view('pajakbulanan.main');
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PajakBulanan  $pajakBulanan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $pajakbulanan = PajakBulanan::find($id);
        return view('pajakbulanan.edit', compact('pajakbulanan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PajakBulanan  $pajakBulanan
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PajakBulanan  $pajakBulanan
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PajakBulanan  $pajakBulanan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pajakbulanan = PajakBulanan::find($id);
        $pajakbulanan->delete();
        return redirect('pajakbulanan');
    }
}