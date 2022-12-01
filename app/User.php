<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\TemplateMessage;
use App\Notifications\ResetPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Notifications\Notifiable;
use Config;
use Illuminate\Support\HtmlString;

/**
 * Class User
 * @package App
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    // use SoftDeletes;
    use EntrustUserTrait, Notifiable;

    const USER_EMPLOYEE = 'Employee';

    protected $table = 'users';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'name', 'email', 'password', "role", "context_id",'supplier_id', "type", "profile_picture"
	];
	
	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
	protected $hidden = [
		'password', 'remember_token',
    ];
    
    // protected $dates = ['deleted_at'];

    /**
     * @return mixed
     */
    public function uploads()
    {
        return $this->hasMany('App\Upload');
    }

    public function role_user()
    {
        return $this->hasOne('App\Models\RoleUser');
    }

    public function employee()
    {
        $employee = Employee::where('id',$this->context_id)->first();
        if ($employee) {
            return $employee;
        }
        return null;
    }
    public function partner()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }
    public function company()
    {
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function canAccess()
    {
        $canAccess = true;
        if(\Entrust::hasRole("PARTNER") && \Auth::user()->company->plan_type == Company::PLAN_COMMUNITY) {
            $canAccess = false;
        }
        return $canAccess;
    }

    public function getFirstName() {
        $parts = preg_split('/\s+/', $this->name);
        return current($parts);
    }

    public function getLastName() {
        $parts = preg_split('/\s+/', $this->name);
        return end($parts);
    }

    public static function getBiddingUser() {
        return self::first();
    }

    public static function getCurrentUser() {
        $user = \Auth::user();
        if (empty($user)) {
            $user = self::getBiddingUser();
        }
        return $user;
    }

    public function getFromEmailAddress() {
        $userName = strtolower(str_replace(" ",".",$this->name));
        $fromAddress = $userName? $userName.Config::get('constant.mailDomain') : Config::get('constant.fromEmailAddress');
        return $fromAddress;
    }

    public function getFromName() {
        $fromUsername = $this->name? $this->name :Config::get('constant.mailFrom');
        return $fromUsername;
    }

    public function getEmailSignature($language, $projectId) {
        if ($templateEmailSignature = TemplateMessage::getTemplate(TemplateMessage::EMAIL_SIGNATURE,$language)) {
            $getFromName = '';
            if (!empty($this->getFromName())) {
                $getFromName = new HtmlString('<strong>' . $this->getFromName() . '</strong>');
            }
            $about = '';
            if (!empty($this->employee()->about)) {
                $about = new HtmlString('<strong>' . $this->employee()->about . '</strong>');
            }
            $emailSignatureArray = array(
                'userFullName'    => $getFromName ?:'',
                'userAbout'       => $about?:'',
            );

            return $templateEmailSignature->generate($projectId ,$emailSignatureArray);
        }
        return '';
    }

    public function getBidUserDetails()
    {
        return array(
            'userFirstName'      => $this->getFirstName(),
            'userLastName'       => $this->getLastName(),
            'userPhoneExtension' => '',
            'userEmail'          => $this->email
        );
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
