<?php
namespace App\Http\Controllers;
use App\Models\Products;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Validator;
/**
*
* @version 1.0
* @license Copyright  IF&L Chile 2017. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que gestiona la administracion de it's possible
*
**/
class AdministrationController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof AdministrationController
    */
    public function __construct(){
        //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
        /*$this->middleware('validate.administrator_user', ['only' => [
            'createProduct',
            'updateProduct',
            'deleteProduct'
        ]]);
        */
    }

    /**
    * Función para hacer login en administración
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof AdministrationController
    */
    public function login(Request $request,$lang = 'en'){
      $error = '';

      if(trim($lang!=='') )
        \App::setLocale($lang);

      if(isset($request->login) && trim($request->login)!==''){
        $inputs = $request->only('email', 'password');

        $validator = Validator::make($inputs, [
          'email' => 'required|email|min:5',
          'password' => 'required|min:8|max:15',
        ]);

        if ($validator->fails()){
          $errors = $validator->errors();
          $error = $errors->all();
        }

        $inputs['id_type_user']= 2;

        //verificamos las credenciales y retornamos un error de no poderse autenticar
        if (JWTAuth::attempt($inputs))
          return redirect('administration/dashboard');
        else
          $error = \Lang::get('validation.messages.session_impossible');


      }else{
        if(isset($request->recover) && trim($request->recover)!==''){
          $there_is_mail=Users::where('email',$inputs['email'])->first();
          if(!$there_is_mail){
            $error = \Lang::get('validation.messages.user_not_found');
          }else{
            $email_data = [];
            $there_is_mail->update();
            $email_data['remember_token']=\Hash::make($inputs['email'].Carbon::now());
            $there_is_mail->remember_token = $email_data['remember_token'];
            $there_is_mail->save();
            $email_data['remember_send_email']=htmlentities(\Lang::get('form.remember_send_email'));
            $email_data['click_remember_email']=htmlentities(\Lang::get('form.click_remember_email'));
            $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
            $email_data['language']=$lang;

            \Mail::send('emails.remember_password', $email_data, function($message) use ($email_data) {
              $message->to($email_data['email']);
              $message->subject($email_data['register_send_email']);
            });
            if(count(\Mail::failures()) > 0)
              return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
            else
              return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_remember_email'))]];

          }
        }
      }

      return view('login', ['error' => $error]);
    }

    /**
    * Función para recordar contraseña
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof AdministrationController
    */

    public function remember(Request $request,$lang = 'en'){
      $error = '';

      if(trim($lang!=='') )
        \App::setLocale($lang);

      if(isset($request->login) && trim($request->login)!==''){
        $inputs = $request->only('email', 'password');

        $validator = Validator::make($inputs, [
          'email' => 'required|email|min:5',
          'password' => 'required|min:8|max:15',
        ]);

        if ($validator->fails()){
          $errors = $validator->errors();
          $error = $errors->all();
        }

        $inputs['id_type_user']= 2;

        //verificamos las credenciales y retornamos un error de no poderse autenticar
        if (JWTAuth::attempt($inputs))
          return redirect('administration/dashboard');
        else
          $error = \Lang::get('validation.messages.session_impossible');


      }else{
        if(isset($request->recover) && trim($request->recover)!==''){
          $there_is_mail=Users::where('email',$inputs['email'])->first();
          if(!$there_is_mail){
            $error = \Lang::get('validation.messages.user_not_found');
          }else{
            $email_data = [];
            $there_is_mail->update();
            $email_data['remember_token']=\Hash::make($inputs['email'].Carbon::now());
            $there_is_mail->remember_token = $email_data['remember_token'];
            $there_is_mail->save();
            $email_data['remember_send_email']=htmlentities(\Lang::get('form.remember_send_email'));
            $email_data['click_remember_email']=htmlentities(\Lang::get('form.click_remember_email'));
            $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
            $email_data['language']=$lang;

            \Mail::send('emails.remember_password', $email_data, function($message) use ($email_data) {
              $message->to($email_data['email']);
              $message->subject($email_data['register_send_email']);
            });
            if(count(\Mail::failures()) > 0)
              return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
            else
              return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_remember_email'))]];

          }
        }
      }

      return view('login', ['error' => $error]);
    }

    /**
    * Función para obtener el dashboard de administración
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof AdministrationController
    */
    public function dashboard($lang = 'en'){
      return view('dashboard', ['name' => 'James']);
    }
}
