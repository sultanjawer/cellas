<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Inspiring;

class LoginController extends Controller
{
	use AuthenticatesUsers;

	protected $redirectTo = RouteServiceProvider::HOME;

	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function showLoginForm()
	{
		$quote = Inspiring::quote();

		return view('auth.login', compact('quote'));
	}

	protected function validateLogin(Request $request)
	{
		$request->validate([
			$this->username() => 'required|string',
			'password' => 'required|string',
		]);
	}

	protected function attemptLogin(Request $request)
	{
		return $this->guard()->attempt(
			$this->credentials($request),
			$request->filled('remember')
		);
	}

	protected function credentials(Request $request)
	{
		return $request->only($this->username(), 'password');
	}

	protected function sendLoginResponse(Request $request)
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		if ($response = $this->authenticated($request, $this->guard()->user())) {
			return $response;
		}

		return $request->wantsJson()
			? new JsonResponse([], 204)
			: redirect()->intended($this->redirectPath());
	}

	protected function sendFailedLoginResponse(Request $request)
	{
		throw ValidationException::withMessages([
			$this->username() => [trans('auth.failed')],
		]);
	}

	public function username()
	{
		return 'username';
	}

	public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		if ($response = $this->loggedOut($request)) {
			return $response;
		}

		return $request->wantsJson()
			? new JsonResponse([], 204)
			: redirect('/');
	}

	protected function loggedOut(Request $request)
	{
		//
	}

	protected function guard()
	{
		return Auth::guard();
	}
}
