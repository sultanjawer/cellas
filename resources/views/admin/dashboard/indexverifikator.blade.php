@extends('layouts.admin')
@section('content')
{{-- @include('partials.breadcrumb') --}}
{{-- @include('partials.subheader') --}}
@can('dashboard_access')
<!-- Page Content -->
<div class="subheader">
	<h1 class="subheader-title">
		<i class="subheader-icon {{ ($heading_class ?? '') }}"></i><span class="fw-700 mr-2 ml-2">{{  ($page_heading ?? '') }}</span><span class="fw-300">Realisasi & Verifikasi</span>
	</h1>
	<div class="subheader-block d-lg-flex align-items-center  d-print-none d-block">
		<div class="d-inline-flex flex-column justify-content-center ">
			<div class="form-group row">
				<label for="periodetahun" class="col-sm-4 col-form-label text-right">Tahun</label>
				<div class="col-sm-8">
					<input id="periodetahun" name="periode" type="text" class="form-control custom-select yearpicker" placeholder="{{$currentYear}}" aria-label="Pilih tahun" aria-describedby="basic-addon2">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-danger-300">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah antrian pengajuan verifikasi">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="ajucount">{{$ajucount ? $ajucount:0}}</span>
						<small class="m-0 l-h-n">Pengajuan</small> 
					</h3>
				</div>
			</div>
			<i class="fal fa-landmark position-absolute pos-right pos-bottom opacity-25 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-warning-400">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah antrian dalam proses.">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="proccesscount">{{$proccesscount ? $proccesscount : 0}}</span>
						<small class="m-0 l-h-n">Diproses</small>
					</h3>
				</div>
			</div>
			<i class="fal fa-balance-scale position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-info-300">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah pengajuan yang telah selesai RIPH periode ini.">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="verifiedcount">{{$verifiedcount ? $verifiedcount : 0}}</span>
						<small class="m-0 l-h-n">Selesai</small>
					</h3>
				</div>
			</div>
			<i class="fal fa-seedling position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-success-500">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah SKL diterbitkan untuk RIPH periode ini.">
						<span id="lunascount">{{$lunascount ? $lunascount : 0}}</span>
						<small class="m-0 l-h-n">Lunas</small>
					</h3>
				</div>
			</div>
			<i class="fal fa-dolly position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
</div>
					
<div class="row">
	<div class="col-md-12">
		<div class="panel" id="panel-2">
			<div class="panel-hdr">
				<h2>
					<i class="subheader-icon fal fa-ballot-check mr-1"></i>Daftar Verifikasi<span class="fw-300"><i> Dalam Proses</i></span>
				</h2>
				<div class="panel-toolbar">
					{{-- @include('layouts.globaltoolbar') --}}
				</div>
			</div>
			<div class="panel-container show">
				<div class="panel-content">
					<table class="table table-bordered table-hover table-sm w-100" id="verifprogress">
						<thead>
							<th>Nama Perusahaan</th>
							<th>Nomor Pengajuan</th>
							<th>Nomor RIPH</th>
							<th>Pengajuan</th>
							<th>Tahap 1</th>
							<th>Tahap 2</th>
							<th>Tahap 3</th>
						</thead>
						<tbody>
							@foreach ($allPengajuan as $pengajuan)
								<tr>
									<td>{{$pengajuan->commitment->datauser->company_name}}</td>
									<td>{{$pengajuan->no_pengajuan}}</td>
									<td>{{$pengajuan->no_ijin}}</td>
									<td class="text-center">
										@if ($pengajuan->status)
											<span class="btn btn-xs btn-icon btn-info"><i class="fa fa-check-circle"></i></span>
										@endif
									</td>
									<td class="text-center">
										@if ($pengajuan->onlinestatus === '2')
											<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-check-circle"></i></span>
										@elseif ($pengajuan->onlinestatus === '3')
											<span class="btn btn-xs btn-icon btn-danger"><i class="fa fa-ban"></i></span>
										@endif
									</td>
									<td class="text-center">
										@if ($pengajuan->onlinestatus === '4')
											<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-check-circle"></i></span>
										@elseif ($pengajuan->onlinestatus === '5')
											<span class="btn btn-xs btn-icon btn-danger"><i class="fa fa-ban"></i></span>
										@endif
									</td>
									<td class="text-center">
										@if ($pengajuan->status === '6')
											<span class="btn btn-xs btn-icon btn-info"><i class="fa fa-file-signature"></i></span>
										@elseif ($pengajuan->status === '7')
											<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-award"></i></span>
										@elseif ($pengajuan->status === '8')
											<span class="btn btn-xs btn-icon btn-danger"><i class="fa fa-ban"></i></span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table><hr>
					<span class="help-block mt-2">
						<label for="" class="form-label">Keterangan:</label>
						<div class="row d-flex align-items-top">
							<div class="col-md-4 col-sm-6">
								<ul>
									<li>Tahap 1: Pemeriksaan Data</li>
									<li>Tahap 2: Pemeriksaan Lapangan</li>
									<li>Tahap 3: Rekomendasi dan Penerbitan SKL</li>
								</ul>
							</div>
							<div class="col-md-5 col-sm-6">
								<ul>
									<li>
										<span class="btn btn-icon btn-xs btn-success">
											<i class="fal fa-check-circle mr-1"></i>
										</span> : Pemeriksaan selesai dan dinyatakan sesuai.
									</li>
									<li>
										<span class="btn btn-icon btn-xs btn-danger">
											<i class="fal fa-ban mr-1"></i>
										</span> : Pemeriksaan selesai, data dinyatakan <span class="text-danger">TIDAK SESUAI</span>.
									</li>
									<li>
										<span class="btn btn-icon btn-xs btn-info">
											<i class="fal fa-file-signature mr-1"></i>
										</span> : Rekomendasi penerbitan SKL.</span>.
									</li>
									<li>
										<span class="btn btn-icon btn-xs btn-success">
											<i class="fal fa-award mr-1"></i>
										</span> : Komitmen dinyatakan <span class="fw-700">LUNAS dan SKL diterbitkan.</span></span>.
									</li>
								</ul>
							</div>
						</div>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
	<!-- End Page Content -->

@endcan
@endsection
@section('scripts')
@parent
	<script>
		$(document).ready(function() {
			//initialize datatable verifprogress
			$('#verifprogress').dataTable({
				responsive: true,
				lengthChange: false,
				dom:
				"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'<'select'>>>" +
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
				buttons: [
					{
						extend: 'pdfHtml5',
						text: '<i class="fa fa-file-pdf"></i>',
						titleAttr: 'Generate PDF',
						className: 'btn-outline-danger btn-sm btn-icon mr-1'
					},
					{
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel"></i>',
						titleAttr: 'Generate Excel',
						className: 'btn-outline-success btn-sm btn-icon mr-1'
					},
					{
						extend: 'csvHtml5',
						text: '<i class="fal fa-file-csv"></i>',
						titleAttr: 'Generate CSV',
						className: 'btn-outline-primary btn-sm btn-icon mr-1'
					},
					{
						extend: 'copyHtml5',
						text: '<i class="fa fa-copy"></i>',
						titleAttr: 'Copy to clipboard',
						className: 'btn-outline-primary btn-sm btn-icon mr-1'
					},
					{
						extend: 'print',
						text: '<i class="fa fa-print"></i>',
						titleAttr: 'Print Table',
						className: 'btn-outline-primary btn-sm btn-icon mr-1'
					}
				]
			});

			// Create the "Status" select element and add the options
			// var selectStatus = $('<select>')
			// 	.attr('id', 'selectverifprogressStatus')
			// 	.addClass('custom-select custom-select-sm col-3 mr-2')
			// 	.on('change', function() {
			// 	var status = $(this).val();
			// 	table.column(6).search(status).draw();
			// 	});

			// $('<option>').val('').text('Semua Status').appendTo(selectStatus);
			// $('<option>').val('1').text('Sudah Terbit').appendTo(selectStatus);
			// $('<option>').val('2').text('Belum Terbit').appendTo(selectStatus);

			// // Add the select elements before the first datatable button in the second table
			// $('#verifprogress_wrapper .dt-buttons').before(selectStatus);
		});
	</script>
	<script>
		$(document).ready(function() {
			// Initialize the year picker
			$('.yearpicker').datepicker({
				format: 'yyyy',
				viewMode: 'years',
				minViewMode: 'years',
				autoclose: true
			});
			$('#periodetahun').on('change', function() {
				var periodetahun = $(this).val();
				var url = '{{ route("admin.verifikatormonitoringDataByYear", ":periodetahun") }}';
				url = url.replace(':periodetahun', periodetahun);

				$.get(url, function (data) {
					$('#ajucount').text(data.ajucount);
					$('#proccesscount').text(data.proccesscount);
					$('#verifiedcount').text(data.verifiedcount);
					$('#recomendationcount').text(data.recomendationcount);
					$('#lunascount').text(data.lunascount);

					// // Build table for pengajuan
					var tableBody = $("#verifprogress tbody");
					tableBody.empty(); // Clear previous table data
					$.each(data.verifikasis, function (index, verifikasi) {
						console.log('Verifikasi:', verifikasi);
						var row = $("<tr></tr>");
						var namaPerusahaan = $("<td></td>").text(verifikasi.commitment.datauser.company_name);
						var nomorPengajuan = $("<td></td>").text(verifikasi.no_pengajuan);
						var nomorRIPH = $("<td></td>").text(verifikasi.no_ijin);
						
						var ajuCell = $('<td class="text-center"></td>').html(function() {
							if (verifikasi.status) {
								return '<span class="btn btn-xs btn-icon btn-info"><i class="fa fa-check-circle"></i></span>';
							}
						});

						var dataCell = $('<td class="text-center"></td>').html(function() {
							if (verifikasi.status === '2') {
								return '<span class="badge badge-xs badge-success"><i class="fal fa-check-circle mr-1"></i>Selesai</span>';
							} else if (verifikasi.status === '3') {
								return '<span class="badge badge-xs badge-danger"><i class="fal fa-ban mr-1"></i>Tidak Sesuai</span>';
							}
						});

						var lapanganCell = $('<td class="text-center"></td>').html(function() {
							if (verifikasi.status === '2' && !verifikasi.onfarmstatus) {
								return '<span class="badge badge-xs badge-warning"><i class="fal fa-exclamation-circle mr-1"></i>Belum diperiksa</span>';
							} else if (verifikasi.status === '4') {
								return '<span class="badge badge-xs badge-success"><i class="fal fa-check-circle mr-1"></i>Selesai</span>';
							} else if (verifikasi.status === '5') {
								return '<span class="badge badge-xs badge-danger"><i class="fal fa-ban mr-1"></i>Tidak Sesuai</span>';
							}
						});

						var lunasCell = $('<td></td>').html(function() {
							if (verifikasi.status === '6') {
								return '<span class="badge badge-xs badge-primary"><i class="fal fa-file-signature mr-1"></i>Rekomendasi</span>';
							} else if (verifikasi.status === '7') {
								return '<span class="badge badge-xs badge-success"><i class="fal fa-award mr-1"></i>Lunas</span> <span hidden>7</span>';
							}
						});

						row.append(namaPerusahaan, nomorPengajuan, nomorRIPH, ajuCell, dataCell, lapanganCell, lunasCell);
						tableBody.append(row);
					});
				
					function formatNumber(number) {
						return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					}

					function formatdecimals(number) {
						var parts = number.toFixed(2).toString().split(".");
						var formattedNumber = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						if (parts.length > 1) {
							formattedNumber += "," + parts[1];
						} else {
							formattedNumber += ",00"; // Add two decimal places if there are none
						}
						return formattedNumber;
					}
				});
			});
		});
	</script>
@endsection