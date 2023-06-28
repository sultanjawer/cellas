<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaMitraController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		$anggotaMitras = Lokasi::with([
			'pks' => function ($query) {
				$query->with('commitment');
			},
			'pks',
			'masteranggota'
		])
			->whereNotNull('latitude')
			->get();

		$result = [];

		foreach ($anggotaMitras as $anggotaMitra) {
			$luasTanam = $anggotaMitra->luas_tanam ? $anggotaMitra->luas_tanam : 'belum tanam';
			$volume = $anggotaMitra->volume ? $anggotaMitra->volume : 'belum panen';

			$result[] = [
				'id' => $anggotaMitra->id,
				'npwp' => str_replace(['.', '-'], '', $anggotaMitra->npwp),
				'latitude' => $anggotaMitra->latitude,
				'longitude' => $anggotaMitra->longitude,
				'polygon' => $anggotaMitra->polygon,

				'pks_mitra_id' => $anggotaMitra->poktan_id,
				'no_ijin' => $anggotaMitra->pullriph->no_ijin,
				'periodetahun' => $anggotaMitra->pullriph->periodetahun,
				'no_perjanjian' => $anggotaMitra->pks->no_perjanjian,
				'nama_petani' => $anggotaMitra->masteranggota->nama_petani,
				'nama_kelompok' => $anggotaMitra->pks->masterpoktan->nama_kelompok,
				'nama_lokasi' => $anggotaMitra->nama_lokasi,

				'altitude' => $anggotaMitra->altitude,
				'luas_kira' => $anggotaMitra->luas_kira,
				'tgl_tanam' => $anggotaMitra->tgl_tanam,
				'luas_tanam' => $luasTanam,
				'varietas' => $anggotaMitra->varietas,
				'tgl_panen' => $anggotaMitra->tgl_panen,
				'volume' => $volume,
				'tanam_pict' => $anggotaMitra->tanam_pict,
				'panen_pict' => $anggotaMitra->panen_pict,

				'company' => $anggotaMitra->pullriph->datauser->company_name,
			];
		}

		return response()->json($result);
	}

	public function ByYears($periodeTahun)
	{
		$anggotaMitras = Lokasi::with([
			'pks' => function ($query) {
				$query->with('commitment');
			},
			'pks',
			'masteranggota'
		])
			->whereNotNull('latitude')
			->get();
		$result = [];

		foreach ($anggotaMitras as $anggotaMitra) {
			$periodetahun = $anggotaMitra->pullriph->periodetahun;
			if ($periodetahun == $periodeTahun) {
				$luasTanam = $anggotaMitra->luas_tanam ? $anggotaMitra->luas_tanam : 'belum tanam';
				$volume = $anggotaMitra->volume ? $anggotaMitra->volume : 'belum panen';

				$result[] = [
					'periodetahun' => $periodetahun,
					'id' => $anggotaMitra->id,
					'npwp' => str_replace(['.', '-'], '', $anggotaMitra->npwp),
					'latitude' => $anggotaMitra->latitude,
					'longitude' => $anggotaMitra->longitude,
					'polygon' => $anggotaMitra->polygon,

					'pks_mitra_id' => $anggotaMitra->poktan_id,
					'no_ijin' => $anggotaMitra->pullriph->no_ijin,
					'periodetahun' => $anggotaMitra->pullriph->periodetahun,
					'no_perjanjian' => $anggotaMitra->pks->no_perjanjian,
					'nama_petani' => $anggotaMitra->masteranggota->nama_petani,
					'nama_kelompok' => $anggotaMitra->pks->masterpoktan->nama_kelompok,
					'nama_lokasi' => $anggotaMitra->nama_lokasi,

					'altitude' => $anggotaMitra->altitude,
					'luas_kira' => $anggotaMitra->luas_kira,
					'tgl_tanam' => $anggotaMitra->tgl_tanam,
					'luas_tanam' => $luasTanam,
					'varietas' => $anggotaMitra->varietas,
					'tgl_panen' => $anggotaMitra->tgl_panen,
					'volume' => $volume,
					'tanam_pict' => $anggotaMitra->tanam_pict,
					'panen_pict' => $anggotaMitra->panen_pict,

					'company' => $anggotaMitra->pullriph->datauser->company_name,
				];
			}
		}

		return response()->json($result);
	}

	public function ByIdYears($id, $periodeTahun)
	{
		$user = Auth::user();
		$anggotaMitras = Lokasi::with([
			'pks' => function ($query) {
				$query->with('commitment');
			},
			'pks',
			'masteranggota'
		])
			->where('npwp', $npwp)
			->whereNotNull('latitude')
			->get();

		$result = [];

		foreach ($anggotaMitras as $anggotaMitra) {
			$periodetahun = $anggotaMitra->pullriph->periodetahun;
			if ($periodetahun == $periodeTahun) {
				$luasTanam = $anggotaMitra->luas_tanam ? $anggotaMitra->luas_tanam : 'belum tanam';
				$volume = $anggotaMitra->volume ? $anggotaMitra->volume : 'belum panen';

				$result[] = [
					'periodetahun' => $periodetahun,
					'id' => $anggotaMitra->id,
					'npwp' => str_replace(['.', '-'], '', $anggotaMitra->npwp),
					'latitude' => $anggotaMitra->latitude,
					'longitude' => $anggotaMitra->longitude,
					'polygon' => $anggotaMitra->polygon,

					'pks_mitra_id' => $anggotaMitra->poktan_id,
					'no_ijin' => $anggotaMitra->pullriph->no_ijin,
					'periodetahun' => $anggotaMitra->pullriph->periodetahun,
					'no_perjanjian' => $anggotaMitra->pks->no_perjanjian,
					'nama_petani' => $anggotaMitra->masteranggota->nama_petani,
					'nama_kelompok' => $anggotaMitra->pks->masterpoktan->nama_kelompok,
					'nama_lokasi' => $anggotaMitra->nama_lokasi,

					'altitude' => $anggotaMitra->altitude,
					'luas_kira' => $anggotaMitra->luas_kira,
					'tgl_tanam' => $anggotaMitra->tgl_tanam,
					'luas_tanam' => $luasTanam,
					'varietas' => $anggotaMitra->varietas,
					'tgl_panen' => $anggotaMitra->tgl_panen,
					'volume' => $volume,
					'tanam_pict' => $anggotaMitra->tanam_pict,
					'panen_pict' => $anggotaMitra->panen_pict,

					'company' => $anggotaMitra->pullriph->datauser->company_name,
				];
			}
		}

		return response()->json($result);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		// the url for the REST Api of anggotaMitra : http://127.0.0.1:8000/api/getAPIAnggotaMitra/{id}
		$anggotaMitra = Lokasi::with([
			'pks' => function ($query) {
				$query->with('commitment');
			},
			'pks',
			'masteranggota'
		])->find($id);

		$result[] = [
			'id' => $anggotaMitra->id,
			// 'latitude' => $anggotaMitra->latitude,
			// 'longitude' => $anggotaMitra->longitude,
			// 'polygon' => $anggotaMitra->polygon,

			'pks_mitra_id' => $anggotaMitra->poktan_id,
			'npwp' => str_replace(['.', '-'], '', $anggotaMitra->npwp),
			'no_ijin' => $anggotaMitra->pullriph->no_ijin,
			'periodetahun' => $anggotaMitra->pullriph->periodetahun,
			'no_perjanjian' => $anggotaMitra->pks->no_perjanjian,
			'nama_petani' => $anggotaMitra->masteranggota->nama_petani,
			'nama_kelompok' => $anggotaMitra->pks->masterpoktan->nama_kelompok,
			'nama_lokasi' => $anggotaMitra->nama_lokasi,

			'altitude' => $anggotaMitra->altitude,
			'luas_kira' => $anggotaMitra->luas_kira,
			'tgl_tanam' => $anggotaMitra->tgl_tanam,
			'luas_tanam' => $anggotaMitra->luas_tanam,
			'varietas' => $anggotaMitra->varietas,
			'tgl_panen' => $anggotaMitra->tgl_panen,
			'volume' => $anggotaMitra->volume,
			'tanam_pict' => $anggotaMitra->tanam_pict,
			'panen_pict' => $anggotaMitra->panen_pict,

			'company' => $anggotaMitra->pullriph->datauser->company_name,
		];
		return response()->json($result);
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
