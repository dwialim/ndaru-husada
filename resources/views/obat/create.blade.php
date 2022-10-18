@extends('layouts.main')
@section('title', 'Tambah Obat ')
@section('content')

    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Create Data Obat </h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="post" action="{{URL::to('obat/tambah')}}">
                            @csrf
                          
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Nama Obat </label>
                                        <select class="form-control select2" id="nama_obat" name="nama_obat">
                                            <option selected> Pilih Obat </option>
                                            <option value="Jenis Obat 1"> Jenis Obat 1 </option>
                                            <option value="Jenis Obat 2"> Jenis Obat 2 </option>
                                            <option value="Jenis Obat 3"> Jenis Obat 3 </option>
                                            <option value="Jenis Obat 4"> Jenis Obat 4 </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label>No Batch</label>
                                        <input type="text" class="form-control" id="no_batch" name="no_batch" placeholder="No.Batch">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label>Jumlah Obat</label>
                                        <input type="text" class="form-control" id="jumlah_obat" name="jumlah_obat" placeholder="Jumlah Obat">
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
                                        <input type="text" class="form-control" id="harga_beli" name="harga_beli" placeholder="Harga Beli">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Harga Jual </label>
                                        <input type="text" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga Jual">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Tanggal Masuk</label>
                                    <input class="tm form-control" type="date" id="tanggal_masuk"  name="tanggal_masuk" 
                                    data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                </div>
                               
                                <div class="col-md-6">
                                    <label>Tanggal Expired</label>
                                    <input class="tm form-control" type="date" id="tanggal_expired"  name="tanggal_expired" 
                                    data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required autofocus>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label> Laba </label>
                                        <input type="text" class="form-control" id="laba" name="laba" placeholder="Persen Laba%">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div  class="form-group">
                                        <label> Pajak </label>
                                        <input type="text" class="form-control" id="pajak" name="pajak" placeholder="Persen Pajak%">
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
                                        <input type="text" class="form-control" id="stock_awal" name="stock_awal" placeholder="Masukkan Stock Awal">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div  class="form-group">
                                        <label> Sisa Stock </label>
                                        <input type="text" class="form-control" id="sisa_stock" name="sisa_stock" placeholder="Masukkan Sisa Stock">
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

    <script>
        $(".tm").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format( this.getAttribute("data-date-format") )
        )
        }).trigger("change")
    </script>

    @push('script')
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script
        src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js">
        </script>
    @endpush

@endsection
