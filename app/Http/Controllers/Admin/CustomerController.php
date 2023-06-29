<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Order;
use App\Models\CashInOut;

class CustomerController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = Auth::user();

		$module_name = 'Customers';
		$page_title = 'Customers';
		$page_subtitle = 'List';
		$page_heading = 'List of Customers';
		$heading_class = 'fal fa-users';
		$page_desc = 'Insert page description or punch line';

		$customers = Customer::all();

		return view('admin.master.customer.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'customers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module_name = 'Customers';
		$page_title = 'Customers';
		$page_subtitle = 'List';
		$page_heading = 'List of Customers';
		$heading_class = 'fal fa-users';
		$page_desc = 'Insert page description or punch line';


		return view('admin.master.customer.create', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$customer = new Customer();
		$customer->name = $request->input('name');
		$customer->mobile_phone = $request->input('mobile_phone');
		$customer->notes = $request->input('notes');

		// dd($customer);
		$customer->save();

		return redirect()->route('admin.master.customers.index')->with('success', 'Data successfully saved');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$module_name = 'Customers';
		$page_title = 'Customer';
		$page_subtitle = 'Detail';
		$page_heading = 'Customer Detail';
		$heading_class = 'fal fa-user-tie';
		$page_desc = 'Detail of Customer';

		$customer = Customer::find($id);

		$words = explode(' ', $customer->name); // Split the name into individual words
		if (count($words) === 1) {
			// If the name has only one word, retrieve the first two characters
			$initialName = Str::limit($customer->name, 2, '');
		} else {
			// Retrieve the first character of each word
			$initialName = '';
			foreach ($words as $word) {
				$initialName .= Str::substr($word, 0, 1);
			}
		}

		// dd($initialName);

		return view('admin.master.customer.show', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'customer', 'initialName'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$module_name = 'Customers';
		$page_title = 'Customer';
		$page_subtitle = 'Detail';
		$page_heading = 'Customer Detail';
		$heading_class = 'fal fa-user-tie';
		$page_desc = 'Detail of Customer';

		$customer = Customer::find($id);
		return view('admin.master.customer.edit', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'customer'));
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
		$customer = Customer::find($id);
		$customer->name = $request->input('name');
		$customer->mobile_phone = $request->input('mobile_phone');
		$customer->notes = $request->input('notes');

		$customer->save();

		return redirect()->route('admin.master.customers.index')->with('success', 'Data successfully saved');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$customer = Customer::find($id);
		$customer->delete();
		return redirect()->route('admin.master.customers.index')->with('success', 'Data successfully deleted');
	}

	//transactions
	public function transactions($id)
	{
		$module_name = 'Customers';
		$page_title = 'Transactions';
		$page_subtitle = 'History';
		$page_heading = 'Customer Transactions History';
		$heading_class = 'fal fa-history';
		$page_desc = 'History of transactions of the current Customer';

		$customer = Customer::find($id);

		$words = explode(' ', $customer->name);
		$initialName = count($words) === 1 ? Str::limit($customer->name, 2, '') : implode('', array_map(fn ($word) => Str::substr($word, 0, 1), $words));

		$orders = Order::where('customer_id', $customer->id)->latest('created_at')->get();
		$payments = Payment::where('customer_id', $customer->id)->latest('created_at')->get();
		$lastOrder = $orders->first() ?? new Order();
		$lastPayment = $payments->first() ?? new Payment();

		$totalOrders = $orders->sum(fn ($order) => $order->amount * $order->sell + $order->charges);
		$totalPayments = $payments->sum(fn ($payment) => $payment->amount);
		$balance = $totalPayments - $totalOrders;

		$customerOrders = Order::where('customer_id', $customer->id)
			->join('products', 'orders.product_id', '=', 'products.id')
			->select(
				'orders.customer_id',
				'products.symbol as currency',
				'orders.amount as order',
				'pcharges',
				'orders.created_at as transaction_date',
				\DB::raw('((orders.amount + orders.cfa) * orders.sell) + orders.ccharges as shop')
			)
			->get();


		$customerPayments = Payment::where('customer_id', $customer->id)
			->select('customer_id', 'slip_date as transaction_date', 'amount as deposit')
			->get();

		$customerTransactions = collect();

		foreach ($customerOrders as $order) {
			$customerTransactions->push(new CashInOut([
				'customer_id' => $order->customer_id,
				'transaction_date' => $order->transaction_date,
				'deposit' => null,
				'currency' => $order->currency,
				'order' => $order->order,
				'charges' => $order->charges,
				'cashOut' => $order->shop,
			]));
		}

		// dd($customerTransactions);
		foreach ($customerPayments as $payment) {
			$customerTransactions->push(new CashInOut([
				'customer_id' => $payment->customer_id,
				'transaction_date' => $payment->transaction_date,
				'deposit' => $payment->deposit,
				'currency' => null,
				'order' => null,
				'charges' => null,
				'cashOut' => null,
			]));
		}
		// dd($customerTransactions);
		$customerTransactions = $customerTransactions->sortByDesc('transaction_date');

		return view('admin.master.customer.transactions', compact(
			'module_name',
			'page_title',
			'page_subtitle',
			'page_heading',
			'heading_class',
			'page_desc',
			'customer',
			'initialName',
			'lastOrder',
			'lastPayment',
			'totalOrders',
			'totalPayments',
			'balance',
			'customerTransactions'
		));
	}
}
