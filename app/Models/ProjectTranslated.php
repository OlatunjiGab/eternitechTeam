<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTranslated extends Model
{
    use SoftDeletes;

    protected $table = 'projects_translated';

    protected $hidden = [

    ];

    protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'name', 'description', 'categories', 'channel', 'skills', 'service', 'competition', 'project_type', 'remote', 'provider_type', 'provider_experience', 'qualification', 'project_length', 'project_state', 'project_urgency', 'budget', 'client_knowlegeable', 'client_experience_with_dev', 'industry', 'is_client_it_company', 'client_company_size', 'is_trained', 'is_manual'];


    const SERVICE_WEBSITE = 0;
    const SERVICE_SUPPORT_TO = 1;
    const SERVICE_SOCIAL_MEDIA = 2;
    const SERVICE_GRAPHICS = 3;
    const SERVICE_DATA_ENTRY = 4;
    const SERVICE_WEBSITE_DESIGNER = 5;
    const SERVICE_LOGO = 6;
    const SERVICE_WEB_DEV = 7;
    const SERVICE_WEB_DESIGN = 8;
    const SERVICE_APP = 9;
    const SERVICE_NOT_AVAILABLE = 10;
    const SERVICE = [
        self::SERVICE_WEBSITE => 'Website',
        self::SERVICE_SUPPORT_TO => 'Support to an existing platform for completion',
        self::SERVICE_SOCIAL_MEDIA => 'Social Media Management',
        self::SERVICE_GRAPHICS => 'Graphics landing Page PDFs',
        self::SERVICE_DATA_ENTRY => 'Data Entry Accountant',
        self::SERVICE_WEBSITE_DESIGNER => 'Website Designer',
        self::SERVICE_LOGO => 'Logo Design',
        self::SERVICE_WEB_DEV => 'Web Development',
        self::SERVICE_WEB_DESIGN => 'Web Design',
        self::SERVICE_APP => 'Build an app',
        self::SERVICE_NOT_AVAILABLE => 'Not Available'
    ];

    const PROJECT_TYPE_FULL_TIME = 0;
    const PROJECT_TYPE_HOURLY = 1;
    const PROJECT_TYPE_FIXED = 2;
    const PROJECT_TYPE_INTERNSHIP = 3;
    const PROJECT_NOT_AVAILABLE = 4;
    const PROJECT_TYPE = [
        self::PROJECT_TYPE_FULL_TIME => 'Full Time',
        self::PROJECT_TYPE_HOURLY => 'Hourly Based',
        self::PROJECT_TYPE_FIXED => 'Fixed Price',
        self::PROJECT_TYPE_INTERNSHIP => 'Internship',
        self::PROJECT_NOT_AVAILABLE => 'Not Available'
    ];

    const REMOTE_YES = 0;
    const REMOTE_NO = 1;
    const REMOTE = [
        self::REMOTE_YES => 'Yes',
        self::REMOTE_NO => 'No'
    ];

    const PROVIDER_TYPE_FREELANCER = 0;
    const PROVIDER_TYPE_IT = 1;
    const PROVIDER_TYPE_FULL_TIME = 2;
    const PROVIDER_TYPE_INTERNSHIP = 3;
    const PROVIDER_NOT_AVAILABLE = 4;
    const PROVIDER_TYPE = [
        self::PROVIDER_TYPE_FREELANCER => 'Freelancer',
        self::PROVIDER_TYPE_IT         => 'IT Agency/Company',
        self::PROVIDER_TYPE_FULL_TIME  => 'Full Time Employee',
        self::PROVIDER_TYPE_INTERNSHIP => 'Internship',
        self::PROVIDER_NOT_AVAILABLE => 'Not Available'
    ];

    const EXPERIENCE_FRESHER = 0;
    const EXPERIENCE_JUNIOR = 1;
    const EXPERIENCE_MID = 2;
    const EXPERIENCE_SENIOR = 3;
    const EXPERIENCE_EXPERT = 4;
    const EXPERIENCE_NOT_AVAILABLE = 5;
    const PROVIDER_EXPERIENCE = [
        self::EXPERIENCE_FRESHER => 'Intern/Fresher',
        self::EXPERIENCE_JUNIOR  => 'Junior',
        self::EXPERIENCE_MID     => 'Mid Level',
        self::EXPERIENCE_SENIOR  => 'Senior',
        self::EXPERIENCE_EXPERT  => 'Expert',
        self::EXPERIENCE_NOT_AVAILABLE  => 'Not Available'
    ];

    const QUALIFICATION_GRADUATE = 0;
    const QUALIFICATION_POST_GRADUATE = 1;
    const QUALIFICATION_NOT_AVAILABLE = 2;
    const QUALIFICATION = [
        self::QUALIFICATION_GRADUATE => 'Graduate',
        self::QUALIFICATION_POST_GRADUATE => 'Post Graduate',
        self::QUALIFICATION_NOT_AVAILABLE => 'Not Available'
    ];

    const STATE_NEED = 0;
    const STATE_HAVE_SPECS = 1;
    const STATE_TAKE_OVER = 2;
    const STATE_SUPPORT = 3;
    const STATE_NOT_AVAILABLE = 4;
    const PROJECT_STATE = [
        self::STATE_NEED => 'Need Architecture and Specs',
        self::STATE_HAVE_SPECS => 'Have specs and need to build from scratch',
        self::STATE_TAKE_OVER => 'Take over a project',
        self::STATE_SUPPORT => 'Support existing/finished Project',
        self::STATE_NOT_AVAILABLE => 'Not Available'
    ];
}
