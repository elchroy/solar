<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Panel;

class OneDayElectricityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel = Panel::select('id')->where('serial', $request->panel_serial)->first();

        $oneEs = DB::table('one_hour_electricities')
            ->select(['hour', 'kilowatts'])
            ->where('panel_id', $panel->id)
            ->whereDate('hour', Carbon::today())
            ->get();

        return response()->json([
            'day' => substr($oneEs->first()->hour, 0, 1),
            'sum' => (double) $oneEs->sum($kw = 'kilowatts'),
            'min' => (double) $oneEs->min($kw),
            'max' => (double) $oneEs->max($kw),
            'average' => (double) $oneEs->avg($kw),
        ], 200);
    }
}
