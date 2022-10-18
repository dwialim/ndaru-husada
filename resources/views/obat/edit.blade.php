@extends('layouts.main')
@section('title', 'Edit Data Obat')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>  Edit Obat </h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="post" action="{{URL::to('obat/edit' , [$obat->id])}}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">

                            <div class="row">
                                <div class="col-md-12">
                                    <div  class="form-group">
                                        <label>Nama Obat</label>
                                        <input value="{{ $obat->nama_obat }}" type="text" class="form-control" id="nama_obat" name="nama_obat" placeholder="Masukkan Nama Obat">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label>No Batch</label>
                                        <input value="{{ $obat->no_bacth }}" type="text" class="form-control" id="no_bacth" name="no_bacth" placeholder="No.Batch">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label>Jumlah Obat</label>
                                        <input value="{{ $obat->jumlah_obat }}" type="text" class="form-control" id="jumlah_obat" name="jumlah_obat" placeholder="Jumlah Obat">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Suppiler </label>
                                        <select class="form-control select2" id="supplier" name="supplier">
                                            <option selected> Pilih Supplier </option>
                                            <option value="Supplier 1"> Supplier 1 </option>
                                            <option value="Supplier 2"> Supplier 2 </option>
                                            <option value="Supplier 3"> Supplier 3 </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Harga Beli </label>
                                        <input value="{{ $obat->harga_beli }}" type="text" class="form-control" id="harga_beli" name="harga_beli" placeholder="Harga Beli">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Harga Jual </label>
                                        <input value="{{ $obat->harga_jual }}" type="text" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga Jual">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Tanggal Masuk </label>
                                        <input  value="{{ $obat->tanggal_masuk }}" class="tm form-control" type="date" id="tanggal_masuk"  name="tanggal_masuk" 
                                        data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label>Tanggal Expired</label>
                                        <input  value="{{ $obat->tanggal_expired }}" class="tm form-control" type="date" id="tanggal_expired"  name="tanggal_expired" 
                                        data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label> Laba </label>
                                        <input value="{{ $obat->laba }}" type="text" class="form-control" id="laba" name="laba" placeholder="Persen Laba%">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label> Pajak </label>
                                        <input value="{{ $obat->pajak }}" type="text" class="form-control" id="pajak" name="pajak" placeholder="Persen Pajak%">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Status </label>
                                        <select class="form-control select2" id="status" name="status">
                                            <option selected> Pilih Status </option>
                                            <option value="Aman"> Aman </option>
                                            <option value="Expired"> Expired </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Stock Awal </label>
                                        <input value="{{ $obat->stock_awal }}"  type="text" class="form-control" id="stock_awal" name="stock_awal" placeholder="Masukkan Stock Awal">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Sisa Stock </label>
                                        <input value="{{ $obat->sisa_stock }}"  type="text" class="form-control" id="sisa_stock" name="sisa_stock" placeholder="Masukkan Sisa Stock">
                                    </div>
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