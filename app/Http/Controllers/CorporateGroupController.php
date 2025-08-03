<?php

namespace App\Http\Controllers;

use App\Models\CorporateGroup;
use App\Models\CorporateUser;
use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\Models\User;
use Auth;

class CorporateGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Groups = CorporateGroup::where('corporate_id','=',Auth::user()->id)->orderBy('created_at' , 'desc')->get();
        return view('corporate.group.index', compact('Groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = ServiceType::all();
        return view('corporate.group.create',compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|max:255',
            'payment_mode' => 'required',
        ]);

        try{
            $Group = $request->all();
            $Group['corporate_id'] = Auth::user()->id;
            $Group = CorporateGroup::create($Group);
            return back()->with('flash_success','Group created Successfully');

        } 

        catch (Exception $e) {
            return back()->$e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CorporateGroup  $corporateGroup
     * @return \Illuminate\Http\Response
     */
    public function show(CorporateGroup $corporateGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CorporateGroup  $corporateGroup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $services = ServiceType::all();
            $Group = CorporateGroup::findOrFail($id);
            return view('corporate.group.edit',compact('services','Group'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CorporateGroup  $corporateGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'group_name' => 'required|max:255',
            'payment_mode' => 'required',
        ]);

        try {

            $Group = CorporateGroup::findOrFail($id);
            $Group->group_name = $request->group_name;
            $Group->payment_mode = $request->payment_mode;
            $Group->ride_service_type = $request->ride_service_type ? : '';
            $Group->allowed_days = $request->allowed_days ? : '';
            $Group->time_range = $request->time_range ? : '';
            $Group->save();

            return redirect()->route('corporate.group.index')->with('flash_success', 'Group Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Group Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CorporateGroup  $corporateGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $corporate_user = CorporateUser::where('corporate_group_id','=',$id)->pluck('id');
            User::whereIn('corporate_user_id',$corporate_user)->update(['corporate_user_id' => null, 'corporate_status' => 0]);
            CorporateUser::where('corporate_group_id','=',$id)->delete();
            CorporateGroup::find($id)->delete();
            return back()->with('message', 'Group deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Group Not Found');
        }
    }
}
