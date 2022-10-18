<?php

namespace App\Http\Controllers;

use App\Models\Pbf;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Penjualan;

class DashboardController extends Controller
{
    public function index()
    {
        $obat = Barang::all()->count();
        $pbf = Pbf::all()->count();
        $kasir = Penjualan::all()->count();

        $data = [
            'title' => 'Dashboard',
            'obat' => $obat,
            'pbf' => $pbf,
            'kasir' => $kasir,
        ];
        return view('pages.dashboard', $data);
    }

	public function session_hapus_notif(Request $request)
	{
		session_start();
		
		$_SESSION["".$request->nama] = "Ya";
	}
}
