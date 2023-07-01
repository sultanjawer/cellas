<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$module_name = 'Dashboard';
		$page_title = 'Dashboard';
		$page_subtitle = 'INSIGHT';
		$page_heading = 'Dashboard Insight';
		$heading_class = 'fal fa-analytics';
		$page_desc = 'Quick References to monitor and analyze your transactions.';

		return view('admin.dashboard.index', compact('module_name', 'page_title', 'page_heading', 'page_subtitle', 'heading_class', 'page_desc'));
	}
}
