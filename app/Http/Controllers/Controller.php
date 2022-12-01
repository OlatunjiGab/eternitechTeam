<?php

namespace App\Http\Controllers;

use App\Models\ProjectMessage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use DB;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $routes = ['Prospects','Partners','Leads','Experts'];
    public $deleteMessage = "return confirm('Are you sure you want to delete this?');";
    protected $unReadMessages;

    public function __construct()
    {
        $this->unReadMessages = DB::table("project_messages")
            ->join('projects','project_messages.project_id','=','projects.id')
            ->select(DB::raw("project_messages.id as id, project_id,
                projects.name as project_name,
                project_messages.message as event_message,
                project_messages.updated_at as event_datetime"))
            ->where('project_id', '!=', 0)
            ->where('is_system_created', '=', 0)
            ->where('is_read', '=', 0)
            ->where('event_type', '=',ProjectMessage::EVENT_TYPE_EMAIL_REPLY)
            ->orderBy('id', 'DESC')
            ->get()
            ->unique('project_id');

        View::share('unReadMessages', $this->unReadMessages);
    }
}
