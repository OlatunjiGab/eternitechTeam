<?php

namespace App\Models;

use App\Jobs\AutomatedFollowupJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AutomatedMessage extends Model
{
    const SECONDS    = 1;
    const MINUTES    = 2;
    const HOURS      = 3;
    const DAYS       = 4;
    const MONTHS     = 5;
    const YEARS      = 6;

    const TIME_UNITS = [
//        self::SECONDS => 'Seconds',
        self::MINUTES => 'Minutes',
        self::HOURS   => 'Hours',
        self::DAYS    => 'Days',
        self::MONTHS  => 'Months',
//        self::YEARS   => 'Years'
    ];

    const SKILLS_SEPARATOR = ',';

    public    $timestamps = true;
    protected $table      = 'automated_message';
    protected $guarded    = [];

    public function template()
    {
        return TemplateMessage::find($this->action_template_id);
    }

    public static function scheduleAutomatedFollowup($project, $projectMessage)
    {
        $matchingFollowups = self::getMatchingAutomatedMessages($project, $projectMessage);

        foreach ($matchingFollowups as $followup) {
            $delayValue = !empty($followup->action_delay) ? $followup->action_delay : 1;
            $delay = Carbon::now();
            switch ($followup->action_delay_unit) {
                case self::MINUTES:
                    $delay = Carbon::now()->addMinutes($delayValue);
                    break;
                case self::HOURS:
                    $delay = Carbon::now()->addHours($delayValue);
                    break;
                case self::DAYS:
                    $delay = Carbon::now()->addDays($delayValue);
                    break;
                case self::MONTHS:
                    $delay = Carbon::now()->addMonths($delayValue);
                    break;
                case self::YEARS:
                    $delay = Carbon::now()->addYears($delayValue);
                    break;
            }

            dispatch((new AutomatedFollowupJob($project->id, $projectMessage->id, $followup->id))->delay($delay));
        }
    }

    protected static function getMatchingAutomatedMessages($project, $projectMessage)
    {
        $autoM = self::where('trigger_event_type', '=', $projectMessage->event_type)
            ->where(function ($query) use ($project) {
                $query->whereNull('lead_filter_channel')
                    ->orWhere('lead_filter_channel', '=', $project->channel);
            })->where(function ($query) use ($project) {
                $query->whereNull('lead_filter_source')
                    ->orWhere('lead_filter_source', '=', $project->source);
            })->where(function ($query) use ($projectMessage) {
                $query->whereNull('lead_filter_countries')
                    ->orWhere('lead_filter_countries', '=', '')
                    ->orWhere('lead_filter_countries', 'like', '%' . $projectMessage->country_code . '%');
            })->get();

        // get followups already used
        $usedFollowups = ProjectMessage::where('project_id', '=', $project->id)->pluck('message_details')->toArray();

        $matchingAutomatedMassages = [];

        // search config value to be contained in message
        foreach ($autoM as $automatedMassage) {
            // check if automation followup was already sent on this project. (to avoid resending same)
            if (!in_array($automatedMassage->id, $usedFollowups)) {
                if ((($projectMessage->event_type == ProjectMessage::EVENT_TYPE_AUTO_FOLLOWUP) && ($projectMessage->message_details == $automatedMassage->trigger_event_config)) ||
                    (preg_match('/^' . $automatedMassage->trigger_event_config . '/im', $projectMessage->message))) {

                    if (!empty($automatedMassage->lead_filter_skills)) { // match specific skill
                        $skills = explode(self::SKILLS_SEPARATOR, $automatedMassage->lead_filter_skills);
                        $projectSkillIds = array_values(array_column($project->projectSkills(), 'skill_id'));
                        $matchingSkills = array_intersect($skills, $projectSkillIds);
                        if (!empty($matchingSkills)) {
                            $matchingAutomatedMassages[] = $automatedMassage;
                        }
                    } else { // all skills
                        $matchingAutomatedMassages[] = $automatedMassage;
                    }
                }
            }
        }

        return $matchingAutomatedMassages;
    }


//    public static function getInactiveProjects($template)
//    {
//        $timeRange = $template->period;
//        $timeValue =  $template->time;
//        $eventType = $template->event_type;
//
//        $typeUnderDay = true;
//
//        switch ($timeRange) {
//            case self::SECONDS:
//                $fromDateTime = date('Y-m-d H:i:s', strtotime("-$timeValue seconds"));
//                $timeValue = $timeValue - 60;
//                $toDateTime = date('Y-m-d H:i:s', strtotime("-$timeValue seconds"));
//                break;
//            case self::MINUTES:
//                $fromDateTime = date('Y-m-d H:i:s', strtotime("-$timeValue minutes"));
//                if ($timeValue >= 20) {
//                    $timeValue = $timeValue - 10;
//                } else {
//                    $timeValue = $timeValue - 5;
//                }
//                $toDateTime = date('Y-m-d H:i:s', strtotime("-$timeValue minutes"));
//                break;
//            case self::HOURS:
//                $fromDateTime = date('Y-m-d H:i:s', strtotime("-$timeValue hours"));
//                $timeValue = $timeValue - 1;
//                $toDateTime = date('Y-m-d H:i:s', strtotime("-$timeValue hours"));
//                break;
//            default:
//                $typeUnderDay = false;
//                $todayDate = date('Y-m-d');
//                $totalDays = self::getDays($timeRange, $timeValue);
//        }
//
//        $query = \DB::table("project_messages")
//            ->select(\DB::raw("MAX(id) as id, project_id,MAX(updated_at) as updated_at"))
//            ->where('project_id', '!=', 0)
//            ->where('is_system_created', '=', 0);
//
//        if ($eventType == ProjectMessage::EVENT_TYPE_EMAIL_CLOSE) {
//            $query = $query->where('event_type', '!=', ProjectMessage::EVENT_TYPE_EMAIL_OPEN)
//                ->where('event_type', '!=', ProjectMessage::EVENT_TYPE_EMAIL_REPLY)
//                ->where('event_type', '=', ProjectMessage::EVENT_TYPE_EMAIL_SEND);
//        } else {
//            $query = $query->where('event_type', '=', $eventType);
//        }
//
//        $query = $query->groupBy('project_id');
//        $query = $query->orderBy('id', 'DESC');
//        $values = $query->get();
//
//        $projectIds = [];
//        foreach ($values as $timeValue) {
//            if ($typeUnderDay) {
//                $lastActivityDate = date('Y-m-d H:i:s', strtotime($timeValue->updated_at));
//                if ($fromDateTime <= $lastActivityDate && $toDateTime >= $lastActivityDate) {
//                    $projectIds[] = $timeValue->project_id;
//                }
//            } else {
//                $lastActivityDate = date('Y-m-d', strtotime($timeValue->updated_at));
//                $inActiveDay = self::countDays($lastActivityDate, $todayDate);
//                if ($inActiveDay == $totalDays) {
//                    $projectIds[] = $timeValue->project_id;
//                }
//            }
//        }
//
//        $projects = Project::with(['projectCompany.company', 'projectSkills'])
//            ->whereIn('id', $projectIds)
//            ->where('channel', '=', $template->channel)
//            ->where('source', '=', $template->project_type)
//            ->get();
//
//        return  $projects;
//    }

    public static function getAutomatedTemplates()
    {
        return $templates = AutomatedMessage::with('template')
            ->orderBy('action_delay_unit', 'DESC')
            ->orderBy('action_delay', 'DESC')
            ->get();
    }
}
