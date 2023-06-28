@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col-lg-4 mb-3">
			<div class="card">
				@if($customer->attachments)
					<img src="{{ asset('storage/uploads/'.$customer->id.'/'.$customer->attachments) }}" class="card-img-top cover" alt="cover" style="max-width: 100%;">
				@else
					<div class="pt-5 pb-5 card-img-top cover align-items-center justify-content-center text-center {{ ($balance ?? 0) <= 0 ? 'bg-danger-500' : 'bg-success-500' }}">
						<h3 class="display-4 fw-500">{{$customer->name}}</h3>
						{{-- {{$initialName}} --}}
					</div>
				@endif
				<div class="card-body">
					<h5 class="card-title fw-700">{{$customer->name}}</h5>
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Updated at</span>
						<span class="js-get-date">Today</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Last Order</span>
						<span class="fw-500 text-right">
							@if ($lastOrder && $lastOrder->product)
								{{ date('d M Y', strtotime($lastOrder->created_at)) }}<br>
								{{$lastOrder->product->symbol}}
								{{ number_format($lastOrder->amount, 2, '.', ',') }}
							@else
								<span class="text-danger">No transaction yet.</span>
							@endif
						</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Last Transfer</span>
						<span class="text-right class="fw-500"">
							@if($lastPayment->slip_date)
								{{ date('d M Y', strtotime($lastPayment->slip_date)) }}
								<br>
								Rp {{ number_format($lastPayment->amount, 2, '.', ',') }}
							@else
							<span class="text-danger">No deposit payment yet.</span>
							@endif
						</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Balance</span>
						<span class="fw-500 text-right {{ ($balance ?? 0) <= 0 ? 'text-danger' : 'text-primary' }}">
							{{ number_format($balance, 2, '.', ',') }}
						</span>
					</li>
				</ul>
				
			</div>
		</div>
		<div class="col-lg-8">
			<div class="panel" id="panel-2">
				<div class="panel-hdr">
					<h2>Transactions History</h2>
				</div>
				<div class="panel-container">
					<div>
						<div class="panel-content">
							<table class="table table-sm table-striped table-bordered table-hovered w-100" id="cashInOut">
								<thead>
									<th hidden>Customer ID</th>
									<th>Date</th>
									<th>Deposit</th>
									<th>Order</th>
									<th>Value</th>
									<th></th>
								</thead>
								<tbody>
									@foreach ($customerTransactions as $transaction)
										<tr>
											<td hidden>{{$transaction->customer_id}}</td>
											<td>{{ date('d/m/Y', strtotime($transaction->transaction_date)) }}</td>
											<td class="text-right">
												@if($transaction->deposit)
													Rp {{ number_format($transaction->deposit, 2, '.', ',') }}
												@else
												@endif
											</td>
											<td class="text-right">
												@if($transaction->order)
												{{$transaction->currency}} {{ number_format($transaction->order, 2, '.', ',') }}
													@if($transaction->charges)
														+ Rp {{ number_format($transaction->charges, 2, '.', ',') }}
													@endif
												@else
												@endif
											</td>
											<td class="text-right">
												@if($transaction->cashOut)
													Rp {{ number_format($transaction->cashOut, 2, '.', ',') }}
												@else
												@endif
											</td>
											<td></td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="editCustomer" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						Edit Customer
						<small class="m-0 text-muted">
							Edit current customer.
						</small>
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fal fa-times"></i></span>
					</button>
				</div>
				<form action="{{route('admin.master.customer.update', $customer->id)}}" method="post">
					@csrf
					@method('put')
					<div class="modal-body">
						<div class="form-group">
							<label for="">Customer Name</label>
							<input type="text" name="name" id="name" class="form-control" placeholder="currency symbol" aria-describedby="helpId" value="{{old('name', $customer->name)}}">
							<small id="helpId" class="text-muted">Customer Name</small>
						</div>
						<div class="form-group">
							<label for="">Phone Contact</label>
							<input type="text" name="mobile_phone" id="mobile_phone" class="form-control" placeholder="currency symbol" aria-describedby="helpId" value="{{old('mobile_phone', $customer->mobile_phone)}}">
							<small id="helpId" class="text-muted">Phone contact number</small>
						</div>
						<div class="form-group">
							<label for="">Customer Notes</label>
							<textarea class="form-control" id="notes" name="notes" rows="4">{{old('notes', $customer->notes)}}</textarea>
							<small id="helpId" class="text-muted">Notes for this customer</small>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary waves-effect waves-themed" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary waves-effect waves-themed">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

{{-- script section --}}
@section('scripts')
@parent
<script>
	$(document).ready(function()
	{
		$('#cashInOut').dataTable(
		{
			responsive: true,
			lengthChange: true,
			pageLength: 10,
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-xs btn-icon mr-1'
				},
				{
					extend: 'copyHtml5',
					text: '<i class="fa fa-copy"></i>',
					titleAttr: 'Copy to clipboard',
					className: 'btn-outline-primary btn-xs btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-xs btn-icon mr-1'
				},
				{
					text: '<i class="fa fa-cash-register"></i>',
					titleAttr: 'New Order',
					className: 'btn btn-info btn-xs btn-icon ml-2',
					action: function(e, dt, node, config) {
						window.location.href = '';
					}
				}
			]
		});
	});
</script>
@endsection
