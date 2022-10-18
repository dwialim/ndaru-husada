@extends('layouts.main')
@section('title', 'Edit Data Retail')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3> Form Edit Retail </h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="post" action="{{URL::to('retail/edit' , [$retail->id])}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">

                            <div class="row">
                                <div class="col-md-12">
                                    <div style="background: " class="form-group">
                                        <label>Nama Obat</label>
                                        <input value="{{ $retail->nama_barang }}" type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="background: " class="form-group">
                                        <label>No Bacth</label>
                                        <input value="{{ $retail->no_batch }}" type="text" class="form-control" id="no_batch" name="no_batch" placeholder="Masukkan No Batch ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <label>Harga Beli</label>
                                        <input value="{{ $retail->harga_beli }}" type="text" class="form-control" id="harga_beli" name="harga_beli" placeholder="Harga Beli">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="background: " class="form-group">
                                            <label>Harga Jual</label>
                                            <input value="{{ $retail->harga_jual }}" type="text" class="form-control" id="harga_jual" name="harga_jual" placeholder="Masukkan Harga Jual">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                            <label>Tanggal Expired</label>
                                            <input value="{{ $retail->expired }}"  class="tm form-control" type="date" id="tanggal_expired"  name="tanggal_expired" 
                                            data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                            </div>
                                    </div>

                                <div class="row">
                                <div class="col-md-6">
                                    <div style="background: " class="form-group">
                                        <label>Tgl Masuk</label>
                                        <input value="{{ $retail->tgl_masuk }}" class="tm form-control" type="date" id="tanggal_masuk"  name="tanggal_masuk" 
                                        data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div style="background: " class="form-group">
                                        <label>Stock Awal</label>
                                        <input value="{{ $retail->stock_awal }}"  type="text" class="form-control" id="stock_awal" name="stock_awal" placeholder="Stock Awal">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div style="background: " class="form-group">
                                        <label>Sisa Stock</label>
                                        <input value="{{ $retail->sisa_stock }}" type="text" class="form-control" id="sisa_stock" name="sisa_stock" placeholder="Sisa Stock">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div style="background: " class="form-group">
                                        <label>Supplier</label>
                                        <select class="form-control select2" id="supplier" name="supplier">
                                            <option selected> Pilih Supplier </option>
                                            <option value="Supplier 1"> Supplier 1 </option>
                                            <option value="Supplier 2"> Supplier 2 </option>
                                            <option value="Supplier 3"> Supplier 3 </option>
                                        </select>
                                   </div>               
                                </div>
                                <div class="col-md-4">
                                    <div style="background: " class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option selected> Pilih Status </option>
                                            <option value="Aman"> Aman </option>
                                            <option value="Expired"> Expired </option>
                                        </select>
                                   </div>               
                                </div>
                           


                            <button type="submit" class="btn btn-primary mr-2">{{ __('Submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection