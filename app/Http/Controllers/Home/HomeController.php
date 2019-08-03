<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Trade;
use Dirape\Token\Token;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('home.index');
	}

	/**
	 * @return array
	 */
	public function tradesData()
	{
		$data = DB::table('trades')
			->select(DB::raw('count(*) as total, status'))
			->where(function ($query) {
				$query->where('partner_id', Auth::id())
					->orWhere('user_id', Auth::id());
			})
			->groupBy('status')->get();

		$statuses = $data->pluck('status');

		if (!$statuses || !$statuses->contains('active')) {
			$data->push([
				'total'  => 0,
				'status' => 'active'
			]);
		}

		if (!$statuses || !$statuses->contains('successful')) {
			$data->push([
				'total'  => 0,
				'status' => 'successful'
			]);
		}

		if (!$statuses || !$statuses->contains('cancelled')) {
			$data->push([
				'total'  => 0,
				'status' => 'cancelled'
			]);
		}

		if (!$statuses || !$statuses->contains('dispute')) {
			$data->push([
				'total'  => 0,
				'status' => 'dispute'
			]);
		}

		return [
			'data'  => $data->pluck('total'),
			'label' => $data->pluck('status')->map(function ($item) {
				return ucfirst($item);
			})
		];
	}


}
