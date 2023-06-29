@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	@can('order_edit')
		<form action="{{route('admin.order.update', $order->id)}}" method="post" enctype="multipart/form-data">
			@csrf
			@method('put')
			<div class="row">
				<div class="col-12">
					<div class="panel" id="panel-1">
						<div class="panel-container show">
							<div class="panel-content">
								<div class="row d-flex">
								<div class="form-group col-md-6">
									<label for="">Customer</label>
									<select class="form-control" id="select2Customer" name="customer_id" required>
										<option value=""></option>
										@foreach ($customers as $customer)
										<option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
											{{ $customer->name }}
										</option>
										@endforeach
									</select>
									<small id="helpId" class="text-muted">Customer</small>
								</div>
								<div class="form-group col-md-6">
									<label for="">Designated Company</label>
									<select class="form-control" id="select2Company" name="company_id" required>
										<option value=""></option>
										@foreach ($companies as $company)
										<option value="{{ $company->id }}" {{ old('company_id', $order->company_id) == $company->id ? 'selected' : '' }}>
											{{ $company->company_name }}
										</option>
										@endforeach
									</select>
									<small id="helpId" class="text-muted">The designated Company to run this order.</small>
								</div>
								<div class="form-group col-md-5 col-sm-6">
									<label for="">Currency</label>
									<select class="form-control" id="product_id" name="product_id" required>
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
									<input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="Order amount" value="{{old('amount', $order->amount)}}" aria-describedby="helpId" required>
									<small id="helpId" class="text-muted">Order Amount</small>
								</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel" id="panel-2">
						<div class="panel-container show">
							<div class="panel-content">
								<table class="table table-sm table-bordered table-hover w-100">
									<thead class="text-muted text-center">
										<th>Purchased</th>
										<th>Desc</th>
										<th>Sell</th>
									</thead>
									<tbody>
										<tr class="align-items-center">
											<td>
												<input type="number" step="0.01" class="form-control form-control-sm" placeholder="purchased rates" name="buy" id="buy" value="{{old('buy', $order->buy)}}" />
											</td>
											<td class="text-center text-muted">-Rates (Rp)-</td>
											<td>
												<input type="number" step="0.01" class="form-control form-control-sm" placeholder="sell rates" name="sell" id="sell" value="{{old('sell', $order->sell)}}" />
											</td>
										</tr>
										<tr class="align-items-center">
											<td>
												<input type="number" step="0.01" class="form-control form-control-sm" placeholder="purchased charges" name="pcharges" id="pcharges"  value="{{old('pcharges', $order->pcharges)}}" />
											</td>
											<td class="text-center text-muted">-Charges (Rp)-</td>
											<td>
												<input type="number" step="0.01" class="form-control form-control-sm" placeholder="customer charges" name="ccharges" id="ccharges"  value="{{old('ccharges', $order->ccharges)}}" />
											</td>
										</tr>
										<tr class="align-items-center">
											<td>
												<input type="number" step="0.01" class="form-control form-control-sm" placeholder="full amount" name="pfa" id="pfa" value="{{old('pfa', $order->pfa)}}" />
											</td>
											<td class="text-center text-muted">-FA ($)-</td>
											<td>
												<input type="number" step="0.01" class="form-control form-control-sm" placeholder="customer full amount" name="cfa" id="cfa" value="{{old('cfa', $order->cfa)}}" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="panel" id="panel-3">
						<div class="panel-container show">
							<div class="panel-content">
								<table class="table table-sm table-bordered table-striped w-100" id="selectedBank">
									<thead>
										<th hidden>id</th>
										<th>Bank</th>
										<th>Account</th>
										<th>Acc. Name</th>
										<th></th>
									</thead>
									<tbody>
										@foreach ($banks as $bank)
											<tr data-id="{{$bank->id}}">
												<td hidden>{{$bank->id}}</td>
												<td>{{$bank->bank_name}}</td>
												<td>{{$bank->account}}</td>
												<td>{{$bank->acc_name}}</td>
												<td>
													<input type="checkbox" class="bank-checkbox" value="{{ $bank->id }}"
													@if(in_array($bank->id, $selectedBankIDs)) checked @endif>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
								<span id="selectedRows" hidden></span>
								<input type="hidden" name="selectedRows" id="selectedRowsInput">
							</div>
						</div>
						<div class="card-footer d-flex justify-content-between align-items-center">
							<div></div>
							<div>
								<a href="{{route('admin.orders.index')}}" class="btn btn-default btn-sm waves-effect waves-themed">Back</a>
								@can('user_management_access')
								<button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">Update</button>
								@endcan
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	@endcan
@endsection

{{-- script section --}}
@section('scripts')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
@parent
<script>
	$(document).ready(function() {
		$("#select2Customer").select2({
			placeholder: "-- Select Customer --",
		});

		$("#product_id").select2({
			placeholder: "-- Select Currency --",
		});
		$("#select2Company").select2({
			placeholder: "-- Select Bank --",
		});
	});
</script>
<script>
    $(document).ready(function() {
        var table = $('#selectedBank').DataTable({
            // select: {
            //     style: 'multi'
            // },
            // keys: true,
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
