<?php

namespace App\Http\Controllers\LA;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Message;
use App\Helpers\ShortLink;
use App\Models\AutomatedMessage;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Notifications\ExceptionCought;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\TemplateMessage;
use Datatables;
use Collective\Html\FormFacade as Form;
use Validator;
use App\Models\Skill;

class AutomateMessageController extends Controller
{

    protected function validateRequest($data) {
       return Validator::make($data, [
            'name' => 'required',
            'action_template_id' => 'required',
            'action_delay' => 'required|integer',
            'action_delay_unit' => 'required|integer',
            'trigger_event_type' => 'required|integer',
//            'lead_filter_source' => 'required',
//            'lead_filter_channel' => 'required',
//            'action_sender_email' => 'required|email',
//            'action_sender_name' => 'required'
        ],
            [
                'name.required' => 'Name field is required.',
                'action_template_id.required' => 'The Template field is required.',
                'lead_filter_source.required' => 'The Source field is required.',
            ]);
    }

    protected static function prepareDataBeforeSaved ($data) {
        if (  $data['trigger_event_type'] == ProjectMessage::EVENT_TYPE_AUTO_FOLLOWUP) {
            $data['trigger_event_config'] = $data['trigger_event_config_automated_message'];
        } else {
            $data['trigger_event_config'] = $data['trigger_event_text'];
        }

        unset($data['trigger_event_config_automated_message']);
        unset($data['trigger_event_config_text']);

        $data['lead_filter_skills'] = implode(AutomatedMessage::SKILLS_SEPARATOR, $data['lead_filter_skills']);
        return $data;
    }

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
        return view('la.automate-message.index',$array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $templates = TemplateMessage::where('status',TemplateMessage::STATUS_ACTIVE)->pluck('title', 'id');
        $delayUnitList = AutomatedMessage::TIME_UNITS;
        $triggerEventList = ProjectMessage::EVENT_TYPE_NAMES;
        $leadSourceList = Project::SOURCE_LIST;
        $messageChannelList = Message::CHANNELS;
        $skillList  = Skill::getSkillList();
        $skillList = array_column($skillList, 'keyword', 'id');
        $channelList = BaseChannel::getAllChannels();
        $automatedMessagesList = $values = AutomatedMessage::orderBy('automated_message.id','DESC')->pluck('name', 'id')->toArray();
        return view('la.automate-message.add',['templates'=>$templates,'delayUnitList'=>$delayUnitList,'triggerEventList'=>$triggerEventList,'leadSourceList'=>$leadSourceList,'skillList'=>$skillList,'messageChannelList'=>$messageChannelList,'channelList' => $channelList,'automatedMessagesList' => $automatedMessagesList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = self::validateRequest($data);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            /*if(!empty($data['skills'])){
                $data['skills'] = implode(',',$data['skills']);
            }*/

            $data = self::prepareDataBeforeSaved($data);

            $automatedMessage = AutomatedMessage::create($data);
            return redirect()->route(config('laraadmin.adminRoute') . '.automate-message.index')->with('success',
                'Automate template has been saved successfully.');
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
        $templates = TemplateMessage::where('status',1)->pluck('title', 'id');
        $delayUnitList = AutomatedMessage::TIME_UNITS;
        $automatedMessage = AutomatedMessage::find($id);
        $triggerEventList = ProjectMessage::EVENT_TYPE_NAMES;
        $leadSourceList = Project::SOURCE_LIST;
        $messageChannelList = Message::CHANNELS;
        $channelList = BaseChannel::getAllChannels();
        $skillList  = Skill::getSkillList();
        $skillList = array_column($skillList, 'keyword', 'id');
        $selectedSkills = explode(AutomatedMessage::SKILLS_SEPARATOR ,$automatedMessage->lead_filter_skills);
        $automatedMessagesList = $values = AutomatedMessage::where('id', '!=', $id)->orderBy('automated_message.id','DESC')->pluck('name', 'id')->toArray();
        if (isset($automatedMessage->id)) {
            $user = \Auth::user();
            return view('la.automate-message.edit',['automatedMessage'=>$automatedMessage,'id'=>$id,'templates'=>$templates,'delayUnitList'=>$delayUnitList, 'triggerEventList'=>$triggerEventList,'leadSourceList'=>$leadSourceList, 'channelList'=> $channelList, 'user'=> $user, 'skillList'=>$skillList,'messageChannelList'=>$messageChannelList, 'selectedSkills' => $selectedSkills,'automatedMessagesList' => $automatedMessagesList]);
        } else {
            return view('errors.404', [
                'record_id' => $id,
                'record_name' => ucfirst("Automate Message"),
            ]);
        }
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
        $data = $request->all();
        $validator = self::validateRequest($data);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $data = self::prepareDataBeforeSaved($data);

            $template_message = AutomatedMessage::where('id',$id)->first();
            /*if(!empty($data['skills'])){
                $request['skills'] = implode(',',$request['skills']);
            }*/

            $template_message->update($data);
            return redirect()->route(config('laraadmin.adminRoute') . '.automate-message.index')->with('success',
                'Automate template has been Updated successfully.');
        }
        return back()->with("flash.success","Automate template has been updated successfully");
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
            AutomatedMessage::destroy($id);
            return back()->with('flash.success','Automate Template has been deleted successfully');
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
        $values = AutomatedMessage::orderBy('automated_message.id','DESC');

        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        foreach($data->data as $k=>$val) {
            $templateName = $val[2];
            if($val[2]){
                $template = TemplateMessage::select('title')->where('id',$val[2])->first()->toArray();
                $templateName = $template['title'];
            }
            $actionDelayUnit = AutomatedMessage::TIME_UNITS[$val[4]];
            $triggerEventType = ProjectMessage::EVENT_TYPE_NAMES[$val[8]];
            $projectSource = Project::SOURCE_LIST[$val[9]];
            $data->data[$k] = [$k+1,$val[1],strtoupper($templateName),$actionDelayUnit,$val[3],$triggerEventType,$projectSource];

            $output = '';
            $output .= '<a href="'.route(config('laraadmin.adminRoute') . '.automate-message.edit',$val[0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
            $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.automate-message.destroy', $val[0]], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$this->deleteMessage]);
            $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
            $output .= Form::close();
            $data->data[$k][] = (string)$output;
        }
        $out->setData($data);
        return $out;
    }

}
