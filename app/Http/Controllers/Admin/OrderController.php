<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\Product;
use App\Models\Bank;
use App\Models\Companies;
use App\Models\Customer;
use App\Models\DesignateBank;
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

		$orders = Order::with(['customer', 'product', 'company'])->orderByDesc('created_at')->get();
		$customers = Customer::select('id', 'name')->get();
		$deposits = Payment::all();
		$products = Product::select('id', 'symbol', 'currency')->get();
		$banks = Bank::all();
		$companies = Companies::select('id', 'company_name')->get();

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

		return view('admin.order.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'orders', 'customers', 'products', 'banks', 'sumAmountSell', 'sumCharges', 'sumAmountSellAndCharges', 'sumAmountDeposits', 'aggregates', 'companies'));
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
		$companies = Companies::all(['id', 'company_name']);
		$banks = Bank::all(['id', 'bank_name', 'account']);

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
		try {
			DB::beginTransaction();

			$order = new Order();

			// Populate order attributes
			$order->customer_id = $request->input('customer_id');
			$order->product_id = $request->input('product_id');
			$order->company_id = $request->input('company_id');
			$order->amount = $request->input('amount');
			$order->buy = $request->input('buy');
			$order->sell = $request->input('sell');
			$order->pcharges = $request->input('pcharges');
			$order->ccharges = $request->input('ccharges');
			$order->pfa = $request->input('pfa');
			$order->cfa = $request->input('cfa');

			$order->save(); // Save the order to generate the ID

			$orderID = $order->id; // Retrieve the generated order ID

			$selectedBankIDs = explode(',', $request->input('selectedRows')); // Split the comma-separated string into an array of selected bank IDs

			foreach ($selectedBankIDs as $bankID) {
				$selectedBanks = new DesignateBank();
				$selectedBanks->order_id = $orderID;
				$selectedBanks->bank_id = $bankID;
				$selectedBanks->save();
			}

			DB::commit(); // Commit the transaction

			session()->flash('message', trans('global.create_success'));
			return redirect()->back()->with('success', 'Order created successfully!');
		} catch (\Exception $e) {
			DB::rollback(); // Rollback the transaction in case of any exception

			// Handle the exception as needed
			return redirect()->back()->with('error', 'Failed to create order. Please try again.');
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$module_name = 'Orders';
		$page_title = 'Detail';
		$page_subtitle = 'Order';
		$page_heading = 'Detail Customer Orders';
		$heading_class = 'fal fa-receipt';
		$page_desc = 'Detail Customer Orders';

		$order = Order::findOrFail($id);
		$customer = Customer::where('id', $order->customer_id)->first();
		$deposits = Payment::where('customer_id', $customer->id)->get();
		$words = explode(' ', $customer->name);
		$initialName = count($words) === 1 ? Str::limit($customer->name, 2, '') : implode('', array_map(fn ($word) => Str::substr($word, 0, 1), $words));

		$orderDate = $order->created_at;
		$orderAmount = $order->amount;
		$ordercFa = $order->cfa;
		$orderAmtCfa = $orderAmount + $ordercFa;
		$orderRate = $order->sell;
		$orderTotal = $orderAmtCfa * $orderRate;
		$orderCharges = $order->ccharges;
		$orderCurrent = $orderTotal + $orderCharges;

		$customerOrders = Order::where('customer_id', $customer->id)->get();
		$allOrders = $customerOrders->sum(function ($customerOrder) {
			return (($customerOrder->amount + $customerOrder->cfa) * $customerOrder->sell) + $customerOrder->ccharges;
		});
		$allDeposits = $deposits->sum('amount');
		$currentDeposits = $allDeposits - $allOrders + $orderCurrent;
		$balance = $allDeposits - $allOrders;
		// dd($balance);
		$sumAmountDeposits = $deposits->sum('amount');
		$banks = DesignateBank::where('order_id', $order->id)->get();
		// dd($banks);

		return view('admin.order.orderDetail', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'order', 'customer', 'initialName', 'orderDate', 'orderAmount', 'ordercFa', 'orderRate', 'orderCharges', 'orderCurrent', 'currentDeposits', 'balance', 'banks'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$module_name = 'Orders';
		$page_title = 'Edit';
		$page_subtitle = 'Orders';
		$page_heading = 'Edit Customer Orders';
		$heading_class = 'fal fa-receipt';
		$page_desc = 'Edit Customer Orders';

		$order = Order::findOrFail($id);
		$selectedBankIDs = DesignateBank::where('order_id', $id)->pluck('bank_id')->toArray();
		$customers = Customer::select('id', 'name')->get();
		$products = Product::select('id', 'symbol', 'currency')->get();
		$banks = Bank::all();
		$companies = Companies::select('id', 'company_name')->get();
		// dd($selectedBankIDs);
		return view('admin.order.edit', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'order', 'selectedBankIDs', 'customers', 'products', 'banks', 'companies'));
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
		try {
			DB::beginTransaction();

			// Retrieve the order
			$order = Order::findOrFail($id);

			// Update the order attributes based on the form input
			$order->customer_id = $request->input('customer_id');
			$order->product_id = $request->input('product_id');
			$order->company_id = $request->input('company_id');
			$order->amount = $request->input('amount');
			$order->buy = $request->input('buy');
			$order->sell = $request->input('sell');
			$order->pcharges = $request->input('pcharges');
			$order->ccharges = $request->input('ccharges');
			$order->pfa = $request->input('pfa');
			$order->cfa = $request->input('cfa');

			$order->save(); // Save the updated order

			$existingBankIDs = $order->designateBanks->pluck('bank_id')->toArray(); // Get the existing bank IDs related to the order
			$selectedBankIDs = explode(',', $request->input('selectedRows')); // Split the comma-separated string into an array of selected bank IDs

			// Remove any existing DesignateBank records that are not present in the selected bank IDs
			$removedBankIDs = array_diff($existingBankIDs, $selectedBankIDs);
			DesignateBank::where('order_id', $order->id)->whereIn('bank_id', $removedBankIDs)->delete();

			// Add or update DesignateBank records for the selected bank IDs
			foreach ($selectedBankIDs as $bankID) {
				DesignateBank::updateOrCreate(
					['order_id' => $order->id, 'bank_id' => $bankID],
					['order_id' => $order->id]
				);
			}

			DB::commit(); // Commit the transaction

			session()->flash('message', trans('global.update_success'));
			return redirect()->back()->with('success', 'Order updated successfully!');
		} catch (\Exception $e) {
			DB::rollback(); // Rollback the transaction in case of any exception

			// Handle the exception as needed
			return redirect()->back()->with('error', 'Failed to update order. Please try again.');
		}
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
