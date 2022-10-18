@extends('layouts.main')
@section('title', 'Tambah Data Retail')
@section('content')

    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3> Form Create Retail </h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="post" action="{{URL::to('retail/tambah')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="background: " class="form-group">
                                        <label>Pilih Retail Baru</label>
                                        <select class="form-control select2" id="nama_barang" name="nama_barang">
                                            <option selected> Pilih Retail </option>
                                            <option value="Option 1">Option  1 </option>
                                            <option value="Option 2">Option  2 </option>
                                            <option value="Option 3">Option  3 </option>
                                        </select>
                                   </div>   
                                </div>
                                <div class="col-md-6">
                                    <div style="background: " class="form-group">
                                        <label>No Bacth</label>
                                        <input type="text" class="form-control" id="no_batch" name="no_batch" placeholder="Masukkan No Batch ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <label>Harga Beli</label>
                                        <input type="text" class="form-control" id="harga_beli" name="harga_beli" placeholder="Harga Beli">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="background: " class="form-group">
                                            <label>Harga Jual</label>
                                            <input type="text" class="form-control" id="harga_jual" name="harga_jual" placeholder="Masukkan Harga Jual">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tanggal Expired</label>
                                        <input class="tm form-control" type="date" id="tanggal_expired"  name="tanggal_expired" 
                                         data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                        </div>
                                    </div>

                                <div class="row">
                                <div class="col-md-6">
                                    <div style="background: " class="form-group">
                                        <label> Tanggal Masuk </label>
                                        <input  class="tm form-control" type="date" id="tanggal_masuk"  name="tanggal_masuk" 
                                        data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div style="background: " class="form-group">
                                        <label>Stock Awal</label>
                                        <input type="text" class="form-control" id="stock_awal" name="stock_awal" placeholder="Stock Awal">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div style="background: " class="form-group">
                                        <label>Sisa Stock</label>
                                        <input type="text" class="form-control" id="sisa_stock" name="sisa_stock" placeholder="Sisa Stock">
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
                                        <select class="form-control select2" id="status" name="status">
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

    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    @endpush

@endsection
