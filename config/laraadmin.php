<?php
/**
 * Config genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

return [
    
	/*
    |--------------------------------------------------------------------------
    | General Configuration
    |--------------------------------------------------------------------------
    */
    
	'adminRoute' => 'admin',
	'partnerRoute' => 'partners',

    /*
    |--------------------------------------------------------------------------
    | Uploads Configuration
    |--------------------------------------------------------------------------
    |
    | private_uploads: Show that uploaded file remains private and can be seen by respective owners only
    | default_uploads_security: public / private
    | 
    */
    'uploads' => [
        'private_uploads' => false,
        'default_public' => false,
        'allow_filename_change' => true
    ],
];