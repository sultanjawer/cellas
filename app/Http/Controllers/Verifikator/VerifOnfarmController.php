<?php

namespace App\Http\Controllers\Verifikator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Gate;

use App\Models\LokasiCheck;
use App\Models\Pengajuan;
use App\Models\CommitmentCheck;
use App\Models\PksCheck;
use App\Models\Lokasi;
use App\Models\PullRiph;
use App\Models\Pks;

class VerifOnfarmController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		abort_if(Gate::denies('onfarm_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		//page level
		$module_name = 'Permohonan';
		$page_title = 'Daftar Pengajuan';
		$page_heading = 'Pengajuan Verifikasi Lapangan';
		$heading_class = 'fa fa-map-marked-alt';

		//table pengajuan
		$verifikasis = Pengajuan::whereIn('status', ['2', '4', '5'])
			->orderBy('created_at', 'desc')
			->get();

		// dd($verifikasis);
		// if (request()->ajax()) {
		// 	$pengajuans = Pengajuan::select(sprintf('%s.*', (new Pengajuan())->table))
		// 		->where('status', '==', '3')
		// 		->orderBy('created_at', 'desc')
		// 		->get();

		// 	$commitmentCounts = [];
		// 	$urutans = [];

		// 	foreach ($pengajuans as $pengajuan) {
		// 		$commitmentCount = $pengajuans->where('no_ijin', $pengajuan->no_ijin)->count();
		// 		$commitmentCounts[$pengajuan->no_ijin] = $commitmentCount;

		// 		$urutan = $pengajuans->where('no_ijin', $pengajuan->no_ijin)->sortBy('created_at')->search($pengajuan) + 1;
		// 		$urutans[$pengajuan->no_ijin] = $urutan;
		// 	}

		// 	$table = Datatables::of($pengajuans);
		// 	$table->editColumn('id', function ($row) {
		// 		return $row->id ? $row->id : '';
		// 	});
		// 	$table->editColumn('no_pengajuan', function ($row) {
		// 		return $row->no_pengajuan ? $row->no_pengajuan : '';
		// 	});
		// 	$table->editColumn('no_ijin', function ($row) {
		// 		return $row->no_ijin ? $row->no_ijin : '';
		// 	});
		// 	$table->editColumn('periodetahun', function ($row) {
		// 		return $row->commitment->periodetahun ? $row->commitment->periodetahun : '';
		// 	});

		// 	$table->editColumn('pengajuan', function ($row) {
		// 		$noIjinCount = Pengajuan::where('no_ijin', $row->no_ijin)->count();
		// 		return $noIjinCount ? $noIjinCount : '';
		// 	});

		// 	$table->editColumn('npwp', function ($row) {
		// 		return $row->npwp ? $row->npwp : '';
		// 	});
		// 	$table->editColumn('company_name', function ($row) {
		// 		return $row->datauser->company_name ? $row->datauser->company_name : '';
		// 	});
		// 	$table->editColumn('created_at', function ($row) {
		// 		return $row->created_at ? $row->created_at : '';
		// 	});
		// 	$table->editColumn('status', function ($row) {
		// 		return $row->status ? $row->status : '';
		// 	});
		// 	return $table->make(true);
		// }
		return view('admin.verifikasi.onfarm.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifikasis'));
	}

	public function farmlist($id)
	{
		abort_if(Gate::denies('onfarm_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'Verifikasi';
		$page_title = 'Verifikasi Lapangan';
		$page_heading = 'Daftar Lokasi Sampling';
		$heading_class = 'fal fa-map-marked-alt';

		$verifikasi = Pengajuan::findOrFail($id);
		$onfarms = LokasiCheck::where('pengajuan_id', $id)->get();
		return view('admin.verifikasi.onfarm.farmlist', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifikasi', 'onfarms'));
	}

	public function farmcheck($noIjin, $anggota_id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'Verifikasi Data';
		$page_title = 'Verifikasi Data Lokasi';
		$page_heading = 'Pemeriksaan Data Tanam dan Produksi';
		$heading_class = 'fal fa-ballot-check';

		//convert $noIjin to its original form.
		// $no_ijin = substr_replace(substr_replace(substr_replace(substr_replace(substr_replace($noIjin, '/', 4, 0), '.', 7, 0), '/', 11, 0), '/', 13, 0), '/', 16, 0);
		$no_ijin = substr_replace($noIjin, '/', 4, 0);
		$no_ijin = substr_replace($no_ijin, '.', 7, 0);
		$no_ijin = substr_replace($no_ijin, '/', 11, 0);
		$no_ijin = substr_replace($no_ijin, '/', 13, 0);
		$no_ijin = substr_replace($no_ijin, '/', 16, 0);

		$lokasi = Lokasi::where('anggota_id', $anggota_id)
			->where('no_ijin', $no_ijin)
			->first();

		$pks = Pks::where('poktan_id', $lokasi->poktan_id)
			->where('no_ijin', $no_ijin)
			->latest()
			->first();
		$commitment = PullRiph::where('no_ijin', $no_ijin)->first();

		//karena verifikasi dengan nomor ijin yang sama dapat muncul berulang
		$verifikasi = Pengajuan::where('no_ijin', $no_ijin)
			->latest()
			->first();
		$commitmentcheck = CommitmentCheck::where('no_pengajuan', $verifikasi->no_pengajuan)->first();
		$pkscheck = PksCheck::where('pengajuan_id', $verifikasi->id)->first();
		$lokasicheck = LokasiCheck::where('anggota_id', $anggota_id)
			->where('poktan_id', $pks->poktan_id)
			->where('pengajuan_id', $verifikasi->id)
			->where('commitcheck_id', $commitmentcheck->id)
			->where('pkscheck_id', $pkscheck->id)
			->first();

		$anggotamitra = $lokasi;
		$pksmitra = $pkscheck;
		$verifcommit = $commitmentcheck;
		$verifpks = $pkscheck;
		$veriflokasi = $lokasicheck;
		// dd($veriflokasi);
		return view('admin.verifikasi.onfarm.farmcheck', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'lokasi', 'pks', 'commitment', 'verifikasi', 'commitmentcheck', 'pkscheck', 'lokasicheck', 'pksmitra', 'anggotamitra', 'verifcommit', 'verifpks', 'veriflokasi'));
	}

	public function farmedit($id)
	{
		$lokasicheck = LokasiCheck::findOrFail($id);
	}

	public function farmupdate(Request $request, $id)
	{
		$user = Auth::user();
		$lokasicheck = LokasiCheck::findOrFail($id);
		$lokasicheck->onfarmverif_by = $user->id;
		$lokasicheck->onfarmverif_at = Carbon::now();
		$lokasicheck->latitude = $request->input('latitude');
		$lokasicheck->longitude = $request->input('longitude');
		$lokasicheck->altitude = $request->input('altitude');
		$lokasicheck->polygon = $request->input('polygon');
		$lokasicheck->luas_verif = $request->input('luas_verif');
		// $lokasicheck->tgl_ukur = Carbon::now();
		$lokasicheck->volume_verif = $request->input('volume_verif');
		// $lokasicheck->tgl_timbang = $request->input('tgl_timbang');
		$lokasicheck->volume_verif = $request->input('volume_verif');
		$lokasicheck->volume_verif = $request->input('volume_verif');
		$lokasicheck->metode = $request->input('metode');
		$lokasicheck->onfarmnote = $request->input('onfarmnote');
		$lokasicheck->onfarmstatus = $request->input('onfarmstatus');
		$lokasicheck->metode = $request->input('metode');
		// $no_ijin = $request->input('mod_noijin');
		// dd($no_ijin);
		dd($lokasicheck);
		// $lokasicheck->save();
		return redirect()->route('verification.onfarm.farmlist', $lokasicheck->pengajuan_id)
			->with('success', 'Data berhasil disimpan');
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
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		abort_if(Gate::denies('onfarm_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'Verifikasi';
		$page_title = 'Verifikasi Lapangan';
		$page_heading = 'Daftar Lokasi Sampling';
		$heading_class = 'fal fa-map-marked-alt';

		$verifikasi = Pengajuan::findOrFail($id);
		$onfarms = LokasiCheck::where('pengajuan_id', $id)->get();
		return view('admin.verifikasi.onfarm.farmlist', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifikasi', 'onfarms'));
	}

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
		$user = Auth::user();
		$verifikasi = Pengajuan::findOrFail($id);
		$verifikasi->onfarmcheck_by = $user->id;
		$verifikasi->onfarmdate = Carbon::now();
		$verifikasi->luas_verif = $request->input('luas_verif');
		$verifikasi->volume_verif = $request->input('volume_verif');
		$verifikasi->onfarmstatus = $request->input('onfarmstatus');
		$verifikasi->status = $request->input('onfarmstatus');
		$verifikasi->metode = $request->input('metode');
		$verifikasi->onfarmnote = $request->input('onfarmnote');
		$filenpwp = $request->input('npwp');
		$noIjin = $request->input('noIjin');
		$commitment = PullRiph::where('no_ijin', $verifikasi->no_ijin)->first();
		$commitment->status = $request->input('onfarmstatus');
		if ($request->hasFile('baonfarm')) {
			$file = $request->file('baonfarm');
			$filename = 'baonfarm_' . $noIjin . '.' . $file->getClientOriginalExtension();
			$file->storeAs('uploads/' . $filenpwp . '/' . $commitment->periodetahun, $filename, 'public');
			$verifikasi->baonfarm = $filename;
		}
		$verifikasi->save();
		$commitment->save();
		return redirect()->route('verification.onfarm')
			->with('success', 'Data berhasil disimpan');
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
