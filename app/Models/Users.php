<?php
namespace App\Models;
use Illuminate\Auth\Authenticatable;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Users extends Model implements AuthenticatableContract, CanResetPasswordContract{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_name','password','first_name', 'last_name', 'email', 'external_id','image_profile', 'remember_token','job_title','job_description','push_id','company_name','platform'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password','remember_token'];

    /**
    * Función para ver si hay token o no en el request
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof Users
    */
    private static function thereIsToken(){
        $token = JWTAuth::getToken();
        if(!$token)
            return false;

        return true;
    }
    /**
    * Función para verificar el inicio de sesión en Web
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof Users
    */
    public static function verifyAuthentication(){
        $error=['status'=>'error','data'=>['type'=>'session','message'=>htmlentities(\Lang::get('validation.messages.token_expired'))]];
        $validate=self::thereIsToken();
        if(!$validate)
            return $error;
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user)
            return $error;

        return ['status'=>'success','data'=>['user'=>$user]];
    }

}
