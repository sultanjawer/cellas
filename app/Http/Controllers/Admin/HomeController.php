<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
	public function index()
	{
		$module_name = 'Dashboard';
		$page_title = 'Dashboard';
		$page_subtitle = 'INSIGHT';
		$page_heading = 'Dashboard Insight';
		$heading_class = 'fal fa-analytics';
		$page_desc = 'Quick References to monitor and analyze your transactions.';

		return view('admin.landing.index', compact('module_name', 'page_title', 'page_heading', 'page_subtitle', 'heading_class', 'page_desc'));
	}
}
