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
						<table class="table table-hover table-sm table-striped table-bordered w-100" id="balanceTable">
							<thead>
								<th>Customer Name</th>
								<th>Balance</th>
								<th>Cash in</th>
								<th>Cash Out</th>
							</thead>
							<tbody>
								@foreach ($customers as $customer)
									<tr>
										<td>{{ $customer->name }}</td>
										<td class="text-right">
											@if($customer->result < 0)
											<span class="text-danger">{{ number_format($customer->result, 2, '.', ',') }}</span>
											@elseif(empty($customer->result))
											@else
											{{ number_format($customer->result, 2, '.', ',') }}
											@endif
										</td>
										<td class="text-right">{{ number_format($customer->cashIn, 2, '.', ',') }}</td>
										<td class="text-right">{{ number_format($customer->cashOut, 2, '.', ',') }}</td>
									</tr>
								@endforeach
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
		var table = $('#balanceTable').DataTable({
			responsive: true,
			order: [[1, 'asc']],
			dom:
			"<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
			],
		});

	});
</script>
@endsection
