@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col">
			<div class="panel" id="productsPanel">
				<div class="panel-container card-header">
					<div class="row d-flex justify-content-between">
						<div class="col-md-4">
							<div class="form-group">
								<label for="" class="col-form-label form-label">Start Date</label>
								<div class="input-daterange input-group" id="datepicker-5">
									<input type="text" class="form-control datepicker dataFilter" id="start_date" name="start_date" value="{{ $endDate ?? now()->format('d/m/Y') }}">
								</div>
								<label for="" class="help-block">Select Date to display the report by date.</label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="" class="col-form-label form-label">End Date</label>
								<div class="input-daterange input-group" id="datepicker-5">
									<input type="text" class="form-control datepicker dataFilter" id="end_date" name="end_date" value="{{ $endDate ?? now()->format('d/m/Y') }}">
								</div>
								<label for="" class="help-block">Select Date to display the report by date.</label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="" class="col-form-label form-label">Bank</label>
									<select name="filter_bank" id="filter_bank" class="mr-2 form-control dataFilter">
										<option value="">All Bank</option>
										@foreach ($banks as $bank)
											<option value="{{$bank->id}}">{{$bank->name}}</option>
										@endforeach
									</select>
								<label for="" class="help-block">Profits made for selected date.</label>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="" class="col-form-label form-label">Total Buy</label>
								<input type="text" class="fw-500 form-control text-right" id="total_buy" name="total_buy" placeholder="amount * buy + charges">
								<label for="" class="help-block">Profits made for selected date.</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="" class="col-form-label form-label">Profits</label>
								<input type="text" class="fw-500 text-right form-control" id="totalProfit">
								<label for="" class="help-block">Profits made for selected date.</label>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-hover table-sm table-striped table-bordered w-100" id="ordersTable">
							<thead>
								<th>Date</th>
								<th>Customer Name</th>
								<th>Amount</th>
								<th>Buy</th>
								<th>Sell</th>
								<th>Charges</th>
								<th>Total Buy</th>
								<th>Total Sell</th>
								<th>Profit</th>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
@parent
<script>
	$(document).ready(function() {
		var ordersTable = $('#ordersTable').DataTable({
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
					className: 'btn-outline-danger btn-xs btn-icon mr-1'
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i>',
					titleAttr: 'Generate Excel',
					className: 'btn-outline-success btn-xs btn-icon mr-1'
				},
				{
					extend: 'csvHtml5',
					text: '<i class="fal fa-file-csv"></i>',
					titleAttr: 'Generate CSV',
					className: 'btn-outline-primary btn-xs btn-icon mr-1'
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
				}
			],
			columnDefs: [
				{ className: 'text-right', targets: [2, 3, 4,5,6,7,8] } // Add 'text-right' class to columns 2, 3, and 4
			]
		});

		// Datepicker initialization
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		});

		// Fetch and update orders data based on date range
		function updateOrdersTable(startDate, endDate, filterBank) {
			$.ajax({
			url: '{{ route("admin.report.orders.data") }}',
			type: 'GET',
			data: {
				start_date: startDate,
				end_date: endDate,
				filter_bank: filterBank
			},
			success: function(response) {
				var formattedTotalProfits = response.total_profits.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
				var formattedTotalBuy = response.total_buy.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
				$('#totalProfit').val(formattedTotalProfits);
				$('#total_buy').val(formattedTotalBuy);
				ordersTable.clear().draw();
				if (response.orders.length > 0) {
					$.each(response.orders, function(index, order) {
						var date = new Date(order.created_at);
    					var formattedDate = date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' }); // Change the locale and format options as needed
						var customerName = order.customer.name;
						var product = order.product.symbol;
						var orderAmount = parseFloat(order.amount);
						var orderBuy = parseFloat(order.buy);
						var orderSell = parseFloat(order.sell);
						var charges = parseFloat(order.charges) || 0;
						var totalSell = parseFloat(order.amount) * parseFloat(order.sell) + parseFloat(charges);
						var totalBuy = parseFloat(order.amount) * parseFloat(order.buy) + parseFloat(charges);
						var profitRow = totalSell - totalBuy;
						ordersTable.row.add([
							formattedDate,
							customerName,
							product + ' ' + orderAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }), // Format the amount
							'Rp '+ orderBuy.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
							'Rp '+ orderSell.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
							'Rp ' + charges.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
							'Rp ' + totalBuy.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
							'Rp ' + totalSell.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
							'Rp ' + profitRow.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
						]);
					});
				}

				ordersTable.draw(); // Draw the table after adding the rows
			}
			});
		};

		// Trigger initial data update on page load
		var startDate = $('#start_date').val();
		var endDate = $('#end_date').val();
		var filterBank = $('#filter_bank').val();
		updateOrdersTable(startDate, endDate, filterBank);

		// Fetch and update orders data when the date range changes
		$('.dataFilter').on('change', function() {
			var startDate = $('#start_date').val();
			var endDate = $('#end_date').val();
			var filterBank = $('#filter_bank').val();
			// Convert empty string value to null for proper filtering
			if (filterBank === '') {
				filterBank = null;
			}
			updateOrdersTable(startDate, endDate, filterBank);
		});
	});
</script>
@endsection
