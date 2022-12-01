<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Job;
use DB;
use URL;
use Mail as Email;
use App\Helpers\CustomHelper;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $project;
    public $skill;

    public function __construct($project, array $skill)
    {
        $this->project = $project;
        $this->skill = $skill;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $projectData = $this->project;
        $myLeadPageUrl = URL::to('/admin/projects');
        $skill = DB::table('skills')->whereIn('id',$this->skill)->get(['keyword']);
        $skills = $skill->toArray();
        $supplierID = DB::table('supplier_skills')->whereIn('skill_id',$this->skill)->get();
        $supplierIDs = $supplierID->toArray();
        foreach($supplierIDs as $entry => $vals)
        {
            $vals = (array)$vals;
            $supplierSkillData[$vals['supplier_id']]['skill_id'][$entry]=$vals['skill_id'];
        }
        foreach ($supplierSkillData as $key => $sData){
            $supplierEmail = DB::table('suppliers')->where('id', '=', $key)->first(['partner_email','partner_first_name','partner_last_name','is_email_bounce']);
            $message  = "<p>Hey ".$supplierEmail->partner_first_name. $supplierEmail->partner_last_name."</p>";
            $message .= "<p>We've found new  lead that match your profile skills. You can view all of your  leads by signing into your CRM account and clicking, <a href=$myLeadPageUrl><strong>My Leads</strong></a> on your dashboard.</p><br><br>";
            $message .= "<strong>$projectData->name</strong><br>";
            $message .= $projectData->description? "$projectData->description <br>" : '';
            $message .= "<strong>Required Skills :</strong> ";
            foreach ($skills as $skillData){
                $message .= $skillData->keyword.' ';
            }
            $message .= $projectData->project_budget? "<br/><strong>Fixed Price Budget :</strong> $projectData->project_budget <br><br>" : '';
            $message .= "Disclaimer: You are receiving this email as it matches your skills ";
            foreach ($sData['skill_id'] as $partnerSkill){
                $pSkill = DB::table('skills')->where('id','=',(int)$partnerSkill)->first(['keyword']);
                $message .= $pSkill->keyword.' ';
            }
            $partnersTotalSkills = count($sData['skill_id']);
            $message .= "in the CRM. You will not be notified of the leads that don’t match your register $partnersTotalSkills skills. ";
            $message .= "Login to Dashboard to change your Skill setting";
            $config = array(
                'template' => 'send_message',
                'subject' => 'New Lead Match',
                'params' =>  ["msg"=>$message],
                'to' => [$supplierEmail->partner_email]
            );
            $defaults = array_merge(array('sendAs'=>'html','template'=>'default','body'=>'No message','title'=>'Simple','subject'=>'Subject'),$config);
            $body =  view('emails.'.$defaults['template'], $defaults['params']);
            $body = html_entity_decode($body);
        
            if (!$supplierEmail->is_email_bounce) {
                $res = Email::send('emails.default', ['title' => $defaults['title'], 'body' => $body], function ($message) use($defaults,$body){
                    $message->from('sales@eternitech.com');
                    $message->to($defaults['to']);
                    $message->subject($defaults['subject']);
                    $message->setBody($body,'text/html');
                });
                $emailBounced = CustomHelper::checkAndUpdateBounceFlagEmailSupplier($defaults['to'], $key);
            }
        }
    }
}
