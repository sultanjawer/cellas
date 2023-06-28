@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col-12">
			<div class="panel p-1" id="productsPanel">
				<form action="{{route('admin.master.product.update', $product->id)}}" method="post" enctype="multipart/form-data">
					@csrf
					@method('put')
					<div class="panel-container row d-flex">
						<div class="col-md-8 mb-2">
							<div class="panel-content">
								<div class="form-group row">
									<label for="product_code" class="col-sm-2 col-form-label">Product Code <span class="text-danger">*</span></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="product_code" name="product_code" placeholder="Product Code" value="{{old('product_code', $product->product_code)}}" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="product_name" class="col-sm-2 col-form-label">Product Name <span class="text-danger">*</span></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name" value="{{old('product_name', $product->product_name)}}" required>
									</div>
								</div>
								<div class="form-group row">
									<label for="category" class="col-sm-2 col-form-label">Product Category</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="category" name="category" placeholder="Product Category" value="{{old('category', $product->category)}}">
									</div>
								</div>
								<div class="form-group row">
									<label for="quantity_per_unit" class="col-sm-2 col-form-label">Quantity per Unit <span class="text-danger">*</span></label>
									<div class="col-sm-10">
										<input type="number" class="form-control" id="quantity_per_unit" name="quantity_per_unit" placeholder="Quantity per unit" value="{{old('quantity_per_unit', $product->quantity_per_unit)}}" required>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel-content">
								<div class="form-group">
									<label class="form-label" for="attachments">Product Image</label>
									<div class="input-group">
										<div class="custom-file">
											<input type="file" class="form-control" id="attachments" name="attachments" aria-describedby="attachments" value="{{old('attachments', $product->attachments)}}">
										</div>
									</div>
									<span class="help-block">Image for this product.</span>
								</div>
								<div class="form-group">
									<label for="exampleFormControlTextarea1">Description</label>
									<textarea class="form-control" id="description" name="description" rows="4">{{$product->description}}</textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<div class="d-flex justify-content-between align-itmes-center">
							<div><span class="text-danger">*</span> Required</div>
							<div>
								<a href="{{route('admin.master.products.index')}}" class="btn btn-warning btn-sm" role="button">
									<i class="fal fa-undo"></i>
									Cancel
								</a>
								<button class="btn btn-primary btn-sm" role="button" type="submit">
									<i class="fal fa-save"></i>
									Save
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

{{-- script section --}}
@section('scripts')
@parent

@endsection
