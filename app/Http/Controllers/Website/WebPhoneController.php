<?php

namespace App\Http\Controllers\Website;

use App\Helpers\ShortLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use App\Models\ProjectMessage;

class WebPhoneController extends Controller
{
    /**
     * iframe page of call dialer
     * @param Request $request
     * @param $phone
     * @param $projectID
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request, $phone, $projectID) {
        $number = $phone;
        $countrys = array('1' => '17866303104');
        $i = 4;
        $country = "";
        while ($i > 0) {
            if (isset($countrys[substr($number, 0, $i)])) {
                $country = $countrys[substr($number, 0, $i)];
                break;
            } else {
                $i--;
            }
        }
        if($country == "") {
            $country = "18772320394";
        }
        return view('website.web-phone.index',[
            'country'=>$country,
            'phone'=>$phone,
            'projectID'=>$projectID,
        ]);
    }

    /**
     * call recording store action
     * @param Request $request
     * @return string
     */
    public function recordingStore(Request $request) {
        $file = $request->file('file');
        $projectID = $request->projectID;
        if(!empty($projectID)) {
            $lstcalldata = $request->lstcalldata;
            $phoneNumber = $request->phoneNumber;
            $phoneNumberString = $phoneNumber? "Phone: $phoneNumber ": '';
            $fileName = $projectID."-".time().".".$file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('recordings',$file,$fileName);
            $message = "<b>Call Event</b>".$phoneNumberString.$lstcalldata." <a target='_blank' href='".url('/').Storage::disk('public')->url("recordings/$fileName")."'> Play Recording </a>";
            $projectMessage =new ProjectMessage;
            $projectMessage->project_id = $projectID;
            $projectMessage->message = $message;
            $projectMessage->event_type = ProjectMessage::EVENT_TYPE_CALL;
            $projectMessage->save();
        }
        return "true";
    }
}