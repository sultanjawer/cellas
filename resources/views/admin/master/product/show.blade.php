@extends('layouts.admin')
@section('content')
	@include('partials.subheader')
	@include('partials.sysalert')

	<div class="row">
		<div class="col-12">
			<div class="panel p-1" id="productsPanel">
				<div class="panel-container row d-flex">
					<div class="col-md-4 mb-2">
						<div class="panel-content">
							<div class="card">
								<img src="{{ asset('storage/uploads/products/'.$product->attachments) }}" class="card-img-top cover" alt="cover" style="max-width: 100%;">
								<div class="card-body">
									<h5 class="card-title fw-700">{{$product->product_name}}</h5>
									<p class="card-text">{{$product->description}} Some quick example text to build on the card title and make up the bulk of the card's content.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="panel-content">
							<div class="form-group">
								<label for="product_code" class="col-form-label">Product Code</label>
								<input type="text" class="form-control fw-700" id="product_code" name="product_code" placeholder="Product Code" value="{{old('product_code', $product->product_code)}}" readonly>
							</div>
							<div class="form-group">
								<label for="product_name" class="col-form-label">Product Name</label>
								<input type="text" class="form-control fw-700" id="product_name" name="product_name" placeholder="Product Name" value="{{old('product_name', $product->product_name)}}" readonly>
							</div>
							<div class="form-group">
								<label for="category" class="col-form-label">Product Category</label>
								<input type="text" class="form-control fw-700" id="category" name="category" placeholder="Product Category" value="{{old('category', $product->category)}}" readonly>
							</div>
							<div class="form-group">
								<label for="quantity_per_unit" class="col-form-label">Quantity per Unit</label>
								<input type="number" class="form-control fw-700" id="quantity_per_unit" name="quantity_per_unit" placeholder="Quantity per unit" value="{{old('quantity_per_unit', $product->quantity_per_unit)}}" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="d-flex justify-content-between align-itmes-center">
						<div></div>
						<div>
							<a href="{{route('admin.master.products.index')}}" class="btn btn-primary btn-sm" role="button">
								<i class="fal fa-undo"></i>
								Back
							</a>
							<a href="{{route('admin.master.product.edit', $product->id)}}" class="btn btn-warning btn-sm" role="button">
								<i class="fal fa-edit"></i>
								Edit
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

{{-- script section --}}
@section('scripts')
@parent

@endsection
