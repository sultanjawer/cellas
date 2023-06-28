<?php

namespace App\Http\Controllers\Admin;

use App\Models\Berkas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBerkasRequest;
use App\Http\Requests\UpdateBerkasRequest;
use App\Http\Requests\MassDestroyBerkasRequest;
use App\Models\FileManagement;
use App\Models\PullRiph;
use Gate;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use PhpParser\ErrorHandler\Collecting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DateTime;
use DateTimeZone;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class FileManagementController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function templateindex()
	{
		$module_name = 'File Management';
		$page_title = 'Templates Master';
		$page_heading = 'File Templates';
		$heading_class = 'fab fa-stack-overflow';

		$templates = FileManagement::all();

		return view('admin.filemanagement.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'templates'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function templatecreate()
	{
		$module_name = 'File Management';
		$page_title = 'Templates Master';
		$page_heading = 'New Template';
		$heading_class = 'fal fa-edit';

		return view('admin.filemanagement.create', compact('module_name', 'page_title', 'page_heading', 'heading_class'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function templatestore(Request $request)
	{
		$template = new FileManagement();
		$template->berkas = $request->input('berkas');
		$template->nama_berkas = $request->input('nama_berkas');
		$template->deskripsi = $request->input('deskripsi');

		if ($request->hasFile('lampiran')) {
			$file = $request->file('lampiran');
			$filename = 'template_' . $file->getClientOriginalName();
			$file->storeAs('uploads/master/', $filename, 'public');
			$template->lampiran = $filename;
		}
		$template->save();
		// dd($template);
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
