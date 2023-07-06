<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	public function index()
	{
		$module_name = 'Landing';
		$page_title = 'Home';
		$page_subtitle = 'Land';
		$page_heading = 'Home Land';
		$heading_class = 'fal fa-home';
		$page_desc = 'Quick References to monitor and analyze your transactions.';

		$quote = Inspiring::quote();
		$user = Auth::user();

		return view('admin.landing.index', compact('module_name', 'page_title', 'page_heading', 'page_subtitle', 'heading_class', 'page_desc', 'quote', 'user'));
	}
}
