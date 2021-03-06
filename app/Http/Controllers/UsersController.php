<?php
namespace App\Http\Controllers;
use App\Models\Users;
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
* @overview Clase que gestiona los datos de los usuarios
*
**/
class UsersController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof UsersController
    */
    public function __construct(){
      //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
      $this->middleware('validate.administrator_user', ['only' => [
        'getUsers',
        'getCountUsers',
        'deleteUser'
      ]]);
    }

    /**
    * Función para la autenticación de los usuarios, devuelve error si hay algún problema
    * @author Junior Milano <junior@sappitotech.com>
    * @param  Request  $request contiene todos los campos del formulario enviado y las variables get
    * @return array
    * @memberof UsersController
    */
    public function authenticate(Request $request,$lang ='en'){
      if(trim($lang)!=='' )
        \App::setLocale($lang);

      $inputs = $request->only('email', 'password','push_id');
      $validator = Validator::make($inputs, [
        'email' => 'required|min:5',
        'password' => 'required|min:8|max:15',
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }
      $token;
      $inputs['external_id']= '';
      //verificamos que el usuario no sea de tipo external
      $verification = Users::where(function($query) use($inputs){
        $query->where('email',$inputs['email'])
        ->orWhere('user_name',$inputs['email']);
      })
      ->where('invited','=',0)->first();
      if($verification){
        if(isset($verification->external_id) && trim($verification->external_id)===''){
          try{
            //verificamos las credenciales y retornamos un error de no poderse autenticar
            $token = JWTAuth::attempt([
              'email' => $inputs['email'],
              'password' => $inputs['password'],
            ]);
            if (!$token){
              $token = JWTAuth::attempt([
                'user_name' => $inputs['email'],
                'password' => $inputs['password'],
              ]);
              if (!$token)
                return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invalid_values'))]];
            }
          }catch (JWTException $e) {
            // Hubo un error
            return ['status'=>'error','data'=>['dss'=>$e,'message'=>htmlentities(\Lang::get('validation.messages.session_impossible'))]];
          }
        }else{
          if(isset($verification->platform) && trim($verification->platform)!=='')
            return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.login_by_external_'.$verification->platform))]];
          else
            return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];
        }
      }else{
        // Hubo un error
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];
      }
      if(isset($inputs['push_id']) && trim($inputs['push_id'])!=='')
        Users::where('id','=',$verification->id)->update(['push_id'=>$inputs['push_id']]);

      // if no errors are encountered we can return a JWT
      return ['status'=>'success-token','data'=>compact('token')];
    }

    /**
    * Función para retornar el token
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function getToken(){
      // obtenemos el token del usuario autenticado, si lo está
      $old_token = JWTAuth::getToken();
      $token='';
      if(trim($old_token)!=='')
        $token = JWTAuth::refresh($old_token);
      if ($token)
        return ['status'=>'success','data'=>['token'=>$token]];

      return ['status'=>'error','data'=>['token'=>'']];
    }

    /**
    * Función para cerrar la sesión del usuario, si hay sesión abierta
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function closeSession(){
      //quitamos el push_id del usuario
      $data_user = $this->getDataUser();
      if(isset($data_user['id']))
        Users::where('id','=',$data_user['id'])->update(['push_id'=>'']);
      // obtenemos el token del usuario autenticado, si lo está
      $token = JWTAuth::getToken();
      if ($token)
        JWTAuth::setToken($token)->invalidate();

      return ['status'=>'success','dsfsdfs'=>$data_user['id']];
    }

    /**
    * Función que retorna la información de los usuarios de forma paginada
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function getUsers($page,$quantity){
      // obtenemos la información del usuario autenticado
      $users = Users::orderBy('users.created_at', 'desc')
      ->orderBy('users.updated_at', 'desc')
      ->limit($quantity)
      ->offset(($page-1)*$quantity)
      ->get([
        'users.id',
        'users.first_name',
        'users.last_name',
        'users.user_name',
        'users.email',
        'users.push_id',
        'users.external_id',
        'users.job_title',
        'users.image_profile'
      ]);

      return ['status'=>'success','data'=>['users' => $users->toArray()]];
    }

    /**
    * Función que retorna la cantidad de usuarios en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function getCountUsers(){
      $count = Users::count();

      return ['status'=>'success','data'=>['users_count' => $count]];
    }

    /**
    * Función que retorna la información del usuario actual
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function getUser(){
      $data_user = $this->getDataUser();
      // obtenemos la información del usuario autenticado
      $user = Users::where('users.id',$data_user['id'])
      ->first([
        'users.id',
        'users.user_name',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.push_id',
        'users.external_id',
        'users.job_title',
        'users.job_description',
        'users.company_name',
        'users.image_profile'
      ]);

      return ['status'=>'success','data'=>['user' => $user->toArray()]];
    }

    /**
    * Función que retorna la información más báasica del usuario actual
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function getBasicUser(){
      $data_user = $this->getDataUser();
      // obtenemos la información del usuario autenticado
      $user = Users::where('users.id',$data_user['id'])
      ->first([
        'users.id',
        'users.first_name',
        'users.last_name',
        'users.push_id',
        'users.email',
        'users.image_profile'
      ]);

      return ['status'=>'success','data'=>['user' => $user->toArray()]];
    }

    /**
    * Función para retornar los datos del usuario en la sesión
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function getDataUser(){
      $verification = Users::verifyAuthentication();

      return $verification['data']['user'];
    }

    /**
    * Función para insertar los datos de un nuevo usuario pasado como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function createUser($lang){
      if(trim($lang)!=='')
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'email' => 'required|email|min:5',
        'user_name' => 'required|min:5',
        'job_title' => 'sometimes|min:3',
        'job_description' => 'sometimes|min:3',
        'company_name' => 'sometimes|min:3',
        'password' => 'required|min:8|max:15',
        'push_id' => 'sometimes|min:3'
      ]);
      //'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/'

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      //validamos que el correo sea unico
      $there_is_mail=Users::where('email',$inputs['email'])->where('invited',0)->first();
      if($there_is_mail)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.email_exist'))]];

      $there_is_user_name=Users::where('user_name',$inputs['user_name'])->where('invited',0)->first();
      if($there_is_user_name)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.username_exist'))]];

      $inputs['invited'] = 0;
      $inputs['raw_password']=$inputs['password'];
      $inputs['password']=\Hash::make($inputs['password']);
      $inputs['external_id']= '';
      $found_user=Users::where(function($query) use($inputs){
        $query->where('email',$inputs['email'])
        ->orWhere('user_name',$inputs['user_name']);
      })->where('invited',1)->first();
      $id_user;
      if(!$found_user){
        $user = Users::create($inputs);
        $id_user = $user->id;
      }else{
        $found_user->update($inputs);
        $id_user = $found_user->id;
      }

      $email_data = [];
      $email_data['raw_password']=$inputs['raw_password'];
      $email_data['email']=$inputs['email'];
      $email_data['user_name']=$inputs['user_name'];
      $email_data['first_name']=$inputs['first_name'];
      $email_data['last_name']=$inputs['last_name'];
      $email_data['dear_email']=htmlentities(\Lang::get('validation.messages.dear_email'));
      $email_data['success_register_email']=htmlentities(\Lang::get('validation.messages.success_register_email'));
      $email_data['your_information_email']=htmlentities(\Lang::get('validation.messages.your_information_email'));
      $email_data['email_label']=htmlentities(\Lang::get('validation.messages.email_email'));
      $email_data['password_email']=htmlentities(\Lang::get('validation.messages.password_email'));
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['register_send_email']=htmlentities(\Lang::get('validation.messages.register_send_email'));
      $images = [];
      if(isset($inputs['image_profile'])){
        $images['image_profile'] =$inputs['image_profile'];
        $images['email'] =$inputs['email'];
        $inputs['image_profile'] = $this->insertImageProfile($images);
      }
/*
      \Mail::send('emails.register_user', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['register_send_email']);
      });
      */
      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];

      try{
        $login = ['email'=> $inputs['email'],'password'=> $inputs['raw_password']];
        //verificamos las credenciales y retornamos un error de no poderse autenticar
        $token = JWTAuth::attempt($login);
        if (!$token)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invalid_values'))]];
      }catch (JWTException $e) {
        // Hubo un error
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.register_session_impossible'))]];
      }
      if(isset($inputs['push_id']) && trim($inputs['push_id'])!=='')
        Users::where('id','=',$id_user)->update(['push_id'=>$inputs['push_id']]);

      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_register')),'user_id'=>$id_user,'token'=>$token]];
    }

    /**
    * Función para insertar los datos de un nuevo usuario pasado como parametro, esta vesión no pasa por el formulario de registro, quizas facebook, instagram, entre otros por su API
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function createExternalUser($lang){
      if(trim($lang)!=='')
        \App::setLocale($lang);

      $inputs = Input::all();
      //validamos que el correo sea unico
      $there_is_user=Users::where('email',$inputs['email'])->where('invited',0)->first(['id','platform','external_id']);
      $token;
      $user_push_id = '';
      $platform =$there_is_user['platform'];
      $created = false;
      if($there_is_user){
        if(trim($there_is_user->external_id)==='')
          return ['status'=>'error','data'=>['type'=>'external_login', 'message'=>htmlentities(\Lang::get('validation.messages.not_external_login'))]];

        $user_push_id = $there_is_user->id;
      }else{
        $inputs['password']= \Hash::make($inputs['email'].$inputs['platform']);
        //lo registramos
        //'number_phones' => 'required|min:7',
        $validator = \Validator::make($inputs, [
          'first_name' => 'required|min:2',
          'external_id' => 'required|min:1',
          'last_name' => 'required|min:2',
          'email' => 'required|email|min:5',
          'job_title' => 'sometimes|min:3',
          'job_description' => 'sometimes|min:3',
          'company_name' => 'sometimes|min:3',
          'platform' => 'sometimes|min:1',
          'push_id' => 'sometimes|min:3'
        ]);

        if ($validator->fails()){
          $errors = $validator->errors();
          return ['status'=>'error','data'=>['message'=>$errors->all()]];
        }
        $inputs['invited'] = 0;
        $id_user;
        $platform = $inputs['platform'];
        $found_user=Users::where('email',$inputs['email'])->where('invited',1)->first();
        if(!$found_user){
          $user = Users::create($inputs);
          $id_user = $user->id;
        }else{
          $found_user->update($inputs);
          $id_user = $found_user->id;
        }
        $created = true;
        $user_push_id = $id_user;
      }
      if($platform===$inputs['platform']){
        try{
          $login = ['email'=> $inputs['email'],'password'=> $inputs['email'].$inputs['platform']];
          //verificamos las credenciales y retornamos un error de no poderse autenticar
          $token = JWTAuth::attempt($login);
          if (!$token)
            return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invalid_values'))]];
        }catch (JWTException $e) {
          // Hubo un error
          return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.session_impossible'))]];
        }
      }else{
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.login_by_external_'.$there_is_user->platform))]];
      }
      if(isset($inputs['push_id']) && trim($inputs['push_id'])!=='')
        Users::where('id','=',$user_push_id)->update(['push_id'=>$inputs['push_id']]);

      return ['status'=>'success-token','data'=>['token'=>compact('token'),'created'=>$created,'message'=>htmlentities(\Lang::get('validation.messages.success_register'))]];
    }

    /**
    * Función para generar token de remembertoken y enviar el correo electrónico con el enlace deeplink
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function forgotPassword($lang){
      if(trim($lang)!=='' )
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'email' => 'required|min:5'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();

        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      $user = Users::where('external_id','')->where(function ($query) use ($inputs) {
          $query->where('email',$inputs['email'])->orWhere('user_name',$inputs['email']);
      })->first();
      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($user['remember_token'])!=='')
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.forgot_password_sent'))]];

      $date_remember = new \DateTime();
      $token = \Hash::make($user['email'].$date_remember->format('YmdHis'));
      $user->update(['remember_token'=>$token]);

      $email_data['email']=$user['email'];
      $email_data['first_name']=$user['first_name'];
      $email_data['last_name']=$user['last_name'];
      $email_data['dear_email']=htmlentities(\Lang::get('validation.messages.dear_email'));
      $email_data['context_send_email']=htmlentities(\Lang::get('validation.messages.context_send_email'));
      $email_data['remember_password_send_mail']=htmlentities(\Lang::get('validation.messages.remember_password_send_mail'));
      $email_data['remember_token']= str_replace('/','---',$token);
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['link']=htmlentities(\Lang::get('validation.messages.remember_password'));
      \Mail::send('emails.remember_password', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['remember_password_send_mail']);
      });

      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
      else
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_forgot_password'))]];
    }


    /**
    * Función para redirecionar la pagina y abrir la app
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function rememberPassword($token){
      $lang = explode('-',\Request::server('HTTP_ACCEPT_LANGUAGE'))[0];
      $data = ['email'=>'','password'=>'','confirm_password'=>'','error'=>false,'success'=>false,'token'=>$token];
      if(trim($lang)!=='' )
        \App::setLocale($lang);
      $verifyToken = Users::where('remember_token',$token)->where('external_id','')->first();

      if(!$verifyToken){
        $data['error_token'] = true;
        $data['error_message'] = \Lang::get('validation.messages.not_token_found');
      }else{
        $inputs = Input::all();
        if(isset($inputs['hidden']) && trim($inputs['hidden'])!=='' && trim($inputs['hidden'])==='form'){
          //'number_phones' => 'required|min:7',
          $data['email'] = trim($inputs['email']);
          $data['password'] = trim($inputs['password']);
          $data['confirm_password'] = trim($inputs['confirm_password']);
          $validator = \Validator::make($inputs, [
            'email' => 'required|min:5',
            'password' => 'required|min:8|max:15',
            'confirm_password' => 'required|min:8|max:15'
          ]);

          if ($validator->fails()){
              $data['error_form']=$validator->errors();
              if(trim($data['error_form']->first('email'))!=='')
                $data['error_email_class'] ='is-invalid';
              else
                $data['error_email_class'] ='is-valid';

                if(trim($data['error_form']->first('password'))!=='')
                  $data['error_password_class'] ='is-invalid';
                else
                  $data['error_password_class'] ='is-valid';

                  if(trim($data['error_form']->first('confirm_password'))!=='')
                    $data['error_confirm_password_class'] ='is-invalid';
                  else
                    $data['error_confirm_password_class'] ='is-valid';
          }else{
            if(trim($inputs['password'])!==trim($inputs['confirm_password'])){
              $data['error_match'] =true;
            }else{
              $user = Users::where('remember_token',$token)->where('external_id','')->where(function ($query) use ($inputs) {
                $query->where('email',$inputs['email'])->orWhere('user_name',$inputs['email']);
              })->first();

              if(!$user){
                $data['error_message'] = htmlentities(\Lang::get('validation.messages.user_not_found'));
              }else{
                $user->update(['password'=>\Hash::make($inputs['password']),'remember_token'=>'']);
                $data['success'] = true;
              }
            }
          }

        }

      }

      return view('reset_form', $data);
    }

    /**
    * Función para actualizar la contraseña por solicitud del usuario
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function resetPassword($lang){
      if(trim($lang)!=='' )
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'email' => 'required|min:5',
        'password' => 'required|min:8|max:15'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();

        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      if(!$inputs['token'] || $inputs['token']==='')
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.token_invalid'))]];

      $user = Users::where('remember_token',$inputs['token'])->where('external_id','')->where(function ($query) use ($inputs) {
          $query->where('email',$inputs['email'])->orWhere('user_name',$inputs['email']);
      })->first();

      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $user->update(['password'=>\Hash::make($inputs['password']),'remember_token'=>'']);

      $login = ['email'=> $inputs['email'],'password'=> $inputs['password']];
      //verificamos las credenciales y retornamos un error de no poderse autenticar
      $token = JWTAuth::attempt($login);

      if (!$token){
        $token = JWTAuth::attempt([
          'user_name' => $inputs['email'],
          'password' => $inputs['password'],
        ]);
        if (!$token)
          return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invalid_values'))]];
      }

      return ['status'=>'success','data'=>[ 'token'=>compact('token'), 'message'=>htmlentities(\Lang::get('validation.messages.success_update')) ] ];
    }

    /**
    * Función para actualizar la contraseña por solicitud del usuario
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function changePassword($lang){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='' )
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'old_password' => 'required|min:8|max:15',
        'password' => 'required|min:8|max:15'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();

        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      $user = Users::where('id',$data_user['id'])->first();

      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if (!\Hash::check($inputs['old_password'],$user['password']  )) {
          return ['status'=>'error','data'=>[ 'message'=>htmlentities(\Lang::get('validation.messages.password_not_match'))]];
      }

      $user->update(['password'=>\Hash::make($inputs['password']),'remember_token'=>'']);

      return ['status'=>'success','data'=>[ 'message'=>htmlentities(\Lang::get('validation.messages.success_update')) ] ];
    }

    /**
    * Función para actualizar los datos del usuario que ha iniciado sesión
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function updateUser($lang){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='')
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'email' => 'required|email|min:5',
        'user_name' => 'required|min:5',
        'job_title' => 'sometimes|min:3',
        'job_description' => 'sometimes|min:3',
        'company_name' => 'sometimes|min:3',
        'push_id' => 'sometimes|min:3'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      $user = Users::find($data_user['id']);
      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      //validamos que el correo sea unico
      $there_is_mail=Users::where('email',$inputs['email'])->where('id','!=',$data_user['id'])->first();
      if($there_is_mail)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.email_exist'))]];

      $there_is_user_name=Users::where('user_name',$inputs['user_name'])->where('id','!=',$data_user['id'])->first();
      if($there_is_user_name)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.username_exist'))]];

      $inputs['id']=$data_user['id'];

      $images = [];
      if(isset($inputs['image_profile'])){
        if(isset($inputs['old_image_profile']))
          $images['old_image_profile'] =$inputs['old_image_profile'];

        $images['image_profile'] =$inputs['image_profile'];
        $images['email'] =$inputs['email'];
        $inputs['image_profile'] = $this->insertImageProfile($images);
      }
      $user->update($inputs);

      return ['status'=>'success','data'=>[ 'message'=>htmlentities(\Lang::get('validation.messages.success_update')) ] ];
    }

    /**
    * Función para guardar en el servidor la imagen de un usuario si existe
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function insertImageProfile($inputs){
        $name='';
        if(isset($inputs['image_profile'])){
            $file=$inputs['image_profile'];
            $photoDate = new \DateTime();
            if($file && $file!=='' && $file!=='undefined'){
              $extension = strtolower($file->guessClientExtension());
                if($extension!=='jpg' && $extension!=='jpeg' && $extension!=='png'){
                  return response()->json(['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.image_invalid_format'))]], ResponseHelper::getTypeResponse('error'), ['content-type'=>ResponseHelper::getCodification()] );
                }else {
                  if(isset($inputs['old_image_profile']) && $inputs['old_image_profile']!==''){
                    $storage=\Illuminate\Support\Facades\Storage::disk('profiles');
                    $storage->delete(($inputs['old_image_profile']));
                  }
                  $find = array(' ','_','ñ','á','é','í','ó','ú','-');
                  $replace = array('','','','','','','','');
                  $new_name=str_replace($find,$replace,$inputs['email'].$photoDate->format('YmdHis').'.'.$extension);
                  $file->move(base_path('/public/images/profiles'),$new_name);
                  $name=$new_name;
                }
            }
        }

        return $name;
    }

    /**
    * Función para eliminar el usuario pasado como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function deleteUser($id){
      $deletedRows = Users::destroy($id);

      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_delete'))]];
    }
}
