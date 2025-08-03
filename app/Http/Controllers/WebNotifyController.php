<?php

namespace App\Http\Controllers;

use App\Models\WebNotify;
use Illuminate\Http\Request;

class WebNotifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $note = WebNotify::find($request->id);
        $note->status = 1;
        $note->save();
        return response()->json(['success'=>'Status change successfully.']);        
    }

    public function clearall(Request $request)
    {
        WebNotify::where('status', '=', 0)->update(['status' => '1']);       
        return response()->json(['success'=>'Status change successfully.']);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebNotify  $webNotify
     * @return \Illuminate\Http\Response
     */
    public function show(WebNotify $webNotify)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebNotify  $webNotify
     * @return \Illuminate\Http\Response
     */
    public function edit(WebNotify $webNotify)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WebNotify  $webNotify
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebNotify $webNotify)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebNotify  $webNotify
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebNotify $webNotify)
    {
        //
    }
}
