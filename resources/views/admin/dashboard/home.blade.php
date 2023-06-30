@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')
	{{-- @include('partials.sysalert') --}}
	<div class="row d-flex justify-content-between mb-3">
		<div class="col-lg-4">
			<div class="form-group">
				<div class="input-group" id="">
					<select class="form-control form-control-sm" name="selectCustomer" id="selectCustomer">
						@foreach ($customers as $customer)
							<option value="{{$customer->id}}">{{$customer->name}}</option>
						@endforeach
					</select>
				</div>
				<label for="" class="help-block">Select Customer.</label>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="form-group row">
				<label for="" class="col-form-label form-label col-md-3 text-left">Report Date</label>
				<div class="col-md-9">
					<div class="input-daterange input-group" id="datepicker-5">
						<input type="text" class="form-control form-control-sm datepicker dateFilter" id="start_date" name="start_date" value="{{ $endDate ?? now()->format('d/m/Y') }}">
						<div class="input-group-append input-group-prepend">
							<span class="input-group-text"><i class="fal fa-ellipsis-h"></i></span>
						</div>
						<input type="text" class="form-control form-control-sm datepicker dateFilter" id="end_date" name="end_date" value="{{ $endDate ?? now()->format('d/m/Y') }}">
					</div>
					<label for="" class="help-block">Select Date to display the report by date.</label>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div id="new_request" class="p-3 bg-warning-500 rounded overflow-hidden position-relative text-white mb-g">
				<div class="">
					<h1 class="d-block l-h-n m-0 fw-500" data-toggle="tooltip" title data-original-title="Your Profits for selected periodes">
						<span id="Totalbalance"></span>
						<small class="m-0 l-h-n">Deposit Balance (until today)</small>
					</h1>
				</div>
				<i class="fal fa-chart-bar  position-absolute pos-right pos-bottom opacity-25 mb-n1 mr-n1" style="font-size:4rem"></i>
			</div>
		</div>
		<div class="col-md-6">
			<div id="new_request" class="p-3 bg-info-300 rounded overflow-hidden position-relative text-white mb-g">
				<div class="">
					<h1 class=" d-block l-h-n m-0 fw-500" data-toggle="tooltip" title data-original-title="Your Profits for selected periodes">
						<span id="totalSale"></span>
						<small class="m-0 l-h-n">Orders (Rupiah)</small>
					</h1>
				</div>
				<i class="fal fa-receipt fa-flip-horizontal position-absolute pos-right pos-bottom opacity-25 mb-n1 mr-n1" style="font-size:4rem"></i>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="panel" id="panel-1">
				<div class="panel-hdr">
					<h2>Orders Summary</h2>
					<div class="panel-toolbar">
					</div>
				</div>
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-hover table-sm table-striped table-bordered w-100" id="Tableorders">
							<thead>
								<th>Company</th>
								<th>Total Orders</th>
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
		$("#selectCustomer").select2({
			placeholder: "-- Select Customer --",
		});
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		});

		var debtCustomersTable = $('#debtCustomers').DataTable(getDataTableConfig([[1, 'desc']]));
		var ordersTable = $('#Tableorders').DataTable(getDataTableConfig([[1, 'asc']]));

		fetchInsightByDateRange($('#start_date').val(), $('#end_date').val());

		$('.dateFilter').on('change', function() {
			fetchInsightByDateRange($('#start_date').val(), $('#end_date').val());
		});

		function fetchInsightByDateRange(startDate, endDate) {
			var id = $('#selectCustomer').val(); // Get the selected ID value
			var url = '{{ route("admin.report.customerDashboard", ":id") }}';
			url = url.replace(':id', encodeURIComponent(id)); // Replace the placeholder with the ID
			$.get(url, { start_date: startDate, end_date: endDate })
			.done(function(data) {
				displayInsight(data);
			})
			.fail(function(error) {
				console.error('Error:', error);
			});
		}

		function displayInsight(data) {
			var formattedBalance = formatNumber(data.balance);
			var formattedOrders = formatNumber(data.totalSale);

			$('#Totalbalance').text(formattedBalance);
			$('#totalSale').text(formattedOrders);

			var ordersTable = $('#Tableorders').DataTable();
			ordersTable.clear().draw();

			$.each(data.customerOrders, function(companyId, totalOrders) {
				var row = $('<tr>');
					$('<td>').text(data.orders[companyId].company.company_name).appendTo(row);
				$('<td class="text-right">').text(formatNumber(totalOrders)).appendTo(row);
				ordersTable.row.add(row);
			});
			ordersTable.draw();
		}

		function formatNumber(number) {
			return parseFloat(number).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}

		function getDataTableConfig(order) {
			return {
			responsive: true,
			lengthChange: true,
			pageLength: 10,
			// order: order,
			dom:
				'<"row mb-3"<"col-sm-12 col-md-6 d-flex align-items-center justify-content-start"f><"col-sm-12 col-md-6 d-flex align-items-center justify-content-end"lB>>' +
				'<"row"<"col-sm-12"tr>>' +
				'<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
			buttons: [
				{
				extend: 'excelHtml5',
				text: '<i class="fa fa-file-excel"></i>',
				titleAttr: 'Generate Excel',
				className: 'btn-outline-success btn-xs btn-icon ml-3 mr-1'
				},
				{
				extend: 'print',
				text: '<i class="fa fa-print"></i>',
				titleAttr: 'Print Table',
				className: 'btn-outline-primary btn-xs btn-icon mr-1'
				}
			]
			};
		}
	});
</script>
@endsection
