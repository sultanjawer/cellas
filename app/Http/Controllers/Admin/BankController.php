<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use App\Models\Bank;
use App\Models\Companies;

class BankController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Banks';
		$page_title = 'Banks';
		$page_subtitle = 'Recipient Banks';
		$page_heading = 'Recipient Banks';
		$heading_class = 'fal fa-university';
		$page_desc = 'The designated Bank Account that accepts the payment or deposit';

		$banks = Bank::all();
		$companies = Companies::all();
		return view('admin.master.bank.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'banks', 'companies'));
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
		$bank = new Bank();

		$bank->company_id = $request->input('company_id');
		$bank->bank_name = $request->input('bank_name');
		$bank->account = $request->input('account');
		$bank->acc_name = $request->input('acc_name');
		$bank->save();

		return redirect()->route('admin.master.banks.index')->with('success', 'Data successfully saved');
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
		$bank = Bank::find($id);

		$bank->company_id = $request->input('company_id');
		$bank->bank_name = $request->input('bank_name');
		$bank->account = $request->input('account');
		$bank->acc_name = $request->input('acc_name');
		$bank->save();

		return redirect()->route('admin.master.banks.index')->with('success', 'Data successfully updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$bank = Bank::find($id);
		$bank->delete();
		return redirect()->route('admin.master.banks.index')->with('success', 'Data successfully removed');
	}
}
