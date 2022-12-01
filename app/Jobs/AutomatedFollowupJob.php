<?php

namespace App\Jobs;

use App\Channels\BaseChannel;
use App\Classes\Slack;
use App\Helpers\Curl;
use App\Helpers\ShortLink;
use App\Models\AutomatedMessage;
use App\Models\Language;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Models\TemplateMessage;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Job;

class AutomatedFollowupJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    public $projectId;
    public $projectMessageId;
    public $automatedMessageId;

    /**
     * @var string
     */

    /**
     * @var string
     */
    public $queue = 'automated-followups';

    /**
     * AutomatedFollowupJob constructor.
     *
     * @param $projectId
     * @param $projectMessageId
     * @param $automatedMessageID
     */
    public function __construct($projectId, $projectMessageId, $automatedMessageId)
    {
        $this->projectId = $projectId;
        $this->projectMessageId = $projectMessageId;
        $this->automatedMessageId = $automatedMessageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $project = Project::where('id', $this->projectId)->first();
            $followupMsg = AutomatedMessage::where('id', $this->automatedMessageId)->first();
            $offeringUser = User::getBiddingUser();
            $template = $followupMsg->template();

            $messagingChannels = explode(',', $followupMsg->action_message_channel);
            $msgChannel = $project->getMessageChannel();
            if (!empty($messagingChannels)) {
                foreach ($messagingChannels as $messagingChannel) {
                    if ($project->canSendMessage($messagingChannel)) {
                        $msgChannel = $messagingChannel;
                        break;
                    }
                }
            }

            $shouldOperate = $project->automated;
            $lastProjectMessage = ProjectMessage::where('project_id', '=', $project->id)->orderBy('id', 'desc')->first();
            // validate last message on project timeline is the same as triggered this followup (if not - abort - client engaged/manual intervention)
            $shouldOperate &= ($lastProjectMessage->id == $this->projectMessageId);

            $slackDetails = array('Automated Message' => $followupMsg->name, 'url'=> ShortLink::getCrmLeadLink($project->id), 'automation on'=> $project->automated, 'jobMessageId' => $this->projectMessageId, 'lastMessageId' => $lastProjectMessage->id);

            if ($shouldOperate) {
                $offer = $project->getOffer($template->slug, $offeringUser);

                $success = $project->sendMessage([
                    'subject' => $offer['title'] ?? '',
                    'message' => $offer['content'] ?? '',
                    'type'  => ProjectMessage::EVENT_TYPE_AUTO_FOLLOWUP,
                    'message_details'  => $followupMsg->id
                ], $msgChannel,$offeringUser,$template);

                Slack::send(new LogJob($success, 'Follow-up message sent',  array('channel' => $msgChannel, 'content' => print_r($slackDetails, true)), LogJob::CHANNEL_FOLLOWUP));
            } else {
                Slack::send(new LogJob(false, 'Aborted Follow-up message (client engaged/manual intervention)',  array('channel' => $msgChannel, 'content' => print_r($slackDetails, true)), LogJob::CHANNEL_FOLLOWUP));
            }

        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
        }
    }
}