<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //log activitas

    public function main(Request $request)
    {
        $data['menu'] = 'log-activity';
        $data['page'] = 'Log Activity';

        $data['log_activity'] = LogActivity::orderBy('tanggal','DESC')->get();

        return view('admin.log-activity.main', $data);
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
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(LogActivity $logActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogActivity $logActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogActivity  $logActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogActivity $logActivity)
    {
        //
    }
}
