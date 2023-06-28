<div class="modal fade" id="newBank" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					New Bank
					<small class="m-0 text-muted">
						The designated Bank Account that accepts the payment or deposit.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.master.bank.store')}}" method="post">
				@csrf
				<div class="modal-body">
					{{-- <div class="form-group">
						<label for="">Company</label>
						<div class="input-group">
							<select name="compay_id" id="company_id" class="form-control form-control-sm custom-select">
								<option value=""></option>
								@foreach ($companies as $company)
									<option value="{{$company->id}}">{{$company->company_name}}</option>
								@endforeach
							</select>
							<div class="input-group-append">
								<a href="javascript:void()" data-toggle="modal" class="btn btn-default" data-target="#modalCreateCompany">
									<i class="fal fa-plus mr-1"></i>new company
								</a>
							</div>
						</div>
						<span class="help-block">Before adding any new Data, you need to have at least one registered company.</span>
					</div> --}}
					<div class="form-group">
						<label for="">Bank <span class="text-danger">*</span></label>
						<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="bank name" aria-describedby="helpId" required>
						<span class="help-block">Consistency in giving bank names will assist you in the future.</span>
					</div>
					<div class="form-group">
						<label for="">Account Number <span class="text-danger">*</span></label>
						<input type="text" name="account" id="account" class="form-control" placeholder="account number" aria-describedby="helpId" required>
						<span class="help-block">Please use the official format issued by the bank.</span>
					</div>
					<div class="form-group">
						<label for="">Account Name <span class="text-danger">*</span></label>
						<input type="text" name="acc_name" id="acc_name" class="form-control" placeholder="account name" aria-describedby="helpId" required>
						<span class="help-block">Account holder's name.</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect waves-themed" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary waves-effect waves-themed">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
