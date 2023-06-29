<div class="modal fade" id="editOrder{{$order->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					Edit Order
					<small class="m-0 text-muted">
						Edit current order.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.order.update', $order->id)}}" method="post">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-12">
							<label for="">Customer</label>
							<select class="form-control" id="selectCustomer" name="customer_id" required>
								<option value=""></option>
								@foreach ($customers as $customer)
									<option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
										{{ $customer->name }}
									</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Customer</small>
						</div>
						<div class="form-group col-md-5 col-sm-6">
							<label for="">Currency</label>
							<select class="form-control" id="productEdit" name="product_id" required>
								<option value=""></option>
								@foreach ($products as $product)
									<option value="{{ $product->id }}" {{ old('product_id', $order->product_id) == $product->id ? 'selected' : '' }}>
										{{ $product->symbol }} {{ $product->currency }}
									</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Select Currency to trades.</small>
						</div>
						<div class="form-group col-md-7 col-sm-6">
							<label for="">Order Amount</label>
							<input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="Order amount" aria-describedby="helpId"
								value="{{old('amount', $order->amount)}}" required>
							<small id="helpId" class="text-muted">Order Amount</small>
						</div>
						<div class="form-group col-md-4">
							<label for="">Buy Rates</label>
							<input type="number" step="0.01" name="buy" id="buy" class="form-control" placeholder="Buy rates" aria-describedby="helpId" value="{{old('buy', $order->buy)}}" required>
							<small id="helpId" class="text-muted">Buy Rates</small>
						</div>
						<div class="form-group col-md-4">
							<label for="">Sell Rates</label>
							<input type="number" step="0.01" name="sell" id="sell" class="form-control" placeholder="Sell rates" aria-describedby="helpId" value="{{old('sell', $order->sell)}}" required>
							<small id="helpId" class="text-muted">Sell Rates</small>
						</div>
						<div class="form-group col-md-4">
							<label for="">Charges</label>
							<input type="number" name="charges" id="charges" class="form-control" aria-describedby="helpId" value="{{old('charges', $order->charges)}}">
							<small id="helpId" class="text-muted">Charges fee.</small>
						</div>
						<div class="form-group col-12">
							<label for="">Bank Info</label>
							<select class="form-control" id="selectBank" name="bank_id" required>
								<option value=""></option>
								@foreach ($banks as $bank)
									<option value="{{ $bank->id }}" {{ old('bank_id', $order->bank_id) == $bank->id ? 'selected' : '' }}>
										{{$bank->name}}
									</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">The designated Bank Account that accepts the payment or deposit.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
					@can('user_management_access')
					<button type="submit" class="btn btn-primary waves-effect waves-themed">Save changes</button>
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
		$("#selectCustomer").select2({
			placeholder: "-- Select Customer --",
			dropdownParent: $('#editOrder{{$order->id}}')
		});

		$("#productEdit").select2({
			placeholder: "-- Select Customer --",
			dropdownParent: $('#editOrder{{$order->id}}')
		});
		$("#selectBank").select2({
			placeholder: "-- Select Bank --",
			dropdownParent: $('#editOrder{{$order->id}}')
		});
	});
</script>
@endsection
