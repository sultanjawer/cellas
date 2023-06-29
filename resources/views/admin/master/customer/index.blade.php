@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')
	@can('master_customer_access')
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
											@can('customer_edit')
												@include('admin.master.customer.modalEdit')
											@endcan
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endcan
	@can('master_customer_create')
		@include('admin.master.customer.modalCreate')
	@endcan
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
