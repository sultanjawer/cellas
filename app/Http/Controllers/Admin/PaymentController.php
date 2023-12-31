<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Bank;
use App\Models\Companies;
use App\Models\PaymentGhost;
use Svg\Tag\Rect;

class PaymentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Deposits & Payments';
		$page_title = 'Deposits';
		$page_subtitle = '& Payments';
		$page_heading = 'Depostis & Payments';
		$heading_class = 'fal fa-money-check';
		$page_desc = 'Customer Deposits and Payments';

		$customers = Customer::select('id', 'name')->get();
		$currencies = Product::select('id', 'symbol', 'currency')->get();
		$companies = Companies::all();
		$banks = Bank::select('id', 'bank_name', 'account', 'acc_name')->get();

		$payments = Payment::with('customer')->get();
		// $checkedPayments = Payment::where('status', '1')->sum('amount');

		return view('admin.payment.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'payments', 'customers', 'currencies', 'banks', 'companies'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$module_name = 'Deposits & Payments';
		$page_title = 'Create Deposits';
		$page_subtitle = '& Payments';
		$page_heading = 'Create Depostis & Payments';
		$heading_class = 'fal fa-money-check';
		$page_desc = 'Here You can create Customer Deposits/Payments or Cash In-out';

		$customers = Customer::all();
		$banks = Bank::all();
		$companies = Companies::all();

		return view('admin.payment.create', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'customers', 'banks', 'companies'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$payment = new Payment();

		$payment->customer_id = $request->input('customer_id');
		$payment->company_id = $request->input('company_id');
		$payment->bank_id = $request->input('bank_id');
		$payment->origin_bank = $request->input('origin_bank');
		$payment->origin_account = $request->input('origin_account');
		$payment->slip_date = $request->input('slip_date');
		$payment->amount = $request->input('amount');

		$payment->save();

		return redirect()->back()->with('success', 'Order created successfully!');
	}

	public function bulkstore(Request $request)
	{
		$request->validate([
			'customer_id' => 'required',
			'slip_date' => 'required',
			'amount' => 'required|array',
			'bank_id' => 'required|array',
			'company_id' => 'required|array',
			'amount.*' => 'required',
			'bank_id.*' => 'required',
			'company_id.*' => 'required',
		]);

		$amounts = $request->input('amount');
		$bank_ids = $request->input('bank_id');
		$company_ids = $request->input('company_id');

		try {
			for ($i = 0; $i < count($amounts); $i++) {
				$payment = new Payment();
				$payment->customer_id = $request->input('customer_id');
				$payment->slip_date = $request->input('slip_date');
				$payment->amount = $amounts[$i];
				$payment->bank_id = $bank_ids[$i];
				$payment->company_id = $company_ids[$i];
				$payment->save();
			}

			// Flash the success message to the session
			session()->flash('message', trans('global.create_success'));

			// Redirect to a specific route
			return redirect()->route('admin.payments.index');
		} catch (\Exception $e) {
			// Flash an error message to the session
			session()->flash('error', trans('global.create_failed'));

			// Redirect to a specific route
			return redirect()->route('admin.payments.index');
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
		$module_name = 'Deposits & Payments';
		$page_title = 'Deposits';
		$page_subtitle = '& Payments';
		$page_heading = 'Depostis & Payments';
		$heading_class = 'fal fa-money-check';
		$page_desc = 'Customer Deposits and Payments';

		$payment = Payment::find($id);
		$customers = Customer::select('id', 'name')->get();
		$currencies = Product::select('id', 'symbol', 'currency')->get();
		$companies = Companies::all();
		$banks = Bank::select('id', 'bank_name', 'account', 'acc_name')->get();

		return view('admin.payment.edit', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'payment', 'customers', 'banks', 'payment', 'companies', 'currencies'));
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
		$payment = Payment::find($id);

		$payment->customer_id = $request->input('customer_id');
		$payment->company_id = $request->input('company_id');
		$payment->bank_id = $request->input('bank_id');
		$payment->origin_bank = $request->input('origin_bank');
		$payment->origin_account = $request->input('origin_account');
		$payment->slip_date = $request->input('slip_date');
		$payment->amount = $request->input('amount');

		$payment->save();

		return redirect()->back()->with('success', 'Order created successfully!');
	}

	public function setStatus($id)
	{
		$payment = Payment::find($id);

		// Check the old value of the status field
		$oldStatus = $payment->status;

		// Set the new status value based on the old value
		$newStatus = ($oldStatus == 'unchecked') ? 'checked' : 'unchecked';

		// Update the status field
		$payment->status = $newStatus;
		// dd($payment);
		$payment->save();

		// Set the success message based on the new status
		$message = ($newStatus == 'checked') ? 'Status updated to CHECKED.' : 'Status updated to UNCHECKED.';
		session()->flash('message', $message);
		return redirect()->back();
	}

	public function setValidation($id)
	{
		$payment = Payment::find($id);
		// dd($payment);
		$oldVal = $payment->validation;
		$newVal = ($oldVal == 'unvalidated') ? 'validated' : 'unvalidated';
		$payment->validation = $newVal;
		$payment->save();

		$message = ($newVal == 'validated') ? 'Validation updated to VALIDATED.' : 'Validation updated to UNVALIDATED.';
		session()->flash('message', $message);
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$payment = Payment::find($id);
		$payment->delete();
		return redirect()->back()->with('success', 'Deleted successfully');
	}
}
