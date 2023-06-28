<!-- start alert pesan -->
@if(session('message'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
		</button>
		<strong>{{ session('message') }}.</strong>
	</div>
@endif
<!-- end alert pesan -->
<!-- start alert error -->
@if($errors->count() > 0)
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true"><i class="fal fa-times-circle"></i></span>
		</button>
		<strong>PERHATIAN!</strong>
		<ul class="list-unstyled">
			@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
