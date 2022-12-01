<?php
define('MAIN_SITE', 'https://eternitech.com');
define('WHATSAPP_PHONE_NUMBER', '17868392506');

define('PARTNERS_LANDING_PAGE','/partner');

return [

    'mailDomain' => '@eterni.tech',
    'USER_TYPE_CANDIDATE' => 'candidate',
    'WHATSAPP_SEND_MESSAGE_LINK' => 'https://wa.me/',
    'lead_short_link_prefix' => 'https://eterni.tech/lead/',
    'mailPrefix' => 'reply_',
    'mailFrom' => 'Eternitech',
    'fromEmailAddress' => 'sales@eterni.tech',
    'adminEmailAddress' => 'sales@eternitech.com',
    'noReplyEmailAddress' => 'no-reply@eternitech.com',
    /*
    |--------------------------------------------------------------------------
    | Static Content Language && Templates
    |--------------------------------------------------------------------------
    |
    | Define Language
    |
    */
    'language' => ['en'=>'English','he'=>'Hebrew'],

    'templates' => \App\Models\TemplateMessage::TEMPLATE_LIST,

    /*
    |--------------------------------------------------------------------------
    | Project Status
    |--------------------------------------------------------------------------
    |
    | Get all project status
    |
    */
    
    /*
    |--------------------------------------------------------------------------
    | MailTypes
    |--------------------------------------------------------------------------
    |
    | Get mail type define
    |
    */
    'parse'=>[
        'mail_types' => ['project'=>'project','xplace'=>'xplace','email'=>'email'],
        'forwardTo'=>'/To:/',
        'forwardFrom'=>'/From:/',
        'phoneRegex' => '/\b[0-9]{3}\s*[-]?\s*[0-9]{3}\s*[-]?\s*[0-9]{4}\b/',
        'xplaceProjectURL'=>'www.xplace.com?p'
    ],

    /*
    |--------------------------------------------------------------------------
    | Channel
    |--------------------------------------------------------------------------
    |
    | by default value are assign on default index which is xplace
    |
    */
    'channel' => [
        'options' => [
            'site'       => 'Site',
            'email'      => 'Email',
            'xplace'     => 'Xplace',
            'freelancer' => 'Freelancer.com',
            'craigslist' => 'Craigslist',
            'partner'    => 'Partner',
            'linkedin'   => 'LinkedIn'
        ],
        'default' => 'email'
    ],

    /*
    |--------------------------------------------------------------------------
    | OLD-DB migration
    |--------------------------------------------------------------------------
    |
    | 
    |
    */
    'old_db_migration' => [
        'tables'    =>  [
            'companies'         =>  'companies',
            'company_contacts'  =>  'contact_persons',
            //'users'             =>  'users',
            'projects'          =>  'projects',
            'project_messages' =>  'project_notes',
            'project_companies' =>  'project_companies',
            'skills'            =>  'skills',
            'project_skills'    =>  'project_skills',
            'suppliers'         =>  'workers_skills',
            'supplier_skills'   =>  'workers_skills',
            //'template_messages' =>  'static_contents'  
        ],
        'column'    => [
            'companies' => [
                            'id','IFNULL(name,"") as name','IFNULL(email,"") as email','IFNULL(homepage,"") as homepage','IFNULL(address,"") as address',
                            'IFNULL(address2,"") as address2','IFNULL(city,"") as city','IFNULL(state,"") as state','IFNULL(country,"") as country',
                            'IFNULL(zipcode,"") as zipcode','IFNULL(ongoing_potential,"") as strategic','IFNULL(phone_number,"") as phone','IFNULL(fax_number,"") as fax',
                            'IFNULL(logo_file,"") as logo_url','language','created_on as created_at','updated_on as updated_at','"xplace" as channel'
                        ],
            'company_contacts' => [
                            'contact_persons.id','contact_persons.company_id','IFNULL(contact_persons.first_name,"") as first_name',
                            'IFNULL(contact_persons.last_name,"") as last_name','IFNULL(contact_persons.telephone,"") as phone',
                            'IFNULL(contact_persons.email,"") as email','IFNULL(companies.language,"") as language','IFNULL(contact_persons.is_prime,0) as is_prime'
                        ],
            'skills' => [
                            'id','IFNULL(keyword,"") as keyword','IFNULL(description,"") as description','IFNULL(keywords,"") as keywords',
                            'IFNULL(hourly_rate,"0.0") as hourly_rate','IFNULL(reply_text,"") as reply_text','"" as  url'
                        ],
            'projects' => [
                            'projects.id','IFNULL(projects.name,"") as name','IFNULL(projects.description,"") as description','IFNULL(projects.categories,"") as categories',
                            'IFNULL(projects.project_status,0) as status','IFNULL(projects.project_budget,"") as project_budget','IFNULL(projects.hourly,0) as is_hourly',
                            'IFNULL(projects.url,"")  as  url','"xplace" as channel','projects.created_on as created_at','projects.updated_on as updated_at'
                        ],
            'project_companies' => [
                            'project_id','company_id'
                        ],
            'project_messages' => [
                            'id','project_id as project_id','IFNULL(text,"") as message','IFNULL(owner_id,0) as sender_id','"" as message_details','"xplace" as  channel','date_created as created_at','date_created as updated_at'
                        ],
            'project_skills' => [
                            'project_id as project_id','skill_id as skill_id'
                        ],
            'suppliers' => [
                            'workers_skills.company_id','SUM(IFNULL((workers_skills.hourly_rate),"0.0")) as hourly_rate',
                            'IFNULL(companies.supplier_rate,"0.0") as closing_rate','"" as  avg_response_time'
                        ],
            'supplier_skills' => [
                            'company_id','skill_id'
                        ],
            /*'template_messages' => [
                            'IFNULL(keyword,"") as keyword','IFNULL(description,"") as description','IFNULL(keywords,"") as keywords',
                            'IFNULL(hourly_rate,"0.0") as hourly_rate','IFNULL(reply_text,"") as reply_text','"" as  url'
                        ],*/
            /*'users' => [
                            'IFNULL(keyword,"") as keyword','IFNULL(description,"") as description','IFNULL(keywords,"") as keywords',
                            'IFNULL(hourly_rate,"0.0") as hourly_rate','IFNULL(reply_text,"") as reply_text','"" as  url'
                        ],*/

        ]
    ]
];
