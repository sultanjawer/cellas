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
								<th>Name</th>
								<th>Code</th>
								<th>Action</th>
							</thead>
							<tbody>
								@foreach ($companies as $company)
									<tr>
										<td><span class="pl-1">{{$company->company_name}}</span></td>
										<td><span class="pl-1">{{$company->company_code}}</span></td>
										<td width="5%" class="text-center">
											<button class="btn btn-toolbar-master" data-toggle="dropdown">
												<i class="fas fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" data-toggle="modal" class="dropdown-item fw-500"
												data-target="#modalEdit{{$company->id}}">
													<i class="fal fa-edit mr-1"></i>Edit/Show
												</a>
												<form action="{{route('admin.master.company.delete', $company->id)}}" method="post">
													@csrf
													@method('delete')
													<button class="dropdown-item" type="submit" onclick="return confirm('Are you sure you want to delete this data?')">
														<i class="fal fa-trash mr-1"></i>Delete
													</button>
												</form>
											</div>
										</td>
										@include('admin.master.company.modalEdit')
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('admin.master.company.modalCreate')

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
					text: '<i class="fa fa-plus"></i>',
					titleAttr: 'Add New Company',
					className: 'btn btn-info btn-sm btn-icon ml-2',
					action: function(e, dt, node, config) {
						$('#modalCreateCompany').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			]
		});
	});
</script>


@endsection
