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
	<div class="subheader-block d-lg-flex align-items-center  d-print-none">
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
			<div class="card-body bg-primary-400">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah Perusahaan Pemegang RIPH">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="jumlah_importir">{{$jumlah_importir}}</span>
						<small class="m-0 l-h-n">Perusahaan / <span id="company" class="mr-1">{{$company}}</span>Terdaftar</small> 
					</h3>
				</div>
			</div>
			<i class="fal fa-landmark position-absolute pos-right pos-bottom opacity-25 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-danger-300">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah volume import pada periode ini.">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="v_pengajuan_import">{{ number_format($v_pengajuan_import, 0, ',', '.') }}</span>
						<small class="m-0 l-h-n">Volume Import (ton)</small>
					</h3>
				</div>
			</div>
			<i class="fal fa-balance-scale position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-success-500">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah wajib tanam pada periode ini.">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="v_beban_tanam">{{ number_format($v_beban_tanam, 2, ',', '.') }}</span>
						<small class="m-0 l-h-n">Kewajiban Tanam (ha)</small>
					</h3>
				</div>
			</div>
			<i class="fal fa-seedling position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel rounded overflow-hidden position-relative text-white mb-g">
			<div class="card-body bg-warning-500">
				<div class="">
					<h3 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah wajib tanam pada periode ini.">
						<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
						<span id="v_beban_produksi">{{ number_format($v_beban_produksi, 2, ',', '.') }}</span>
						<small class="m-0 l-h-n">Kewajiban Produksi (ton)</small>
					</h3>
				</div>
			</div>
			<i class="fal fa-dolly position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel" id="panel-2">
			<div class="panel-hdr">
				<h2>
					<i class="subheader-icon fal fa-seedling mr-1"></i>Wajib Tanam
				</h2>
			</div>
			<div class="panel-container show">
				<div class="panel-content">
					<!-- Row -->
					<div class="row mb-3 align-items-center">
						<div class="col-lg-5 col-sm-6 align-self-center text-center">
							<div class="c-chart-wrapper">
								<div 
									id = "naschartTanam"
									class="js-easy-pie-chart color-success-300 position-relative d-inline-flex align-items-center justify-content-center"
									data-percent="{{ number_format($prosenTanam, 2, ',', '.') }}"
									data-piesize="145"
									data-linewidth="10"
									data-linecap="butt"
									data-scalelength="7"
									data-toggle="tooltip"
									title data-original-title="{{ number_format($prosenTanam, 2, ',', '.') }}% dari total kewajiban."
									data-placement="bottom">
									<div class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-xl">
										<span class="fs-xxl fw-500 text-dark">
											<span name="prosenTanam" id="prosenTanam">
												{{ number_format($prosenTanam, 2, ',', '.') }}
											</span>
											<sup>%</sup>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-7 col-sm-6">
							<div class="shadow-1 p-2 bg-success-600 rounded overflow-hidden position-relative text-white mb-2">
								<div class="card-body">
									<h4 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah Perusahaan Pemegang RIPH">
										<!-- nilai ini diperoleh dari jumlah seluruh pengajuan yang belum diverifikasi. where status = 1 (user) -->
										<span id="total_luastanam">{{ number_format($total_luastanam, 2, ',', '.') }}</span> ha
										<small class="m-0 l-h-n">Total realisasi luas tanam.</small> 
									</h4>
								</div>
								<i class="fal fa-seedling position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel" id="panel-2">
			<div class="panel-hdr">
				<h2>
					<i class="subheader-icon fal fa-dolly mr-1"></i>Wajib Produksi
				</h2>
			</div>
			<div class="panel-container show">
				<div class="panel-content">
					<!-- Row -->
					<div class="row mb-3 align-items-center">
						<div class="col-lg-5 col-sm-6 align-self-center text-center">
							<div class="c-chart-wrapper">
								<div
									id = "naschartProduksi"
									data-percent="{{ number_format($prosenProduksi, 2, ',', '.') }}"
									data-piesize="145"
									data-linewidth="10"
									data-linecap="butt"
									data-scalelength="7"
									data-toggle="tooltip"
									title data-original-title="{{ number_format($prosenProduksi, 2, ',', '.') }}% dari total kewajiban."
									data-placement="bottom"
									class="js-easy-pie-chart color-warning-500 position-relative d-inline-flex align-items-center justify-content-center">
									<div class="d-flex flex-column align-items-center justify-content-center position-absolute pos-left pos-right pos-top pos-bottom fw-300 fs-xl">
										<span class="fs-xxl fw-500 text-dark">
											<span name="prosenProduksi" id="prosenProduksi">
												{{ number_format($prosenProduksi, 2, ',', '.') }}
											</span>
											<sup>%</sup>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-7 col-sm-6">
							<div class="shadow-1 p-2 bg-warning-600 rounded overflow-hidden position-relative text-white mb-2">
								<div class="card-body">
									<h4 class="display-5 d-block l-h-n m-0 fw-500 text-white" data-toggle="tooltip" title data-original-title="Jumlah Perusahaan Pemegang RIPH">
										<span id="total_volume">{{ number_format($total_volume, 2, ',', '.') }}</span> ha
										<small class="m-0 l-h-n">Total realisasi produksi.</small> 
									</h4>
								</div>
								<i class="fal fa-dolly position-absolute pos-right pos-bottom opacity-40 mb-n1 mr-n1" style="font-size:4rem"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="panel" id="panel-2">
			<div class="panel-hdr">
				<h2>
					<i class="subheader-icon fal fa-ballot-check mr-1"></i>Daftar <span class="fw-300"><i>Verifikasi</i></span>
				</h2>
				<div class="panel-toolbar">
					@include('layouts.globaltoolbar')
				</div>
			</div>
			<div class="panel-container show">
				<div class="panel-content">
					<div class="row d-flex">
						<div class="col-md-3">
							<div class="shadow-1 p-2 bg-primary-100 rounded overflow-hidden position-relative text-white mb-2">
								{{-- where status != empty, seluruh data pengajuan yang memiliki status (1 s.d 7) --}}
								<div data-toggle="tooltip" title data-original-title="Jumlah Pengajuan Verifikasi Wajib Tanam-Produksi">
									<div class="d-flex">
										<h5 class="d-block l-h-n m-0 fw-500 mr-1" id="ajucount">{{$ajucount ? $ajucount : 0}}</h5>
										<span>RIPH</span>
									</div>
									<span class="small">Pengajuan</span>
								</div>
								<i class="fal fa-download position-absolute pos-right pos-bottom opacity-30 mb-n1 mr-n1" style="font-size:3rem"></i>
							</div>
						</div>
						<div class="col-md-3">
							{{-- where onlinestatus = 1 and onfarmstatus = empty, seluruh pengajuan yang memiliki onlinestatus dengan nilai 1 (sesuai) namun tidak memiliki tidak memiliki onfarmstatus (belum diperiksa) --}}
							<div class="shadow-1 p-2 bg-primary-200 rounded overflow-hidden position-relative text-white mb-2">
								<div data-toggle="tooltip" title data-original-title="Jumlah RIPH yang sedang diverifikasi">
									<div class="d-flex">
										<h5 class="d-block l-h-n m-0 fw-500 mr-1" id="proccesscount">{{$proccesscount ? $proccesscount : 0}}</h5>
										<span>RIPH</span>
									</div>
									<span class="small">Dalam Proses</span>
								</div>
								<i class="fal fa-hourglass position-absolute pos-right pos-bottom opacity-30 mb-n1 mr-n1" style="font-size:3rem"></i>
							</div>
						</div>
						<div class="col-md-3">
							{{-- where onfarmstatus != empty, ini berarti data telah diperiksa dan survey lokasi telah dilakukan. di tabel ini, status sesuai atau tidak diabaikan. --}}
							<div class="shadow-1 p-2 bg-primary-300 rounded overflow-hidden position-relative text-white mb-2">
								<div data-toggle="tooltip" title data-original-title="Jumlah RIPH yang telah diverifikasi">
									<div class="d-flex">
										<h5 class="d-block l-h-n m-0 fw-500 mr-1" id="verifiedcount">{{$verifiedcount ? $verifiedcount : 0}}</h5>
										<span>RIPH</span>
									</div>
									<span class="small">Terverifikasi</span>
								</div>
								<i class="fal fa-check-circle position-absolute pos-right pos-bottom opacity-30 mb-n1 mr-n1" style="font-size:3rem"></i>
							</div>
						</div>
						<div class="col-md-3">
							<div class="shadow-1 p-2 bg-primary-500 rounded overflow-hidden position-relative text-white mb-2">
								{{-- where status = '7' --}}
								<div data-toggle="tooltip" title data-original-title="Jumlah RIPH Lunas Wajib Tanam-Produksi">
									<div class="d-flex justify-content-between">
										<div class="d-flex">
											<h5 class="d-block l-h-n m-0 fw-500 mr-1" id="lunascount">{{$lunascount ? $lunascount : 0}}</h5>
											<span>RIPH</span>
										</div>
										<span class="fw-700">LUNAS</span>
									</div>

									<div class="d-flex">
										<div class="d-flex">
											<h5 class="d-block l-h-n m-0 fw-500 mr-1" id="lunasLuas">0</h5>
											<span>ha  |  </span>
										</div>
										<div class="d-flex ml-1">
											<h5 class="d-block l-h-n m-0 fw-500 mr-1" id="lunasVolume">0</h5>
											<span>ton</span>
										</div>
									</div>
								</div>
								<i class="fal fa-award position-absolute pos-right pos-bottom opacity-30 mb-n1 mr-n1" style="font-size:3rem"></i>
							</div>
						</div>
					</div><hr>
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
										@if ($pengajuan->onfarmstatus === '4')
											<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-check-circle"></i></span>
										@elseif ($pengajuan->onfarmstatus === '5')
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
					</table>
					<hr>
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
							<div class="col-md-4 col-sm-6">
								<ul>
									<li class="mb-1">
										<span class="btn btn-icon btn-xs btn-success mr-1">
											<i class="fa fa-check-circle"></i>
										</span> : Pemeriksaan selesai dan dinyatakan sesuai.
									</li>
									<li>
										<span class="btn btn-icon btn-xs btn-danger mr-1">
											<i class="fa fa-ban"></i>
										</span> : Pemeriksaan selesai, data dinyatakan <span class="text-danger">TIDAK SESUAI</span>.
									</li>
								</ul>
							</div>
							<div class="col-md-4 col-sm-6">
								<ul>
									<li class="mb-1">
										<span class="btn btn-icon btn-xs btn-info mr-1">
											<i class="fa fa-file-signature"></i>
										</span> : Rekomendasi penerbitan SKL.</span>.
									</li>
									<li>
										<span class="btn btn-icon btn-xs btn-success mr-1">
											<i class="fa fa-award"></i>
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
			var selectStatus = $('<select>')
				.attr('id', 'selectverifprogressStatus')
				.addClass('custom-select custom-select-sm col-3 mr-2')
				.on('change', function() {
				var status = $(this).val();
				table.column(6).search(status).draw();
				});

			$('<option>').val('').text('Semua Status').appendTo(selectStatus);
			$('<option>').val('1').text('Sudah Terbit').appendTo(selectStatus);
			$('<option>').val('2').text('Belum Terbit').appendTo(selectStatus);

			// Add the select elements before the first datatable button in the second table
			$('#verifprogress_wrapper .dt-buttons').before(selectStatus);
		});
	</script>
	<script>
		$(document).ready(function() {
			$('.yearpicker').datepicker({
				format: 'yyyy',
				viewMode: 'years',
				minViewMode: 'years',
				autoclose: true
			});
			$('#periodetahun').on('change', function() {
				var periodetahun = $(this).val();
				var url = '{{ route("admin.monitoringDataByYear", ":periodetahun") }}';
				url = url.replace(':periodetahun', periodetahun);

				$.get(url, function (data) {
					$('#jumlah_importir').text(data.jumlah_importir);
					$('#v_pengajuan_import').text(formatNumber(data.v_pengajuan_import));
					$('#v_beban_tanam').text(formatNumber(data.v_beban_tanam));
					$('#v_beban_produksi').text(formatNumber(data.v_beban_produksi));
					$('#company').text(data.company);
					$('#volume_import').text(formatdecimals(data.volume_import));
					$('#total_luastanam').text(formatdecimals(data.total_luastanam));
					$('#total_volume').text(formatdecimals(data.total_volume));
					$('#prosenTanam').text(formatdecimals(data.prosenTanam));
					$('#prosenProduksi').text(formatdecimals(data.prosenProduksi));
					$('#ajucount').text(data.ajucount);
					$('#proccesscount').text(data.proccesscount);
					$('#verifiedcount').text(data.verifiedcount);
					$('#recomendationcount').text(data.recomendationcount);
					$('#lunascount').text(data.lunascount);
					$('#lunasLuas').text(formatdecimals(data.lunasLuas));
					$('#lunasVolume').text(formatdecimals(data.lunasVolume));
					
					var prosentanam = (data.prosenTanam);
					$('#naschartTanam').attr('data-percent', prosentanam);
					$('#naschartTanam').attr('data-original-title', prosentanam  + '% dari kewajiban');
					var $chartTanam = $('#naschartTanam');
					$chartTanam.data('easyPieChart').update(prosentanam);

					var prosenproduksi = (data.prosenProduksi);
					$('#naschartProduksi').attr('data-percent', prosenproduksi);
					$('#naschartProduksi').attr('data-original-title', prosenproduksi  + '% dari total kewajiban');
					var $chartProduksi = $('#naschartProduksi');
					$chartProduksi.data('easyPieChart').update(prosenproduksi);

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
							if (verifikasi.onlinestatus === '2') {
								return '<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-check-circle"></i></span>';
							} else if (verifikasi.onlinestatus === '3') {
								return '<span class="btn btn-xs btn-danger"><i class="fa fa-ban"></i></span>';
							}
						});

						var lapanganCell = $('<td class="text-center"></td>').html(function() {
							if (verifikasi.status === '2' && !verifikasi.onfarmstatus) {
								return '<span class="btn btn-xs btn-icon btn-warning"><i class="fa fa-exclamation-circle"></i></span>';
							} else if (verifikasi.onfarmstatus === '4') {
								return '<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-check-circle"></i></span>';
							} else if (verifikasi.onfarmstatus === '5') {
								return '<span class="btn btn-xs btn-icon btn-btn"><i class="fa fa-ban"></i></span>';
							}
						});

						var lunasCell = $('<td class="text-center"></td>').html(function() {
							if (verifikasi.status === '6') {
								return '<span class="btn btn-xs btn-icon btn-primary"><i class="fa fa-file-signature"></i></span>';
							} else if (verifikasi.status === '7') {
								return '<span class="btn btn-xs btn-icon btn-success"><i class="fa fa-award"></i></span> <span hidden>7</span>';
							}
						});

						row.append(namaPerusahaan, nomorPengajuan, nomorRIPH, ajuCell, dataCell, lapanganCell, lunasCell);
						tableBody.append(row);
					});
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
	</script>
@endsection