<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;
use Session;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [

        'receive-email-response',
        'partner-registration',
        'frontend-activity',
        'phone-recording',
        'get-project-details',
        'set-project-details',
        'partner-login',
        'check-user-login',
        'email-event-response',
        'save-skills',
        'subscribe-facebook-webhook',
    ];

    /**
     * use to handle expire token in whole system
     *
     */
    public function handle($request, Closure $next)
	{
	  	if($request->input('_token'))
	  	{
		    if ( \Session::token() != $request->input('_token'))
		    {
		    	return redirect()->guest('/login');
		    }
	  	}
	  	return parent::handle($request, $next);
	}
}
