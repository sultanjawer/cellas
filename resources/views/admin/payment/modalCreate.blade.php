<div class="modal fade" id="modalCreatePayment" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					New Payment
					<small class="m-0 text-muted">
						Create new payment.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.payment.store')}}" method="post">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-12">
							<label for="">Customer</label>
							<select class="form-control" id="selectCustomerPayment" name="customer_id" required>
								<option value=""></option>
								@foreach ($customers as $customer)
									<option value="{{$customer->id}}">{{$customer->name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Customer</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Origin Bank</label>
							<input type="type" name="origin_bank" id="origin_bank" class="form-control" placeholder="from bank..." aria-describedby="helpId">
							<small id="helpId" class="text-muted">Payment/Deposit transfered from.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Origin Account</label>
							<input type="type" name="origin_account" id="origin_account" class="form-control" placeholder="Bank Account sender" aria-describedby="helpId">
							<small id="helpId" class="text-muted">Payment/Deposit transfered from.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Slip Date</label>
							<input type="date" name="slip_date" id="slip_date" class="form-control" placeholder="Order amount" aria-describedby="helpId" required>
							<small id="helpId" class="text-muted">Order Amount</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Transfered Amount</label>
							<input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="amount" aria-describedby="helpId" required>
							<small id="helpId" class="text-muted">The amount of money transfered.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="selectCompanyCreate">Designated Company</label>
							<select class="form-control" id="selectCompanyCreate" name="company_id">
								<option value=""></option>
								@foreach ($companies as $company)
									<option value="{{$company->id}}">{{$company->company_name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">The designated Company for this payment or deposit.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="selectBankPayment">Designated Bank</label>
							<select class="form-control" id="selectBankPayment" name="bank_id">
								<option value=""></option>
								@foreach ($banks as $bank)
									<option value="{{$bank->id}}">{{$bank->bank_name}}, {{$bank->account}} - {{$bank->acc_name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">The designated Bank Account that accepts the payment or deposit.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer card-header">
					<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
					@can('user_management_access')
					<button type="submit" class="btn btn-primary waves-effect waves-themed">Save</button>
					@endcan
				</div>
			</form>
		</div>
	</div>
</div>

@section('scripts')
@parent
<script>
	$(document).ready(function() {
		$("#selectCustomerPayment").select2({
			placeholder: "-- Select Customer --",
			dropdownParent: $('#modalCreatePayment')
		});
		$("#selectBankPayment").select2({
			placeholder: "-- Select Bank --",
			dropdownParent: $('#modalCreatePayment')
		});
		$("#selectCompanyCreate").select2({
			placeholder: "-- Select Company --",
			dropdownParent: $('#modalCreatePayment')
		});
	});
</script>
@endsection
