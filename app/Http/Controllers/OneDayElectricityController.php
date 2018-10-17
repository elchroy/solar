<?php

namespace App\Http\Controllers;

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

        return [[
            'day' => null,
            'sum' => 0,
            'min' => 0,
            'max' => 0,
            'average' => 0
        ]];
    }
}
