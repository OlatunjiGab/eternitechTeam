<?php

namespace App\Http\Controllers\LA;

use App\Classes\Slack;
use App\Models\Company;
use App\Models\Country;
use App\Models\PopupContent;
use App\Models\Project;
use App\Models\Upload;
use App\Notifications\ExceptionCought;
use Collective\Html\FormFacade as Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Datatables;

class PopupContentController extends Controller
{
    public $listing_cols = ['id', 'title', 'content'];
    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if(!Auth::user()->canAccess()) {
            return view('errors.403');
        }
        else{
            return view('la.popup-content.index', [
                'user' => $user
            ]);
        }
    }

    public function create() {
        $countriesList = Country::getAllCountries();
        $statusList    = Project::getStatuses();
        $sourceList    = Project::SOURCE_LIST;
        $companyList   = Company::getCompanyList();
        return view('la.popup-content.create',['countriesList'=>$countriesList,'statusList'=>$statusList,'sourceList'=>$sourceList,'companyList'=>$companyList]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data,
            [
                'title' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:1000',
                'specific_page' => 'active_url',
                'pop_after' => 'digits_between:1,5',
                'company_id' => 'required',
            ],
            [
                'specific_page.active_url' => 'The On specific page is not a valid URL.',
                'pop_after.digits_between' => 'The Show popup after must be between 1 and 5 digits.',
                'company_id.required' => 'The Client field is required.',
            ]
        );
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $uploaddocid = null;
            if($request->hasfile('image')) {
                $file = $request->file('image');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
                $file->move($folder, $date_append . $fname);
                $path = $folder . DIRECTORY_SEPARATOR . $date_append . $fname;
                if (!empty($fname)) {
                    $uploadCV = Upload::create([
                        'name' => $fname,
                        'path' => $path,
                        'extension' => pathinfo($fname, PATHINFO_EXTENSION),
                        "caption" => "",
                        "hash" => "",
                        "public" => true,
                        'user_id' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    // apply unique random hash to file
                    while (true) {
                        $hash = strtolower(str_random(20));
                        if (!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
                    $uploaddocid = $uploadCV->id;
                }
            }
            $uploaddocid ? $data['background_image'] = $uploaddocid : null;
            $popupContent = PopupContent::create($data);
            return redirect()->route(config('laraadmin.adminRoute') . '.popup-content.index')->with('success',
                'Popup Content has been saved successfully.');
        }
    }

    public function edit($id)
    {
        $countriesList = Country::getAllCountries();
        $statusList    = Project::getStatuses();
        $sourceList    = Project::SOURCE_LIST;
        $companyList   = Company::getCompanyList();
        $popupContent = PopupContent::find($id);
        if (isset($popupContent->id)) {
            $user = Auth::user();
                    return view('la.popup-content.edit',['popupContent'=>$popupContent,'countriesList'=>$countriesList,'statusList'=>$statusList,'sourceList'=>$sourceList,'companyList'=>$companyList,'user'=>$user]);
        } else {
            return view('errors.404', [
                'record_id' => $id,
                'record_name' => ucfirst("Popup Content"),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data,
            [
                'title' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:1000',
                'specific_page' => 'active_url',
                'pop_after' => 'digits_between:1,5',
                'company_id' => 'required',
            ],
            [
                'specific_page.active_url' => 'The On specific page is not a valid URL.',
                'pop_after.digits_between' => 'The Show popup after must be between 1 and 5 digits.',
                'company_id.required' => 'The Client field is required.',
            ]
        );

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $uploaddocid = null;
            if($request->hasfile('image')) {
                $file = $request->file('image');
                $folder = storage_path('uploads');
                $fname = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $date_append = date("Y-m-d-His-");
                $file->move($folder, $date_append . $fname);
                $path = $folder . DIRECTORY_SEPARATOR . $date_append . $fname;
                if (!empty($fname)) {
                    $uploadCV = Upload::create([
                        'name' => $fname,
                        'path' => $path,
                        'extension' => pathinfo($fname, PATHINFO_EXTENSION),
                        "caption" => "",
                        "hash" => "",
                        "public" => true,
                        'user_id' => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    // apply unique random hash to file
                    while (true) {
                        $hash = strtolower(str_random(20));
                        if (!Upload::where("hash", $hash)->count()) {
                            $uploadCV->hash = $hash;
                            break;
                        }
                    }
                    $uploadCV->save();
                    $uploaddocid = $uploadCV->id;
                }
            }
            $uploaddocid ? $data['background_image'] = $uploaddocid : null;
            $popupContent = PopupContent::where('id','=',$id)->first();
            $popupContent->update($data);
            return redirect()->route(config('laraadmin.adminRoute') . '.popup-content.index')->with('success',
                'Popup Content has been Updated successfully.');
        }
        return back()->with("flash.success","Popup Content has been Updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            PopupContent::destroy($id);
            return back()->with('flash.success','Popup Content has been deleted successfully');
        }catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
            return back()->with('flash.error',$e->getMessage());
        }
    }

    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtAjax(Request $request)
    {
        if(\Entrust::hasRole('SUPER_ADMIN')){
            $values = PopupContent::select($this->listing_cols)->orderBy('popup_contents.id','DESC');
        } else {
            $values = PopupContent::select($this->listing_cols)->where('company_id','=',Auth::user()->company_id)->orderBy('popup_contents.id','DESC');
        }

        $out = Datatables::of($values)->make();
        $data = $out->getData();
        foreach($data->data as $k=>$val) {
            $title = $val[1];
            $content = $val[2];
            $data->data[$k] = [$k+1,strtoupper($title),$content];

            $output = '';
            $output .= '<a href="'.route(config('laraadmin.adminRoute') . '.popup-content.edit',$val[0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
            $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.popup-content.destroy', $val[0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
            $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
            $output .= Form::close();
            $data->data[$k][] = (string)$output;
        }
        $out->setData($data);
        return $out;
    }
}
