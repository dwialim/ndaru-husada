<?php
function rupiah($angka)
{
  $hasil_rupiah = "Rp. " . number_format((int)$angka);
  $hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
  return $hasil_rupiah;
}
?>

  <table id="body_excel">
    <thead>
      <tr>
        <td colspan="8" align="center" ><b>{{ $judul }}</b></td>
      </tr>
      <tr>
        <td colspan="8" align="center">APOTEK NDARU HUSAHA</td>
      </tr>
      <tr>
        <td colspan="8"align="center">{{ $date }}</td>
      </tr>
    </thead>
</table>
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th colspan="8" style="font-weight:bold;text-align:right">Total = {{rupiah($total)}} </th>
    </tr>
     <tr>
          <th style="font-weight:bold;text-align:center">NO</th>
          <th style="font-weight:bold;text-align:center">NAMA BARANG</th>
          <th style="font-weight:bold;text-align:center;">SATUAN</th>
          <th style="font-weight:bold;text-align:center;">JENIS</th>
          <th style="font-weight:bold;text-align:center;">HARGA BELI</th>
          <th style="font-weight:bold;text-align:center;">PPN</th>
          <th style="font-weight:bold;text-align:center;">QTY</th>
          <th style="font-weight:bold;text-align:center;">TOTAL</th>
    </tr>
  </thead>
   @php
      $no = 1;
  @endphp
  <tbody id='panelHasil'>
      @foreach ($lap as $item)
        <tr>
              <td style="padding: 5px;" align="center" valign="middle">{{$no}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->barang->nama}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->barang->satuan->nama ?? 'Null'}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->barang->jenis}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{rupiah($item->stok_barang->harga_beli)}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->stok_barang->nominal_pajak}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->qty}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{rupiah($item->pajakbulanan)}}</td>
        </tr>    
        @php
            $no++;
        @endphp
      @endforeach
      @if($no == '1')
          <tr>
            <td colspan="9" style="text-align: center;padding: 5px;">Tidak Ada Data</td>
          </tr>
      @endif
  </tbody>
</table>
