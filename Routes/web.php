<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', function () {
	/*\Mail::send('welcome', [], function ($message){
		$message->setBody("some html string", 'text/html');
	    $message->to('markjerks@gmail.com')->subject('demomesila');
	});
	die("mail sent");*/
    return view('welcome');
});

/* ================== Homepage + Admin Routes ================== */
//if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
	require base_path('Routes/admin_routes.php');
//} else {
//	// require app_path('Http/admin_routes.php');
//}

/* ================== All APIS routes ================== */

/*Route::group(['prefix' => 'api'], function () {
	require __DIR__.'/api.php';
});*/
Route::get('api/sendgrid', 'IncomingController@checkSendgridMail');
//Route::get('partner-registration', 'Website\PartnersController@partnerRegistrationFrom');
Route::get('impersonate/user/{id}', 'LA\UsersController@impersonate')->name('impersonate');
Route::get('impersonate/destroy', 'LA\UsersController@destroyimpersonate')->name('LA.impersonate.destroy');
Route::match(['get', 'post'], '/partner-registration', 'Website\PartnersController@partnerRegistrationFrom');
Route::post('save-skills', 'Website\PartnersController@savePartnerSkills');
Route::get('thank-you', 'Website\PartnersController@thankYou');
Route::post('partner-login', 'Website\PartnersController@partnerLogin');
Route::get('update-supplier-skills', 'Website\PartnersController@supplierSkillUpdate');
Route::get('delete-supplier-skills', 'Website\PartnersController@deleteSupplierSkill');
Route::get('api/portfolios', 'LA\PortfoliosController@getPortfolios');
Route::get('api/portfolio/{slug}', 'LA\PortfoliosController@getPortfolioDetail');

// Website Talents (Experts)
Route::get("talents",'Website\TalentsController@getFilters');
Route::get("talent/{expert}",'Website\TalentsController@getExpertDetails');

// Website Monitoring
Route::get("wa",'Website\MonitoringController@getWhatsAppUrl');

// get and store phone number using whatsapp short link click
Route::get("get-phone-number/{id}",'Website\MonitoringController@getPhoneNumber');
Route::post("store-phone-number/{id}",'Website\MonitoringController@storePhoneNumber');
Route::get("get-otp/{key}",'Website\PartnersController@getOTPAccessCode');
Route::post("set-otp/{key}",'Website\PartnersController@setOTPAccessCode');

Route::post("frontend-activity",'Website\MonitoringController@frontendActivityAdd')->middleware("cors");
Route::post("get-project-details",'Website\MonitoringController@getProjectDetails')->middleware("cors");
Route::post("set-project-details",'Website\MonitoringController@setProjectDetails')->middleware("cors");
Route::any("check-user-login",'Website\MonitoringController@checkUserLogin')->middleware("cors");
Route::get("generate-lead-link/{pid}",'Website\MonitoringController@getLeadShortLink');
Route::get("{type}/{id}",'Website\MonitoringController@projectClientActivity');

/* ================== For Resource File public access link ================== */
Route::get('/assets/js/{filename}', function($filename){
    $path = resource_path() . '/assets/js/' . $filename;
    if(!File::exists($path)) {
        return response()->json(['message' => 'file not found.'], 404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});
/* ================== For test incoming mail from sendgrid using webhook ================== */
Route::any("receive-email-response", 'Website\MonitoringController@receiveEmailResponse')->middleware("cors");
Route::any("email-event-response", 'Website\MonitoringController@emailEventResponse')->middleware("cors");
Route::any("subscribe-facebook-webhook", 'Website\MonitoringController@subscribeFacebookWebhook')->middleware("cors");

Route::any('phone-recording','Website\WebPhoneController@recordingStore');

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}