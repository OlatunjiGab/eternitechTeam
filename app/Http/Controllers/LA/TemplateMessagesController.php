<?php

namespace App\Http\Controllers\LA;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Quote;
use App\Helpers\ShortLink;
use App\Models\Project;
use App\Notifications\ExceptionCought;
use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\TemplateMessage;
use App\Models\Language;
use Datatables;
use Collective\Html\FormFacade as Form;
use Auth;
use DB;
use App\Models\ProjectMessage;

class TemplateMessagesController extends Controller
{

    public $listing_cols = ['id', 'slug', 'title','content'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->canAccess()) {
            return view('errors.403');
        }
        $user = \Auth::user();
        $array = ['user'=>$user];
        return view('la.template_messages.index',$array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aLanguageData = Language::where(['status' =>1])->orderBy('id', 'ASC')->pluck('name', 'code');
        return view('la.template_messages.add',['aLanguageData'=>$aLanguageData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request) {
            // insert new template content
            $parent_id = 0;
            $partnerID = null;
            if(\Entrust::hasRole('PARTNER')) {
                $partnerID = Auth::user()->supplier_id;
            }

            if (isset($request->template_message) && !empty($request->template_message)) {
                $slug = '';
                foreach ($request->template_message as $key => $aRowData) {
                    if($key == 1) {
                        $slugString = str_slug($aRowData[3],'_');
                        $slug = strtoupper($slugString);
                        $slug = substr($slug, 0, 50);
                    } else {
                        if(empty($slug)){
                            $slug = $aRowData[1];
                        }
                    }
                    $template_message = new TemplateMessage;
                    $template_message->slug = $slug; //slug name
                    $template_message->language = $aRowData[2]; //template language
                    $template_message->title = $aRowData[3]; // template message title
                    $template_message->content = $aRowData[4]; // template message content
                    $template_message->partner_id = $partnerID; // template partner_id
                    if (isset($aRowData[3]) && !empty($aRowData[3]) && isset($aRowData[4]) && !empty($aRowData[4])) {
                        $template_message->status = 1;
                    } else {
                        $template_message->status = 0;
                    }
                    $template_message->version = 1.0;
                    $template_message->save();
                    if($key == 1){
                        $parent_id = $template_message->id;
                        $template_message->parent_id = $parent_id;
                        $template_message->save();
                    }
                    if(!empty($parent_id) && $parent_id != 0){
                        $template_message->parent_id = $parent_id;
                        $template_message->save();
                    }
                }
            }
            return redirect()->route(config('laraadmin.adminRoute') . '.templates.index')->with('flash.success','Template Message has been saved successfully.');

        } else {
            return back()->with("flash.error","something wrong, please try again");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TemplateMessage $templates)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TemplateMessage  $template_message
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $templates = TemplateMessage::where('parent_id',$id)->get();
        if(count($templates) <= 0){
            $template = TemplateMessage::select('slug')->where('id',$id)->first();
            if(!empty($template) && $template->slug){
                $slug = $template->slug;
            } else {
                $slug = 'PLACE_BID_MESSAGE';
            }
            return redirect(config('laraadmin.adminRoute')."/templates/all_template_edit/".$slug);
        }
        $user = \Auth::user();
        return view('la.template_messages.edit',['templates'=>$templates,'id'=>$id,'user'=>$user]);
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
        if (isset($request->template_message) && !empty($request->template_message)) {
            foreach ($request->template_message as $key => $aRowData) {
                if (isset($aRowData[3]) && !empty($aRowData[3]) && isset($aRowData[4]) && !empty($aRowData[4])) {
                    $requestData['title'] = $aRowData[3];
                    $requestData['content'] = $aRowData[4];
                    $requestData['status'] = 1;
                    if($template_message = TemplateMessage::where('parent_id',$id)->where('language',$aRowData[2])->first()){
                        if($requestData['title'] != $template_message->title || $requestData['content'] != $template_message->content) {
                            $requestData['version'] = $template_message->version + 0.1;
                        }
                        $template_message->update($requestData);
                    }
                }
            }
        }
        return back()->with("flash.success","Template has been updated successfully");
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
            $templates = TemplateMessage::where('parent_id',$id)->get();
            if(count($templates) <= 0){
                $template = TemplateMessage::where('id',$id)->first();
                DB::table('template_messages')->where('slug',$template->slug)->delete();
            } else {
                DB::table('template_messages')->where('parent_id',$id)->delete();
            }
            return back()->with('flash.success','Template has been deleted successfully');
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
        if(\Entrust::hasRole('SUPER_ADMIN')) {
        $values = TemplateMessage::select($this->listing_cols)->where('status','=',1)->where('language','=','en')->orderBy('template_messages.id','DESC');
        } else {
        $values = TemplateMessage::select($this->listing_cols)->where('status','=',1)->where('language','=','en')->where('partner_id','=',Auth::user()->supplier_id)->orderBy('template_messages.id','DESC');
        }

        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        foreach($data->data as $k=>$val) {
            $data->data[$k] = [$k+1,strtoupper($val[1]),$val[2],nl2br($val[3])];
            
            //if($this->show_action) {
                $output = '';
                $id = $val[0];
                if($val[12] != 0){
                    $id = $val[12];
                }
                //if(Module::hasAccess("Projects", "edit")) {
                    $output .= '<a href="'.route(config('laraadmin.adminRoute') . '.templates.edit',$id).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                //}
                
                //if(Module::hasAccess("Projects", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.templates.destroy', $val[0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                //}
                $data->data[$k][] = (string)$output;
            //}
        }
        $out->setData($data);
        return $out;
    }

    /**
     * edit all active language template
     * @param $type
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function allTemplateMessageEdit($type)
    {
        $aRowLanguageData = Language::where(['status' =>1])->get();
        //$aRowTemplateData = TemplateMessage::with('getLanguage')->where(['status'=>1])->orderBy('id','ASC')->get();

        return view('la.template_messages.edit-all',["aRowLanguageData"=>$aRowLanguageData,'slug'=>$type]);
    }

    /**
     * store all active language template while create
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAll(Request $request)
    {
        try{
            if ($request) {
                // update old template content
                if (isset($request['content']) && !empty($request['content'])) {
                    foreach ($request['content'] as $key => $value) {
                        $id = $key;
                        $requestData['content'] = $value;  
                        $requestData['title'] = $request->title[$key];
                        $template_message = TemplateMessage::where('id',$id)->first();
                        if($requestData['title'] != $template_message->title || $requestData['content'] != $template_message->content) {
                            $requestData['version'] = $template_message->version + 0.1;
                        }
                        $template_message->update($requestData);
                    }
                }

                // insert new template content            
                if (isset($request->template_message) && !empty($request->template_message)) {                    
                    foreach ($request->template_message as $key => $aRowData) {                                         
                        if (isset($aRowData[3]) && !empty($aRowData[3])) {                            
                            $template_message = new TemplateMessage;
                            $template_message->slug = $aRowData[1]; //slug name 
                            $template_message->language = $aRowData[2]; //template language
                            $template_message->title = $aRowData[3]; // template message title
                            $template_message->content = $aRowData[4]; // template message content
                            $template_message->status = 1;
                            $template_message->version = 1.0;
                            $template_message->save();
                        }
                    
                    }
                }

                return back()->with("flash.success","Templates has been updated successfully");

            } else {
                return back()->with("flash.error","something wrong, please try again");
            }

        }catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
            return back()->with('flash.error',$e->getMessage());
        }        
    }

    /**
     * get template preview with dynamic data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getTemplatePreview(Request $request) {
        $templateMessage = TemplateMessage::where(['id' => $request['template_id']])->first();
        if($templateMessage > 0) {
            $project = Project::with(['projectCompany.company', 'projectSkills'])->find($request['project_id']);

            $offeringUser = User::getCurrentUser();
            $offer = $project->getOffer($templateMessage->slug, $offeringUser, $request->is_signature,true);

            $data = [
                'subject' => $offer['title'] ?? '',
                'message' => $offer['content'] ? $offer['content'] : '',
            ];
            $response = [
                'status' => true,
                'message' => "get template successfully",
                'data' => $data,
            ];
        } else {
            $data['subject'] = "";
            $data['message'] = "";
            $response = [
                'status' => false,
                'message' => "Data not found",
                'data' => $data
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * get template statistics count for Send, Unread, Open and Clicks
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function templateStatistics(Request $request)
    {
        $data = [];
        $templates = TemplateMessage::pluck('slug', 'id');
        $totalSendTemplates = ProjectMessage::select('template_id',DB::raw('count(*) as total'),'version')
            ->whereIn('event_type',[ProjectMessage::EVENT_TYPE_EMAIL_SEND,ProjectMessage::EVENT_TYPE_BID_PLACE])
            ->whereNotNull("template_id")
            ->whereNotNull("version")
            ->groupBy(['template_id', 'version'])
            ->orderBy('template_id', 'ASC')->orderBy('version', 'ASC')
            ->get()->toArray();
        $totalUnreadTemplates = ProjectMessage::select('template_id',DB::raw('count(*) as total'),'version')
            ->whereIn('event_type',[ProjectMessage::EVENT_TYPE_EMAIL_SEND,ProjectMessage::EVENT_TYPE_BID_PLACE])
            ->whereNotNull("template_id")
            ->whereNotNull("version")
            ->where("user_engaged","=","0")
            ->groupBy(['template_id', 'version'])
            ->orderBy('template_id', 'ASC')->orderBy('version', 'ASC')
            ->get()->toArray();
        $totalOpenTemplates = ProjectMessage::select('template_id',DB::raw('count(*) as total'),'version')
            ->where('event_type','=',ProjectMessage::EVENT_TYPE_EMAIL_OPEN)
            ->whereNotNull("template_id")
            ->whereNotNull("version")
//            ->where("user_engaged","=","1")
            ->groupBy(['template_id', 'version'])
            ->orderBy('template_id', 'ASC')->orderBy('version', 'ASC')
            ->get()->toArray();
        $totalClicksByTemplates = ProjectMessage::select('template_id',DB::raw('count(*) as total'),'version')
            ->whereIn('event_type',[ProjectMessage::EVENT_TYPE_LANDING_SHORT_LINK,ProjectMessage::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK,ProjectMessage::EVENT_TYPE_MEETING_SHORT_LINK,ProjectMessage::EVENT_TYPE_HOMEPAGE_SHORT_LINK,ProjectMessage::EVENT_TYPE_LEAD_SHORT_LINK,ProjectMessage::EVENT_TYPE_SKILL_SHORT_LINK,ProjectMessage::EVENT_TYPE_WHATSAPP_LINK])
            ->whereNotNull("template_id")
            ->whereNotNull("version")
            ->groupBy(['template_id', 'version'])
            ->orderBy('template_id', 'ASC')->orderBy('version', 'ASC')
            ->get()->toArray();
        foreach($totalSendTemplates as $k => $sendTemplate){
            $key = $sendTemplate['template_id'] . '-' . $sendTemplate['version'];
            if (!key_exists($key, $data)) {
                $data[$key]['template']= $templates[$sendTemplate['template_id']];
                $data[$key]['version'] = $sendTemplate['version'];
            }
            $data[$key]['cntSend'] = $sendTemplate['total'];
        }
        foreach($totalUnreadTemplates as $k => $unreadTemplates){
            $key = $unreadTemplates['template_id'] . '-' . $unreadTemplates['version'];
            if (!key_exists($key, $data)) {
                $data[$key]['template']= $templates[$unreadTemplates['template_id']];
                $data[$key]['version'] = $unreadTemplates['version'];
            }
            $data[$key]['cntUnread'] = $unreadTemplates['total'];
        }
        foreach($totalOpenTemplates as $k => $openTemplates){
            $key = $openTemplates['template_id'] . '-' . $openTemplates['version'];
            if (!key_exists($key, $data)) {
                $data[$key]['template']= $templates[$openTemplates['template_id']];
                $data[$key]['version'] = $openTemplates['version'];
            }
            $data[$key]['cntOpen'] = $openTemplates['total'];
        }
        foreach($totalClicksByTemplates as $k => $clickTemplates){
            $key = $clickTemplates['template_id'] . '-' . $clickTemplates['version'];
            if (!key_exists($key, $data)) {
                $data[$key]['template']= $templates[$clickTemplates['template_id']];
                $data[$key]['version'] = $clickTemplates['version'];
            }
            $data[$key]['cntClick'] = $clickTemplates['total'];
        }
        return view('la.template_messages.template-statistics',['data'=>$data]);
    }
}
