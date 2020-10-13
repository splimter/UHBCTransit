<?php

namespace App\Http\Controllers;

use App\Pin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Pin::create([
            'lati' => $request->lati,
            'long' => $request->long,
            'desc' => $request->desc
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Pin $pin
     * @return void
     */
    public function update(Request $request, Pin $pin)
    {
        $pin->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Pin $pin
     * @return void
     * @throws Exception
     */
    public function destroy(Pin $pin)
    {
        $pin->delete();
    }

    public function reset(){
        DB::statement("ALTER TABLE pins AUTO_INCREMENT = 1");
    }
}
