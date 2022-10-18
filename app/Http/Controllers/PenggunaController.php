<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna'
        ];
        return view('pages.master-pengguna.main', $data);
    }
}
