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
use App\Models\PenangkarRiph;
use App\Models\PullRiph;
use App\Models\MasterAnggota;
use App\Models\DataUser;
use App\Models\MasterDesa;
use App\Models\Pks;
use App\Models\Poktans;

class VerifOnlineController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		//page level
		$module_name = 'Permohonan';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Daftar Pengajuan Verifikasi Data';
		$heading_class = 'fal fa-file-search';

		//table pengajuan
		$verifikasis = Pengajuan::where('status', '<=', '3')
			->orderBy('created_at', 'desc')
			->get();
		return view('admin.verifikasi.online.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifikasis'));
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
	public function check($id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		// Page level
		$module_name = 'Permohonan';
		$page_title = 'Data Pengajuan';
		$page_heading = 'Data Pengajuan Verifikasi';
		$heading_class = 'fa fa-file-search';

		// Populate related data
		$verifikasi = Pengajuan::findOrFail($id);
		$commitment = PullRiph::where('no_ijin', $verifikasi->no_ijin)->firstOrFail();
		$commitmentcheck = CommitmentCheck::where('pengajuan_id', $verifikasi->id)->firstOrFail();
		$pkschecks = PksCheck::where('pengajuan_id', $verifikasi->id)->get();
		$lokasichecks = LokasiCheck::where('pengajuan_id', $verifikasi->id)->orderBy('created_at', 'desc')->get();

		$pkss = Pks::withCount('lokasi')->where('no_ijin', $commitment->no_ijin)->get();
		$lokasis = collect();
		foreach ($pkschecks as $pkscheck) {
			$lokasi = Lokasi::where('poktan_id', $pkscheck->poktan_id)
				->where('no_ijin', $commitmentcheck->no_ijin)
				->get();
			$lokasis->push($lokasi);
		}

		$total_luastanam = $commitment->lokasi->sum('luas_tanam');
		$total_volume = $commitment->lokasi->sum('volume');

		return view('admin.verifikasi.online.subindex', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifikasi', 'commitment', 'commitmentcheck', 'pkschecks', 'lokasichecks', 'pkss', 'lokasis', 'total_luastanam', 'total_volume'));
	}

	/**
	 * berikut ini adalah detail-detail verifikasi.
	 * 1. Verifikasi Kommitmen (Unggahan Berkas Kelengkapan RIPH)
	 * 2. Verifikasi PKS/Perjanjian Kerjasama dengan Poktan
	 * 3. Sekilas data Lokasi
	 */

	public function commitmentcheck($id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'Verifikasi';
		$page_title = 'Verifikasi Data';
		$page_heading = 'Pemeriksaan Berkas Komitmen';
		$heading_class = 'fal fa-file-search';

		$user = Auth::user();
		$commitmentcheck = CommitmentCheck::findOrFail($id);
		$commitment = PullRiph::findOrFail($commitmentcheck->pengajuan->commitment_id);

		// dd($commitmentcheck);

		return view('admin.verifikasi.online.commitmentcheck', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'user', 'commitmentcheck', 'commitment'));
	}

	public function commitmentstore(Request $request, $id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$user = Auth::user();
		$commitmentcheck = CommitmentCheck::findOrFail($id);

		$pengajuan = Pengajuan::find($commitmentcheck->pengajuan_id);
		$commitmentcheck->verif_by = $user->id;
		$commitmentcheck->verif_at = Carbon::now();
		$commitmentcheck->formRiph = $request->input('formRiph');
		$commitmentcheck->formSptjm = $request->input('formSptjm');
		$commitmentcheck->logbook = $request->input('logbook');
		$commitmentcheck->formRt = $request->input('formRt');
		$commitmentcheck->formRta = $request->input('formRta');
		$commitmentcheck->formRpo = $request->input('formRpo');
		$commitmentcheck->formLa = $request->input('formLa');
		$commitmentcheck->note = $request->input('note');

		$commitmentcheck->save();
		return redirect()->route('verification.data.show', $pengajuan->id)
			->with('success', 'Data Pemeriksaan berhasil disimpan');
	}

	public function pkscheck($poktan_id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$module_name = 'Verifikasi';
		$page_title = 'Verifikasi Data';
		$page_heading = 'Data dan Berkas PKS';
		$heading_class = 'fal fa-ballot-check';

		$pks = Pks::where('poktan_id', $poktan_id)->latest()->first();
		$commitment = PullRiph::where('no_ijin', $pks->no_ijin)
			->first();
		$verifikasi = Pengajuan::where('no_ijin', $commitment->no_ijin)
			->latest()
			->first();
		$commitmentcheck = CommitmentCheck::where('no_pengajuan', $verifikasi->no_pengajuan)
			->first();
		$pkscheck = PksCheck::find($pks->id);
		return view('admin.verifikasi.online.pkscheck', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'pks', 'commitment', 'verifikasi', 'commitmentcheck', 'pkscheck'));
	}

	public function pksstore(Request $request, $poktan_id)
	{
		$user = Auth::user();

		$pks = Pks::find($poktan_id);
		$pkscheck = new PksCheck();
		$pkscheck->pengajuan_id = $request->input('pengajuan_id');
		$pkscheck->commitcheck_id = $request->input('commitmentcheck_id');
		$pkscheck->pks_id = $request->input('pks_id');
		$pkscheck->poktan_id = $request->input('poktan_id');
		$pkscheck->npwp = $request->input('npwp');
		$pkscheck->no_ijin = $request->input('no_ijin');
		$pkscheck->note = $request->input('note');
		$pkscheck->status = $request->input('status');
		$pkscheck->verif_at = Carbon::now();
		$pkscheck->verif_by = $user->id;


		// dd($pkscheck);
		$pkscheck->save();
		return redirect()->route('verification.data.show', $pkscheck->pengajuan_id)
			->with('success', 'Data Pemeriksaan berhasil disimpan');
	}

	public function pksedit($id)
	{

		$module_name = 'Verifikasi';
		$page_title = 'Verifikasi Data';
		$page_heading = 'Ubah data Verifikasi PKS';
		$heading_class = 'fal fa-ballot-check';

		$pkscheck = PksCheck::find($id);
		$pks = Pks::where('poktan_id', $pkscheck->poktan_id)->first();
		$commitment = PullRiph::where('no_ijin', $pkscheck->no_ijin)
			->first();
		$verifikasi = Pengajuan::find($pkscheck->pengajuan_id);
		$commitmentcheck = CommitmentCheck::where('pengajuan_id', $verifikasi->id)
			->first();

		// dd($pkscheck);
		return view('admin.verifikasi.online.pksedit', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'pks', 'commitment', 'verifikasi', 'commitmentcheck', 'pkscheck'));
	}

	public function pksupdate(Request $request, $id)
	{
		$user = Auth::user();

		$pkscheck = PksCheck::find($id);
		// dd($pkscheck);
		$pkscheck->note = $request->input('note');
		$pkscheck->status = $request->input('status');
		$pkscheck->verif_at = Carbon::now();
		$pkscheck->verif_by = $user->id;

		$pkscheck->save();
		return redirect()->route('verification.data.show', $pkscheck->pengajuan_id)
			->with('success', 'Data Pemeriksaan berhasil disimpan');
	}

	public function lokasicheck($noIjin, $anggota_id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'Verifikasi Data';
		$page_title = 'Verifikasi Data Lokasi';
		$page_heading = 'Pemeriksaan Data Tanam dan Produksi';
		$heading_class = 'fal fa-ballot-check';

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
			->where('commitcheck_id', $commitmentcheck->id)
			->where('pkscheck_id', $pkscheck->id)
			->first();

		$anggotamitra = $lokasi;
		$pksmitra = $pkscheck;
		$verifcommit = $commitmentcheck;
		$verifpks = $pkscheck;
		$veriflokasi = $lokasicheck;
		// dd($anggotamitra);
		return view('admin.verifikasi.online.locationcheck', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'lokasi', 'pks', 'commitment', 'verifikasi', 'commitmentcheck', 'pkscheck', 'lokasicheck', 'pksmitra', 'anggotamitra', 'verifcommit', 'verifpks', 'veriflokasi'));
	}

	public function lokasistore(Request $request)
	{
		$user = Auth::user();
		$locationcheck = new LokasiCheck();
		$locationcheck->pengajuan_id = $request->input('pengajuan_id');
		$locationcheck->commitcheck_id = $request->input('verifcommit_id');
		$locationcheck->pkscheck_id = $request->input('verifpks_id');
		$locationcheck->poktan_id = $request->input('poktan_id');
		$locationcheck->anggota_id = $request->input('anggotamitra_id');
		$locationcheck->npwp = $request->input('npwp');
		$locationcheck->no_ijin = $request->input('no_ijin');
		$locationcheck->onlineverif_at = Carbon::now();
		$locationcheck->onlineverif_by = $user->id;
		$locationcheck->onlinestatus = $request->input('onlinestatus');
		$locationcheck->onlinenote = $request->input('onlinenote');
		// dd($locationcheck);
		$locationcheck->save();
		return redirect()->route('verification.data.show', $locationcheck->pengajuan_id)
			->with('success', 'Data berhasil disimpan');
	}

	public function show($id)
	{
		abort_if(Gate::denies('online_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$module_name = 'Permohonan';
		$page_title = 'Verifikasi Data';
		$page_heading = 'Data-data Verifikasi';
		$heading_class = 'fal fa-ballot-check';

		$verifikasi = Pengajuan::findOrFail($id);
		$commitment = CommitmentCheck::where('pengajuan_id', $verifikasi->id)
			->latest()
			->first();

		$pksmitras = PksCheck::where('pengajuan_id', $verifikasi->id)
			->get();

		$onfarms = LokasiCheck::where('pengajuan_id', $verifikasi->id)
			->get();
		return view('admin.verifikasi.online.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'verifikasi', 'commitment', 'pksmitras', 'onfarms'));
	}

	public function baonline(Request $request, $id)
	{
		//verifikator
		$user = Auth::user();

		//pilih tabel pengajuans
		$verifikasi = Pengajuan::findOrFail($id);
		$commitment = PullRiph::where('no_ijin', $verifikasi->no_ijin)->first();
		abort_if(
			Gate::denies('online_access') ||
				($verifikasi->no_pengajuan != $request->input('no_pengajuan') &&
					$verifikasi->no_ijin != $request->input('no_ijin') &&
					$verifikasi->npwp != $request->input('npwp')),
			Response::HTTP_FORBIDDEN,
			'403 Forbidden'
		);

		//simpan data ke tabel pengajuans
		$verifikasi->onlinecheck_by = $user->id;
		$verifikasi->onlinedate = Carbon::now();
		$verifikasi->onlinenote = $request->input('onlinenote');
		$verifikasi->onlinestatus = $request->input('onlinestatus');
		$verifikasi->status = $request->input('onlinestatus');

		//ubah status di tabel pull_riphs
		$commitment->status = $request->input('onlinestatus');

		// dd($verifikasi);
		$verifikasi->save();
		$commitment->save();
		return redirect()->route('verification.data.show', $id)
			->with('success', 'Data berhasil disimpan');
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
