@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col">
			<div class="panel" id="productsPanel">
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-hover table-sm table-striped table-bordered w-100" id="customersTable">
							<thead>
								<th>Customer Name</th>
								<th>Mobile</th>
								<th></th>
							</thead>
							<tbody>
								@foreach ($customers as $customer)
									<tr>
										<td>{{$customer->name}}</td>
										<td>{{$customer->mobile_phone}}</td>
										<td width="5%" class="text-center">
											<button class="btn btn-toolbar-master" data-toggle="dropdown">
												<i class="fas fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-right">
												<form action="{{route('admin.master.customer.delete', $customer->id)}}" method="post">
													@csrf
													@method('delete')
													<a href="{{route('admin.master.customer.show', $customer->id)}}" class="dropdown-item">
														<i class="fal fa-address-card mr-1"></i>Show
													</a>
													<a href="javascript:void()" data-toggle="modal" class="dropdown-item fw-500"
														data-target="#editCustomer{{$customer->id}}">
														<i class="fal fa-edit mr-1"></i>Edit
													</a>
													<button class="dropdown-item" type="submit">
														<i class="fal fa-trash mr-1"></i>Delete
													</button>
													<div class="dropdown-divider m-0"></div>
													<a href="" class="dropdown-item">
														<i class="fal fa-money-check-edit-alt mr-1"></i>Bank Account
													</a>
													<a href="" class="dropdown-item">
														<i class="fal fa-clock mr-1"></i>Orders History
													</a>
												</form>
											</div>
										</td>
										<div class="modal fade" id="editCustomer{{$customer->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
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
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="newCustomer" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						New Customer
						<small class="m-0 text-muted">
							Add new Customer.
						</small>
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fal fa-times"></i></span>
					</button>
				</div>
				<form action="{{route('admin.master.customer.store')}}" method="post">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<label for="">Customer Name</label>
							<input type="text" name="name" id="name" class="form-control" placeholder="customer name" aria-describedby="helpId">
							<small id="helpId" class="text-muted">Customer Name</small>
						</div>
						<div class="form-group">
							<label for="">Phone Contact</label>
							<input type="text" name="mobile_phone" id="mobile_phone" class="form-control" placeholder="mobile phone number" aria-describedby="helpId">
							<small id="helpId" class="text-muted">Phone contact number</small>
						</div>
						<div class="form-group">
							<label for="">Customer Notes</label>
							<textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
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
		$('#customersTable').dataTable(
		{
			responsive: true,
			lengthChange: false,
			dom:
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [
				{
					extend: 'pdfHtml5',
					text: '<i class="fa fa-file-pdf"></i>',
					titleAttr: 'Generate PDF',
					className: 'btn-outline-danger btn-sm btn-icon mr-1'
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-sm btn-icon mr-1'
				},
				{
					extend: 'csvHtml5',
					text: '<i class="fal fa-file-csv"></i>',
					titleAttr: 'Generate CSV',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				},
				{
					extend: 'copyHtml5',
					text: '<i class="fa fa-copy"></i>',
					titleAttr: 'Copy to clipboard',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i>',
					titleAttr: 'Print Table',
					className: 'btn-outline-primary btn-sm btn-icon mr-1'
				},
				{
					text: '<i class="fa fa-user-plus"></i>',
					titleAttr: 'Add New Cutomer',
					className: 'btn btn-info btn-sm btn-icon ml-2',
					action: function(e, dt, node, config) {
						$('#newCustomer').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			]
		});
	});
</script>
@endsection
