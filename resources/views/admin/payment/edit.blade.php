@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

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
							<div class="form-group col-md-12">
								<label for="select2Bank">Designated Bank</label>
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
					<div class="card-footer">
						<div class="d-flex justify-content-between align-itmes-center">
							<div><span class="text-danger">*</span> Required</div>
							<div>
								<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
								@can('user_management_access')
									<button type="submit" class="btn btn-primary waves-effect waves-themed">Save changes</button>
								@endcan
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
