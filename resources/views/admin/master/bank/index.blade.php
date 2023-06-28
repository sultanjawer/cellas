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
								<th>Bank</th>
								<th>Account No.</th>
								<th>Account Name</th>
								<th>Action</th>
							</thead>
							<tbody>
								@foreach ($banks as $bank)
									<tr>
										<td>{{$bank->bank_name}}</td>
										<td>{{$bank->account}}</td>
										<td>{{$bank->acc_name}}</td>
										<td width="5%" class="text-center">
											<button class="btn btn-toolbar-master" data-toggle="dropdown">
												<i class="fas fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" data-toggle="modal" class="dropdown-item fw-500"
												data-target="#editBank{{$bank->id}}">
													<i class="fal fa-edit mr-1"></i>Edit/Show
												</a>
												<form action="{{route('admin.master.bank.delete', $bank->id)}}" method="post">
													@csrf
													@method('delete')
													<button class="dropdown-item" type="submit" onclick="return confirm('Are you sure you want to delete this data?')">
														<i class="fal fa-trash mr-1"></i>Delete
													</button>
												</form>
											</div>
										</td>
										@include('admin.master.bank.modalEdit')
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('admin.master.bank.modalCreate')

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
			rowGroup:
				{
					dataSrc: 0
				},
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
					text: '<i class="fa fa-plus"></i>',
					titleAttr: 'Add New Product',
					className: 'btn btn-info btn-sm btn-icon ml-2',
					action: function(e, dt, node, config) {
						$('#newBank').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			]
		});
	});
</script>
@endsection
