<div class="modal fade" id="modalEdit{{$company->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					New Company
					<small class="m-0 text-muted">
						Create new company.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.master.company.update', $company->id)}}" method="post">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="">Company Name</label>
							<input type="type" name="company_name" id="company_name" class="form-control" placeholder="give name" aria-describedby="helpId" value="{{old('company_name', $company->company_name)}}">
							<small class="text-muted">The company name will be displayed wherever necessary.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Company Code</label>
							<input type="type" name="company_code" id="company_code" class="form-control" placeholder="code given" aria-describedby="helpId" value="{{old('company_code', $company->company_code)}}">
							<small id="helpId" class="text-muted">You can provide code that will be useful for future reference.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer card-header">
					<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary waves-effect waves-themed">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

@section('scripts')
@parent
@endsection
