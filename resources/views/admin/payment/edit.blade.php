@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')
	@can('payment_edit')
		<div class="row">
			<div class="col-12">
				<div class="panel p-1" id="productsPanel">
					<form action="{{route('admin.payment.update', $payment->id)}}" method="post">
						@csrf
						@method('put')
						<div class="panel-container">
							<div class="panel-content row d-flex">
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
								<div class="form-group col-md-6">
									<label for="selectCompanyCreate">Designated Company</label>
									<select class="form-control" id="selectCompanyCreate" name="company_id">
										<option value=""></option>
										@foreach ($companies as $company)
											<option value="{{$company->id}}" {{ old('company_id', $payment->company_id) == $company->id ? 'selected' : '' }}>
												{{$company->company_name}}
											</option>
										@endforeach
									</select>
									<small id="helpId" class="text-muted">The designated Company for this payment or deposit.</small>
								</div>
								<div class="form-group col-md-6">
									<label for="select2Bank">Designated Bank</label>
									<select class="form-control" id="select2Bank" name="recipient_bank">
										<option value=""></option>
										@foreach ($banks as $bank)
											<option value="{{ $bank->id }}" {{ old('recipient_bank', $payment->recipient_bank) == $bank->id ? 'selected' : '' }}>
												{{$bank->bank_name}}, {{$bank->account}} - {{$bank->acc_name}}
											</option>
										@endforeach
									</select>
									<small id="helpId" class="text-muted">The designated Bank Account that accepts the payment or deposit.</small>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<div class="d-flex justify-content-between align-itmes-center">
								<div><span class="text-danger">*</span> Required</div>
								<div>
									<a href="{{route('admin.payments.index')}}" type="button" class="btn btn-default btn-sm waves-effect waves-themed">Back</a>
									@can('user_management_access')
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">Save changes</button>
									@endcan
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endcan
@endsection

{{-- script section --}}
@section('scripts')
@parent
<script>
	$(document).ready(function() {
		$("#select2Customer").select2({
			placeholder: "-- Select Customer --",
		});
		$("#select2Bank").select2({
			placeholder: "-- Select Bank --",
		});
		$("#selectCompanyCreate").select2({
			placeholder: "-- Select Company --",
		});
	});
</script>
@endsection
