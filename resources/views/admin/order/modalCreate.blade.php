<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					New Order
					<small class="m-0 text-muted">
						Create new order.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.order.store')}}" method="post">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-12">
							<label for="">Customer</label>
							<select class="form-control" id="select2Customer" name="customer_id" required>
								<option value=""></option>
								@foreach ($customers as $customer)
									<option value="{{$customer->id}}">{{$customer->name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Customer</small>
						</div>
						<div class="form-group col-md-5 col-sm-6">
							<label for="">Currency</label>
							<select class="form-control" id="product_id" name="product_id" required>
								<option value=""></option>
								@foreach ($products as $product)
									<option value="{{$product->id}}">{{$product->symbol}} {{$product->currency}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Select Currency to trades.</small>
						</div>
						<div class="form-group col-md-7 col-sm-6">
							<label for="">Order Amount</label>
							<input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="Order amount" aria-describedby="helpId" required>
							<small id="helpId" class="text-muted">Order Amount</small>
						</div>
						<div class="form-group col-md-3">
							<label for="">Buy Rates</label>
							<input type="number" step="0.01" name="buy" id="buy" class="form-control" placeholder="Buy rates" aria-describedby="helpId" required>
							<small id="helpId" class="text-muted">Buy Rates</small>
						</div>
						<div class="form-group col-md-3">
							<label for="">Sell Rates</label>
							<input type="number" step="0.01" name="sell" id="sell" class="form-control" placeholder="Sell rates" aria-describedby="helpId" required>
							<small id="helpId" class="text-muted">Sell Rates</small>
						</div>
						<div class="form-group col-6">
							<label for="">Bank Info</label>
							<select class="form-control" id="select2Bank" name="bank_id" required>
								<option value=""></option>
								@foreach ($banks as $bank)
									<option value="{{$bank->id}}">{{$bank->name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">The designated Bank Account that accepts the payment or deposit.</small>
						</div>
					</div>
				</div>
				<div class="modal-footer card-header">
					<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
					@can('user_management_access')
					<button type="submit" class="btn btn-primary waves-effect waves-themed">Create Order</button>
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
			dropdownParent: $('#modalCreate')
		});

		$("#product_id").select2({
			placeholder: "-- Select Currency --",
			dropdownParent: $('#modalCreate')
		});
		$("#select2Bank").select2({
			placeholder: "-- Select Bank --",
			dropdownParent: $('#modalCreate')
		});
	});
</script>

@endsection