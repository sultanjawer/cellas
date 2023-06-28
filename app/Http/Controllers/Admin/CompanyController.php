<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companies;

class CompanyController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module_name = 'Companies';
		$page_title = 'Company';
		$page_subtitle = 'Lists';
		$page_heading = 'Recipient Banks';
		$heading_class = 'fal fa-university';
		$page_desc = 'Company will hold several Bank Account';
		$companies = Companies::all();

		return view('admin.master.company.index', compact('module_name', 'page_title', 'page_subtitle', 'page_heading', 'heading_class', 'page_desc', 'companies'));
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
		$company = new Companies();
		$company->company_name = $request->input('company_name');
		$company->company_code = $request->input('company_code');
		$company->save();

		return redirect()->back()->with('success', 'Data successfully saved');
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
		$company = Companies::find($id);
		$company->company_name = $request->input('company_name');
		$company->company_code = $request->input('company_code');
		$company->save();

		return redirect()->route('admin.master.companies.index')->with('success', 'Data successfully updated');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$company = Companies::find($id);
		$company->delete();

		return redirect()->route('admin.master.companies.index')->with('success', 'Data successfully deleted');
	}
}
