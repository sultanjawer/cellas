<div class="modal fade" id="modalOrderCreate" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
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
					<div class="row mb-2">
						<div class="form-group col-6">
							<label for="">Customer</label>
							<select class="form-control" id="select2Customer" name="customer_id" required>
								<option value=""></option>
								@foreach ($customers as $customer)
									<option value="{{$customer->id}}">{{$customer->name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">Customer</small>
						</div>
						<div class="form-group col-6">
							<label for="">Designated Company</label>
							<select class="form-control" id="select2Company" name="company_id" required>
								<option value=""></option>
								@foreach ($companies as $company)
									<option value="{{$company->id}}">{{$company->company_name}}</option>
								@endforeach
							</select>
							<small id="helpId" class="text-muted">The designated Company to run this order.</small>
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
					</div>
					<div class="row mb-2">
						<div class="col">
							<table class="table table-sm table-bordered table-hover w-100">
								<thead class="text-muted text-center">
									<th>Purchased</th>
									<th>Desc</th>
									<th>Sell</th>
								</thead>
								<tbody>
									<tr class="align-items-center">
										<td>
											<input type="number" step="0.01" class="form-control form-control-sm" placeholder="purchased rates" name="buy" id="buy" />
										</td>
										<td class="text-center text-muted">-Rates (Rp)-</td>
										<td>
											<input type="number" step="0.01" class="form-control form-control-sm" placeholder="sell rates" name="sell" id="sell" />
										</td>
									</tr>
									<tr class="align-items-center">
										<td>
											<input type="number" step="0.01" class="form-control form-control-sm" placeholder="purchased charges" name="pcharges" id="pcharges" />
										</td>
										<td class="text-center text-muted">-Charges (Rp)-</td>
										<td>
											<input type="number" step="0.01" class="form-control form-control-sm" placeholder="customer charges" name="ccharges" id="ccharges" />
										</td>
									</tr>
									<tr class="align-items-center">
										<td>
											<input type="number" step="0.01" class="form-control form-control-sm" placeholder="full amount" name="pfa" id="pfa" />
										</td>
										<td class="text-center text-muted">-FA ($)-</td>
										<td>
											<input type="number" step="0.01" class="form-control form-control-sm" placeholder="customer full amount" name="cfa" id="cfa" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<table class="table table-sm table-bordered table-striped w-100" id="selectedBank">
								<thead>
									<th>id</th>
									<th>Bank</th>
									<th>Account</th>
									<th>Acc. Name</th>
									<th></th>
								</thead>
								<tbody>
									@foreach ($banks as $bank)
										<tr data-id="{{$bank->id}}">
											<td>{{$bank->id}}</td>
											<td>{{$bank->bank_name}}</td>
											<td>{{$bank->account}}</td>
											<td>{{$bank->acc_name}}</td>
											<td><input type="checkbox" class="bank-checkbox" value="{{$bank->id}}"></td>
										</tr>
									@endforeach
								</tbody>
							</table>
							<span id="selectedRows"></span>
							<input type="hidden" name="selectedRows" id="selectedRowsInput">

						</div>
					</div>
				</div>
				<div class="modal-footer card-header">
					<button type="button" class="btn btn-default btn-sm waves-effect waves-themed" data-dismiss="modal">Close</button>
					@can('user_management_access')
					<button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">Create Order</button>
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
			dropdownParent: $('#modalOrderCreate')
		});

		$("#product_id").select2({
			placeholder: "-- Select Currency --",
			dropdownParent: $('#modalOrderCreate')
		});
		$("#select2Company").select2({
			placeholder: "-- Select Bank --",
			dropdownParent: $('#modalOrderCreate')
		});
	});
</script>
<script>
    $(document).ready(function() {
        var table = $('#selectedBank').DataTable({
            select: {
                style: 'multi'
            },
            keys: true,
        });

        // Event listener for checkbox change event
        $('#selectedBank').on('change', '.bank-checkbox', function() {
            var selectedIds = [];

            $('.bank-checkbox:checked').each(function() {
                var checkboxValue = $(this).val();
                selectedIds.push(checkboxValue);
            });

            $('#selectedRows').text(selectedIds.join(', ')); // Display selected IDs in the span element
            $('#selectedRowsInput').val(selectedIds.join(',')); // Set the selected IDs in the hidden input field
            console.log(selectedIds);
            // Process the selectedIds as needed
        });
    });
</script>
@endsection
