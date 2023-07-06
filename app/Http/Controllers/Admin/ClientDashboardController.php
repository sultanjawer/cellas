<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;

use App\Models\Order;
use App\Models\Payment;

class ClientDashboardController extends Controller
{
	public function index()
	{
		$module_name = 'Dashboard';
		$page_title = 'Customer';
		$page_subtitle = 'Dashboard';
		$page_heading = 'Client Dashboard';
		$heading_class = 'fal fa-analytics';
		$page_desc = 'Quick References to monitor and analyze Customer transactions summary.';

		$customers = Customer::all();

		return view('admin.dashboard.home', compact('module_name', 'page_title', 'page_heading', 'page_subtitle', 'heading_class', 'page_desc', 'customers'));
	}

	public function InsightByDateRange(Request $request)
	{
		// only filtered by selectCustomer
		$client = $request->input('selectCustomer');
		$deposits = Payment::where('customer_id', $client)->where('status', 'checked')->sum('amount');
		$allOrders = Order::where('customer_id', $client)->get();

		$all_orders = $allOrders->sum(function ($order) {
			return ($order->amount * $order->sell) + ($order->cfa * $order->sell) + $order->ccharges;
		});
		$balance = $deposits - $all_orders;

		//filtered by date range and select customer
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		$query = Order::query()->where('customer_id', $client);
		if ($startDate && $endDate) {
			$formattedStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
			$formattedEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();
			$query->whereBetween('created_at', [$formattedStartDate, $formattedEndDate]);
		}
		$filteredOrders = $query->with('product', 'company')->get();
		$customerOrders = $filteredOrders->mapToGroups(function ($filteredOrder) {
			$total = (($filteredOrder->amount * $filteredOrder->sell) + ($filteredOrder->cfa * $filteredOrder->sell) + $filteredOrder->ccharges);
			return [
				$filteredOrder->company_id => $total,
			];
		})->map(function ($orders) {
			return $orders->sum();
		});

		$filteredSell = $filteredOrders->sum(function ($order) {
			return (($order->amount * $order->sell) + ($order->cfa * $order->sell) + $order->ccharges);
		});

		$data = [
			'balance' => $balance,
			'orders' => $filteredOrders,
			'totalSale' => $filteredSell,
			'customerOrders' => $customerOrders,
		];

		return response()->json($data);
	}
}
