<?php
namespace App\Http\Controllers;
use App\Models\Users;
use App\Models\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

/**
*
* @version 1.0
* @license Copyright Sappitotech 2017. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que gestiona los mensajes enviados desde contact us
*
**/
class ContactUsController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof ContactUsController
    */
    public function __construct(){}
      /**
      * Función para retornar los datos del usuario en la sesión
      * @author Junior Milano <junior@sappitotech.com>
      * @return array
      * @memberof ContactUsController
      */
      public function getDataUser(){
        $verification = Users::verifyAuthentication();

        return $verification['data']['user'];
      }

    /**
    * Función para insertar los datos de un nuevo usuario pasado como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactUsController
    */
    public function sendMessage($lang){
      if(trim($lang)!=='')
        \App::setLocale($lang);

      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $inputs = Input::all();
      $validator = \Validator::make($inputs, [
        'message' => 'required|min:10'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      //datos del usuario que envía el mensaje
      $user=Users::where('id',$data_user['id'])->first();
      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_exist'))]];

      $general=General::where('name','email_contact_us')->first(['value']);
      if(!$general)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.email_no_exist_contact_us'))]];

      $email_data['first_name']=$user['first_name'];
      $email_data['last_name']=$user['last_name'];
      $email_data['email']=$general['value'];
      $email_data['user_name']=$user['user_name'];
      $email_data['user_email']=$user['email'];
      $email_data['user_email_label']=htmlentities(\Lang::get('validation.messages.email_label'));
      $email_data['user_label']=htmlentities(\Lang::get('validation.messages.user_email'));
      $email_data['name_label']=htmlentities(\Lang::get('validation.messages.name_label'));
      $email_data['contact_us_email_init']=htmlentities(\Lang::get('validation.messages.contact_us_email_init'));
      $email_data['contact_us_email_end']=htmlentities(\Lang::get('validation.messages.contact_us_email_end'));
      $email_data['content']=$inputs['message'];
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['subject_email_contact_us']=htmlentities(\Lang::get('validation.messages.subject_email_contact_us').' '.$user['first_name'].' '.$user['last_name']);

      \Mail::send('emails.contact_us', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['subject_email_contact_us']);
      });
      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];


      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_contact_us'))]];
    }
}
