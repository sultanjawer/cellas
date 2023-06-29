@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	{{-- @include('partials.sysalert') --}}
	@can('payment_access')
		<div class="row">
			<div class="col">
				<div class="panel" id="productsPanel">
					<div class="panel-container card-header collapse" id="filterCollapse">
						<div class="row d-flex justify-content-between" >
							<div class="col-md-3">
								<div class="form-group">
									<label for="" class="col-form-label form-label">Date Filter</label>
									<div class="input-daterange input-group" id="datepicker-5">
										<input type="text" class="form-control datepicker dataFilter" id="start_date" name="start_date" value="{{ now()->format('d/m/Y') }}">
									</div>
									<label for="" class="help-block">Select Date to display the report by date.</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="customerFilter" class="col-form-label form-label">Customer Filter</label>
									<select class="form-control custom-select dataFilter"
										name="customerFilter" id="customerFilter">
										<option value=""></option>
											<option value="all">All Customer</option>
										@foreach ($customers as $customer)
											<option value="{{$customer->id}}">
												{{$customer->id}} - {{$customer->name}}
											</option>
										@endforeach
									</select>
									<label for="" class="help-block">Select Date to display the report by date.</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="" class="col-form-label form-label">Company Filter</label>
										<select name="Company_Filter" id="Company_Filter" class="mr-2 form-control dataFilter">
											<option value=""></option>
											<option value="all">All Company</option>
											@foreach ($companies as $company)
												<option value="{{$company->id}}">{{$company->company_name}}</option>
											@endforeach
										</select>
									<label for="" class="help-block">Filter data by company.</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="" class="col-form-label form-label">Account Filter</label>
										<select name="Account_Filter" id="Account_Filter" class="mr-2 form-control dataFilter">
											<option value=""></option>
											<option value="all">All Account</option>
											@foreach ($banks as $bank)
												<option value="{{$bank->id}}">{{$bank->acc_name}}, {{$bank->account}}</option>
											@endforeach
										</select>
									<span class="help-block">filter the data based on the bank account to which the customer made transfers.</span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="" class="col-form-label form-label">Status Filter</label>
										<select name="filter_status" id="filter_status" class="mr-2 form-control dataFilter">
											<option value=""></option>
											<option value="all">All Status</option>
											<option value="unchecked">Unchecked</option>
											<option value="checked">Checked</option>
										</select>
									<label for="" class="help-block">Filter status.</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="" class="col-form-label form-label">Validation Filter</label>
										<select name="validation_Filter" id="validation_Filter" class="mr-2 form-control dataFilter">
											<option value=""></option>
											<option value="all">All State</option>
											<option value="unvalidated">Not Validate</option>
											<option value="validated">Validated</option>
										</select>
									<label for="" class="help-block">for customer payment validation.</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="" class="col-form-label form-label">Total Filtered</label>
									<input type="text" class="fw-500 form-control text-right" id="total_checked" name="total_checked" readonly>
									<label for="" class="help-block">Total value filtered.</label>
								</div>
							</div>
						</div>
					</div>
					<div class="panel-container">
						<div class="panel-content">
							<table class="table table-hover table-sm table-striped table-bordered w-100" id="paymentsTable">
								<thead>
									<th>Validation</th>
									<th>Status</th>
									<th>Customer Name</th>
									<th>Slip date</th>
									<th>Amount</th>
									<th>Date</th>
									<th></th>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endcan
	@can('payment_create')
		@include('admin.payment.modalCreate')
	@endcan
@endsection

@section('scripts')
@parent
<script>
	$(document).ready(function() {
		var paymentsTable = $('#paymentsTable').DataTable({
			responsive: true,
			lengthChange: false,
			dom:
			"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
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
					className: 'btn-outline-primary btn-xs btn-icon mr-1'
				},
				{
					text: '<i class="fas fa-filter"></i>',
					titleAttr: 'Show Filter',
					className: 'btn btn-outline-warning btn-sm btn-icon ml-2',
					action: function (e, dt, node, config) {
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
					titleAttr: 'Add New Payment',
					className: 'btn btn-info btn-sm btn-icon ',
					action: function(e, dt, node, config) {
						$('#modalCreatePayment').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			],
			columnDefs: [
				{ className: 'text-right', targets: [4] },
				{ className: 'text-center', targets: [0, 1, 6] },
			]
		});

		// Datepicker initialization
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		});

		// Fetch and update orders data based on date range
		function updatepaymentsTable(startDate, filterCustomer,filterCompany, filterAccount,filterStatus, filterValid) {
			$.ajax({
			url: '{{ route("admin.report.deposits.data") }}',
			type: 'GET',
			data: {
				start_date: startDate,
				customerFilter: filterCustomer,
				Company_Filter: filterCompany,
				Account_Filter: filterAccount,
				filter_status: filterStatus,
				validation_Filter: filterValid,
			},
			success: function(response) {
				var formattedTotalChecked = response.total_filtered.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
				$('#total_checked').val(formattedTotalChecked);
				paymentsTable.clear().draw();
				if (response.payments.length > 0) {
					$.each(response.payments, function(index, payment) {
						var date = new Date(payment.created_at);
						var formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' }); // Change the locale and format options as needed

						var slipDate = payment.slip_date;
						var formattedSlipDate = slipDate.split('-').reverse().join('/');

						var customerName = '<a href="{{ route("admin.customer.transactions", ["id" => ":customerId"]) }}">'+payment.customer.name+'</a>';
						customerName = customerName.replace(':customerId', payment.customer_id);
						var amount = payment.amount;
						var formatter = new Intl.NumberFormat('en-GB', {
							style: 'decimal',
							minimumFractionDigits: 2,
							maximumFractionDigits: 2,
						});
						var formattedAmount = formatter.format(amount);
						var checkStatus = payment.status;
						var validBtn = `
							<form action="{{ route("admin.payment.setValidation", ":paymentId") }}" method="post">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="_method" value="put">
								<button type="submit" class="btn btn-xs btn-icon ${payment.validation === 'validated' ? 'btn-success' : 'btn-warning'}">
									<i class="fa ${payment.validation === 'validated' ? 'fa-check-double' : 'fa-upload'}"></i>
								</button>
								<span hidden>${payment.validation}</span>
							</form>
						`;
						var formHtml = `
							<form action="{{ route("admin.payment.setStatus", ":paymentId") }}" method="post">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="_method" value="put">
								<button type="submit" class="btn btn-xs btn-icon ${payment.status === 'checked' ? 'btn-success' : 'btn-default'}">
									<i class="fal ${payment.status === 'checked' ? 'fa-check' : 'fa-upload'}"></i>
								</button>
								<span hidden>${payment.status}</span>
							</form>
						`;
						var editDeleteButtons = `
							<div class="dropdown">
								<a href="javascript:void(0)" data-toggle="dropdown">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<form action="{{ route('admin.payment.delete', ['id' => 'paymentId']) }}" method="post">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="_method" value="delete">
										<a href="{{ route('admin.payment.edit', ['id' => ':paymentId']) }}" class="dropdown-item fw-500">
											<i class="fal fa-edit mr-1"></i>Edit
										</a>
										<button class="dropdown-item" type="submit">
											<i class="fal fa-trash mr-1"></i>Delete
										</button>
									</form>
								</div>
							</div>
						`;

						editDeleteButtons = editDeleteButtons.replace(/paymentId/g, payment.id);
						validBtn = validBtn.replace(/:paymentId/g, payment.id);
						formHtml = formHtml.replace(/:paymentId/g, payment.id);

						paymentsTable.row.add([
							validBtn,
							formHtml,
							customerName,
							formattedSlipDate,
							formattedAmount,
							formattedDate,
							editDeleteButtons,
						]);
					});
				}
				paymentsTable.draw(); // Draw the table after adding the rows
			}
			});
		};

		// Trigger initial data update on page load
		var startDate = $('#start_date').val();
		var filterCompany = $('#Company_Filter').val();
		var filterCustomer = $('#customerFilter').val();
		var filterAccount = $('#Account_Filter').val();
		var filterStatus = $('#filter_status').val();
		var filterValid = $('#validation_Filter').val();
		updatepaymentsTable(startDate, filterCustomer,filterCompany, filterAccount, filterStatus, filterValid);

		// Fetch and update status data when the datafilter changes
		$('.dataFilter').on('change', function() {
			var startDate = $('#start_date').val();
			var filterCustomer = $('#customerFilter').val();
			var filterCompany = $('#Company_Filter').val();
			var filterAccount = $('#Account_Filter').val();
			var filterStatus = $('#filter_status').val();
			var filterValid = $('#validation_Filter').val();
			if (filterCustomer === 'all') {
				filterCustomer = '';
			}
			if (filterAccount === 'all') {
				filterAccount = '';
			}
			if (filterCompany === 'all') {
				filterCompany = '';
			}
			if (filterStatus === 'all') {
				filterStatus = '';
			}
			if (filterValid === 'all') {
				filterValid = '';
			}
			updatepaymentsTable(startDate, filterCustomer,filterCompany, filterAccount, filterStatus, filterValid);
		});
	});
</script>
<script>
	$(document).ready(function() {
		$("#Company_Filter").select2({
			placeholder: "-- Select Company --",
		});
		$("#customerFilter").select2({
			placeholder: "-- Select Customer --",
		});
		$("#Account_Filter").select2({
			placeholder: "-- Select Account --",
		});
		$("#filter_status").select2({
			placeholder: "-- Select Status --",
		});
		$("#validation_Filter").select2({
			placeholder: "-- Select State --",
		});
	});
</script>
@endsection
