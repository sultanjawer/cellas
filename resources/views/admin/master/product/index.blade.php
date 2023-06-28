@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col">
			<div class="panel" id="productsPanel">
				<div class="panel-container">
					<div class="panel-content">
						<table class="table table-hover table-striped table-bordered w-100" id="productTable">
							<thead>
								<th>Symbol</th>
								<th>Name</th>
								<th>Action</th>
							</thead>
							<tbody>
								@foreach ($products as $product)
									<tr>
										<td>{{$product->symbol}}</td>
										<td>{{$product->currency}}</td>
										<td width="5%" class="text-center">
											<button class="btn btn-toolbar-master" data-toggle="dropdown">
												<i class="fas fa-ellipsis-v"></i>
											</button>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="javascript:void()" data-toggle="modal" class="dropdown-item fw-500"
												data-target="#editProduct{{$product->id}}">
													<i class="fal fa-edit mr-1"></i>Edit/Show
												</a>
												<form action="{{route('admin.master.product.delete', $product->id)}}" method="post">
													@csrf
													@method('delete')
													<button href="" class="dropdown-item" type="submit">
														<i class="fal fa-trash mr-1"></i>Delete
													</button>
												</form>
											</div>
										</td>
										<div class="modal fade" id="editProduct{{$product->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title">
															Edit Currency
															<small class="m-0 text-muted">
																Edit currency for trades.
															</small>
														</h4>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true"><i class="fal fa-times"></i></span>
														</button>
													</div>
													<form action="{{route('admin.master.product.update', $product->id)}}" method="post">
														@csrf
														@method('put')
														<div class="modal-body">
															<div class="form-group">
															<label for="">Symbol</label>
															<input type="text" name="symbol" id="symbol" class="form-control" placeholder="currency symbol" aria-describedby="helpId" value="{{old('symbol', $product->symbol)}}">
															<small id="helpId" class="text-muted">currency symbol</small>
															</div>
															<div class="form-group">
																<label for="">Currency name</label>
																<input type="text" name="currency" id="currency" class="form-control" placeholder="currency name" aria-describedby="helpId" value="{{old('currency', $product->currency)}}">
																<small id="helpId" class="text-muted">currency name</small>
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
	{{-- modal create --}}

	<div class="modal fade" id="newProduct" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						New Currency
						<small class="m-0 text-muted">
							Add currency for trades.
						</small>
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fal fa-times"></i></span>
					</button>
				</div>
				<form action="{{route('admin.master.product.store')}}" method="post">
					@csrf
					<div class="modal-body">
						<div class="form-group">
						<label for="">Symbol</label>
						<input type="text" name="symbol" id="symbol" class="form-control" placeholder="currency symbol" aria-describedby="helpId">
						<small id="helpId" class="text-muted">currency symbol</small>
						</div>
						<div class="form-group">
							<label for="">Currency name</label>
							<input type="text" name="currency" id="currency" class="form-control" placeholder="currency name" aria-describedby="helpId">
							<small id="helpId" class="text-muted">currency name</small>
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
		$('#productTable').dataTable(
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
					titleAttr: 'Add New Product',
					className: 'btn btn-info btn-sm btn-icon ml-2',
					action: function(e, dt, node, config) {
						$('#newProduct').modal('show'); // Replace #myModal with the ID of your modal element
					}
				}
			]
		});
	});
</script>
@endsection
