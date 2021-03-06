<?php
namespace App\Http\Controllers;
use App\Models\Intros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

/**
*
* @version 1.0
* @license Copyright  Sappitotech 2017. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que gestiona las introducciones de los usuarios en el sistema (INTROS)
*
**/
class IntrosController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof IntrosController
    */
    public function __construct(){
      //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
      $this->middleware('validate.administrator_user', ['only' => []]);
    }

    /**
    * Función para retornar los datos del usuario en la sesión
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof IntrosController
    */
    public function getDataUser(){
      $verification = \App\Models\Users::verifyAuthentication();

      return $verification['data']['user'];
    }

    /**
    * Función que retorna la información los intros para el dashbioard, totales, y el ultimo registro de invitationes hechas y recibidas
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof IntrosController
    */
    public function getIntrosDashBoard($lang){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      //invitaciones hechas
      $made = Intros::where('intros.id_user',$data_user['id'])
      ->join('users_friends as user_friend_1', function ($join) {
        $join->on('user_friend_1.id','=','intros.id_friend_1')
        ->on('user_friend_1.id_user', '=', 'intros.id_user');
      })
      ->join('users as friend_1','user_friend_1.id_user_friend','=','friend_1.id')
      ->join('users_friends as user_friend_2', function ($join) {
        $join->on('user_friend_2.id','=','intros.id_friend_2')
        ->on('user_friend_2.id_user', '=', 'intros.id_user');
      })
      ->join('users as friend_2','user_friend_2.id_user_friend','=','friend_2.id')
      ->orderBy('intros.created_at', 'desc')
      ->orderBy('intros.updated_at', 'desc')
      ->first([
        'intros.id',
        'intros.id_friend_1',
        'friend_1.first_name as friend_1_first_name',
        'friend_1.last_name as friend_1_last_name',
        'friend_1.image_profile as friend_1_image_profile',
        'intros.id_friend_2',
        'friend_2.first_name as friend_2_first_name',
        'friend_2.last_name as friend_2_last_name',
        'friend_2.image_profile as friend_2_image_profile',
        'intros.reason'
      ]);

      //invitaciones hechas
      $count_made = Intros::where('intros.id_user',$data_user['id'])
      ->join('users_friends as user_friend_1', function ($join) {
        $join->on('user_friend_1.id','=','intros.id_friend_1')
        ->on('user_friend_1.id_user', '=', 'intros.id_user');
      })
      ->join('users as friend_1','user_friend_1.id_user_friend','=','friend_1.id')
      ->join('users_friends as user_friend_2', function ($join) {
        $join->on('user_friend_2.id','=','intros.id_friend_2')
        ->on('user_friend_2.id_user', '=', 'intros.id_user');
      })
      ->count();

      $received = Intros::join('users_friends as owner_friend',function($join) use ($data_user){
        $join->on(function($join2){
            $join2->on('owner_friend.id','=','intros.id_friend_1')
            ->orOn('owner_friend.id','=','intros.id_friend_2');
        })
        ->where('owner_friend.id_user_friend', '=', $data_user['id']);
      })
      ->join('users_friends as user_friend_1','user_friend_1.id','=','intros.id_friend_1')
      ->join('users as user_1','user_1.id','=','user_friend_1.id_user_friend')
      ->join('users_friends as user_friend_2','user_friend_2.id','=','intros.id_friend_2')
      ->join('users as user_2','user_2.id','=','user_friend_2.id_user_friend')
      ->orderBy('intros.created_at', 'desc')
      ->orderBy('intros.updated_at', 'desc')
      ->first([
        'intros.id',
        'intros.id_friend_1',
        'intros.id_friend_2',
        'user_1.id as id_user_1',
        'user_2.id as id_user_2',
        'intros.reason',
        'user_1.first_name as friend_1_first_name',
        'user_1.last_name as friend_1_last_name',
        'user_1.image_profile as friend_1_image_profile',
        'user_2.first_name as friend_2_first_name',
        'user_2.last_name as friend_2_last_name',
        'user_2.image_profile as friend_2_image_profile'
      ]);

      $count_received = \App\Models\Intros::join('users_friends',function($join) use ($data_user){
        $join->on(function($join2){
            $join2->on('users_friends.id','=','intros.id_friend_1')
            ->orOn('users_friends.id','=','intros.id_friend_2');
        })
        ->where('users_friends.id_user_friend', '=', $data_user['id']);
      })->count(['intros.id']);

      $result=[];
      $result['count_made'] = $count_made;
      $result['count_received'] = $count_received;
      if($made)
        $result['made'] = $made;

      if($received){

        $result['received'] = [
          'id' =>$received['id'],
          'reason' =>$received['reason']
        ];
        //retorno los datos de la persona que no es el usuario que se está consultando, porque es con quien lo están presentando
        if($data_user['id']==$received['id_user_1']){

          $result['received']['id_user'] = $received['id_user_2'];
          $result['received']['friend_image_profile'] = $received['friend_2_image_profile'];
          $result['received']['friend_first_name'] = $received['friend_2_first_name'];
          $result['received']['friend_last_name'] = $received['friend_2_last_name'];
        }else{
          $result['received']['id_user'] = $received['id_user_1'];
          $result['received']['friend_image_profile'] = $received['friend_1_image_profile'];
          $result['received']['friend_first_name'] = $received['friend_1_first_name'];
          $result['received']['friend_last_name'] = $received['friend_1_last_name'];
        }
      }

      return ['status'=>'success','data'=>['dashboard' => $result]];
    }

    /**
    * Función para insertar los intros de un usuario
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof IntrosController
    */
    public function addIntro($lang){
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];
      if(trim($lang)!=='')
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'friend_1' => 'required',
        'friend_2' => 'required',
        'question_friend_1' => 'required|min:2',
        'question_friend_2' => 'required|min:2',
        'reason' => 'required|min:2'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      //validamos que el correo sea unico
      $there_is_friend1=\App\Models\UsersFriends::join('users','users.id','=','users_friends.id_user_friend')->where('users_friends.id',$inputs['friend_1'])->where('users_friends.status',1)->first(['users.id','users.first_name','users.last_name','users.email']);
      if(!$there_is_friend1)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_1_not_exist'))]];

      $there_is_friend2=\App\Models\UsersFriends::join('users','users.id','=','users_friends.id_user_friend')->where('users_friends.id',$inputs['friend_2'])->where('users_friends.status',1)->first(['users.id','users.first_name','users.last_name','users.email']);
      if(!$there_is_friend2)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_2_not_exist'))]];

      $there_is_contact=\App\Models\UsersFriends::where(function($query) use($there_is_friend2,$there_is_friend1){
        $query->where(function($query2) use($there_is_friend2,$there_is_friend1){
          $query2->where('id_user','=',$there_is_friend2['id'])
          ->where('id_user_friend','=',$there_is_friend1['id']);
        })->orWhere(function($query2) use($there_is_friend2,$there_is_friend1){
          $query2->where('id_user','=',$there_is_friend1['id'])
          ->where('id_user_friend','=',$there_is_friend2['id']);
        });
      })->where('status',1)->first(['id']);
      if($there_is_contact)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.both_contacts'))]];

      $there_is_intros=Intros::where('id_user',$data_user['id'])->where(function($query) use($data_user,$inputs){
        $query->where(function($query2) use($data_user,$inputs){
          $query2->where('id_friend_1',$inputs['friend_1'])
          ->where('id_friend_2',$inputs['friend_2']);
        })->orWhere(function($query2) use($data_user,$inputs){
          $query2->where('id_friend_1',$inputs['friend_2'])
          ->where('id_friend_2',$inputs['friend_1']);
        });
      })->first();
      if($there_is_intros)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.intros_exists'))]];

      $new_intro = Intros::create([
        'id_user'=>$data_user['id'],
        'id_friend_1'=>$inputs['friend_1'],
        'id_friend_2'=>$inputs['friend_2'],
        'reason'=>$inputs['reason'],
        'friend_1_info'=>$inputs['question_friend_1'],
        'friend_2_info'=>$inputs['question_friend_2']
      ]);

      $insert = [];
      foreach ($inputs['gainings'] as $index =>$value) {
        if($value===true || $value==='true'){
          $insert[] = [
            'id_intro'=>$new_intro['id'],
            'id_gain'=>$index
          ];
        }
      }
      if(count($insert)>0)
        \App\Models\GainingsIntros::insert($insert);

      //creamos el chat
      $room=\App\Models\Rooms::create([
        'id_intro'=>$new_intro['id'],
        'id_user_1'=>$data_user['id'],
        'id_user_2'=>$there_is_friend1['id'],
        'id_user_3'=>$there_is_friend2['id'],
      ]);

      $email_data = [];
      $email_data['full_name']=$there_is_friend1['first_name'].' '.$there_is_friend1['last_name'];
      $email_data['email']=$there_is_friend1['email'];
      $email_data['contact_name']=$data_user['first_name'].' '.$data_user['last_name'];
      $email_data['friend_email']=$there_is_friend2['first_name'].' '.$there_is_friend2['last_name'];
      $email_data['dear_email']=htmlentities(\Lang::get('validation.messages.dear_email'));
      $email_data['success_invitation_email']=htmlentities(\Lang::get('validation.messages.success_invitation_email'));
      $email_data['success_invitation_final_email']=htmlentities(\Lang::get('validation.messages.success_invitation_final_email'));
      $email_data['accept_invitation_link']=htmlentities(\Lang::get('validation.messages.accept_invitation'));
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['invitation_email']=htmlentities(\Lang::get('validation.messages.invitation_email'));
/*
      \Mail::send('emails.invitation', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['invitation_email']);
      });
      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
*/
      $email_data['full_name']=$there_is_friend2['first_name'].' '.$there_is_friend2['last_name'];
      $email_data['email']=$there_is_friend2['email'];
      $email_data['contact_name']=$data_user['first_name'].' '.$data_user['last_name'];
      $email_data['friend_email']=$there_is_friend1['first_name'].' '.$there_is_friend1['last_name'];
      $email_data['dear_email']=htmlentities(\Lang::get('validation.messages.dear_email'));
      $email_data['success_invitation_email']=htmlentities(\Lang::get('validation.messages.success_invitation_email'));
      $email_data['success_invitation_final_email']=htmlentities(\Lang::get('validation.messages.success_invitation_final_email'));
      $email_data['accept_invitation_link']=htmlentities(\Lang::get('validation.messages.accept_invitation'));
      $email_data['regards_email']=htmlentities(\Lang::get('validation.messages.regards_email'));
      $email_data['invitation_email']=htmlentities(\Lang::get('validation.messages.invitation_email'));
/*
      \Mail::send('emails.invitation', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['invitation_email']);
      });
      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];
*/
      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_register'))]];
    }

    /**
    * Función para redirecionar la pagina y abrir la app
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function redirectLink(){
      $domain = str_replace('http://','',str_replace('/api/public','',url('/')));

      return view('redirect', ['url'=>$domain.'/intros','token' =>'']);
    }

}
