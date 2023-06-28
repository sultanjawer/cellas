<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\RiphAdmin;
use App\Models\Saprodi;
use App\Models\PullRiph;
use App\Models\Lokasi;
use App\Models\Pks;
use App\Models\Pengajuan;

class DashboardDataController extends Controller
{
	public function monitoringDataByYear($periodetahun)
	{
		abort_if(Auth::user()->roleaccess != 1, Response::HTTP_FORBIDDEN, '403 Forbidden');

		$riphData = RiphAdmin::where('periode', $periodetahun)->get();
		$commitments = PullRiph::where('periodetahun', $periodetahun)->get();

		$allPengajuan = Pengajuan::whereNotNull('status')
			->whereYear('created_at', $periodetahun)
			->get();

		$jumlahImportir = $riphData->sum('jumlah_importir');
		$v_pengajuan_import = $riphData->sum('v_pengajuan_import');
		$v_beban_tanam = $riphData->sum('v_beban_tanam');
		$v_beban_produksi = $riphData->sum('v_beban_produksi');
		$volume_import = $commitments->sum('volume_riph');
		$company = $commitments->count('no_ijin');

		$total_luastanam = $commitments->flatMap(function ($commitment) {
			return $commitment->lokasi->pluck('luas_tanam');
		})->sum();
		$total_volume = $commitments->flatMap(function ($commitment) {
			return $commitment->lokasi->pluck('volume');
		})->sum();

		$verifikasis = $allPengajuan->map(function ($singlePengajuan) {
			return [
				'no_pengajuan' => $singlePengajuan->no_pengajuan,
				'commitment' => [
					'datauser' => [
						'company_name' => $singlePengajuan->commitment->datauser->company_name,
					],
				],
				'no_ijin' => $singlePengajuan->commitment->no_ijin,
				'status' => $singlePengajuan->status,
				'onlinestatus' => $singlePengajuan->onlinestatus,
				'onfarmstatus' => $singlePengajuan->onfarmstatus,
			];
		});

		$ajucount = $allPengajuan->where('status', '1')->count();
		$proccesscount = $allPengajuan->where('onlinestatus', '2')->where('onfarmstatus', '')->count();
		$verifiedcount = $allPengajuan->whereNotNull('onfarmstatus')->count();
		$recomendationcount = $allPengajuan->where('status', '6')->count();
		$lunascount = $allPengajuan->where('status', '7')->count();
		$lunasLuas = $allPengajuan->where('status', '7')->sum('luas_verif');
		$lunasVolume = $allPengajuan->sum('volume_verif');

		$data = [
			'jumlah_importir'       => $jumlahImportir,
			'v_pengajuan_import'    => $v_pengajuan_import,
			'v_beban_tanam'         => $v_beban_tanam,
			'v_beban_produksi'      => $v_beban_produksi,
			'company'               => $company,
			'volume_import'         => $volume_import,
			'total_luastanam'       => $total_luastanam,
			'total_volume'          => $total_volume,
			'prosenTanam'           => ($v_beban_tanam == 0) ? 0 : ($total_luastanam / $v_beban_tanam * 100),
			'prosenProduksi'        => ($v_beban_produksi == 0) ? 0 : ($total_volume / $v_beban_produksi * 100),
			'ajucount'              => $ajucount,
			'proccesscount'         => $proccesscount,
			'verifiedcount'         => $verifiedcount,
			'lunascount'            => $lunascount,
			'lunasLuas'             => $lunasLuas,
			'lunasVolume'           => $lunasVolume,
			'verifikasis'           => $verifikasis,
		];

		return response()->json($data);
	}


	public function verifikatorMonitoringDataByYear($periodetahun)
	{
		abort_if(Auth::user()->roleaccess != 1, Response::HTTP_FORBIDDEN, '403 Forbidden');

		$allPengajuan = Pengajuan::whereNotNull('status')
			->whereYear('created_at', $periodetahun)
			->get();

		$verifikasis = $allPengajuan->map(function ($singlePengajuan) {
			return [
				'no_pengajuan' => $singlePengajuan->no_pengajuan,
				'commitment' => [
					'datauser' => [
						'company_name' => $singlePengajuan->commitment->datauser->company_name,
					],
				],
				'no_ijin' => $singlePengajuan->commitment->no_ijin,
				'status' => $singlePengajuan->status,
				'onlinestatus' => $singlePengajuan->onlinestatus,
				'onfarmstatus' => $singlePengajuan->onfarmstatus,
			];
		});

		$ajucount = $allPengajuan->where('status', '1')->count();
		$proccesscount = $allPengajuan->where('onlinestatus', '2')
			->where('onfarmstatus', '')
			->count();
		$verifiedcount = $allPengajuan->whereNotNull('onfarmstatus')
			->count();
		$recomendationcount = $allPengajuan->where('status', '')
			->count();
		$lunascount = $allPengajuan->where('status', '7')
			->count();

		$data = [
			'ajucount' => $ajucount,
			'proccesscount' => $proccesscount,
			'verifiedcount' => $verifiedcount,
			'recomendationcount' => $recomendationcount,
			'lunascount' => $lunascount,
			'verifikasis' => $verifikasis,
		];

		return response()->json($data);
	}

	public function userMonitoringDataByYear($periodetahun)
	{
		$npwpuser = Auth::user()->data_user->npwp_company;
		// Retrieve all data from RiphAdmin based on the selected 'periode'
		$commitments = PullRiph::where('periodetahun', $periodetahun)
			->where('npwp', $npwpuser)
			->get();

		$volumeImport = $commitments->sum('volume_riph');
		$wajib_tanam = $commitments->sum('luas_wajib_tanam');
		$wajib_produksi = $commitments->sum('volume_produksi');

		$jumlah_poktan = $commitments->flatMap(function ($commitment) {
			return $commitment->pks->pluck('id');
		})->count();
		$jumlah_anggota = $commitments->flatMap(function ($commitment) {
			return $commitment->lokasi->pluck('id');
		})->count();
		$realisasi_tanam = $commitments->flatMap(function ($commitment) {
			return $commitment->lokasi->pluck('luas_tanam');
		})->sum();
		$realisasi_produksi = $commitments->flatMap(function ($commitment) {
			return $commitment->lokasi->pluck('volume');
		})->sum();


		if ($wajib_tanam == 0) {
			$prosentanam = 0;
		} else {
			$prosentanam = $realisasi_tanam / $wajib_tanam;
		}
		if ($wajib_produksi == 0) {
			$prosenproduksi = 0;
		} else {
			$prosenproduksi = $realisasi_produksi / $wajib_produksi;
		}

		$allPengajuan = Pengajuan::whereNotNull('status')
			->whereYear('created_at', $periodetahun)
			->get();
		$verifikasis = $allPengajuan->map(function ($singlePengajuan) {
			return [
				'no_pengajuan' => $singlePengajuan->no_pengajuan,
				'commitment' => [
					'datauser' => [
						'company_name' => $singlePengajuan->commitment->datauser->company_name,
					],
				],
				'no_ijin' => $singlePengajuan->commitment->no_ijin,
				'status' => $singlePengajuan->status,
				'onlinestatus' => $singlePengajuan->onlinestatus,
				'onfarmstatus' => $singlePengajuan->onfarmstatus,
			];
		});

		$data = [
			'volumeImport'			=> $volumeImport,
			'wajib_tanam'			=> $wajib_tanam,
			'wajib_produksi'		=> $wajib_produksi,
			'total_luastanam'		=> $realisasi_tanam,
			'total_volume'			=> $realisasi_produksi,
			'count_poktan'			=> $jumlah_poktan,
			'count_anggota'			=> $jumlah_anggota,
			'prosenTanam'			=> $prosentanam,
			'prosenProduksi'		=> $prosenproduksi,
			'verifikasis'			=> $verifikasis,
		];
		return response()->json($data);
	}

	public function rekapRiphData(Request $request)
	{
		try {
			$options = array(
				'soap_version' => SOAP_1_1,
				'exceptions' => true,
				'trace' => 1,
				'cache_wsdl' => WSDL_CACHE_MEMORY,
				'connection_timeout' => 25,
				'style' => SOAP_RPC,
				'use' => SOAP_ENCODED,
			);

			$client = new \SoapClient('http://riph.pertanian.go.id/api.php/simethris?wsdl', $options);
			$parameter = array(
				'user' => 'simethris',
				'pass' => 'wsriphsimethris',
				'tahun' => $request->string('periodetahun')
			);
			$response = $client->__soapCall('get_rekap', $parameter);
		} catch (\Exception $e) {

			Log::error('Soap Exception: ' . $e->getMessage());
			throw new \Exception('Problem with SOAP call');
		}
		$res = json_decode(json_encode((array)simplexml_load_string($response)), true);

		return $res;
	}
}
