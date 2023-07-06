@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@can('payment_create')
		<div class="row">
			<div class="col-12">
				<div class="panel p-1" id="productsPanel">
					<form id="bulk-store-form" action="" method="POST">
						@csrf
						<div class="panel-container">
							<div class="panel-content">
								<div class="row d-flex justify-content-between">
									<div class="form-group col-md-6">
										<label for="">Customer <span class="text-danger">*</span></label>
										<select class="form-control custom-select" name="customer_id" id="customer_id" required>
											@foreach($customers as $customer)
												<option value="{{$customer->id}}">{{$customer->name}}</option>
											@endforeach
										</select>
										<small id="helpId" class="text-muted">Select Customer</small>
									</div>
									<div class="form-group col-md-6">
										<label for="">Slip Date <span class="text-danger">*</span></label>
										<input class="form-control " type="date" id="slip_date" name="slip_date" required>
										<small id="helpId" class="text-muted">Pick Date</small>
									</div>
								</div>
							</div>
							<span class="col help-block"><span class="text-info">* </span>You can add rows as much as you need in table below.</span>
							<hr>
							<div class="panel-content">
								<div class="table table-responsive">
									<table class="table-bordered table-hover table-striped table-sm w-100" id="items_payment">
										<thead class="thead-themed">
											<th>Amount</th>
											<th>Company</th>
											<th>Bank</th>
											<th>Action</th>
										</thead>
										<tbody>
											<tr>
												<td><input class="form-control form-control-sm" type="number" step="0.01" name="amount[]" required></td>
												<td>
													<select name="company_id[]" class="company_id form-control form-control-sm " required>
														@foreach ($companies as $company)
															<option value="{{$company->id}}">{{$company->company_name}}</option>
														@endforeach
													</select>
												</td>
												<td>
													<select name="bank_id[]" class="bank_id form-control form-control-sm" required>
														@foreach ($banks as $bank)
															<option value="{{$bank->id}}">{{$bank->bank_name}}, {{$bank->account}}</option>
														@endforeach
													</select>
												</td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="card-footer d-flex justify-content-between">
							<div class="text-right">
							</div>
							<div >
								<button class="btn btn-info btn-sm" type="button" id="add_row">Add Row</button>
								<button class="btn btn-outline-danger btn-sm" type="submit"><i class="fal fa-save mr-1"></i> Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endcan
@endsection

{{-- script section --}}
@section('scripts')
@parent
<script>
    $(document).ready(function() {
		$("#customer_id").select2({
			placeholder: "-- Select Customer --",
		});
        $('#add_row').click(function() {
            var html = '<tr>' +
                '<td><input class="form-control form-control-sm" type="number" step="0.01" name="amount[]"></td>' +
                '<td>' +
                '<select name="company_id[]" class="company_id form-control form-control-sm">' +
                '@foreach ($companies as $company)' +
                '<option value="{{$company->id}}">{{$company->company_name}}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select name="bank_id[]" class="bank_id form-control form-control-sm">' +
                '@foreach ($banks as $bank)' +
                '<option value="{{$bank->id}}">{{$bank->bank_name}}, {{$bank->account}}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td class="text-center"><button type="button" title="Remove this row" class="btn btn-icon btn-danger btn-xs remove-row"><i class="fa fa-times-circle"></i></button></td>' +
                '</tr>';

            $('#items_payment tbody').append(html);
        });

        // Event delegation to handle remove button clicks
        $('#items_payment').on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>



{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
<script>
    $(document).ready(function() {
        $('#bulk-store-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize the form data

            // Send an Ajax request to the server
            $.ajax({
                url: '{{ route("admin.payment.bulkstore") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Handle the success response
                    alert('Payments saved successfully!');
                    // Perform any other desired actions, such as updating the UI
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    alert('Failed to save payments.');
                    // Perform any other desired error handling actions
                }
            });
        });
    });
</script>
@endsection
