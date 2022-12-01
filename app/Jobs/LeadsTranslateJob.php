<?php

namespace App\Jobs;

use App\Classes\Slack;
use App\Helpers\Curl;
use App\Models\Project;
use App\Notifications\ExceptionCought;
use App\Notifications\LogJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Job;
use App\Models\ProjectTranslated;
//use Stichoza\GoogleTranslate\GoogleTranslate;
//use Google\Cloud\Translate\V3\TranslationServiceClient;
use Google\Cloud\Translate\V2\TranslateClient;

class LeadsTranslateJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;
    
    /**
     * @var string
     */
    public $queue = 'get-leads'; // temporary based on this queue as the scraper server cannot hold it.

    /**
     * LeadsTranslateJob constructor.
     *
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $projects  = \DB::table("projects")->where('deleted_at', '=', null)->get();
            if (!empty($projects)) {
                $is_it_related = 1;
                $is_trained = 0;
                            
                $translate = new TranslateClient([
                    'key' => env('GOOGLE_CLOUD_TRANSLATE_API_KEY')
                ]);

                // Translate text from english to french.
                /*$result = $translate->translate('Hello world!', [
                    'target' => 'fr'
                ]);
                echo $result['text'] . "\n";*/

                foreach ($projects as $project) {
                    if($project->status == Project::STATUS_NOT_RELEVANT){
                        $is_it_related = 0;
                        $is_trained = 1;
                    }
                    $projectTranslated = ProjectTranslated::where('id','=',$project->id)->first();
                    if (!$projectTranslated) {
                
                        //$result = $translate->translate('Hello world!', ['target' => 'fr']);

                        $resProjectName = $project->name? $translate->translate($project->name, ['target' => 'en']) : $project->name;
                        $project->name = $resProjectName['text'];

                        $resProjectDescription = $project->description? $translate->translate($project->description, ['target' => 'en']) : $project->description;
                        $project->description = $resProjectDescription['text'];

                        $resProjectCategories = $project->categories? $translate->translate($project->categories, ['target' => 'en']) : $project->categories;
                        $project->categories = $resProjectCategories['text'];

                        $resProjectChannel = $project->channel? $translate->translate($project->channel, ['target' => 'en']) : $project->channel;
                        $project->channel = $resProjectChannel['text'];
                    
                        $project->is_trained = $is_trained;
                        $project->is_it_related = $is_it_related;
                        $projectTranslated = new ProjectTranslated((array) $project);
                        $projectTranslated->save();
                    }
                }
            } else {
                Slack::send('Cannot find projects');
            }
        } catch (\Exception $e) {
            Slack::send(new ExceptionCought($e));
        }
    }
}