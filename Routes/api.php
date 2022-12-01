<?php

use App\Classes\WriteLog;
use Illuminate\Http\Request;

Route::get("craigslist_bidplaced/{projectId}",'IncomingController@craigslistBidPlaced');
Route::post("handle_incoming_emails",'IncomingController@handleIncomingEmails');
Route::post("handle_incoming_reply_emails",'IncomingController@handleIncomingReplyEmails');
Route::post('expertDetails', 'LA\ExpertsController@addNewExpertCV')->middleware("cors");
Route::post('download-cv-form', 'LA\ExpertsController@downloadCvForm')->middleware("cors");
Route::post('hire-developer-form', 'Website\MonitoringController@hireDeveloperForm')->middleware("cors");
Route::post('build-your-team-form', 'Website\MonitoringController@buildYourTeamForm')->middleware("cors");
Route::post('get-quote-form', 'Website\MonitoringController@getQuoteForm')->middleware("cors");
Route::post('meeting-form', 'Website\MonitoringController@meetingForm')->middleware("cors");
Route::post('contact-form', 'Website\MonitoringController@contactForm')->middleware("cors");
Route::post('checkout-form', 'Website\MonitoringController@checkoutForm')->middleware("cors");
Route::post('online-tools', 'Website\MonitoringController@onlineTools')->middleware("cors");
Route::post('get-online-tools', 'Website\MonitoringController@getOnlineTools')->middleware("cors");
Route::post('add-ratings', 'Website\MonitoringController@addRatings')->middleware("cors");
//Route::post('uploadfile', 'LA\ExpertsController@uploadfile');
Route::get('handle', function(Request $request) {
	WriteLog::write('Inbound/log.txt',"Start Incoming Emails"."\r\n" .json_encode($request->all())."End Incoming Emails");
	die("asd");
});