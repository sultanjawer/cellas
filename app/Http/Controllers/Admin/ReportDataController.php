<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;

use App\Models\Order;
use App\Models\Payment;

class ReportDataController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	}

	public function byDateRangeByBank(Request $request)
	{
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');
		$filterBank = $request->input('filter_bank');

		$query = Order::query();

		if ($startDate && $endDate && $filterBank) {
			$formattedStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
			$formattedEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();
			$query->whereBetween('created_at', [$formattedStartDate, $formattedEndDate])
				->where('bank_id', $filterBank);
		}

		$orders = $query->with('customer')->with('product')->get();

		$totalBuy = $orders->sum(function ($order) {
			return $order->amount * $order->buy + $order->charges;
		});

		return response()->json(['orders' => $orders, 'total_buy' => $totalBuy]);
	}

	public function depositsData(Request $request)
	{
		//data filter
		$startDate = $request->input('start_date');
		$filterCustomer = $request->input('customerFilter');
		$filterCompany = $request->input('Company_Filter');
		$filterAccount = $request->input('Account_Filter');
		$filterStatus = $request->input('filter_status');
		$filterValid = $request->input('validation_Filter');

		$query = Payment::query();

		if ($startDate) {
			$formattedStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
			$query->whereDate('created_at', $formattedStartDate);
		}
		if ($filterCustomer) {
			$query->where('customer_id', $filterCustomer);
		}

		if ($filterCompany) {
			$query->where('company_id', $filterCompany);
		}

		if ($filterAccount) {
			$query->where('bank_id', $filterAccount);
		}

		if ($filterStatus) {
			$query->where('status', $filterStatus);
		}

		if ($filterValid) {
			$query->where('validation', $filterValid);
		}

		$payments = $query->with('customer')->get();
		$totalFiltered = $payments->sum('amount');

		$data = [
			'payments' => $payments,
			'total_filtered' => $totalFiltered,
		];

		return response()->json($data);
	}

	public function customerOrderByDate(Request $request)
	{
		$startDate = $request->input('start_date');
		$filterCustomer = $request->input('customerFilter');
		$filterCompany = $request->input('Company_Filter');

		$query = Order::query();
		if ($startDate) {
			$formattedStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
			$query->whereDate('created_at', $formattedStartDate);
		}

		if ($filterCustomer) {
			$query->where('customer_id', $filterCustomer);
		}

		if ($filterCompany) {
			$query->where('company_id', $filterCompany);
		}

		$orders = $query->with('customer')->with('company')->with('product')->get();
		$totalSell = $orders->sum(function ($order) {
			return ($order->amount + $order->cfa) * $order->sell + $order->ccharges;
		});
		$totalFiltered  = $totalSell;
		$data = [
			'orders' => $orders,
			'total_filtered' => $totalFiltered,
		];
		return response()->json($data);
	}

	public function transactsData(Request $request)
	{
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');
		$filterBank = $request->input('filter_bank');

		$query = Order::query();

		if ($startDate && $endDate) {
			$formattedStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
			$formattedEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();
			$query->whereBetween('created_at', [$formattedStartDate, $formattedEndDate]);
		}

		if ($filterBank) {
			$query->where('bank_id', $filterBank);
		}

		$orders = $query->with('customer')->with('product')->get();
		$totalProfits = $orders->sum(function ($order) {
			return $order->amount * ($order->sell - $order->buy) + $order->charge;
		});
		$totalBuy = $orders->sum(function ($order) {
			return $order->amount * $order->buy + $order->charges;
		});

		return response()->json(['orders' => $orders, 'total_profits' => $totalProfits, 'total_buy' => $totalBuy]);
	}

	//this function is intended for dashboard summary
	/**
	 * balance = all validated formula: total payment - total purchase (amount + pfa)*buy + pcharges)
	 * purchase =
	 */
	public function InsightByDateRange(Request $request)
	{
		//not affected by $request
		$payments = Payment::where('validation', 'validated')->get();
		$deposits = $payments->sum('amount');
		$bankBalances = $payments->load('bank');
		$balance = $deposits;

		$balanceInBanks = $bankBalances->groupBy('bank_id')->map(function ($balances) {
			$bank = $balances->first()->bank;
			$bankName = $bank->bank_name;
			$account = $bank->account;
			$combined = $bankName . ' - ' . $account;

			return [
				'bank_name' => $combined,
				'balance' => $balances->sum('amount')
			];
		});

		//by $requests
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');
		$query = Order::query();
		if ($startDate && $endDate) {
			$formattedStartDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
			$formattedEndDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();
			$query->whereBetween('created_at', [$formattedStartDate, $formattedEndDate]);
		}
		$orders = $query->with('company')->with('customer')->with('product')->get();

		$companyOrders = $orders->groupBy('company_id')->map(function ($orders) {
			return [
				'company_name' => $orders->first()->company->company_name,
				'total_amount' => $orders->sum('amount')
			];
		});

		$totalPurchase = $orders->sum(function ($order) {
			return (($order->amount + $order->pfa) * $order->buy) + $order->pcharges;
		});
		$totalSale = $orders->sum(function ($order) {
			return (($order->amount + $order->cfa) * $order->sell) + $order->ccharge;
		});
		$totalProfits = $totalSale - $totalPurchase;
		$data = [
			'balance' 			=> $balance,
			'totalPurchase'		=> $totalPurchase,
			'totalSale'			=> $totalSale,
			'totalProfits'		=> $totalProfits,
			'balanceInBanks'	=> $balanceInBanks,
			'companyOrders'		=> $companyOrders,
		];
		// dd($data);
		return response()->json($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
