@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@can('landing_access')
		<!-- Page Content -->
		<div class="row">

		</div>
		<!-- Page Content -->
	@endcan
@endsection
@section('scripts')
	@parent
	<script>
		$(document).ready(function() {

		});
	</script>
@endsection
