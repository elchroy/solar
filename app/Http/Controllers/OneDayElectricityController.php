<?php

namespace App\Http\Controllers;

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
        $panel = Panel::where('serial', $request->panel_serial)->first();
        $oneHourElectricities = $panel->oneHourElectricities()
            ->whereDate('hour', Carbon::today())
            ->get();

        return response()->json([
            'day' => $oneHourElectricities->first()->hour->format("Y-m-d"),
            'sum' => (double) $oneHourElectricities->sum($kw = 'kilowatts'),
            'min' => (double) $oneHourElectricities->min($kw),
            'max' => (double) $oneHourElectricities->max($kw),
            'average' => (double) $oneHourElectricities->avg($kw),
        ], 200);
    }
}
