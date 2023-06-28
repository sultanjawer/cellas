@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col-12">
			<div class="panel p-1" id="productsPanel">
				<form action="{{route('admin.master.customer.store')}}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="panel-container row d-flex">
						<div class="col-md-12 mb-2">
							<div class="panel-content">
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">Customer Name <span class="text-danger">*</span></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="name" name="name" placeholder="Customer name" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="mobile_phone" class="col-sm-2 col-form-label">Mobile <span class="text-danger">*</span></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="mobile_phone" name="mobile_phone" placeholder="Phone contact number" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="notes" class="col-sm-2 col-form-label">Mobile <span class="text-danger">*</span></label>
									<div class="col-sm-10">
										<textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel-content">
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="d-flex justify-content-between align-itmes-center">
							<div><span class="text-danger">*</span> Required</div>
							<div>
								<a href="{{route('admin.master.customers.index')}}" class="btn btn-primary btn-sm">
									<i class="fal fa-undo"></i>
									Cancel
								</a>
								<button class="btn btn-warning btn-sm" role="button" type="submit">
									<i class="fal fa-save"></i>
									Save
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

{{-- script section --}}
@section('scripts')
@parent

@endsection
