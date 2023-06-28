<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use App\Models\Order;
use App\Models\Product;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Payment;

class OrderController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Orders';
		$page_title = 'Customer';
		$page_subtitle = 'Orders';
		$page_heading = 'Customer Orders';
		$heading_class = 'fal fa-receipt';
		$page_desc = 'List of Customer Orders';

		$orders = Order::orderByDesc('created_at')->get();
		$customers = Customer::select('id', 'name')->get();
		$deposits = Payment::all();
		$products = Product::select('id', 'symbol', 'currency')->get();
		$banks = Bank::all();

		$sumAmountSell = $orders->groupBy('customer_id')->map(function ($orders) {
			return $orders->sum(function ($order) {
				return $order->amount * $order->sell;
			});
		});

		$sumCharges = $orders->groupBy('customer_id')->map(function ($orders) {
			return $orders->sum('charges');
		});

		$sumAmountSellAndCharges = $sumAmountSell->map(function ($amountSell, $customerId) use ($sumCharges) {
			return $amountSell + $sumCharges->get($customerId, 0);
		});

		$sumAmountDeposits = $deposits->groupBy('customer_id')->map(function ($deposits) {
			return $deposits->sum('amount');
		});

		$aggregates = $sumAmountDeposits->map(function ($amountDeposits, $customerId) use ($sumAmountSellAndCharges) {
			return $amountDeposits - $sumAmountSellAndCharges->get($customerId);
		});

		return view('admin.order.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'orders', 'customers', 'products', 'banks', 'sumAmountSell', 'sumCharges', 'sumAmountSellAndCharges', 'sumAmountDeposits', 'aggregates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module_name = 'Orders';
		$page_title = 'Order';
		$page_subtitle = 'Create New';
		$page_heading = 'New Order';
		$heading_class = 'fal fa-receipt';
		$page_desc = 'Create new Customer Order';

		$customers = Customer::all(['name', 'id']);
		$products = Product::all(['id', 'product_name']);

		return view('admin.order.create', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'customers'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$order = new Order();

		$order->customer_id = $request->input('customer_id');
		$order->product_id = $request->input('product_id');
		$order->amount = $request->input('amount');
		$order->buy = $request->input('buy');
		$order->sell = $request->input('sell');
		$order->bank_id = $request->input('bank_id');

		if ($request->input('product_id') == 1) {
			$amount = $request->input('amount');
			if ($amount < 5000) {
				$order->charges = 150000;
			} elseif ($amount >= 5000 && $amount < 20000) {
				$order->charges = 85000;
			}
		}

		// dd($order);
		$order->save();
		return redirect()->back()->with('success', 'Order created successfully!');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
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
		$order = Order::find($id);

		$order->customer_id = $request->input('customer_id');
		$order->product_id = $request->input('product_id');
		$order->amount = $request->input('amount');
		$order->buy = $request->input('buy');
		$order->sell = $request->input('sell');
		$order->bank_id = $request->input('bank_id');
		$order->charges = $request->input('charges');

		$order->save();

		return redirect()->back()->with('success', 'Order saved successfully!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$order = Order::find($id);
		$order->delete();
		return redirect()->back()->with('success', 'Order removed successfully!');
	}
}
