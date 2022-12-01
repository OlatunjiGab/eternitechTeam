<?php

/* ================== Homepage ================== */
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::auth();

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');

/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
    $as = config('laraadmin.adminRoute').'.';

    // Routes for Laravel 5.3
    Route::get('/logout', 'Auth\LoginController@logout');
} else {
    Route::get('/logout', 'Auth\LoginController@logout');
}




Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {

    /* ================== Dashboard ================== */

    Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
    Route::get(config('laraadmin.partnerRoute'), 'LA\DashboardController@partnersIndex');
    Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
    Route::get(config('laraadmin.adminRoute'). '/translate-leads', 'LA\DashboardController@translateLeads');
    Route::get(config('laraadmin.adminRoute'). '/mysql-translate-leads', 'LA\DashboardController@mysqlExportLeadsTranslate');
    Route::get(config('laraadmin.adminRoute'). '/interested-partners', 'LA\DashboardController@interestedPartners');
    Route::get(config('laraadmin.adminRoute'). '/premium-plan-info', 'LA\DashboardController@premiumPlanInfo');
    Route::post(config('laraadmin.adminRoute'). '/get_dashboard_count_data', 'LA\DashboardController@getCountByDate');
    Route::post(config('laraadmin.adminRoute') . '/send-interested-email', 'LA\DashboardController@sendInterestedEmail');

    /* ================== Users ================== */
    Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController',['names' => 'admin.users']);
    Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtAjax');

    /* ================== Uploads ================== */
    Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController',['names' => 'admin.uploads']);
    Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
    Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
    Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
    Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
    Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
    Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');

    /* ================== Roles ================== */
    Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController',['names' => 'admin.roles']);
    Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@saveModuleRolePermissions');

    /* ================== Permissions ================== */
    Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController',['names' => 'admin.permissions']);
    Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');

    /* ================== Departments ================== */
    Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController',['names' => 'admin.departments']);
    Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtAjax');

    /* ================== Employees ================== */
    Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController',['names' => 'admin.employees']);
    Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax', 'LA\EmployeesController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@changePassword');

    /* ================== Organizations ================== */
    Route::resource(config('laraadmin.adminRoute') . '/organizations', 'LA\OrganizationsController',['names' => 'admin.organizations']);
    Route::get(config('laraadmin.adminRoute') . '/organization_dt_ajax', 'LA\OrganizationsController@dtAjax');

    /* ================== Backups ================== */
    Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController',['names' => 'admin.backups']);
    Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@createBackupAjax');
    Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');

    /* ================== Companies ================== */
    Route::resource(config('laraadmin.adminRoute') . '/companies', 'LA\CompaniesController',['names' => 'admin.companies']);
    Route::get(config('laraadmin.adminRoute') . '/company_dt_ajax', 'LA\CompaniesController@dtAjax');
    Route::get(config('laraadmin.adminRoute') . '/contact_dt_ajax', 'LA\CompaniesController@contactAjax');
    Route::get(config('laraadmin.adminRoute') . '/companies/banned_allowd_bidding/{id}', 'LA\CompaniesController@bannedAllowedForBidding');
    Route::post(config('laraadmin.adminRoute') . '/update-client/{id}', 'LA\CompaniesController@updateClient');

    /* ================== Skills ================== */
    Route::resource(config('laraadmin.adminRoute') . '/skills', 'LA\SkillsController',['names' => 'admin.skills']);
    Route::get(config('laraadmin.adminRoute') . '/skill_dt_ajax', 'LA\SkillsController@dtAjax');

    /* ================== Projects ================== */
    Route::post(config('laraadmin.adminRoute') . '/multi_projects_delete', 'LA\ProjectsController@MultipleProjectsDeleted');
    Route::get(config('laraadmin.adminRoute') . '/project_dt_ajax', 'LA\ProjectsController@dtAjax');
    Route::get(config('laraadmin.adminRoute') . '/project_dt_ajax/{today}/{date}', 'LA\ProjectsController@dtAjax');
    Route::get(config('laraadmin.adminRoute') . '/affiliate_dt_ajax/{today}', 'LA\ProjectsController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/project/send_message/{project}', 'LA\ProjectsController@sendMessage');
    Route::get(config('laraadmin.adminRoute') . '/projects/column-setting', 'LA\ProjectsController@columnSetting');
    Route::post(config('laraadmin.adminRoute') . '/projects/column-setting-update', 'LA\ProjectsController@columnSettingUpdate');
    Route::post(config('laraadmin.adminRoute') . '/project/toggle-automation/{project}', 'LA\ProjectsController@toggleAutomation');

    //unRead message mark as read on click message list item
    Route::get(config('laraadmin.adminRoute') . '/projects/is-read/{id}', 'LA\ProjectsController@isRead');
    //Project Index split in all status type
    Route::get(config('laraadmin.adminRoute') . '/project/{type}', 'LA\ProjectsController@index');
    Route::resource(config('laraadmin.adminRoute') . '/projects', 'LA\ProjectsController',['names' => 'admin.projects']);
    Route::get(config('laraadmin.adminRoute') . '/warmProjects', 'LA\ProjectsController@warmProjects');
    Route::get(config('laraadmin.adminRoute') . '/warmProjectsajax', 'LA\ProjectsController@warmProjectsajax');
    Route::post(config('laraadmin.adminRoute') . '/get-phone', 'LA\ProjectsController@getPhone');
    Route::get(config('laraadmin.adminRoute') . '/recent-leads', 'LA\ProjectsController@recentLeads');
    Route::get(config('laraadmin.adminRoute') . '/recent_lead_dt_ajax', 'LA\ProjectsController@recentLeadDtAjax');
    Route::get(config('laraadmin.adminRoute') . '/recent-lead-event', 'LA\ProjectsController@recentLeadEvent');
    Route::get(config('laraadmin.adminRoute') . '/recent-lead-event/{eventType}', 'LA\ProjectsController@recentLeadEvent');

    /* ================== Suppliers ================== */
    Route::resource(config('laraadmin.adminRoute') . '/partners', 'LA\SuppliersController',['names' => 'admin.partners']);
    Route::get(config('laraadmin.adminRoute') . '/supplier_dt_ajax', 'LA\SuppliersController@dtAjax');
    Route::get(config('laraadmin.adminRoute') . '/partners-by-skills', 'LA\SuppliersController@PartnersBySkills');
    Route::get(config('laraadmin.adminRoute') . '/partners-by-skills-ajax', 'LA\SuppliersController@PartnersBySkillsAJAX');
    Route::get(config('laraadmin.adminRoute') . '/partners-skills-ajax/{id}', 'LA\SuppliersController@PartnersBySkillsAJAX');

    /* ================== Static COntent ================== */
    Route::get(config('laraadmin.adminRoute') . '/templates/all_template_edit/{type}', 'LA\TemplateMessagesController@allTemplateMessageEdit');
    Route::post(config('laraadmin.adminRoute') . '/templates/store_all', 'LA\TemplateMessagesController@storeAll');

    Route::resource(config('laraadmin.adminRoute') . '/templates', 'LA\TemplateMessagesController',['names' => 'admin.templates']);
    Route::get(config('laraadmin.adminRoute') . '/template_dt_ajax', 'LA\TemplateMessagesController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/get-template-preview', 'LA\TemplateMessagesController@getTemplatePreview');
    Route::get(config('laraadmin.adminRoute') . '/template-statistics', 'LA\TemplateMessagesController@templateStatistics');
    
    Route::resource(config('laraadmin.adminRoute') . '/automate-message', 'LA\AutomateMessageController',['names' => 'admin.automate-message']);
    Route::get(config('laraadmin.adminRoute') . '/automate-message-ajax', 'LA\AutomateMessageController@dtAjax');

    /* ================== Language ================== */
    Route::resource(config('laraadmin.adminRoute') . '/language', 'LA\LanguageController',['names' => 'admin.language']);
    Route::get(config('laraadmin.adminRoute') . '/language_dt_ajax', 'LA\LanguageController@dtAjax');
    Route::get(config('laraadmin.adminRoute') . '/change_status/{id}', 'LA\LanguageController@changeStatus');


    /* ================== place bid manually ================== */
    Route::post('admin/placebid/{project}/{type}','LA\ProjectsController@placeBid');
    Route::post('admin/place-individual-bid/{project}/{type}','LA\ProjectsController@placeIndividualBid');


    /* ================== Countries ================== */
    Route::resource(config('laraadmin.adminRoute') . '/countries', 'LA\CountriesController',['names' => 'admin.countries']);
    Route::get(config('laraadmin.adminRoute') . '/country_dt_ajax', 'LA\CountriesController@dtAjax');

    /* ================== Regions ================== */
    Route::resource(config('laraadmin.adminRoute') . '/regions', 'LA\RegionsController',['names' => 'admin.regions']);
    Route::get(config('laraadmin.adminRoute') . '/region_dt_ajax', 'LA\RegionsController@dtAjax');

    /* ================== Experts ================== */
    Route::resource(config('laraadmin.adminRoute') . '/experts', 'LA\ExpertsController',['names' => 'admin.experts']);
    Route::get(config('laraadmin.adminRoute') . '/expert_dt_ajax', 'LA\ExpertsController@dtAjax');

    /* ================== Partner Access ================== */
    Route::get(config('laraadmin.adminRoute'). '/partner-access/{id}', 'LA\PartnerAccessController@index');
    Route::post(config('laraadmin.adminRoute') . '/save_partner_access/{id}', 'LA\PartnerAccessController@store');

    Route::get(config('laraadmin.adminRoute') .'/phone/call/{phone}/{projectID}','Website\WebPhoneController@index');



    /* ================== Popup Content ================== */
    Route::resource(config('laraadmin.adminRoute') . '/popup-content', 'LA\PopupContentController',['names' => 'admin.popup-content']);
    Route::get(config('laraadmin.adminRoute') . '/popup-content-ajax', 'LA\PopupContentController@dtAjax');

    /* ================== Portfolio ================== */
    Route::resource(config('laraadmin.adminRoute') . '/portfolios', 'LA\PortfoliosController',['names' => 'admin.portfolios']);
    Route::get(config('laraadmin.adminRoute') . '/portfolios-ajax', 'LA\PortfoliosController@dtAjax');
    Route::post(config('laraadmin.adminRoute') . '/portfolio-is-live/{id}', 'LA\PortfoliosController@isLive');
    Route::post(config('laraadmin.adminRoute') . '/expert-is-live/{id}', 'LA\ExpertsController@isLive');

    Horizon::auth(function ($request) {
        return Entrust::hasRole('SUPER_ADMIN');
    });

    /* ================== Project Translate ================== */
    Route::resource(config('laraadmin.adminRoute') . '/project-translate', 'LA\LeadsTrainingController',['names' => 'admin.project-translate']);
    Route::get(config('laraadmin.adminRoute') . '/project_translate_dt_ajax', 'LA\LeadsTrainingController@dtAjax');
    Route::get(config('laraadmin.adminRoute') . '/project_translate_description', 'LA\LeadsTrainingController@getDescription');
    Route::get(config('laraadmin.adminRoute') . '/lead-page-name/{page}', 'LA\ProjectsController@pageName');
    Route::get(config('laraadmin.adminRoute') . '/lead-page-name/{menu}', 'LA\ProjectsController@menuName');
    Route::get(config('laraadmin.adminRoute') . '/lead_dt_ajax/{menuName}', 'LA\ProjectsController@dtLeadAjax');
    
});