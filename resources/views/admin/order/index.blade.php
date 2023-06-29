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
						<table class="table table-hover table-sm table-striped table-bordered w-100" id="dataPayment">
							<thead>
								<th>Date</th>
								<th>Customer Name</th>
								<th>Product</th>
								<th>Amount</th>
								<th>Buy</th>
								<th>Sell</th>
								<th>Charges</th>
								<th></th>
							</thead>
							<tbody>
								@foreach ($orders as $order)
									<tr>
										<td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
										<td>
											<a href="javascript:void()" data-toggle="modal" class="fw-500"
												data-target="#detailOrder{{$order->id}}">
												<i class="far fa-info-circle mr-1"></i>{{$order->customer->name}}
											</a>
										</td>
										<td>{{$order->product->currency}} {{$order->product->symbol}}</td>
										<td class="text-right">{{ number_format($order->amount, 2, '.', ',') }}</td>
										<td class="text-right">{{ number_format($order->buy, 2, '.', ',') }}</td>
										<td class="text-right">{{ number_format($order->sell, 2, '.', ',') }}</td>
										<td class="text-right">{{ number_format($order->charges, 2, '.', ',') }}</td>
										<td width="5%" class="text-center">
											<button class="btn btn-toolbar-master" data-toggle="dropdown">
												<i class="fas fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-right">
												<form action="{{route('admin.order.delete', $order->id)}}" method="post">
													@csrf
													@method('delete')

													<a href="{{route('admin.customer.transactions', $order->customer_id)}}" class="dropdown-item fw-500">
														<i class="far fa-history mr-1"></i>Transaction History
													</a>
													<a href="javascript:void()" data-toggle="modal" class="dropdown-item fw-500"
														data-target="#editOrder{{$order->id}}">
														<i class="fal fa-edit mr-1"></i>Edit
													</a>
													@can('user_management_access')
													<button class="dropdown-item" type="submit">
														<i class="fal fa-trash mr-1"></i>Delete
													</button>
													@endcan
												</form>
											</div>
										</td>
										@include('admin.order.modalEdit')
										@include('admin.order.orderDetail')
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('admin.order.modalCreate')
@endsection

{{-- script section --}}
@section('scripts')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
@parent
<script>
	$(document).ready(function() {
		// Get today's date
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0');
		var yyyy = today.getFullYear();
		var currentDate = dd + '/' + mm + '/' + yyyy;

		// Initialize the datatable
		var table = $('#dataPayment').DataTable({
			responsive: true,
			lengthChange: false,
			dom:
				"<'row justify-content-between align-items-center'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'<'select'>>>" +
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
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
					text: '<i class="fa fa-cash-register"></i>',
					titleAttr: 'Add New Order',
					className: 'btn btn-info btn-sm btn-icon ml-2',
					action: function(e, dt, node, config) {
						$('#modalCreate').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			]
		});

		// Create the "Date" select element and add the datepicker
		var selectDate = $('<input>')
			.attr('id', 'selectdataPaymentDate')
			.addClass('form-control form-control-sm col-3 mr-2 datepicker')
			.on('change', function() {
				var date = $(this).val();
				table.column(0).search(date ? '^' + date + '$' : '', true, false).draw();
			});

		// Set the current date as the initial value for the date filter
		selectDate.val(currentDate);

		// Create the "Status" select element and add the options
		var selectStatus = $('<select>')
			.attr('id', 'selectdataPaymentStatus')
			.addClass('custom-select custom-select-sm col-3 mr-2')
			.on('change', function() {
				var status = $(this).val();
				table.column(5).search(status).draw();
			});

		$('<option>').val('').text('All Status').appendTo(selectStatus);
		$('<option>').val('1').text('Checked').appendTo(selectStatus);
		$('<option>').val('0').text('Unchecked').appendTo(selectStatus);

		// Add the select elements before the first datatable button in the second table
		$('#dataPayment_wrapper .dt-buttons').before(selectDate);

		// Initialize the datepicker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		});

		// Set the table to display today's data on load
		selectDate.trigger('change');
	});
</script>
@endsection
