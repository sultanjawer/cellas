@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	{{-- @include('partials.sysalert') --}}

	<div class="row">
		<div class="col-lg-4 mb-3">
			<div class="card">
				@if($customer->attachments)
					<img src="{{ asset('storage/uploads/'.$customer->id.'/'.$customer->attachments) }}" class="card-img-top cover" alt="cover" style="max-width: 100%;">
				@else
					<div class="card-img-top cover align-items-center justify-content-center text-center" style="background-color: #e9e9e9">
						<h1 class="display-1 fw-500">{{$initialName}}</h1>
					</div>
				@endif
				<div class="card-body">
					<h5 class="card-title fw-700">{{$customer->name}}</h5>
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Customer ID</span>
						<span>{{ ($customer->customer_id?? 'no data') }}</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Account No.</span>
						<span>{{ ($customer->account_no?? 'no data') }}</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Company</span>
						<span>{{ ($customer->company?? 'no data') }}</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Email</span>
						<span>{{ ($customer->email_address?? 'no data') }}</span>
					</li>
					<li class="list-group-item  list-group-item-action d-flex justify-content-between align-item-center">
						<span class="text-muted">Phone</span>
						<span>{{ ($customer->mobile_phone?? 'no data') }}</span>
					</li>
					<li class="list-group-item  list-group-item-action ">
						<span class="text-muted row col">Address</span>
						<span>
							<p>{{ ($customer->address?? 'no data') }}.  {{ ($customer->city?? 'no data') }} - {{ ($customer->state_province?? 'no data') }}</p>
						</span>
					</li>
				</ul>
				<div class="card-footer">
					<div class="d-flex justify-content-between align-itmes-center">
						<div></div>
						<div>
							<a href="{{route('admin.master.customers.index')}}" class="btn btn-secondary btn-sm" role="button">
								<i class="fal fa-undo"></i>
								Back
							</a>
							<a href="javascript:void()" data-toggle="modal" class="btn btn-warning btn-sm"
								data-target="#editCustomer">
								<i class="fal fa-edit mr-1"></i>Edit
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="panel" id="panel-2">
				<div class="panel-container">
					<div class="panel-content">
						<div class="row">
							<div class="form-group col-lg-4">
								<label for="product_code" class="col-form-label">Total Deposits/Payment</label>
								<input type="text" class="form-control fw-700" id="payment" name="payment" placeholder="Total" readonly>
							</div>
							<div class="form-group col-lg-4">
								<label for="product_code" class="col-form-label">Total Order</label>
								<input type="text" class="form-control fw-700" id="payment" name="payment" placeholder="Total" readonly>
							</div>
							<div class="form-group col-lg-4">
								<label for="product_code" class="col-form-label">Balance</label>
								<input type="text" class="form-control fw-700" id="payment" name="payment" placeholder="Total" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel" id="panel-3">
				<div class="panel-hdr">
					<h2>Saving/Payment History</h2>
				</div>
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-sm table-striped table-bordered w-100" id="paymentHistory">
							<thead>
								<th>Invoice</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Payment</th>
								<th>Status</th>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="panel" id="panel-4">
				<div class="panel-hdr">
					<h2>Order History</h2>
				</div>
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-sm table-striped table-bordered w-100" id="orderHistory">
							<thead>
								<th>Invoice</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Payment</th>
								<th>Status</th>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
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
		$('#paymentHistory').dataTable(
		{
			responsive: true,
			lengthChange: false,
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				}
			]
		});
	});
</script>
<script>
	$(document).ready(function()
	{
		$('#orderHistory').dataTable(
		{
			responsive: true,
			lengthChange: false,
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				}
			]
		});
	});
</script>
@endsection
