@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')
	@can('order_access')
		<div class="row">
			<div class="col">
				<div class="panel" id="productsPanel">
					<div class="panel-container card-header collapse" id="filterCollapse">
						<div class="row d-flex justify-content-between" >
							<div class="form-group col-md-3">
								<label for="start_date">Date Filter</label>
								<div class="input-daterange input-group" id="datepicker-5">
									<input type="text" class="form-control datepicker dataFilter" id="start_date" name="start_date" value="{{ now()->format('d/m/Y') }}">
								</div>
							</div>
							<div class="form-group col-md-3">
								<label for="Company_Filter">Company Filter</label>
								<select name="Company_Filter" id="Company_Filter" class="mr-2 form-control dataFilter">
									<option value=""></option>
									<option value="all">All Company</option>
									@foreach ($companies as $company)
										<option value="{{$company->id}}">{{$company->company_name}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="customerFilter">Customer Filter</label>
								<select class="form-control custom-select dataFilter"
									name="customerFilter" id="customerFilter">
									<option ></option>
									<option value="all">All Customers</option>
									@foreach ($customers as $customer)
										<option value="{{$customer->id}}">
											{{$customer->id}} - {{$customer->name}}
										</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="total_filtered">Total Value Filter</label>
								<input type="text" class="fw-500 form-control text-right dataFilter" id="total_filtered" name="total_filtered">
							</div>
						</div>
					</div>
					<div class="panel-container">
						<div class="panel-content">
							<table class="table table-hover table-sm table-striped table-bordered w-100" id="dataOrder">
								<thead>
									<th>CustName</th>
									<th>Date</th>
									<th>Product</th>
									<th>Amt</th>
									<th>Buh</th>
									<th>Sell</th>
									<th>PFA</th>
									<th>CFA</th>
									<th>PCh</th>
									<th>CCh</th>
									<th></th>
									{{-- <th>Product</th>
									<th>Amount</th>
									<th>Buy</th>
									<th>Sell</th>
									<th>Charges</th>
									<th></th> --}}
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		@can('order_create')
			@include('admin.order.modalCreate')
		@endcan
	@endcan
@endsection

{{-- script section --}}
@section('scripts')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
@parent
<script>
	$(document).ready(function() {
		$("#customerFilter").select2({
			placeholder: "-- Select Customer --",
		});
		$("#Company_Filter").select2({
			placeholder: "-- Select Company --",
		});
	});
</script>
<script>
	$(document).ready(function() {
		// Get today's date
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0');
		var yyyy = today.getFullYear();
		var currentDate = dd + '/' + mm + '/' + yyyy;

		// Initialize the datepicker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		});

		// Initialize the datatable
		var dataOrder = $('#dataOrder').DataTable({
			responsive: true,
			lengthChange: false,
			dom:
				"<'row justify-content-between align-items-center'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'<'select'>>>" +
				"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			buttons: [
				{
					extend: 'colvis',
					text: '<i class="fa fa-columns"></i>',
					titleAttr: 'Col visibility',
					className: 'btn-outline-danger btn-sm'
				},
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
					text: '<i class="fas fa-filter"></i>',
					titleAttr: 'Show Filter',
					className: 'btn btn-outline-warning btn-sm btn-icon ml-2',
					action: function(e, dt, node, config) {
						var $collapse = $('#filterCollapse');

						if ($collapse.hasClass('show')) {
							$collapse.collapse('hide');
						} else {
							$collapse.collapse('show');
						}
					}
				},
				{
					text: '<i class="fa fa-cash-register"></i>',
					titleAttr: 'Add New Order',
					className: 'btn btn-info btn-sm btn-icon',
					action: function(e, dt, node, config) {
						$('#modalOrderCreate').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			]
		});

		// Fetch and update orders data based on dataFilter
		function updateDataOrderTable(startDate, filterCompany, filterCustomer) {
			$.ajax({
				url: '{{ route("admin.report.orders.customerOrderByDate") }}',
				type: 'GET',
				data: {
					start_date: startDate,
					Company_Filter: filterCompany,
					customerFilter: filterCustomer,
				},
				success: function(response) {
					var formattedTotalFilter = response.total_filtered.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
					$('#total_filtered').val(formattedTotalFilter);
					dataOrder.clear().draw();
					if (response.orders.length > 0) {
						$.each(response.orders, function(index, order) {
							var date = new Date(order.created_at);
							var formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
							var customerName = '<a href="{{ route("admin.order.show", ["id" => ":customerId"]) }}">'+order.customer.name+'</a>';
							customerName = customerName.replace(':customerId', order.id);

							var productSymbol = order.product.symbol;
							var amount = order.amount;
							var purchasePrice = order.buy;
							var sellPrice = order.sell;
							var pfa = order.pfa;
							var cfa = order.pfa;
							var pcharges = order.pcharges;
							var ccharges = order.ccharges;
							var editDeleteButtons = `
							<div class="dropdown">
								<a href="javascript:void(0)" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<form action="{{ route('admin.order.delete', ['id' => 'orderId']) }}" method="post">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="_method" value="delete">
										<a href="{{ route('admin.customer.transactions', ['id' => 'customerId']) }}" class="dropdown-item fw-500">
											<i class="far fa-history mr-1"></i>Transaction Histroy
										</a>
										<a href="{{ route('admin.order.edit', ['id' => 'orderId']) }}" class="dropdown-item fw-500">
											<i class="fal fa-edit mr-1"></i>Edit
										</a>
										<button class="dropdown-item" type="submit">
											<i class="fal fa-trash mr-1"></i>Delete
										</button>
									</form>
								</div>
							</div>
						`;

						editDeleteButtons = editDeleteButtons.replace(/customerId/g, order.customer_id).replace(/orderId/g, order.id);
							dataOrder.row.add([
								customerName,
								formattedDate,
								productSymbol,
								amount,
								purchasePrice,
								sellPrice,
								pfa,
								cfa,
								pcharges,
								ccharges,
								editDeleteButtons,
							]);
						});
					}
					dataOrder.draw(); // Draw the table after adding the rows
				},

				error: function(xhr, status, error) {
					// Handle error if necessary
				}
			});
		}

		// Listen for changes in the filter inputs
		$('.dataFilter').on('change', function() {
			var startDate = $('#start_date').val();
			var filterCompany = $('#Company_Filter').val();
			var filterCustomer = $('#customerFilter').val();
			if (filterCompany === 'all') {
				filterCompany = '';
			}
			if (filterCustomer === 'all') {
				filterCustomer = '';
			}
			updateDataOrderTable(startDate, filterCompany,filterCustomer);
		});

		// Initial data load
		var startDate = $('#start_date').val();
		var filterCompany = $('#Company_Filter').val();
		var filterCustomer = $('#customerFilter').val();
		updateDataOrderTable(startDate, filterCompany, filterCustomer);
	});
</script>
@endsection
