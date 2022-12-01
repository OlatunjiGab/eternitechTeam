<?php

namespace App\Http\Controllers\LA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Datatables;

class LanguageController extends Controller
{
    public $listing_cols = ['id', 'name', 'code', 'status'];
    public $view_col_status = 'status';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if(!empty($user) && $user->roles->first()->name == 'PARTNER') {
            return view('errors.403');
        }
        $array = ['user'=>$user];
        return view('la.language.index',$array);
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


    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtAjax(Request $request)
    {

        $values = Language::orderBy('status','DESC');
        
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        foreach($data->data as $k=>$val) {
            
            $data->data[$k][3]  = $data->data[$k][3]==1 ? '<a href="'.url("admin/change_status/".$data->data[$k][0]).'" class="btn btn-success btn-xs">Active</a>' : '<a href="'.url("admin/change_status/".$data->data[$k][0]).'" class="btn btn-warning btn-xs">InActive</a>';            
            
            //if($this->show_action) {
                $output = '';
                    $output .= '<a href="'.url("admin/change_status/".$data->data[$k][0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;">Change Status</a>';
                
                $data->data[$k][] = (string)$output;
            //}
        }
   
        $out->setData($data);
        return $out;
    }


    /**
     * change language status
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
    */
    public function changeStatus($id)
    {
        $language = Language::find($id);
        $language->status =  $language->status == Language::STATUS_ACTIVE ? Language::STATUS_INACTIVE : Language::STATUS_ACTIVE;
        $language->save();

        return redirect()->back()->with('success', "The ".$language->name." language Status updated successfully");
        
    }
}
