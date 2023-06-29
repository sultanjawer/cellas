<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\SimeviTrait;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\DataUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;


class ProfileController extends Controller
{

	use SimeviTrait;

	public $access_token = '';
	public $data_user;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$module_name = 'Profile';
		$page_title = 'My profile';
		$page_heading = 'My profile';
		$heading_class = 'fa fa-user';
		$page_subtitle = 'User Profile';
		$page_desc = 'Your profiles';
		// $this->access_token = $this->getAPIAccessToken(config('app.simevi_user'), config('app.simevi_pwd'));
		$this->data_user = Auth::user()::find(auth()->id())->data_user;

		$provinsi = $this->getAPIProvinsiAll();

		if ($this->data_user) {
			if ($this->data_user->provinsi) {
				$kabupaten = $this->getAPIKabupatenProp($this->data_user->provinsi);
			}

			if ($this->data_user->kabupaten) {
				$kecamatan = $this->getAPIKecamatanKab($this->data_user->kabupaten);
			}

			if ($this->data_user->kecamatan) {
				$desa = $this->getAPIDesaKec($this->data_user->kecamatan);
			}
		}
		// $access_token = $this->access_token;
		$data_user = $this->data_user;
		return view('admin.profiles.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'data_user', 'page_subtitle', 'page_desc',));
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
	public function update(UpdateProfileRequest $request, $id)
	{
		//$user = User::find($id);
		$data = $request->all();

		$regdata = [
			'name'          => $data['name'],
			'mobile_phone'  => $data['mobile_phone'],
			'fix_phone'     => $data['fix_phone'],
			'company_name'  => $data['company_name'],
			'pic_name'      => $data['pic_name'],
			'jabatan'       => $data['jabatan'],
			'address_company' => $data['address_company'],
		];
		$avatar_path = '';
		if (array_key_exists('avatar', $data)) {
			if ($data['avatar'] != null) {
				$file_name = $data['company_name'] . '_' . 'avatar.' . $data['avatar']->getClientOriginalExtension();
				$file_path = $data['avatar']->storeAs('uploads/', $file_name, 'public');
				$avatar_path = $file_path;
				$regdata += array('avatar' => $avatar_path);
			};
		}
		DataUser::updateOrCreate([
			'user_id' =>  $id,
		], $regdata);
		return redirect()->route('admin.profile.show')->with('message', 'Profile updated successfully');
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
