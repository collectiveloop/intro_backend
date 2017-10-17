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
* @license Copyright  IF&L Chile 2017. Todos los derechos reservados.
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
      if(trim($lang!=='') )
        \App::setLocale($lang);

      $inputs = $request->only('email', 'password');
      $validator = Validator::make($inputs, [
        'email' => 'required|email|min:5',
        'password' => 'required|min:8|max:15',
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }
      $token;
      $inputs['external_id']= '';
      //verificamos que el usuario no sea de tipo external
      $verification = Users::where('email',$inputs['email'])->first();
      if($verification){
        if(isset($verification->external_id) && trim($verification->external_id)===''){
          try{
            //verificamos las credenciales y retornamos un error de no poderse autenticar
            $token = JWTAuth::attempt($inputs);
            if (!$token)
              return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invalid_values'))]];
          }catch (JWTException $e) {

            // Hubo un error
            return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.session_impossible'))]];
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

      // if no errors are encountered we can return a JWT
      return ['status'=>'success-token','data'=>compact('token')];
    }

    /**
    * Función para retornar el token del usuario autenticado, si lo esta
    * @return boolean, $token
    * @memberof UsersController
    */
    private function getAuthenticationToken(){
      $token = JWTAuth::getToken();
      if(!$token)
          return false;

      return $token;
    }

    /**
    * Función para retornar el token del usuario logueado
    * @return array
    * @memberof UsersController
    */
    public function getToken(){
      $token = $this->getAuthenticationToken();
      if(!$token)
          return ['status'=>'error','data'=>['type'=>'session', 'message'=>htmlentities( \Lang::get('validation.messages.not_token') )]];

      try{
          $token = JWTAuth::refresh($token);
      }catch(TokenInvalidException $e){
          return ['status'=>'error','data'=>['type'=>'session', 'message'=>htmlentities(\Lang::get('validation.messages.token_invalid'))]];
      }

      // retornamos el token si no hay errores
      return ['status'=>'success-token','token'=>compact('token')];
    }

    /**
    * Función para cerrar la sesión del usuario, si hay sesión abierta
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function closeSession(){
      // obtenemos el token del usuario autenticado, si lo está
      $token = $this->getAuthenticationToken();
      if ($token)
        JWTAuth::setToken($token)->invalidate();

      return ['status'=>'success'];
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
        'users.email',
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
    * Función que retorna la información del usuario indicado
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
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.external_id',
        'users.job_title',
        'users.job_description',
        'users.company_name',
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
      if(trim($lang!==''))
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'email' => 'required|email|min:5',
        'job_title' => 'sometimes|min:3',
        'company_name' => 'sometimes|min:3',
        'password' => 'required|min:8|max:15'
      ]);
      //'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/'

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      //validamos que el correo sea unico
      $there_is_mail=Users::where('email',$inputs['email'])->first();
      if($there_is_mail)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.email_exist'))]];
      //verificamos si se solicitó resetar el password
      $inputs['raw_password']=$inputs['password'];
      $inputs['password']=\Hash::make($inputs['password']);
      $inputs['external_id']= '';
      $email_data = [];
      $email_data['raw_password']=$inputs['raw_password'];
      $email_data['email']=$inputs['email'];
      $email_data['first_name']=$inputs['first_name'];
      $email_data['last_name']=$inputs['last_name'];
      $email_data['dear_email']=htmlentities(\Lang::get('validation.messages.dear_email'));
      $email_data['success_register_email']=htmlentities(\Lang::get('validation.messages.success_register_email'));
      $email_data['your_information_email']=htmlentities(\Lang::get('validation.messages.your_information_email'));
      $email_data['email_email']=htmlentities(\Lang::get('validation.messages.email_email'));
      $email_data['password_email']=htmlentities(\Lang::get('validation.messages.password_email'));
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['register_send_email']=htmlentities(\Lang::get('validation.messages.register_send_email'));
      /*
      \Mail::send('emails.register_user', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['register_send_email']);
      });
      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
          */
      $user = Users::create($inputs);

      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_register')),'user_id'=>$user->id]];
    }

    /**
    * Función para insertar los datos de un nuevo usuario pasado como parametro, esta vesión no pasa por el formulario de registro, quizas facebook, instagram, entre otros por su API
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function createExternalUser($lang){
      if(trim($lang!==''))
        \App::setLocale($lang);

      $inputs = Input::all();
      $inputs['password']= \Hash::make($inputs['external_id']);
      //validamos que el correo sea unico
      $there_is_user=Users::where('email',$inputs['email'])->first(['platform','external_id']);
      $token;
      if($there_is_user){
        if($there_is_user->external_id==='')
          return ['status'=>'error','data'=>['type'=>'external_login', 'message'=>htmlentities(\Lang::get('validation.messages.not_external_login'))]];
      }else{
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
          'platform' => 'sometimes|min:1'
        ]);

        if ($validator->fails()){
          $errors = $validator->errors();
          return ['status'=>'error','data'=>['message'=>$errors->all()]];
        }

        $user = Users::create($inputs);
      }
      if($there_is_user['platform']===$inputs['platform']){
        try{
          $login = ['email'=> $inputs['email'],'password'=> $inputs['external_id']];
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

      return ['status'=>'success-token','data'=>['token'=>compact('token'),'message'=>htmlentities(\Lang::get('validation.messages.success_register'))]];
    }

    /**
    * Función para insertar los datos de setting del usuario pasado como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function createSettingUser($id,$lang){
      if(trim($lang!=='') )
        \App::setLocale($lang);
      if(!$id || trim($id) ==='')
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'full_name' => 'sometimes|min:2',
        'job_title' => 'sometimes|min:2',
        'company_name' => 'sometimes|min:3',
        'job_description' => 'sometimes|min:3',
        'email' => 'required|email|min:5'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      $user = Users::find($id);
      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $images = [];

      if(isset($inputs['image_profile'])){
        $images['image_profile'] =$inputs['image_profile'];
        $images['email'] =$inputs['email'];
        $inputs['image_profile'] = $this->insertImageProfile($images);
      }

      $user->update($inputs);

      return ['status'=>'success','data'=>[ 'message'=>htmlentities(\Lang::get('validation.messages.success_update')) ] ];
    }

    /**
    * Función para actualizar los datos del usuario pasado como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof UsersController
    */
    public function updateUser($lang){
      if(trim($lang!=='') )
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'password' => 'sometimes|min:8|max:15',
        'job_title' => 'sometimes|min:3',
        'job_description' => 'sometimes|min:3',
        'company_name' => 'sometimes|min:3'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      //datos del usuario
      $data_user = $this->getDataUser();

      $inputs['id']=$data_user['id'];
      if(isset($inputs['password']) && trim($inputs['password'])!=='')
      {
        $inputs['raw_password'] = $inputs['password'];
        $inputs['password'] = \Hash::make($inputs['password']);
      }
      $user = Users::find($data_user['id']);
      if(!$user)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $images = [];
      if(isset($inputs['old_image_profile']))
        $images['old_image_profile'] =$inputs['old_image_profile'];

      if(isset($inputs['image_profile'])){
        $images['image_profile'] =$inputs['image_profile'];
        $images['email'] =$user['email'];
        $inputs['image_profile'] = $this->insertImageProfile($images);
      }

      $user->update($inputs);

      $email_data = [];
      if(isset($inputs['raw_password']))
        $email_data['raw_password']=$inputs['raw_password'];
      $email_data['email']=$user['email'];
      $email_data['name']=$inputs['name'];
      $email_data['last_name']=$inputs['last_name'];
      $email_data['dear_email']=htmlentities(\Lang::get('validation.messages.dear_email'));
      $email_data['success_update_email']=htmlentities(\Lang::get('validation.messages.success_update_email'));
      $email_data['your_information_email']=htmlentities(\Lang::get('validation.messages.your_information_email'));
      $email_data['email_email']=htmlentities(\Lang::get('validation.messages.email_email'));
      $email_data['password_email']=htmlentities(\Lang::get('validation.messages.password_email'));
      $email_data['number_phones_email']=htmlentities(\Lang::get('validation.messages.number_phones_email'));
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['update_send_email']=htmlentities(\Lang::get('validation.messages.update_send_email'));
      /*
      \Mail::send('emails.update_user', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['update_send_email']);
      });
      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
          */

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
                    return response()->json(['status'=>'error','data'=>['message'=>htmlentities('Imagen con formato inválido')]], ResponseHelper::getTypeResponse('error'), ['content-type'=>ResponseHelper::getCodification()] );
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
