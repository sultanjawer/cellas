<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Products';
		$page_title = 'Product List';
		$page_heading = 'List of Products';
		$heading_class = 'fal fa-bags-shopping';
		$page_subtitle = 'Product List';
		$page_desc = 'List of product';

		$products = Product::all();

		return view('admin.master.product.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'products', 'page_subtitle', 'page_desc'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module_name = 'Products';
		$page_title = 'New Product';
		$page_heading = 'New Product';
		$heading_class = 'fal fa-bags-shopping';
		$page_subtitle = 'Add new Product';
		$page_desc = 'Add or create new product';

		return view('admin.master.product.create', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'page_subtitle', 'page_desc'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$product = new Product();
		$product->symbol = $request->input('symbol');
		$product->currency = $request->input('currency');

		// dd($product);
		$product->save();
		return redirect()->route('admin.master.products.index')->with('success', 'Data successfully saved');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($product_id)
	{
		$module_name = 'Products';
		$page_title = 'Product Detail';
		$page_heading = 'Detail of Product';
		$heading_class = 'fal fa-bags-shopping';
		$page_subtitle = 'Product Detail';
		$page_desc = 'Detail of product';

		$product = Product::find($product_id);

		return view('admin.master.product.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'product', 'page_subtitle', 'page_desc'));
	}

	/**\
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($product_id)
	{
		$module_name = 'Products';
		$page_title = 'Edit Product';
		$page_heading = 'Edit Product';
		$heading_class = 'fal fa-bags-shopping';

		$product = Product::find($product_id);

		return view('admin.master.product.edit', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$product = Product::find($id);

		// $product->supplier_d = $request->input('supplier_id');
		// $product->product_id = $request->input('product_id');
		$product->symbol = $request->input('symbol');
		$product->currency = $request->input('currency');

		$product->save();

		return redirect()->route('admin.master.products.index')->with('success', 'Data successfully saved');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$product = Product::find($id);
		$product->delete();
		return redirect()->route('admin.master.products.index')->with('success', 'Data successfully removed');
	}
}
