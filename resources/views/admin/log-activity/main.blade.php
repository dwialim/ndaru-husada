@extends('layouts.main')
@section('content')

<div class="list-group"> 

<?php
function rupiah($angka)
{
  $hasil_rupiah = "Rp. " . number_format((int)$angka);
  $hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
  return $hasil_rupiah;
}
?>

<div class="content-wrapper">
    <div class="main-layer">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{$page}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach($log_activity as $key => $la)
    <div class="card mb-3" style="border-radius: 10px;">
        <div class="card-body p-2">
            <small class="">{{$la->tanggal}}</small>
            <p class="mt-1 m-0">{{$la->activity}}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="other-page"></div>
</div>

@stop

@section('script')

<script>

</script>
</div>

@endsection