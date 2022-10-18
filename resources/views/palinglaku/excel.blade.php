
  <table id="body_excel">
    <thead>
      <tr>
        <td colspan="5" align="center" ><b>{{ $judul }}</b></td>
      </tr>
      <tr>
        <td colspan="5" align="center">APOTEK NDARU HUSAHA</td>
      </tr>
      <tr>
        <td colspan="5"align="center">{{ $date }}</td>
      </tr>
    </thead>
</table>
<table class="table table-striped table-bordered">
  <thead>
     <tr>
      <th style="font-weight:bold;text-align:center">NO</th>
          <th style="font-weight:bold;text-align:center">NAMA BARANG</th>
          {{-- <th style="font-weight:bold;text-align:center;">JAM</th> --}}
          <th style="font-weight:bold;text-align:center;">SATUAN</th>
          <th style="font-weight:bold;text-align:center;">JENIS</th>
          <th style="font-weight:bold;text-align:center;">QTY</th>
    </tr>
  </thead>
   @php
      $no = 1;
  @endphp
  <tbody id='panelHasil'>
      @foreach ($lap as $item)
        <tr>
              <td style="padding: 5px;" align="center" valign="middle">{{$no}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->nama_barang}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->nama_satuan}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->jenis_barang}}</td>
              <td style="padding: 5px;" align="center" valign="middle">{{$item->qty}}</td>
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
