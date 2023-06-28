@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" media="screen, print" href="{{ asset('css/miscellaneous/lightgallery/lightgallery.bundle.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- <script src="{{ asset('js/gmap/js.js') }}"></script> --}}
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ea90fk4RXPswzkOJzd17W3EZx_KNB1M&libraries=drawing,geometry"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.3.0/turf.min.js"></script>

@endsection
@section('content')
{{-- @include('partials.breadcrumb') --}}
@include('partials.subheaderwithfilter')
@include('partials.sysalert')
{{-- @can('commitment_show')  --}}
	<div class="row">
		<div class="col-12">
			<div class="panel" id="panel-1">
				<div class="panel-container show">
					<div id="allMap" style="height: 500px; width: 100%;" class="shadow-sm border-1"></div>
				</div>
			</div>
		</div>
	</div>

	{{-- modal show data --}}
	<!-- Modal -->
	<!-- Modal -->
<!-- Modal -->
	<div class="modal fade" id="markerModal" tabindex="-1" role="dialog" aria-labelledby="markerModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-left modal-sm modal-transparent" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fw-700 text-white" id="nama_lokasi"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
						<div class="d-flex justify-content-end align-items-center js-gallery" id="js-gllery">
							<a data-sub-html="Foto Panen" title="Foto Panen" target="_blank">
								<img class="img-fluid img-thumbnail" alt="Card image cap" id="panenPict">
							</a>
							
							<a data-sub-html="Foto Panen" title="Foto Panen" target="_blank">
								<img class="img-fluid img-thumbnail" alt="Card image cap" id="tanamPict" >
							</a>
						</div>
					<div class="card no-shadow" id="card-1">
						<div class="card-body">
							<ul class="list-group mt-0">
								<li class="list-group-item d-flex justify-content-between align-items-center p-2">
									<a>Nama Lokasi</a>
									<span class="fw-500" id="nama_lokasi"></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center p-2">
									<a>RIPH</a>
									<span class="fw-500" id="no_ijin"></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center p-2">
									<a>PKS</a>
									<span class="fw-500" id="no_perjanjian"></span>
								</li>
								<li class="list-group-item d-flex justify-content-between align-items-center p-2">
									<a>Perusahaan</a>
									<span class="fw-500" id="company"></span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

{{-- @endcan --}}

@endsection

<!-- start script for this page -->
@section('scripts')
<script src="{{ asset('js/miscellaneous/lightgallery/lightgallery.bundle.js') }}"></script>
@parent
@if (Auth::user()->roles[0]->title == 'User')
	<script src="{{ asset('js/gmap/userDashboardMaps.js') }}"></script>
@else
	<script src="{{ asset('js/gmap/allMaps.js') }}"></script>
@endif
{{-- <script src="{{ asset('js/gmap/clickMap.js') }}"></script> --}}

<script>
    $(document).ready(function() {
        $("#periodetahun").select2({
            placeholder: "--Pilih tahun",
        });
		$("#company").select2({
            placeholder: "--Pilih Pelaku Usaha",
        });
		// Add an event listener to the periodetahun select element
			//
    });
</script>

<!-- gallery -->
<script>
	$(document).ready(function() {
		var $initScope = $('#js-gallery');
		if ($initScope.length) {
			$initScope.justifiedGallery({
				border: -1,
				rowHeight: 75,
				margins: 8,
				waitThumbnailsLoad: true,
				randomize: false,
			}).on('jg.complete', function() {
				$initScope.lightGallery({
					thumbnail: true,
					animateThumb: true,
					showThumbByDefault: true,
				});
			});
		};
		$initScope.on('onAfterOpen.lg', function(event) {
			$('body').addClass("overflow-hidden");
		});
		$initScope.on('onCloseAfter.lg', function(event) {
			$('body').removeClass("overflow-hidden");
		});
	});
</script>


<!-- gallery -->
@endsection