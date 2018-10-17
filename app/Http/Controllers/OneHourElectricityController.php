<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Validator;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel = Panel::where('serial', $request->panel_serial)->firstOrFail();
        return OneHourElectricity::where('panel_id', $panel->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $panel = Panel::where('serial', $request->panel_serial)->first();
        $params = $request->all();
        $params['panel_id'] = $panel->id;
        unset($params['panel_serial']);

        $validator = Validator::make($params, OneHourElectricity::$fieldValidations);
        if ($validator->fails()) {
            return Response::json($validator->errors()->all(), 422);
        }

        return $panel->oneHourElectricities()->create($params);
    }
}
