<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCare;
use Auth;

class UsercareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::guard('admin')->user()){
        $enquiries=UserCare::get();
        return view('admin.enquiry.index',compact('enquiries'));
        }
        if(Auth::guard('customercare')->user()){
        $enquiries=UserCare::get();
        return view('customercare.enquiry.index',compact('enquiries'));
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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


    public function inprogress($id)
    {
        UserCare::where('id',$id)->update(['status' => 1]);
        return back()->with('flash_success', "Ticket Inprogress");
    }

    public function closed($id)
    {
        UserCare::where('id',$id)->update(['status' => 2]);
        return back()->with('flash_success', "Ticket Closed");
    }


}
