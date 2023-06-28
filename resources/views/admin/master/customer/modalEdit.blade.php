
<div class="modal fade" id="editCustomer{{$customer->id}}" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
					Edit Customer
					<small class="m-0 text-muted">
						Edit current customer.
					</small>
				</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fal fa-times"></i></span>
				</button>
			</div>
			<form action="{{route('admin.master.customer.update', $customer->id)}}" method="post">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="form-group">
						<label for="">Customer Name</label>
						<input type="text" name="name" id="name" class="form-control" placeholder="currency symbol" aria-describedby="helpId" value="{{old('name', $customer->name)}}">
						<small id="helpId" class="text-muted">Customer Name</small>
					</div>
					<div class="form-group">
						<label for="">Phone Contact</label>
						<input type="text" name="mobile_phone" id="mobile_phone" class="form-control" placeholder="currency symbol" aria-describedby="helpId" value="{{old('mobile_phone', $customer->mobile_phone)}}">
						<small id="helpId" class="text-muted">Phone contact number</small>
					</div>
					<div class="form-group">
						<label for="">Customer Notes</label>
						<textarea class="form-control" id="notes" name="notes" rows="4">{{old('notes', $customer->notes)}}</textarea>
						<small id="helpId" class="text-muted">Notes for this customer</small>
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
