<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use App\Models\Completed;
use App\Models\DataUser;
use App\Models\SklOlder;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Svg\Tag\Rect;

class SklOlderController extends Controller
{
	//digunakan oleh Administrator/Verifikator untuk menyimpan data-data SKL Lama yang pernah terbit sebelum SIAP-RIPH.
	public function index()
	{
		abort_if(Gate::denies('old_skl_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'SKL';
		$page_title = 'Old SKL';
		$page_heading = 'Daftar SKL Lama';
		$heading_class = 'fa fa-file-certificate';

		$oldskls = SklOlder::all();

		return view('admin.oldskl.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'oldskls'));
	}

	//form create old skl. digunakan oleh administrator/verifikator
	public function create()
	{
		abort_if(Gate::denies('old_skl_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'SKL';
		$page_title = 'Old SKL';
		$page_heading = 'Rekam Data SKL Lama';
		$heading_class = 'fa fa-file-certificate';

		$datauser = DataUser::all();

		return view('admin.oldskl.create', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'datauser'));
	}

	private function uploadFile($file, $filenpwp, $periodetahun, $filename)
	{
		$file->storeAs('uploads/' . $filenpwp . '/' . $periodetahun, $filename, 'public');
		return asset('storage/uploads/' . $filenpwp . '/' . $periodetahun . '/' . $filename);
	}

	public function store(Request $request)
	{
		$user = Auth::user();
		$oldskl = new SklOlder();
		$oldskl->no_skl = $request->input('no_skl');
		$oldskl->npwp = $request->input('npwp');
		$oldskl->no_ijin = $request->input('no_ijin');
		$oldskl->periodetahun = $request->input('periodetahun');
		$oldskl->published_date = $request->input('published_date');
		$oldskl->luas_tanam = $request->input('luas_tanam');
		$oldskl->volume = $request->input('volume');
		$oldskl->submit_by = $user->id;

		$completed = new Completed();
		$completed->no_skl = $request->input('no_skl');
		$completed->npwp = $request->input('npwp');
		$completed->no_ijin = $request->input('no_ijin');
		$completed->periodetahun = $request->input('periodetahun');
		$completed->published_date = $request->input('published_date');
		$completed->luas_tanam = $request->input('luas_tanam');
		$completed->volume = $request->input('volume');
		$completed->status = 'Lunas';

		$filenpwp = str_replace(['.', '-'], '', $request->input('npwp'));
		$noIjin = str_replace(['.', '/'], '', $request->input('no_ijin'));

		if ($request->hasFile('sklfile')) {
			$file = $request->file('sklfile');
			$filename = 'skl_' . $noIjin . '.' . $file->getClientOriginalExtension();
			$filePath = $this->uploadFile($file, $filenpwp, $request->input('periodetahun'), $filename);
			$oldskl->sklfile = $filename;
			$completed->skl_upload = $filePath;
		}

		$oldskl->save();
		$completed->save();

		return redirect()->route('verification.oldskl.index')
			->with('success', 'Data SKL berhasil diunggah dan disimpan.');
	}

	public function show($id)
	{
		abort_if(Gate::denies('old_skl_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'SKL';
		$page_title = 'Old SKL';
		$page_heading = 'Data SKL Lama';
		$heading_class = 'fa fa-file-certificate';

		$oldskl = SklOlder::find($id);
		$datauser = DataUser::where('npwp_company', $oldskl->npwp)->first();
		// dd($oldskl);

		return view('admin.oldskl.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'oldskl', 'datauser'));
	}

	public function edit($id)
	{
		abort_if(Gate::denies('old_skl_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'SKL';
		$page_title = 'Old SKL';
		$page_heading = 'Ubah Data SKL Lama';
		$heading_class = 'fa fa-file-certificate';

		$oldskl = SklOlder::find($id);
		$datauser = DataUser::where('npwp_company', $oldskl->npwp)->first();
		$datausers = Datauser::all();
		// dd($oldskl);

		return view('admin.oldskl.edit', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'oldskl', 'datauser', 'datausers'));
	}

	public function update(Request $request, $id)
	{
		$user = Auth::user();
		$oldskl = SklOlder::find($id);
		$oldskl->no_skl = $request->input('no_skl');
		$oldskl->npwp = $request->input('npwp');
		$oldskl->no_ijin = $request->input('no_ijin');
		$oldskl->periodetahun = $request->input('periodetahun');
		$oldskl->published_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('published_date'))));
		$oldskl->luas_tanam = $request->input('luas_tanam');
		$oldskl->volume = $request->input('volume');
		$oldskl->submit_by = $user->id;

		$completed = Completed::where('no_ijin', $oldskl->no_ijin)->first();
		$completed->no_skl = $request->input('no_skl');
		$completed->npwp = $request->input('npwp');
		$completed->no_ijin = $request->input('no_ijin');
		$completed->periodetahun = $request->input('periodetahun');
		$completed->published_date = date('Y-m-d', strtotime(str_replace('/', '-', $request->input('published_date'))));
		$completed->luas_tanam = $request->input('luas_tanam');
		$completed->volume = $request->input('volume');
		$completed->status = 'Lunas';

		$filenpwp = str_replace(['.', '-'], '', $request->input('npwp'));
		$noIjin = str_replace(['.', '/'], '', $request->input('no_ijin'));

		if ($request->hasFile('sklfile')) {
			$file = $request->file('sklfile');
			$filename = 'skl_' . $noIjin . '.' . $file->getClientOriginalExtension();
			$filePath = $this->uploadFile($file, $filenpwp, $request->input('periodetahun'), $filename);
			$oldskl->sklfile = $filename;
			$completed->url = $filePath;
		}
		// dd($completed);
		$oldskl->save();
		$completed->save();
		return redirect()->route('verification.oldskl.index')
			->with('success', 'Data SKL berhasil diperbarui.');
	}

	public function destroy($id)
	{
		abort_if(Gate::denies('old_skl_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$oldskl = SklOlder::find($id);
		// dd($oldskl);
		$oldskl->delete();

		return redirect()->route('verification.oldskl.index')
			->with('success', 'Data SKL berhasil dihapus.');
	}
}
