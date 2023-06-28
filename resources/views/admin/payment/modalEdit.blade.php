<div class="modal fade" id="editPayment{{$payment->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					Edit Payment/Deposit
					<small class="m-0 text-muted">
						Edit current Deposit.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.payment.update', $payment->id)}}" method="post">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-12">
							<label for="">Customer</label>
							<select class="form-control" id="select2Customer" name="customer_id" required>
								<option value=""></option>
								@foreach ($customers as $customer)
									<option value="{{ $customer->id }}" {{ old('customer_id', $payment->customer_id) == $customer->id ? 'selected' : '' }}>
										{{ $customer->name }}
									</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Customer</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Origin Bank</label>
							<input type="type" name="origin_bank" id="origin_bank" class="form-control" placeholder="from bank..." aria-describedby="helpId" value="{{old('origin_bank', $payment->origin_bank)}}" >
							<small id="helpId" class="text-muted">Payment/Deposit transfered from.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Origin Account</label>
							<input type="type" name="origin_account" id="origin_account" class="form-control" placeholder="Bank Account sender" aria-describedby="helpId" value="{{old('origin_account', $payment->origin_account)}}" >
							<small id="helpId" class="text-muted">Payment/Deposit transfered from.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Slip Date</label>
							<input type="date" name="slip_date" id="slip_date" class="form-control" placeholder="Order amount" aria-describedby="helpId" value="{{old('slip_date', $payment->slip_date)}}"  required>
							<small id="helpId" class="text-muted">Date printed on transfer slip.</small>
						</div>
						<div class="form-group col-md-6">
							<label for="">Transfered Amount</label>
							<input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="amount" aria-describedby="helpId" value="{{old('amount', $payment->amount)}}"  required>
							<small id="helpId" class="text-muted">The amount of money transfered.</small>
						</div>
						<div class="form-group col-md-12">
							<label for="">Designated Bank</label>
							<select class="form-control" id="select2Bank" name="bank_id">
								<option value=""></option>
								@foreach ($banks as $bank)
									<option value="{{ $bank->id }}" {{ old('bank_id', $payment->bank_id) == $bank->id ? 'selected' : '' }}>
										{{$bank->bank}}, {{$bank->account}} - {{$bank->acc_name}}
									</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">The designated Bank Account that accepts the payment or deposit.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm waves-effect waves-themed" data-dismiss="modal">Close</button>
					@can('user_management_access')
					<button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">Save changes</button>
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
		$("#select2Customer").select2({
			placeholder: "-- Select Customer --",
			dropdownParent: $('#editPayment{{$payment->id}}')
		});
		$("#select2Bank").select2({
			placeholder: "-- Select Bank --",
			dropdownParent: $('#editPayment{{$payment->id}}')
		});
	});
</script>
@endsection
