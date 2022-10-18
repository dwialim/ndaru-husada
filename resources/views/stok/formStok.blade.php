<?php
	function rupiah($angka){
		$hasil_rupiah = "Rp. " . number_format((int)$angka);
		$hasil_rupiah = str_replace(',', '.', $hasil_rupiah);
		return $hasil_rupiah;
	}
?>
<link href="{{asset('assets/css/jquery.pan.css')}}" rel="stylesheet" type="text/css"/>

<div class="row">
	<div class="col-md-12">
		<div class="alert alert-primary" role="alert" style="border-radius:5px;">
			<!-- <div class="fas fa-exclamation-circle"></div> -->
			<div class="row">
				<div class="col-xs-1" style="">
					<i class="fas fa-exclamation-circle ml-2" style="margin:auto; font-size: 23px;"></i>
				</div>
				<div class="col-xs-11 ml-2">
					<span style="font-size: 16px;">Pastikan data sudah terisi dengan benar sebelum disimpan.</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-stok">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h3> {{$title}} {{$form}} </h3>
				</div>
				<input type="hidden" name="title" id="title" value="{{$title}}">
				<div class="card-body">
					<form class="formInputOld">
						@if($form=='Pembelian Obat')
							<div class="form-group row">
								<label class="col-md-2 col-form-label">No. Registrasi <span class="text-red" style="font-size: 14px;">*</span></label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="noRegistrasi" name="noRegistrasi[]" value="{{(!empty($getFaktur)) ? $getFaktur->no_registrasi : $noReg}}" readonly>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">No. Faktur <span class="text-red" style="font-size: 14px;">*</span></label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="noFaktur" name="noFaktur[]" value="{{!empty($getFaktur)? $getFaktur->no_faktur_pbf : ''}}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">PBF <span class="text-red" style="font-size: 14px;">*</span></label>
								<div class="col-md-5">
									<select class="form-control" id="pbf" name="pbf[]">
										<option value="first">--Cari PBF--</option>
											@foreach($pbfs as $key => $pbf)
												<option value="{{$pbf->id}}" {{(!empty($faktur)) && $getFaktur->pbf_id == $pbf->id ? 'selected' : ''}}>{{$pbf->nama}}</option>
											@endforeach
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">Alamat PBF <span class="text-red" style="font-size: 14px;">*</span></label>
								<div class="col-md-5">
									<input type="text" class="form-control" id="alamatPBF" name="alamatPBF[]" value="{{(!empty($faktur)?$getFaktur->pbf->alamat:'')}}" @if(!empty($faktur)) readonly @endif>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">Materai</label>
								<div class="col-md-5">
									<input type="text" class="form-control" onkeyup="ubahMaterai(this)" id="materai" name="materai" value="{{(!empty($getFaktur->materai)) ? rupiah($getFaktur->materai) : ''}}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">Pajak</label>
								<div class="col-md-2" id="checkFaktur" @if(isset($getDetailFaktur) && (!empty($getDetailFaktur->nominal)||!empty($getDetailFaktur->persentase))) style="display:none;" @endif>
									<div class="form-check mt-1">
										<input type="checkbox" name="cekFakturPajak" id="cekFakturPajak" class="mt-2 form-check-input" {{(!empty($getDetailFaktur)) ? 'checked' : ''}}>
										<label class="form-check-label mt-1">Tambahkan Pajak?</label>
									</div>
								</div>
								<div class="col-md-10" id="formFakturPajak" @if(!isset($getDetailFaktur) && (empty($getDetailFaktur->nominal)||empty($getDetailFaktur->persentase))) style="display: none;" @endif>
									<div class="row">
										<div class="col-md-6">
											<table width="100%">
												<tr>
													<td width="87%">
														<select class="form-control" id="pajakFaktur" name="pajakFaktur[]">
															<option value="first">--Cari Pajak--</option>
															@foreach($mstPajak as $key => $pajak)
																{{--<option value="{{$pajak->id}}" {{(!empty($getStokBarang)) && $getStokBarang->pbf_id == $pajak->id ? 'selected' : ''}}>{{$pajak->nama}}</option>--}}
																<option value="{{$pajak->id}}">{{$pajak->nama}}</option>
															@endforeach
														</select>
													</td>
													<td>
														<button type="button" onclick="addFakturPajak()" class="btn btn-success btn-sm float-right"><i class="fa fa-plus"></i></button>
													</td>
												</tr>
											</table>
										</div>
										<div class="col-md-6" style="margin-top:2px!important;">
											<button type="button" class="btn btn-danger" onclick="batalFakturPajak()">Batal Tambah Pajak</button>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group row mb-4" id="showPajak" @if(!isset($getDetailFaktur) && (empty($getDetailFaktur->nominal)||empty($getDetailFaktur->persentase))) style="display: none;" @endif>
								<div class="col-md-12">
									{{--<div class="row mb-3">
										<label class="col-md-2 col-form-label">Persentase DPP (%)</label>
										<div class="col-md-5">
											<input type="text" class="form-control" id="persentaseDPP" name="persentaseDPP[]" value="{{(!empty($faktur)?$getFaktur->persentase_dpp:'')}}">
										</div>
									</div>--}}
									<div class="row">
										<div class="col-md-12">
											<table id="tbPajak" class="table table-bordered">
												<thead>
													<tr class="text-center">
														<th>Nama Pajak</th>
														<th>Nominal</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody class="tempatPajak">
													@if(isset($detailFaktur) && !empty($detailFaktur))
													@foreach($detailFaktur as $key => $val)
														<tr class="rowPajak" id="rowPajak-{{$loop->index+1}}">
															<td>
																<input type="hidden" id="idDetailPajak" name="idDetailPajak[]" value="{{$val->id}}">
																<input type="hidden" id="idMstPajak" name="idMstPajak[]" value="{{$val->pajak_id}}">
																<center class="mt-2">{{$val->pajak->nama}}</center>
															</td>
															<td>
																<div class="row">
																	<div class="col-md-3">
																		<select class="form-control" name="optionPajak" id="optionPajak">
																			<option hidden>--Pilih Tipe--</option>
																			<option {{(!empty($val->nominal)?'selected':'')}} value="nominal">Nominal</option>
																			<option {{(!empty($val->persentase)?'selected':'')}} value="persen">Persen</option>
																		</select>
																	</div>
																	<div class="col-md-9">
																		<input type="text" class="form-control text-center" id="nominalPajak" name="nominalPajak[]" placeholder="Nominal Pajak" value="{{(!empty($val->nominal))?$val->nominal:$val->persentase}}">
																	</div>
																</div>
																<!-- <input type="text" class="form-control text-center" id="nominalPajak" name="nominalPajak[]" placeholder="Nominal Pajak" value="{{$val->nominal}}"> -->
															</td>
															<td>
																<center class="mt-2">
																	<a href="javascript:;" onclick="deletePajak(`{{$loop->index+1}}`)"><i class="fa fa-trash-alt text-danger"></i></i></a>
																</center>
															</td>
														</tr>
													@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">Jatuh Tempo <span class="text-red" style="font-size: 14px;">*</span></label>
								<div class="col-md-5">
									<input class="tm form-control" type="date" id="jatuhTempo" name="jatuhTempo[]" data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required value="{{(!empty($faktur)) ? $getFaktur->jatuh_tempo :''}}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">Status</label>
								<div class="col-md-2" id="checkPiutang">
									<div class="form-check mt-1">
										<input type="checkbox" name="cekPiutang" id="cekPiutang" @if(!empty($getFaktur)) {{($getFaktur->status_piutang==1)?'checked':''}} @endif class="mt-1 form-check-input">
										<label class="form-check-label mt-1">Piutang?</label>
									</div>
								</div>
								<!-- <div class="col-md-6 mt-1" id="formPiutang" style="display:none;">
									<div class="row">
										<div class="col-md-10 mt-1">
											<input type="file" name="statusPiutang" id="statusPiutang">
										</div>
										<div class="col-md-2">
											<button type="button" class="btn btn-sm btn-danger" onclick="batalPiutang()">Batalkan Piutang</button>
										</div>
									</div>
								</div> -->
							</div>

							<div class="form-group row">
								<label class="col-md-2 col-form-label">Upload Gambar</label>
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-5 mt-1">
											<label class="form-check-label mt-1">Faktur <span class="text-red" style="font-size: 14px;">*</span></label><br>
											<input type="file" name="notaFaktur" id="notaFaktur">
										</div>
										<div class="col-md-6 mt-1" id="showPembayaran" @if(!empty($getFaktur)) @if($getFaktur->status_piutang==1) style="display:none;" @endif @endif>
											<label class="form-check-label mt-1">Nota Pembayaran</label><br>
											<input type="file" name="notaPembayaran" id="notaPembayaran">
										</div>
									</div>
								</div>
							</div>
							
							@if($menu=='pembelianObat')
							@if(isset($faktur)&&!empty($faktur))
							@foreach($faktur as $key => $img)
							<hr class="mb-1 mt-2">
							<div class="form-group row">
								<div class="col-md-6" @if(empty($img->notaFaktur)) style="display:none; "@endif>
									<label>Faktur</label><br>
									<a class="pan" data-big="{{ asset($img->notaFaktur) }}" href="javascript:">
										<img src="{{ asset($img->notaFaktur) }}" style="max-width:150px;">
									</a>
								</div>
								<div class="col-md-6" @if(empty($img->notaPembayaran)) style="display:none; "@endif>
									<label>Bukti Pembayaran</label><br>
									<a class="pan" data-big="{{ asset($img->notaPembayaran) }}" href="javascript:">
										<img src="{{ asset($img->notaPembayaran) }}" alt="" style="max-width:150px;">
									</a>
								</div>
							</div>
							@endforeach
							@endif
							@endif

							<hr class="mb-1 mt-2">
						@endif

						@if($menu == 'stokObat')
						<input type="hidden" id="idForm" name="id_stok_barang[]" value="{{(!empty($getStokBarang)) ? $getStokBarang->id : ''}}">
						@else
						<!-- <input type="hidden" id="idForm" name="id_stok_barang[]" value="{{(!empty($faktur)) ? $getFaktur->id : ''}}"> -->
						<input type="hidden" id="idForm" name="id_faktur[]" value="{{(!empty($getFaktur)) ? $getFaktur->id : ''}}">
						@endif

						<!-- Master Persentase -->
						@foreach($mstPersentase as $key => $val)
						@if($val->id==1)
						<input type="hidden" name="mstUmum" id="mstUmum" value="{{$val->persentase}}">
						@endif
						@if($val->id==2)
						<input type="hidden" name="mstResep" id="mstResep" value="{{$val->persentase}}">
						<!-- <input type="hidden" name="mstResepNominal" id="mstResepNominal" value="{{$val->nominal}}"> -->
						@endif
						@if($val->id==3)
						<input type="hidden" name="mstDispensingPerBox" id="mstDispensingPerBox" value="{{$val->persentase}}">
						@endif
						@if($val->id==4)
						<input type="hidden" name="mstDispensingPerBiji" id="mstDispensingPerBiji" value="{{$val->persentase}}">
						@endif
						@endforeach

						<input type="hidden" id="menu" name="menu[]" value="{{$menu}}">
						{{-- START FORM SELECT OBAT --}}
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label class="mt-3">{{$text}} <span class="text-red" style="font-size: 14px;">*</span></label>
										</div>
										<div class="col-md-6">
											<a href="javascript:" onclick="newObat()" class="float-right btn btn-primary btn-sm rounded-2 mt-2 mb-2" ><i class="fa fa-plus"></i>Master Obat Baru</a>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<select class="form-control" id="barang" name="barang[]">
												<option value="first">--Cari {{$text}}--</option>
												{{--@if($menu == 'stokRetail' || $menu=='pembelianRetail')
													@foreach($retail as $key => $val)
														<option value="{{$val->id}}" {{($menu=='stokRetail' && !empty($getStokBarang) && $getStokBarang->barang_id == $val->id) ? 'selected' : ''}}>{{$val->nama}} ({{$val->satuan->nama ?? 'Null'}})</option>
													@endforeach
													@endif--}}

													@foreach($obat as $key => $val)
													@php
														$nameObat = strtoupper($val->nama);
														if(!empty($val->satuan->nama)){
															$nameSatuan = strtoupper($val->satuan->nama);
														}else{
															$nameSatuan = '';
														}
													@endphp
														<option value="{{$val->id}}" {{($menu=='stokObat' && !empty($getStokBarang) && $getStokBarang->barang_id == $val->id) ? 'selected' : ''}}>{{$nameObat}} ({{$nameSatuan}})</option>
													@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						{{-- END FORM SELECT OBAT --}}

						<div class="row">
							<div class="@if($form=='Stok Obat') col-md-6 @else col-md-6 @endif">
								<div class="form-group">
									<label>No Batch <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="no_batch" name="no_batch[]" placeholder="Masukkan No Batch " value="{{($menu=='stokObat' && !empty($getStokBarang)) ? $getStokBarang->no_batch : ''}}">
								</div>
							</div>
							<div class="@if($form=='Stok Obat') col-md-6 @else col-md-6 @endif">
								<div class="form-group">
									<label>QR/BarCode</label>
									<input type="text" class="form-control" id="barCode" name="barCode[]" placeholder="Kosongkan jika tidak ada QR/BarCode" value="{{($menu=='stokObat' && !empty($getStokBarang)) ? $getStokBarang->barcode : ''}}">
								</div>
							</div>
							{{-- <div class="@if($form=='Stok Obat') col-md-3 @else col-md-4 @endif">
								<div class="form-group">
									<label>Jumlah Box</label>
									<input type="text" class="form-control" id="jumlah" name="jumlah[]" placeholder="Masukkan Jumlah Unit/Obat" value="{{($menu=='stokObat' && (!empty($getStokBarang))) ? $getStokBarang->jumlah : ''}}" onkeyup="ubahFormatNumber(this)">
								</div>
							</div> --}}
							{{-- <div class="@if($form=='Stok Obat') col-md-3 @else col-md-4 @endif">
								<div class="form-group field">
									<label>Jumlah Box <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="jumlahBox" name="jumlahBox[]" placeholder="Masukkan Jumlah Box" value="{{($menu=='stokObat' && (!empty($getStokBarang))) ? $getStokBarang->jumlah_box : ''}}" onkeyup="ubahFormatNumber(this)">
								</div>
							</div>
							<div class="@if($form=='Stok Obat') col-md-3 @else col-md-4 @endif">
								<div class="form-group field">
									<label>Jumlah Biji PerBox <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="jumlahPerBox" name="jumlahPerBox[]" placeholder="Masukkan Jumlah Biji PerBox" value="{{($menu=='stokObat' && (!empty($getStokBarang))) ? $getStokBarang->jumlah_perbox : ''}}" onkeyup="ubahFormatNumber(this)">
								</div>
							</div> --}}
							{{-- @if($form=='Stok Obat')
							<div class="@if($form=='Stok Obat') col-md-4 @else col-md-6 @endif">
								<div class="form-group">
									<label>Supplier</label>
									<select class="form-control" id="pbf" name="pbf[]">
										<option value="first">Cari Supplier</option>
										@foreach($pbfs as $key => $pbf)
											<option value="{{$pbf->id}}" {{(!empty($getStokBarang)) && $getStokBarang->pbf_id == $pbf->id ? 'selected' : ''}}>{{$pbf->nama}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@endif --}}
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group field">
									<label>Jumlah Box <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="jumlahBox" name="jumlahBox[]" placeholder="Masukkan Jumlah Box" value="{{($menu=='stokObat' && (!empty($getStokBarang))) ? $getStokBarang->jumlah_box : ''}}" onkeyup="ubahFormatNumber(this)">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group field">
									<label>Jumlah Biji PerBox <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="jumlahPerBox" name="jumlahPerBox[]" placeholder="Masukkan Jumlah Biji PerBox" value="{{($menu=='stokObat' && (!empty($getStokBarang))) ? $getStokBarang->jumlah_perbox : ''}}" onkeyup="ubahFormatNumber(this)">
								</div>	
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Harga Beli <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" onkeyup="ubahFormat(this)" id="harga_beli" name="harga_beli[]" value="{{($menu=='stokObat' && !empty($getStokBarang)) ? rupiah($getStokBarang->harga_beli) : ''}}" placeholder="Masukkan Harga Beli">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Diskon (%)</label>
									<input type="text" class="form-control" onkeyup="ubahDiskon(this)" id="diskonBeli" name="diskonBeli[]" value="{{($menu=='stokObat' && !empty($getStokBarang->diskon)) ? $getStokBarang->diskon : ''}}" placeholder="Kosongkan Jika Tidak Ada Diskon">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Harga Umum <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" onkeyup="ubahFormat(this)" id="harga_umum" name="harga_umum[]" value="{{($menu=='stokObat' && !empty($getStokBarang)?rupiah($getStokBarang->harga_umum) : '')}}" {{($menu=='stokObat' && !empty($getStokBarang) && (!empty($getStokBarang->nominal_laba) || (!empty($getStokBarang->nominal_pajak)))) ? 'readonly' : ''}} readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Harga Resep <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" onkeyup="ubahFormat(this)" id="harga_resep" name="harga_resep[]" value="{{($menu=='stokObat' && !empty($getStokBarang) ? rupiah($getStokBarang->harga_resep) : '')}}" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Harga Dispensing(<span style="font-weight: 150;">perBox</span>) <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" onkeyup="ubahFormat(this)" id="harga_dispensing" name="harga_dispensing[]" value="{{($menu=='stokObat' && !empty($getStokBarang) ? rupiah($getStokBarang->harga_dispensing) : '')}}" readonly>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Harga Dispensing(<span style="font-weight: 150;">perBiji</span>) <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" onkeyup="ubahFormat(this)" id="harga_dispensing_perbiji" name="harga_dispen_biji[]" value="{{($menu=='stokObat' && !empty($getStokBarang) ? rupiah($getStokBarang->harga_dispensing_perbiji) : '')}}" readonly>
								</div>
							</div>
						</div>

						<div class="row mb-3">

							<div class="col-md-3">
								<div class="form-group">
									<label>Stok Awal(<span style="font-weight: 150;">Total Biji</span>) <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="stokAwal" name="stokAwal[]" value="{{($menu=='stokObat' && !empty($getStokBarang)) ? $getStokBarang->stok_awal : ''}}" placeholder="Stok Awal">
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>Minimal Stok <span class="text-red" style="font-size: 14px;">*</span></label>
									<input type="text" class="form-control" id="minStok" name="minStok[]" value="{{($menu=='stokObat' && !empty($getStokBarang)) ? $getStokBarang->minimal_stok : ''}}" placeholder="Minimal Stok">
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label> Tanggal Masuk <span class="text-red" style="font-size: 14px;">*</span></label>
									<input class="tm form-control" type="date" id="tgl_masuk" name="tgl_masuk[]" data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required value="{{($menu=='stokObat' && !empty($getStokBarang)) ? $getStokBarang->tgl_masuk:''}}">
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>Tanggal Expired <span class="text-red" style="font-size: 14px;">*</span></label>
									<input class="tm form-control" type="date" id="expired" name="expired[]" data-date-format="DD/MMM/YYYY" placeholder="dd/mm/yyyy" required value="{{($menu=='stokObat' && !empty($getStokBarang)) ? $getStokBarang->expired:''}}">
								</div>
							</div>
						</div>

						{{-- <div class="row mb-3">
							<div class="col-md-6">
								<div class="form-check">
									<input type="checkbox" name="claba" id="claba" class="form-check-input" {{($menu=='stokObat' && !empty($getStokBarang) && !empty($getStokBarang->nominal_laba)) ? 'checked' : ''}}>
									<label class="form-check-label">Laba</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-check">
									<input type="checkbox" name="cpajak[]" id="cpajak" class="form-check-input" {{($menu=='stokObat' && !empty($getStokBarang) && !empty($getStokBarang->nominal_pajak)) ? 'checked' : ''}}>
									<label class="form-check-label">Pajak</label>
								</div>
							</div>
						</div> --}}

						<div class="row">
							<div class="col-md-6" id="flaba" @if($menu=='stokObat' && !empty($getStokBarang) && !empty($getStokBarang->nominal_laba))  @else style="display: none;" @endif>
								<div class="form-group">
									<label for="">Laba (%)</label>
									<input type="text" name="laba[]" id="laba" onchange="hitungHargaJual()" onkeyup="ubahFormatNumber(this)" placeholder="Masukkan Laba (%)" class="form-control" value="{{($menu=='stokObat' && !empty($getStokBarang) && !empty($getStokBarang->nominal_laba)) ? $getStokBarang->nominal_laba : ''}}">
								</div>
							</div>
							<div class="col-md-6" id="fpajak" @if($menu=='stokObat' && !empty($getStokBarang) && !empty($getStokBarang->nominal_pajak)) @else style="display: none;" @endif>
								<div class="form-group">
									<label for="">Pajak (%)</label>
									<input type="text" name="pajak[]" id="pajak" onchange="hitungHargaJual()" onkeyup="ubahFormatNumber(this)" placeholder="Masukkan Pajak (%)" class="form-control" value="{{($menu=='stokObat' && !empty($getStokBarang) && !empty($getStokBarang->nominal_pajak)) ? $getStokBarang->nominal_pajak : ''}}">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								@if(!empty($getStokBarang) && $menu=='stokObat')
									<a href="javascript:void(0)" class="btn btn-danger btn-cancel">Kembali</a>
									<button class="btn btn-success float-right btn-simpan">Simpan</button>
								@else
									<button type="button" class="btn btn-info float-right btn-tambah">Tambahkan data</button>
									<div class="btn-update"></div>
								@endif
							</div>
						</div>
					</form>
				</div>
			</div>

			@if(empty($getStokBarang) || $menu=='pembelianObat')
			<div class="card mt-2">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<h4>Data {{$form}}</h4>
						</div>
						@if($form=='Pembelian Obat')
						<div class="col-md-6 text-right mt-2">
							<input type="hidden" class="form-control" id="persentaseDPP" name="persentaseDPP[]" value="{{(!empty($faktur)?$getFaktur->persentase_dpp:'')}}">
							<b><span>Total Semua Harga:&nbsp;</span><span class="mr-4" id="total_semua_harga">Rp. 0</span></b>
						</div>
						@endif
					</div>
					<form class="formInputNew">
						<input type="hidden" name="fixHarga[]" id="fixHarga" value="">
						<div class="table-responsive mb-5">
							<table id="tbBarang" class="table table-bordered">
								<thead>
									<tr>
										<th>No Batch</th>
										<th>Unit</th>
										<th>Nama {{$text}}</th>
										<th>Harga Satuan</th>
										<th>Jumlah</th>
										<th>Diskon</th>
										<th>Potongan</th>
										<th>Total Harga</th>
										@if($menu=='stokObat')
										<!-- <th>Harga Umum</th> -->
										<th>Expired</th>
										<th>Tanggal Masuk</th>
										<!-- <th>Laba</th> -->
										<!-- <th>Pajak</th> -->
										@endif
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody class="tempatData">
									@if($menu=='pembelianObat')
									@if(isset($detailFaktur)&&!empty($detailFaktur))
									@foreach($data as $key => $val)
									<tr id="{{$loop->index+1}}">
										<td id="td_no_batch">
											<input type="hidden" name="no_batch[]" value="{{$val->no_batch}}" id="arr_no_batch" class="form-control">
											<span>{{$val->no_batch}}</span>
										</td>
										<td id="td_jumlah">
											<input type="hidden" name="jumlahBox[]" value="{{$val->jumlah_box}}" id="arr_jumlah_box">
											<input type="hidden" name="jumlahPerBox[]" value="{{$val->jumlah_perbox}}" id="arr_jumlah_perbox">
											<span>{{$val->jumlah_box}} Box</span>
										</td>
										<td id="td_obat">
											<input type="hidden" name="barang[]" value="{{$val->barang->id}}" id="arr_barang" class="form-control">
											<input type="hidden" id="idTable" name="id_stok_barang[]" value="{{$val->id}}">
											<input type="hidden" name="menu[]" value="{{$menu}}">
											<span>{{$val->barang->nama}} ({{$val->barang->satuan->nama}})</span>
										</td>
										<td id="td_harga_beli">
											<input type="hidden" name="harga_beli[]" value="{{rupiah($val->harga_beli)}}" id="arr_harga_beli" class="form-control">
											<span>{{rupiah($val->harga_beli)}}</span>
										</td>
										@php
											$totalHarga = $val->harga_beli * $val->jumlah_box;
											$potongan = round(($totalHarga)*($val->diskon/100));
											$totalHarga2 = $totalHarga-$potongan;
										@endphp
										<td id="td_total_harga">
											<span>{{rupiah($totalHarga)}}</span>
											<input type="hidden" name="total_harga[]" value="{{$totalHarga}}" id="arr_total_harga">
										</td>
										<td id="td_diskon_beli">
											<span>{{($val->diskon!=null)?$val->diskon.'%':'-'}}</span>
											<input type="hidden" name="diskonBeli[]" value="{{$val->diskon}}" id="arr_diskon_beli">
										</td>
										<td id="td_potongan">
											<span>{{($val->diskon!=null)?rupiah($potongan):'-'}}</span>
											<input type="hidden" name="potongan[]" value="{{($val->diskon!=null)?$potongan:''}}" id="arr_potongan">
										</td>
										<td id="td_total_harga_2">
											<span>{{rupiah($totalHarga2)}}</span>
											<input type="hidden" name="totalHarga2[]" value="{{$totalHarga2}}" id="arrTotalHarga2">
										</td>
										<td>
											<input type="hidden" name="barCode[]" value="{{$val->barcode}}" id="arr_barCode">
											<input type="hidden" name="harga_umum[]" value="{{rupiah($val->harga_umum)}}" id="arr_harga_umum">
											<input type="hidden" name="harga_resep[]" value="{{rupiah($val->harga_resep)}}" id="arr_harga_resep">
											<input type="hidden" name="harga_dispensing[]" value="{{rupiah($val->harga_dispensing)}}" id="arr_harga_dispensing">
											<input type="hidden" name="harga_dispen_biji[]" value="{{rupiah($val->harga_dispensing_perbiji)}}" id="arr_harga_dispen_biji">
											<input type="hidden" name="minStok[]" value="{{$val->minimal_stok}}" id="arr_min_stok">
											<input type="hidden" name="stokAwal[]" value="{{$val->stok_awal}}" id="arr_stokAwal">
											<input type="hidden" name="expired[]" value="{{$val->expired}}" id="arr_expired">
											<input type="hidden" name="tgl_masuk[]" value="{{$val->tgl_masuk}}" id="arr_tgl_masuk">
											<!-- <input type="hidden" name="laba[]" value="{{$val->nominal_laba}}" id="arr_laba"> -->
											<!-- <input type="hidden" name="pajak[]" value="{{$val->nominal_pajak}}" id="arr_pajak"> -->
											<center class="mt-2">
												<a href="javascript:;" onclick="editData(`{{$loop->index+1}}`)"><i class="ik ik-edit f-16 mr-15 text-warning"></i></a>
												<a href="javascript:;" onclick="deleteData(`{{$loop->index+1}}`)"><i class="fa fa-trash-alt text-danger"></i></a>
											</center>
										</td>
									</tr>
									@endforeach
									@endif
									@endif
								</tbody>
							</table>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="btn btn-danger btn-cancel">Kembali</button>
							<button class="btn btn-success float-right btn-simpan">Simpan</button>
						</div>
					</div>
				</div>
			</div>	
			@endif
		</div>
	</div>
</div>

<div class="modal fade" role="dialog" id="masterObatModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Data Obat Baru</h4>
			</div>
			<div class="modal-body">
				<div class="card-body">
					<form class="formInputSatuan">
						{{-- @csrf --}}
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="nama">Nama Obat</label>
									<input id="namaObat" type="text" class="form-control"
									name="namaObat" placeholder="Nama Obat" required>
									<div class="help-block with-errors"></div>
									{{-- ERROR FEEDBACK --}}
								</div>
							</div>

							<div class="col-sm-12">
								<div class="form-group">
									<label for="satuan">Satuan Obat</label>
									<div class="row">
										<div class="col-md-10">
											<select class="form-control" id="mstSatuan" name="mstSatuan">
												<option value="first">--Pilih Satuan--</option>
												@foreach ($satuan as $p)
												<option value="{{ $p->id }}">{{ $p->nama }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-2">
											<a href="javascript:" onclick="newMstSatuan()" class="btn btn-success btn-sm rounded-2" ><i class="fa fa-plus"></i></a>
										</div>
									</div>
									<!-- <div class="help-block with-errors"></div> -->
									{{-- ERROR FEEDBACK --}}

								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default closeMstBarang" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success saveMstBarang">Simpan</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{asset('assets/js/jquery.pan.js')}}"></script>
<script type="text/javascript">
	var menu = $('#menu').val();
	var satuanSelect = $('#mstSatuan').val();

	$("#pbf").on("select2:open",()=>{
		document.querySelector(".select2-search__field").focus()
	})

	$("#barang").on("select2:open",()=>{
		document.querySelector(".select2-search__field").focus()
	})

	$("#mstSatuan").select2({
		language: {
			noResults: function(term) {
				satuanSelect = $('.select2-search__field').val()
			}
		}
	});
	$("#pajakFaktur").select2()
	$("#pbf").select2()
	$("#barang").select2({
		language: {
			noResults: function(term) {
				obatSelect = $('.select2-search__field').val()
			}
		}
	});

	// GET HARGA BELI MASTER OBAT
	// SEMENTARA TIDAK DIGUNAKAN
		// $('#barang').change(function(){
		// 	var idBarang = $('#barang').val();
		// 	$.post("{{route('getHargaMaster')}}",{id:idBarang}).done(function(data){
		// 		if(!jQuery.isEmptyObject(data)){
		// 			if(data.harga_beli){
		// 				$('#harga_beli').val(formatRupiah(data.harga_beli,'Rp. '));
		// 			}else{
		// 				$('#harga_beli').val('');
		// 			}
		// 		}
		// 	});
		// })

	function newObat(){
		$("#masterObatModal").modal('show')
	}

	// SIMPAN MASTER SATUAN BARU
	function newMstSatuan(){
		var data = new FormData($('.formInputSatuan')[0]);
		data.append('nama',satuanSelect);
		$.ajax({
			type: "POST",
			url: "{{route('simpanSatuan')}}",
			cache: false,
			contentType: false,
			processData: false,
			data: data,
		}).done(function(data){
			if(data.status == 'success'){
				Swal.fire({
					icon: data.status,
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				});
				var option = {
					id: data.data.id,
					text: data.data.nama,
				}
				var newOption = new Option(option.text, option.id, false, false);
				$("#mstSatuan").append(newOption).trigger('change');
				$('#mstSatuan').val(option.id).trigger('change');
			}else{
				Swal.fire({
					icon: data.status,
					title: 'Gagal',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				});
			}
		});
	}

	// SIMPAN MASTER BARANG BARU
	$(".saveMstBarang").click(function(){
		var data = new FormData($('.formInputSatuan')[0]);
		$.ajax({
			type: "POST",
			url: "{{route('simpanBarang')}}",
			cache: false,
			contentType: false,
			processData: false,
			data: data,
		}).done(function(data){
			if(data.status == 'success'){
				Swal.fire({
					icon: data.status,
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				});
				var option = {
					id: data.data.barang.id,
					text: data.data.barang.nama+' ('+data.data.satuan.nama+')',
				}
				var newOption = new Option(option.text, option.id, false, false);
				$("#barang").append(newOption).trigger('change');
				$('#masterObatModal').modal('toggle');
				$('#namaObat').val('');
				$('#mstSatuan').val('first').trigger('change');
				$('')
			}else{
				Swal.fire({
					icon: data.status,
					title: 'Gagal',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				});
			}
		});
	});

	$(document).ready(function(){
		$(".pan").pan();
		var idForm = $('#idForm').val()
		var title = $('#title').val();
		if(title=='Edit' && menu=='pembelianObat'){
			var harga = $(' input[name^="totalHarga2"]');
			var total_harga = 0;
			for (var i = 0; i < harga.length; i++) {
				total_harga += parseInt(harga[i].value);
			}
			$('#fixHarga').val(total_harga);
			$('#persentaseDPP').val(total_harga)
			$('#total_semua_harga').html(formatRupiah(total_harga, "Rp. "));
		}

		if (idForm.length == 0) {
			$('#jatuhTempo').val(getDateToday());
			$('#tgl_masuk').val(getDateToday());
		}
		$('#tgl_masuk').val(getDateToday());
		@if($title=='Tambah'||($title!='Edit' && $form!='Stok Obat'))
		$('#jumlahPerBox').attr('readonly','readonly');
		$('#harga_beli').attr('readonly','readonly');
		@endif
	});

	$('.btn-cancel').click(function(e){
		e.preventDefault();
		$('.form-stok').animateCss("bounceOutDown");
		$('.otherPages').fadeOut(function(){
			$('.otherPages').empty();
			$('.main-layer').fadeIn();
		});
	});

	$('#cekFakturPajak').change(function(){
		var cFaktur = $('#cekFakturPajak:checked').length;
		if(cFaktur==1){
			$('#checkFaktur').hide();
			$('#formFakturPajak').show();
			$('#showPajak').show();
		}else{
			$('#formFakturPajak').hide();
		}
	});

	if (menu=='pembelianObat') {
		$('#pbf').change(function(){
			var data = $('#pbf').select2('data');
			$.post("{{route('getAlamatPBF')}}",{id:data[0].id}).done(function(data){
				if (data!="") {
					if(data.alamat=="" || data.alamat==null){
						$('#alamatPBF').removeAttr('readonly','readonly')
						$('#alamatPBF').attr("placeholder", "Masukkan Alamat PBF")
						$('#alamatPBF').val('')
					}else{
						$('#alamatPBF').attr('readonly','readonly')
						$('#alamatPBF').removeAttr('placeholder')
						$('#alamatPBF').val(data.alamat)
					}
				}else{
					$('#alamatPBF').removeAttr('readonly','readonly')
					$('#alamatPBF').val('')
				}
			});
		});
	}

	$('#harga_beli').change(function(){
		var claba = $('#claba:checked').length;
		var cpajak = $('#cpajak:checked').length;

		var laba = parseInt($('#laba').val().replace(/\D/g, ''));
		var pajak = parseInt($('#pajak').val().replace(/\D/g, ''));
		var harga_beli = parseInt($('#harga_beli').val().replace(/\D/g, ''));
		var harga_umum = 0;

		if (harga_beli >= 0) {
			if (claba == 1 || cpajak == 1) {
				if (laba >= 0 && pajak >= 0) {
					harga_umum = harga_beli + (harga_beli * (laba / 100)) + (harga_beli * (pajak / 100));
				} else if (laba >= 0) {
					harga_umum = harga_beli + (harga_beli * (laba / 100));
				} else if (pajak >= 0) {
					harga_umum = harga_beli + (harga_beli * (pajak / 100));
				}

				$('#harga_umum').val(formatRupiah(harga_umum, "Rp. "));
			}
		} else {
			$('#laba').val('');
			$('#pajak').val('')
			swal('Maaf','Mohon untuk mengisi Harga Beli terlebih dahulu','warning');
		}
	});

	$('#cekPiutang').change(function(){
		var cPiutang = $('#cekPiutang:checked').length;
		if(cPiutang==1){
			$('#showPembayaran').hide();
			$('#notaPembayaran').val('');
		}else{
			$('#showPembayaran').show();
		}
	});

	// SEMENTARA TIDAK DIGUNAKAN
		// $('#claba').change(function(){
		// 	var claba = $('#claba:checked').length;
		// 	var cpajak = $('#cpajak:checked').length;
		// 	if (claba == 1) {
		// 		$('#flaba').show();
		// 	} else {
		// 		$('#flaba').hide();
		// 		$('#laba').val('');
		// 		hitungHargaJual();
		// 	}

		// 	if (claba == 1 || cpajak == 1) {
		// 		$('#harga_umum').attr('readonly', 'readonly');
		// 	} else {
		// 		$('#harga_umum').removeAttr('readonly', 'readonly');
		// 	}
		// });

	// SEMENTARA TIDAK DIGUNAKAN
		// $('#cpajak').change(function()  {
		// 	var claba = $('#claba:checked').length;
		// 	var cpajak = $('#cpajak:checked').length;
		// 	if (cpajak == 1) {
		// 		$('#fpajak').show();
		// 	}else{
		// 		$('#fpajak').hide();
		// 		$('#pajak').val('');
		// 		hitungHargaJual();
		// 	}

		// 	if (claba == 1 || cpajak == 1) {
		// 		$('#harga_umum').attr('readonly', 'readonly');
		// 	}else{
		// 		$('#harga_umum').removeAttr('readonly', 'readonly');
		// 	}
		// });

	$('.btn-tambah').click(function(){
		var obat			 = $('#barang').val();
		var nama_obat		 = $('#select2-barang-container');
		var pbf				 = $('#pbf').val();
		var nama_pbf		 = $('#select2-pbf-container');
		var title			 = $('#title').val();
		var no_batch		 = $('#no_batch').val();
		var barCode			 = $('#barCode').val();

		var jumlahBox		 = $('#jumlahBox').val();
		var jumlahPerBox	 = $('#jumlahPerBox').val();

		var harga_beli		 = $('#harga_beli').val();
		var diskonBeli		 = $('#diskonBeli').val();

		var harga_umum		 = $('#harga_umum').val();
		var harga_resep		 = $('#harga_resep').val();
		var harga_dispensing = $('#harga_dispensing').val();
		var hargaDispenBiji	 = $('#harga_dispensing_perbiji').val();

		var minStok			 = $('#minStok').val();
		var expired			 = $('#expired').val();
		var tgl_masuk		 = $('#tgl_masuk').val();

		var laba			 = $('#laba').val();
		var pajak			 = $('#pajak').val();
		var baru			 = "baru";
		var fixed			 = harga_beli.replace(/\D+/g, '');
		var total_harga		 = jumlahBox*fixed;
		var totalHarga2		 = Math.round(total_harga-(total_harga*(diskonBeli/100)));

		var stokAwal         = $('#stokAwal').val();

		if (diskonBeli==""||diskonBeli==null||diskonBeli==0) {
			var diskonBeli = "";
			var potongan = "";
		}else{
			var diskonBeli = diskonBeli;
			var potongan = Math.round(total_harga*(diskonBeli/100));
		}

		if(menu=='pembelianObat'){
			var gambarFaktur = $('input[name=notaFaktur]')[0].files[0]
			var noRegistrasi = $('#noRegistrasi').val()
			var noFaktur = $('#noFaktur').val()
			var alamatPBF = $('#alamatPBF').val()
			var jatuhTempo = $('#jatuhTempo').val()
		}

		if(obat == 'first'){
			Swal.fire('Maaf','Kolom Obat Harus di Pilih','warning')
		}else if(!no_batch){
			Swal.fire('Maaf','Kolom No Batch Harus diisi','warning')
		}else if(!jumlahBox) {
			Swal.fire('Maaf','Kolom Jumlah Box Harus diisi','warning')
		}else if(!jumlahPerBox){
			Swal.fire('Maaf', 'Kolom Jumlah Biji perBox Harus diisi', 'warning')
		}else if(!harga_beli) {
			Swal.fire('Maaf','Kolom Harga Beli Harus diisi','warning');
		}else if(!stokAwal){
			Swal.fire('Maaf','Kolom Stok Awal Harus diisi','warning');
		}else if(!minStok) {
			Swal.fire('Maaf','Kolom Minimal Stok Harus diisi','warning');
		}else if(!tgl_masuk) {
			Swal.fire('Maaf','Kolom Tanggal Masuk Harus diisi','warning');
		}else if(!expired) {
			Swal.fire('Maaf','Kolom Tanggal Expired Harus diisi','warning');
		}else{
			var html = '';
			var id = generate_id(5);
			html += '<tr class="rowBarang" id="'+id+'">';
			html += '<td id="td_no_batch">';
			html += '<span>'+no_batch+'</span>';
			html += '<input type="hidden" name="no_batch[]" value="'+no_batch+'" id="arr_no_batch" class="form-control">';
			html += '</td>';

			html += '<td id="td_jumlah">';
			html += '<span>'+jumlahBox+' Box</span>';
			html += '<input type="hidden" name="jumlahBox[]" value="'+jumlahBox+'" id="arr_jumlah_box">';
			html += '<input type="hidden" name="jumlahPerBox[]" value="'+jumlahPerBox+'" id="arr_jumlah_perbox">';
			html += '</td>';

			html += '<td id="td_obat">';
			html += '<span>'+nama_obat.attr('title')+'</span>';
			html += '<input type="hidden" name="barang[]" value="'+obat+'" id="arr_barang" class="form-control">';
			html += '<input type="hidden" id="idTable" name="id_stok_barang[]" value="'+baru+'">';
			html += '<input type="hidden" name="menu[]" value="'+menu+'">';
			// if(menu=='stokObat'){
			// 	html += '<input type="hidden" name="pbf[]" value="'+pbf+'" id="arr_pbf" class="form-control">';
			// }
			html += '</td>';
			// html += '<td id="td_pbf">';
			// html += '<span>'+nama_pbf.attr('title')+'</span>';
			// html += '</td>';
			
			html += '<td id="td_harga_beli">';
			html += '<span>'+harga_beli+'</span>';
			html += '<input type="hidden" name="harga_beli[]" value="'+harga_beli+'" id="arr_harga_beli" class="form-control">';
			html += '</td>';
			// if(menu=='pembelianObat'){
				html += '<td id="td_total_harga">';
				html += '<span>'+formatRupiah(total_harga, "Rp. ")+'</span>';
				html += '<input type="hidden" name="total_harga[]" value="'+total_harga+'" id="arr_total_harga" class="form-control">';
				html += '</td>';

				html += '<td id="td_diskon_beli">';
				html += (diskonBeli)?'<span>'+diskonBeli+'%</span>':'<span>-</span>';
				html += '<input type="hidden" name="diskonBeli[]" value="'+diskonBeli+'" id="arr_diskon_beli" class="form-control">';
				html += '</td>';

				html += '<td id="td_potongan">';
				html += (potongan)?'<span>'+formatRupiah(potongan, "Rp. ")+'</span>':'<span>-</span>';
				html += '<input type="hidden" name="potongan[]" value="'+potongan+'" id="arr_potongan" class="form-control">';
				html += '</td>';

				html += '<td id="td_total_harga_2">';
				html += '<span>'+formatRupiah(totalHarga2,"Rp. ")+'</span>';
				html += '<input type="hidden" name="totalHarga2[]" value="'+totalHarga2+'" id="arrTotalHarga2" class="form-control">';
				html += '</td>';
			// }
			if(menu=='stokObat'){
				// html += '<td id="td_harga_umum">';
				// html += '<span>'+harga_umum+'</span>';
				// html += '</td>';
				html += '<td id="td_expired">'
				html += '<span>'+expired+'</span>'
				html += '</td>'
				html += '<td id="td_tgl_masuk">'
				html += '<span>'+tgl_masuk+'</span>'
				html += '</td>'
				// html += '<td id="td_laba">';
				// html += (laba) ? '<span>'+laba+' %'+'</span>' : '<span>'+'-'+'</span>';
				// html += '</td>';
				// html += '<td id="td_pajak">';
				// html += (pajak) ? '<span>'+pajak+' %'+'</span>' : '<span>'+'-'+'</span>';
				// html += '</td>';
			}
			html += '<td>';
			html += '<input type="hidden" name="harga_umum[]" value="'+harga_umum+'" id="arr_harga_umum" class="form-control">'
			html += '<input type="hidden" name="harga_resep[]" value="'+harga_resep+'" id="arr_harga_resep" class="form-control">'
			html += '<input type="hidden" name="harga_dispensing[]" value="'+harga_dispensing+'" id="arr_harga_dispensing" class="form-control">'
			html += '<input type="hidden" name="harga_dispen_biji[]" value="'+hargaDispenBiji+'" id="arr_harga_dispen_biji">'
			html += '<input type="hidden" name="expired[]" value="'+expired+'" id="arr_expired">'
			html += '<input type="hidden" name="tgl_masuk[]" value="'+tgl_masuk+'" id="arr_tgl_masuk" class="form-control">'
			html += '<input type="hidden" name="minStok[]" value="'+minStok+'" id="arr_min_stok" class="form-control">'
			html += '<input type="hidden" name="barCode[]" value="'+barCode+'" id="arr_barCode" class="form-control">'
			html += '<input type="hidden" name="stokAwal[]" value="'+stokAwal+'" id="arr_stokAwal" class="form-control">'
			// html += '<input type="" name="laba[]" value="'+laba+'" id="arr_laba" class="form-control">';
			// html += '<input type="" name="pajak[]" value="'+pajak+'" id="arr_pajak" class="form-control">';

			html += '<center> <a href="javascript:;" onclick="editData(`'+id+'`)"><i class="ik ik-edit f-16 mr-15 text-warning"></i></a>'
			html += '<a href="javascript:;" onclick="deleteData(`'+id+'`)"><i class="fa fa-trash-alt text-danger"></i></i></a> </center>'
			html += '</td>'
			html += '</tr>'
			$('.tempatData').append(html)
			
			if(menu=='pembelianObat'){
				var harga = $(' input[name^="total_harga"]')
				var harga2 = $(' input[name^="totalHarga2"]')

				var total_harga = 0
				var totalHarga2 = 0

				for (var i = 0; i < harga.length; i++) {
					total_harga += parseInt(harga[i].value)
				}

				for (var i = 0; i < harga2.length; i++) {
					totalHarga2 += parseInt(harga2[i].value)
				}
				$('#fixHarga').val(totalHarga2)
				$('#persentaseDPP').val(totalHarga2)
				$('#total_semua_harga').html(formatRupiah(totalHarga2, "Rp. "))
			}
			$('#barang').val('first').trigger('change')

			$('#no_batch').val('')
			$('#barCode').val('')
			$('#jumlahBox').val('')
			$('#jumlahPerBox').val('')
			$('#jumlahPerBox').attr('readonly', 'readonly')
			$('#jumlahPerBox').attr('readonly', 'readonly')
			$('#harga_beli').attr('readonly', 'readonly')
			if(menu=='stokObat'){
				$('#pbf').val('first').trigger('change')
			}

			$('#diskonBeli').val('')
			$('#harga_beli').val('')
			$('#harga_umum').val('')
			$('#harga_resep').val('')
			$('#harga_dispensing').val('')
			$('#harga_dispensing_perbiji').val('')
			$('#minStok').val('')
			$('#stokAwal').val('')

			$('#expired').val('')
			$('#tgl_masuk').val(getDateToday())

			$('#claba').prop('checked', false)
			$('#cpajak').prop('checked', false)

			$('#flaba').hide()
			$('#fpajak').hide()
			$('#laba').val('')
			$('#pajak').val('')
		}
	});

	$('.btn-simpan').click(function(e){
		e.preventDefault();
		var idTable = $('#idTable').val()
		var idForm = $('#idForm').val()
		var menu = $('#menu').val()

		var noFaktur = $('#noFaktur').val()
		var pbf = $('#pbf').val()
		var jatuhTempo = $('#jatuhTempo').val()
		var title = $('#title').val()
		@if ($menu=='pembelianObat')
		var gambarFaktur = $('input[name=notaFaktur]')[0].files[0]
		var notaBayar = $('input[name=notaPembayaran]')[0].files[0]
		@else
		var gambarFaktur = 0
		var notaBayar = 0
		@endif
		var piutang = $('#cekPiutang:checked').length
		var alamatPBF = $("#alamatPBF").val()

		if (idTable == 'baru' || idForm.length != 0) {
			// $('.btn-simpan').html('Proses Simpan').attr('disabled', true);
			@if($menu=='stokObat' && !empty($getStokBarang))
				var data = new FormData($('.formInputOld')[0]);
				if (menu=='pembelianObat') {
					var idDetailPajak = [];
					var idMstPajak = [];
					var nominalPajak = [];
					var noRegistrasi = $('#noRegistrasi').val();
					var noFaktur = $('#noFaktur').val();
					var jatuhTempo = $('#jatuhTempo').val();
					var pbfId = $('#pbf').select2('data');

					data.append('pbfId[]',pbfId[0].id);
					data.append('noRegistrasi[]',noRegistrasi);
					data.append('noFaktur[]',noFaktur);
					data.append('jatuhTempo[]',jatuhTempo);

					$('#tbPajak .rowPajak').each(function(){
						$(this).find('#idDetailPajak').each(function(i,val){
							idDetailPajak.push(val.value);
						});
						$(this).find('#idMstPajak').each(function(i,val){
							idMstPajak.push(val.value);
						});
						$(this).find('#nominalPajak').each(function(i,val){
							nominalPajak.push(val.value);
						});
					});
					idDetailPajak.forEach(element =>{
						data.append('idDetailPajak[]',element);
					});
					idMstPajak.forEach(element => {
						data.append('idMstPajak[]',element);
					});
					nominalPajak.forEach(element => {
						data.append('nominalPajak[]',element);
					});
				}
			@else
				var data = new FormData($('.formInputNew')[0]);
				if (menu=='pembelianObat') {
					var idDetailPajak = [];
					var idMstPajak = [];
					var nominalPajak = [];
					var arrOptionPajak = [];
					var noRegistrasi = $('#noRegistrasi').val();
					var noFaktur = $('#noFaktur').val();
					var jatuhTempo = $('#jatuhTempo').val();
					var pbfId = $('#pbf').select2('data');
					var cekPiutang = $('#cekPiutang:checked').length;
					var notaPembayaran = $('input[name=notaPembayaran]')[0].files[0];
					var notaFaktur = $('input[name=notaFaktur]')[0].files[0];
					var persentaseDPP = $('#persentaseDPP').val();
					var materai = $('#materai').val()

					data.append('persentaseDPP',persentaseDPP);
					data.append('cekPiutang[]',cekPiutang);
					data.append('notaPembayaran',notaPembayaran);
					data.append('notaFaktur',notaFaktur);
					data.append('idFaktur[]',idForm);
					data.append('pbfId[]',pbfId[0].id);
					data.append('noRegistrasi[]',noRegistrasi);
					data.append('noFaktur[]',noFaktur);
					data.append('jatuhTempo[]',jatuhTempo);
					data.append('materai',materai)

					$('#tbPajak .rowPajak').each(function(){
						var a = $(this).find('select[name=optionPajak]').val();
						arrOptionPajak.push(a);

						$(this).find('#idDetailPajak').each(function(i,val){
							idDetailPajak.push(val.value);
						});

						$(this).find('#idMstPajak').each(function(i,val){
							idMstPajak.push(val.value);
						});

						$(this).find('#nominalPajak').each(function(i,val){
							nominalPajak.push(val.value);
						});
					});
					arrOptionPajak.forEach(element => {
						data.append('arrOptionPajak[]',element);
					});
					idDetailPajak.forEach(element => {
						data.append('idDetailPajak[]',element);
					});
					idMstPajak.forEach(element => {
						data.append('idMstPajak[]',element);
					});
					nominalPajak.forEach(element => {
						data.append('nominalPajak[]',element);
					});
				}
			@endif

			if(!noFaktur && menu == 'pembelianObat') {
				Swal.fire({
					icon: "warning",
					title: "Maaf",
					text: "Kolom No. Faktur tidak boleh kosong!",
				})
			}else if(!jatuhTempo && menu == 'pembelianObat'){
				Swal.fire({
					icon: "warning",
					title: "Maaf",
					text: "Kolom Jatuh Tempo tidak boleh kosong!"
				})
			}else if (pbf == 'first' && menu == 'pembelianObat') {
				Swal.fire({
					icon: "warning",
					title: "Maaf",
					text: "kolom PBF tidak boleh kosong!"
				})
			}else if(!alamatPBF && menu == 'pembelianObat'){
				Swal.fire({
					icon: "warning",
					title: "Maaf",
					text: "Kolom Alamat PBF tidak boleh kosong!",
				})
			}else if(title == 'Tambah' && gambarFaktur==undefined && menu == 'pembelianObat'){
				Swal.fire({
					icon: "warning",
					title: "Maaf",
					text: "Gambar Faktur harus diUpload!"
				})
			}else if(piutang == 0 && notaBayar==undefined && menu == 'pembelianObat'){
				Swal.fire({
					icon: "warning",
					title: "Bukan Piutang",
					text: "Nota Pembayaran harus diUpload!",
				})
			}else{
				$.ajax({
					url: "{{route('storeStokBarang')}}",
					type: 'POST',
					data: data,
					async: true,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data) {
						if (data.status == 'success') {
							Swal.fire({
								position: 'center',
								icon: 'success',
								title: 'Berhasil',
								text: data.message,
								showConfirmButton: false,
								timer: 1200
							}).then(function(){
								$('.otherPages').empty()
								$('#dataTable').DataTable().ajax.reload()
								$('.main-layer').show()
							})
						} else if (data.status == 'error') {
							Swal.fire('Maaf', data.message, 'warning')
						}
					}
				})
			}
		}else{
			Swal.fire({
				icon: 'error',
				title: 'Whoops...',
				text: 'Data tidak lengkap!'
			});
		}
	});

	// TAMBAH PAJAK UNTUK FAKTUR
	function addFakturPajak(){
		var data = $('#pajakFaktur').select2('data');
		var text = data[0].text;
		var idForTable = data[0].id;
		var id = $('input[name^="idMstPajak"]');
		var cek_data = 0;
		for (var i = 0; i < id.length; i++) {
			if (data[0].id == id[i].value) {
				cek_data += 1;
			}
		}
		if(data[0].id=='first') {
			Swal.fire({
				title: 'Whoops',
				text: "Silahkan pilih pajak!",
				icon: 'warning',
			});
		}else if(cek_data == 0){
			var html = '';
			var kode = generate_id(5);
			html += '<tr class="rowPajak" id="rowPajak-'+kode+'">';
			html += '<td><span>'+text+'</span></td>';
			html += '<td>';
			html += '<div class="row"><div class="col-md-3">'
			html += '<select class="form-control" name="optionPajak" id="optionPajak"><option hidden>--Pilih Tipe--</option><option value="nominal">Nominal</option><option value="persen">Persen</option></select>';
			html += '</div><div class="col-md-9">'
			html += '<input type="text" class="form-control" onkeyup="ubahPajak(`'+kode+'`)" id="nominalPajak" name="nominalPajak[]" placeholder="Masukkan Nominal / Persentase Pajak">';
			html += '</div></div>';
			html += '<input type="hidden" class="form-control" id="idDetailPajak" name="idDetailPajak[]" value="baru">';
			html += '<input type="hidden" class="form-control" id="idMstPajak" name="idMstPajak[]" value="'+idForTable+'">';
			// html += '<input type="hidden" class="form-control" id="namaMstPajak" name="namaMstPajak[]" value="'+text+'">';
			html += '</td>';
			html += '<td class="text-center">';
			html += '<a href="javascript:;" onclick="deletePajak(`'+kode+'`)"><i class="fa fa-trash-alt text-danger"></i></i></a>';
			html += '</td>'
			html += '</tr>';

			$('.tempatPajak').append(html);
			$('#pajakFaktur').val('first').trigger('change');
		}else{
			Swal.fire({
				title: 'Whoops',
				text: "Pajak sudah masuk ke dalam List!",
				icon: 'warning',
			});
		}
	}

	// EDIT DATA
	function editData(id) {
		var obat	  = $('#'+id+' #arr_barang').val()
		var nama_obat = $('#'+id+' #td_obat span').html()
		// if (menu=='stokObat') {
		// 	var supplier = $('#'+id+' #arr_pbf').val()
		// 	var nama_supplier = $('#'+id+' #td_pbf span').html()
		// }
		var no_batch		 = $('#'+id+' #arr_no_batch').val()
		var barCode			 = $('#'+id+' #arr_barCode').val()

		var jumlahBox		 = $('#'+id+' #arr_jumlah_box').val()
		var jumlahPerBox	 = $('#'+id+' #arr_jumlah_perbox').val()

		var harga_beli		 = $('#'+id+' #arr_harga_beli').val()
		var harga_umum		 = $('#'+id+' #arr_harga_umum').val()
		var harga_resep		 = $('#'+id+' #arr_harga_resep').val()
		var harga_dispensing = $('#'+id+' #arr_harga_dispensing').val()
		var hargaDispenBiji	 = $('#'+id+' #arr_harga_dispen_biji').val()

		var diskonBeli		 = $('#'+id+' #arr_diskon_beli').val()
		var expired			 = $('#'+id+' #arr_expired').val()
		var tgl_masuk		 = $('#'+id+' #arr_tgl_masuk').val()
		var minStok			 = $('#'+id+' #arr_min_stok').val()
		var stokAwal		 = $('#'+id+' #arr_stokAwal').val()
		// var laba = $('#'+id+' #arr_laba').val();
		// var pajak = $('#'+id+' #arr_pajak').val();

		$('#barang').val(obat)
		$('#select2-barang-container').attr('title',nama_obat)
		$('#select2-barang-container').html(nama_obat)
		// if (menu=='stokObat') {
		// 	// $('#pbf').val(supplier)
		// 	$('#pbf').val(supplier).trigger('change')
		// 	// $('#select2-pbf-container').attr('title',nama_supplier);
		// 	// $('#select2-pbf-container').html(nama_supplier);
		// 	// $('#harga_beli').val(harga_beli);
		// 	// $('#harga_umum').val(harga_umum);
		// }else{
		// 	// $('#harga_umum').val(formatRupiah(harga_umum,"Rp. "));
		// 	// $('#harga_beli').val(formatRupiah(harga_beli,"Rp. "));
		// }
		$('#harga_beli').val(harga_beli)
		$('#harga_umum').val(harga_umum)
		$('#harga_resep').val(harga_resep)
		$('#harga_dispensing').val(harga_dispensing)
		$('#harga_dispensing_perbiji').val(hargaDispenBiji)
		$('#diskonBeli').val(diskonBeli)


		$('#no_batch').val(no_batch)
		$('#barCode').val(barCode)
		$('#jumlahBox').val(jumlahBox)
		$('#jumlahPerBox').val(jumlahPerBox)
		$('#minStok').val(minStok)
		$('#stokAwal').val(stokAwal)
		$('#expired').val(expired)
		$('#tgl_masuk').val(tgl_masuk)

		// if (laba) {
		// 	$('#claba').prop('checked', true);
		// 	$('#flaba').show();
		// } else {
		// 	$('#claba').prop('checked', false);
		// 	$('#flaba').hide();
		// }

		// if (pajak) {
		// 	$('#cpajak').prop('checked', true)
		// 	$('#fpajak').show();
		// } else {
		// 	$('#cpajak').prop('checked', false)
		// 	$('#fpajak').hide();
		// }

		// if (laba || pajak) {
		// 	$('#harga_umum').attr('readonly', 'readonly');
		// } else {
		// 	$('#harga_umum').removeAttr('readonly', 'readonly');
		// }
		$('#jumlahPerBox').attr('readonly',false);
		$('#harga_beli').attr('readonly',false);
		// $('#laba').val(laba);
		// $('#pajak').val(pajak);

		$('.btn-tambah').hide();
		$('.btn-update').html('<button type="button" class="btn btn-warning float-right" onclick="updateData(`'+id+'`)">Ubah Data</button>');
	}

	function updateData(id) {
		var obat = $('#barang').val()
		var nama_obat = $('#select2-barang-container').html()

		var no_batch = $('#no_batch').val()
		var barCode = $('#barCode').val()
		var jumlahBox = $('#jumlahBox').val()
		var jumlahPerBox = $('#jumlahPerBox').val()
		var supplier = $('#pbf').val()
		// console.log(supplier)
		var nama_supplier = $('#select2-pbf-container').html()

		var harga_beli = $('#harga_beli').val()
		var harga_umum = $('#harga_umum').val()
		var harga_resep = $('#harga_resep').val()
		var harga_dispensing = $('#harga_dispensing').val()
		var hargaDispenBiji	 = $('#harga_dispensing_perbiji').val()
		var diskonBeli = $('#diskonBeli').val()
		var minStok = $('#minStok').val()
		var stokAwal = $('#stokAwal').val()

		var expired = $('#expired').val()
		var tgl_masuk = $('#tgl_masuk').val()
		var laba = $('#laba').val()
		var pajak = $('#pajak').val()

		var fixed = harga_beli.replace(/\D+/g, '')
		var total_harga1 = jumlahBox*fixed
		var totalHarga2 = Math.round(total_harga1-(total_harga1*(diskonBeli/100)))

		if (diskonBeli==""||diskonBeli==null||diskonBeli==0) {
			var diskonBeli = ""
			var potongan = ""
		}else{
			var diskonBeli = diskonBeli
			var potongan = Math.round(total_harga1*(diskonBeli/100))
		}

		if (!obat) {
			Swal.fire('Maaf','Kolom Obat Harus di Pilih','warning')
		} else if (!supplier && menu == 'pembelianObat') {
			Swal.fire('Maaf','Kolom Supplier Harus di Pilih','warning')
		} else if (!jumlahBox) {
			Swal.fire('Maaf','Kolom Jumlah Harus diisi','warning')
		} else if (!harga_beli) {
			Swal.fire('Maaf','Kolom Harga Beli Harus diisi','warning')
		} else if (!harga_umum) {
			Swal.fire('Maaf','Kolom Harga Jual Harus diisi','warning')
		} else if(!stokAwal){
			Swal.fire('Maaf','Kolom Stok Awal Harus diisi','warning');
		} else if(!minStok) {
			Swal.fire('Maaf','Kolom Minimal Stok Harus diisi','warning');
		} else if (!expired) {
			Swal.fire('Maaf','Kolom Expired Harus diisi','warning')
		} else if (!tgl_masuk) {
			Swal.fire('Maaf','Kolom Tanggal Masuk Harus diisi','warning')
		} else {
			//update data table(display)
			$('#'+id+' #td_obat span').html(nama_obat);
			// if (menu=='stokObat') {
			// 	// $('#'+id+' #td_supplier span').html(nama_supplier);
			// 	$('#'+id+' #arr_pbf').val(supplier);
			// 	$('#pbf').val('first').trigger('change');
			// }
			$('#'+id+' #td_total_harga span').html(formatRupiah(total_harga1, "Rp. "))
			$('#'+id+' #td_no_batch span').html(no_batch)
			$('#'+id+' #td_jumlah span').html(jumlahBox+' Box')
			$('#'+id+' #td_harga_beli span').html(harga_beli)
			// $('#'+id+' #td_harga_umum span').html(harga_umum);
			$('#'+id+' #td_diskon_beli span').html((diskonBeli)?diskonBeli+'%':'-')
			$('#'+id+' #td_potongan span').html((potongan)?formatRupiah(potongan, "Rp. "):'-')
			$('#'+id+' #td_total_harga_2 span').html(formatRupiah(totalHarga2,"Rp. "))

			$('#'+id+' #td_expired span').html(expired)
			$('#'+id+' #td_tgl_masuk span').html(tgl_masuk)

			$('#'+id+' #td_laba span').html((laba) ? laba+' %' : '-')
			$('#'+id+' #td_pajak span').html((pajak) ? pajak+' %' : '-')

			//update data table(value)
			$('#'+id+' #arr_barang').val(obat)
			$('#'+id+' #arr_no_batch').val(no_batch)
			$('#'+id+' #arr_barCode').val(barCode)
			$('#'+id+' #arr_jumlah_box').val(jumlahBox)
			$('#'+id+' #arr_jumlah_perbox').val(jumlahPerBox)

			$('#'+id+' #arr_harga_beli').val(harga_beli)
			$('#'+id+' #arr_harga_umum').val(harga_umum)
			$('#'+id+' #arr_harga_resep').val(harga_resep)
			$('#'+id+' #arr_harga_dispensing').val(harga_dispensing)
			$('#'+id+' #arr_harga_dispen_biji').val(hargaDispenBiji)
			$('#'+id+' #arr_diskon_beli').val(diskonBeli)
			$('#'+id+' #arr_potongan').val(potongan)
			$('#'+id+' #arrTotalHarga2').val(totalHarga2)
			$('#'+id+' #arr_min_stok').val(minStok)
			$('#'+id+' #arr_stokAwal').val(stokAwal)

			$('#'+id+' #arr_expired').val(expired)
			$('#'+id+' #arr_tgl_masuk').val(tgl_masuk)

			$('#'+id+' #arr_laba').val(laba)
			$('#'+id+' #arr_pajak').val(pajak)

			if (menu=='pembelianObat') {
				$('#'+id+' #arr_total_harga').val(total_harga1);
				var harga = $(' input[name^="totalHarga2"]');
				var total_harga = 0;
				for (var i = 0; i < harga.length; i++) {
					total_harga += parseInt(harga[i].value);
				}
				$('#fixHarga').val(total_harga);
				$('#persentaseDPP').val(total_harga)
				$('#total_semua_harga').html(formatRupiah(total_harga, "Rp. "));
			}
			// if(menu=='pembelianObat'){
				// $('#'+id+' #arr_no_faktur').val(noFaktur);
			// 	$('#'+id+' #arr_alamat_pbf').val(alamatPBF);
			// 	$('#'+id+' #arr_jatuh_tempo').val(jatuhTempo);
			// 	$('#alamatPBF').val('').removeAttr('readonly','readonly');
			// 	$('#jatuhTempo').val(getDateToday());
			// }

			//clear form
			$('#barang').val('first').trigger('change')

			$('#no_batch').val('')
			$('#barCode').val('')

			$('#jumlahBox').val('')
			$('#jumlahPerBox').val('')

			$('#harga_beli').val('')
			$('#harga_umum').val('')
			$('#harga_resep').val('')
			$('#harga_dispensing').val('')
			$('#harga_dispensing_perbiji').val('')
			$('#jumlahPerBox').attr('readonly', 'readonly')
			$('#jumlahPerBox').attr('readonly', 'readonly')
			$('#harga_beli').attr('readonly', 'readonly')

			$('#diskonBeli').val('')
			$('#minStok').val('')
			$('#stokAwal').val('')
			// $('#harga_umum').removeAttr('readonly', 'readonly');

			$('#expired').val('')
			$('#tgl_masuk').val(getDateToday())

			// $('#claba').prop('checked', false);
			// $('#cpajak').prop('checked', false)

			// $('#flaba').hide();
			// $('#fpajak').hide();

			// $('#laba').val('');
			// $('#pajak').val('');

			$('.btn-tambah').show()
			$('.btn-update').html('')
		}
	}

	function deletePajak(id){
		Swal.fire({
			title: 'Anda yakin?',
			text: " Ingin menghapus data ini!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Saya yakin!',
			cancelButtonText: 'Batal!',
			// closeOnConfirm: true,
		}).then((result) => {
			if (result.value == true) {
				// console.log(id);
				$('#rowPajak-' +id).remove();
			}
		});
	}

	// HAPUS DATA OBAT
	function deleteData(id) {
		Swal.fire({
			title: 'Anda yakin?',
			text: " Ingin menghapus data ini!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Saya yakin!',
			cancelButtonText: 'Batal!',
			// closeOnConfirm: true,
		}).then((result) => {
			if (result.value == true) {
				$('#'+id).remove();
				total_harga(id);
			}
		});
	}

	// BATAL PAJAK UNTUK FAKTUR
	function batalFakturPajak(){
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: " Ingin membatalkan pajak!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Saya yakin!',
			cancelButtonText: 'Batal!',
			// closeOnConfirm: true,
		}).then((result) => {
			if (result.value == true) {
				$('#tbPajak tbody').empty();
				$('#persentaseDPP').val('');
				$('#showPajak').hide();
				$('#pajakFaktur').val('').trigger('change');
				$('#formFakturPajak').hide();
				$('#cekFakturPajak').prop('checked',false);
				$('#checkFaktur').show();
			}
		});
	}

	function batalPiutang(){
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: " Ingin membatalkan status piutang!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Saya yakin!',
			cancelButtonText: 'Batal!',
		}).then((result) => {
			if (result.value == true) {
				$('#formPiutang').hide();
				$('#cekPiutang').prop('checked',false);
				$('#checkPiutang').show();
			}
		});
	}

	function getDateToday(){
		var fullDate = new Date();
		var date = fullDate.getDate();
		var month = fullDate.getMonth()+1;
		var year = fullDate.getFullYear();
		var dateToday = year+'-'+(month<10?'0':'')+month+'-'+(date<10?'0':'')+date;
		return dateToday;
	}

	function formatRupiah(angka, prefix) {
		var number_string = angka.toString().replace(/[^,\d]/g, "");
		split = number_string.split(",");
		sisa = split[0].length % 3;
		rupiah = split[0].substr(0, sisa);
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			separator = sisa ? "." : "";
			rupiah += separator + ribuan.join(".");
		}

		rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
		return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
	}

	// PROSES HITUNG UNTUK HARGA JUAL
	function hitungHargaJual(arrOptionPajak='',nominalPajak='') {
		var mstUmum = $('#mstUmum').val();
		var mstResep = $('#mstResep').val();
		// var mstResepNominal = parseInt($('#mstResepNominal').val());
		var mstDispensingPerBox = $('#mstDispensingPerBox').val();
		var mstDispensingPerBiji = $('#mstDispensingPerBiji').val();
		var jumlahBox = $('#jumlahBox').val();
		var jumlahPerBox = $('#jumlahPerBox').val();

		var diskonBeli = $('#diskonBeli').val();

		var harga_beli = parseInt($('#harga_beli').val().replace(/\D/g, ''));
		var harga_umum = 0;
		var hargaResep = 0;
		var hargaDispensing = 0;
		var hargaDispensingBox = 0;
		var totalBiji = parseInt(harga_beli/jumlahPerBox);
		var wadahPajak = 0;
		var hasil = 0;
		var untukBox = 0

		@if ($menu=='pembelianObat')
			var materai = parseInt($('#materai').val().replace(/\D/g, ''))
			if(materai){
				materai = materai
			}else{
				materai = 0;
			}
			
			if(diskonBeli){
				hasil = jumlahBox*(harga_beli-(harga_beli*diskonBeli/100));
			}else{
				hasil = jumlahBox*harga_beli;
			}
			$.each(arrOptionPajak,function(i,val){
				if(val=='persen'){
					wadahPajak += hasil*(nominalPajak[i]/100);
				}else{
					wadahPajak += nominalPajak[i];
				}
			})
			var hasil2 = hasil+wadahPajak+materai;
			untukBox = (hasil2/jumlahBox);
		@else
			if(diskonBeli){
				hasil = jumlahBox*(harga_beli-(harga_beli*diskonBeli/100));
			}else{
				hasil = jumlahBox*harga_beli;
			}
			// $.each(arrOptionPajak,function(i,val){
			// 	if(val=='persen'){
			// 		wadahPajak += hasil*(nominalPajak[i]/100);
			// 	}else{
			// 		wadahPajak += nominalPajak[i];
			// 	}
			// })
			// var hasil2 = hasil+wadahPajak+materai;
			untukBox = (hasil/jumlahBox);
		@endif
		var untukBiji = untukBox/jumlahPerBox;

		harga_umum = parseInt((untukBiji+(untukBiji*(mstUmum/100) )).toFixed())
		// hargaResep = parseInt((mstResepNominal+untukBiji)+(untukBiji*(mstResep/100) ));
		hargaResep = parseInt((harga_umum+(harga_umum*(mstResep/100) )).toFixed());
		hargaDispensingBox = parseInt(untukBox+(untukBox*(mstDispensingPerBox/100) ));
		hargaDispensing = parseInt((untukBiji+(untukBiji*(mstDispensingPerBiji/100) )).toFixed());
		
		$('#harga_umum').val(formatRupiah(harga_umum, "Rp. "));
		$('#harga_resep').val(formatRupiah(hargaResep, "Rp. "));
		$('#harga_dispensing').val(formatRupiah(hargaDispensingBox, "Rp. "));
		$('#harga_dispensing_perbiji').val(formatRupiah(hargaDispensing,"Rp. "));
		// console.log(arrOptionPajak,nominalPajak)
	}

	function formatNumber(angka){
		return angka.toString().replace(/[^,\d]/g, "");
	}

	// KEYUP
	function ubahFormatNumber(v) {
		let empty1 = false;
		let empty2 = false;

		$('#jumlahBox').each(function(){
			empty1 = $(this).val().length == 0;
		});

		$('#jumlahPerBox').each(function(){
			empty2 = $(this).val().length == 0;
		});

		if(empty1){
			$('#jumlahPerBox').attr('readonly', 'readonly');
		}else{
			$('#jumlahPerBox').attr('readonly', false);
		}
		if(empty2){
			$('#harga_beli').attr('readonly', 'readonly');
		}
		else{
			$('#harga_beli').attr('readonly', false);
			loopPajak();
		}
			
		$(v).val(formatNumber(v.value));
	}

	// AMBIL DATA DARI PAJAK BARU DAN SIMPAN KE ARRAY
	function loopPajak(){
		var nominalPajak1 = [];
		var arrOptionPajak1 = [];
		$('#tbPajak .rowPajak').each(function(){
			var a = $(this).find('select[name=optionPajak]').val();
			arrOptionPajak1.push(a);

			$(this).find('#nominalPajak').each(function(i,val){
				nominalPajak1.push(parseInt(val.value));
			});
		});
		if(arrOptionPajak1.length != 0 || nominalPajak1 != 0){
			hitungHargaJual(arrOptionPajak1,nominalPajak1); // SEMUA HITUNG HARGA JUAL DENGAN PAJAK
		}else{
			hitungHargaJual(); // HITUNG SEMUA HARGA JUAL TANPA PAJAK
		}
	}

	function ubahMaterai(val){
		var materai = $('#materai').val();
		$(val).val(formatRupiah(val.value, 'Rp. '));
		loopPajak();
	}

	function ubahDiskon(v){
		var cekDiskon = $('#diskonBeli').val();
		if(cekDiskon >= 0 && cekDiskon <=100){
		}else{
			Swal.fire({
				title: 'Whoops',
				text: 'Max Diskon 100%',
				icon: 'warning',
				timer: 1200,
				showConfirmButton: false,
			});
			$('#diskonBeli').val('')
		}
		loopPajak();
	}

	function ubahPajak(kode){
		loopPajak();
	}

	function ubahFormat(v) {
		loopPajak();
		$(v).val(formatRupiah(v.value, "Rp. "));
	}

	function total_harga(id) {
		var harga = $(' input[name^="total_harga"]');
		var total_harga = 0;
		for (var i = 0; i < harga.length; i++) {
			total_harga += parseInt(harga[i].value);
		}
		$('#fixHarga').val(total_harga);
		$('#persentaseDPP').val(total_harga)
		$('#total_semua_harga').html(formatRupiah(total_harga, "Rp. "));
	}

	function generate_id(n) {
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		for (var i = 0; i < n; i++) {
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return text; 
	}
</script>