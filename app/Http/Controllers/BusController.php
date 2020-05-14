<?php

namespace App\Http\Controllers;

use App\Bus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'matricule' => 'required|unique:buses,matricule',
            'nmbrPlace'  => 'required|numeric',
            'nmbrPlaceDebout'  => 'required|numeric'
        ]);

        Bus::create([
            'matricule' => $request->matricule,
            'nmbrPlace' => $request->nmbrPlace,
            'nmbrPlaceDebout' => $request->nmbrPlaceDebout
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Bus $bus)
    {
        $bus->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Bus $bus)
    {
        $bus->delete();
    }

    public function reset(){
        DB::statement("ALTER TABLE pins AUTO_INCREMENT = 1");
    }
}
