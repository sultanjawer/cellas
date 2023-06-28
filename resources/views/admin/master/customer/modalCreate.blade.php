<div class="modal fade" id="newCustomer" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					New Customer
					<small class="m-0 text-muted">
						Add new Customer.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.master.customer.store')}}" method="post">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label for="">Customer Name</label>
						<input type="text" name="name" id="name" class="form-control" placeholder="customer name" aria-describedby="helpId">
						<small id="helpId" class="text-muted">Customer Name</small>
					</div>
					<div class="form-group">
						<label for="">Phone Contact</label>
						<input type="text" name="mobile_phone" id="mobile_phone" class="form-control" placeholder="mobile phone number" aria-describedby="helpId">
						<small id="helpId" class="text-muted">Phone contact number</small>
					</div>
					<div class="form-group">
						<label for="">Customer Notes</label>
						<textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
						<small id="helpId" class="text-muted">Notes for this customer</small>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm waves-effect waves-themed" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary btn-sm waves-effect waves-themed">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
