<?php

namespace App\Http\Controllers;

use App\Bus;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matricule' => 'required|numeric|unique:buses,matricule',
            'nmbrPlace' => 'required|numeric',
            'nmbrPlaceDebout' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            //return redirect()->back()->withInput();
            return Response::json(['error' => $validator->messages()->first()],422);
        } else {
            Bus::create([
                'matricule' => $request->matricule,
                'nmbrPlace' => $request->nmbrPlace,
                'nmbrPlaceDebout' => $request->nmbrPlaceDebout
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Bus $bus)
    {
        $validator = Validator::make($request->all(), [
            'matricule' => 'required|numeric',
            'nmbrPlace' => 'required|numeric',
            'nmbrPlaceDebout' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            //return redirect()->back()->withInput();
            return Response::json(['error' => $validator->messages()->first()],422);
        } else {
            $bus->update($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Bus $bus)
    {
        $bus->delete();
    }

    public function reset()
    {
        DB::statement("ALTER TABLE pins AUTO_INCREMENT = 1");
    }
}
