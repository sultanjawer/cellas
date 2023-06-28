<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;

use App\Models\Order;
use App\Models\Bank;
use App\Models\Customer;

class ReportController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$module_name = 'Reports';
		$page_title = 'Report';
		$page_subtitle = 'Orders';
		$page_heading = 'Report Orders';
		$heading_class = 'fal fa-print';
		$page_desc = 'Report for customer orders';

		$banks = Bank::all();
		// $now = date(now());
		// dd($now);

		return view('admin.report.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'banks'));
	}

	public function deposit(Request $request)
	{
		$module_name = 'Reports';
		$page_title = 'Report';
		$page_subtitle = 'Orders';
		$page_heading = 'Report Orders';
		$heading_class = 'fal fa-print';
		$page_desc = 'Report for customer orders';

		$customers = Customer::with(['payment', 'order'])->get();

		foreach ($customers as $customer) {
			$paymentTotal = $customer->payment->sum('amount');
			$orderTotal = $customer->order->sum(function ($order) {
				return $order->amount * $order->sell + $order->charges;
			});

			$result = $paymentTotal - $orderTotal;

			$customer->result = $result;
			$customer->cashIn = $paymentTotal;
			$customer->cashOut = $orderTotal;
		}
		$banks = Bank::all();


		return view('admin.report.deposit', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'banks', 'customers'));
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
