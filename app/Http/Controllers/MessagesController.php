<?php
namespace App\Http\Controllers;
use App\Models\Messages;
use App\Models\Rooms;
use App\Models\Members;
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
* @overview Clase que consulta los mensajes en el sistema
*
**/
class MessagesController extends Controller
{
  /**
  * Constructor de la clase
  * @author Junior Milano <junior@sappitotech.com>
  * @memberof MessagesController
  */
  public function __construct(){
    //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
    $this->middleware('validate.administrator_user', ['only' => []]);
  }

  /**
  * Función para retornar los datos del usuario en la sesión
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function getDataUser(){
    $verification = \App\Models\Users::verifyAuthentication();

    return $verification['data']['user'];
  }

  /**
  * Función que retorna los mensajes más viejos que hay
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function getOldMessages($lang,$intro,$quantity,$room=false,$last_message=''){
    return $this->getMessages($lang,$intro,$quantity,$last_message,'<',$room);
  }

  /**
  * Función que retorna los mensajes nuevos despues del último mensajes pasado como parámetro
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function getNewMessages($lang,$intro,$room=false,$last_message=''){
    return $this->getMessages($lang,$intro,0,$last_message,'>',$room);
  }

  /**
  * Función que retorna los mensajes existentes
  * @author Junior Milano <junior@sappitotech.com>
  * @return object
  * @memberof MessagesController
  */
  public function getMessages($lang,$intro,$quantity,$last_message,$orientation,$room =false){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $columns;
    if($room===true || $room ==='true'){
      $columns = [
        'rooms.id',
        'user_1.id as id_user_1',
        'user_1.first_name as user_1_first_name',
        'user_1.last_name as user_1_last_name',
        'user_1.user_name as user_1_name',
        'user_1.push_id as user_1_push_id',
        'user_1.image_profile as user_1_image_profile',
        'user_2.id as id_user_2',
        'user_2.first_name as user_2_first_name',
        'user_2.last_name as user_2_last_name',
        'user_2.user_name as user_2_name',
        'user_2.push_id as user_2_push_id',
        'user_2.image_profile as user_2_image_profile',
        'user_3.id as id_user_3',
        'user_3.first_name as user_3_first_name',
        'user_3.last_name as user_3_last_name',
        'user_3.user_name as user_3_name',
        'user_3.push_id as user_3_push_id',
        'user_3.image_profile as user_3_image_profile'
      ];
    }else{
        $columns = ['rooms.id'];
    }
    $accessRoom = Rooms::where(function($query) use($data_user){
      $query->where('rooms.id_user_1','=',$data_user['id'])
      ->orWhere('rooms.id_user_2','=',$data_user['id'])
      ->orWhere('rooms.id_user_3','=',$data_user['id']);
    })
    ->leftJoin('users as user_1','user_1.id','=','rooms.id_user_1')
    ->leftJoin('users as user_2','user_2.id','=','rooms.id_user_2')
    ->leftJoin('users as user_3','user_3.id','=','rooms.id_user_3')
    ->where('rooms.id_intro','=',$intro)
    ->first($columns);

    if(!$accessRoom)
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.not_access_chat'))]];

    $query = Messages::join('rooms', function ($join) use ($intro) {
      $join->on('rooms.id','=','messages.id_room')
      ->where('rooms.id_intro', '=', $intro);
    });

    if(trim($last_message)!=='' && is_numeric($last_message))
      $query->where('messages.id', $orientation, $last_message);

    if($quantity!==0)
      $query->limit($quantity);

    $messages = $query->orderBy('messages.created_at','desc')
    ->orderBy('messages.id','desc')
    ->get([
      'messages.id',
      'messages.id_user',
      'messages.message',
      'messages.created_at'
    ]);
    $return = ['id_user' =>$data_user['id'],'messages' => $messages->toArray()];
    if($room===true || $room ==='true')
      $return['room'] = $accessRoom->toArray();
    return ['status'=>'success','data'=>$return];
  }

  /**
  * Función para insertar los datos de un nuevo contacto
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function addMessage($lang,$room,$intro,$last_message=''){
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    if(trim($lang)!=='')
      \App::setLocale($lang);

    $inputs = Input::all();
    //'number_phones' => 'required|min:7',
    $validator = \Validator::make($inputs, [
      'message' => 'required|min:1',
    ]);

    if ($validator->fails()){
      $errors = $validator->errors();
      return ['status'=>'error','data'=>['message'=>$errors->all()]];
    }

    // validamso que el nuevo contacto no sea el mismo usuario
    $thereIsRoom = Rooms::where('id','=',$room)->where(function($query) use($data_user){
        $query->where('id_user_1','=',$data_user['id'])
        ->orWhere('id_user_2','=',$data_user['id'])
        ->orWhere('id_user_3','=',$data_user['id']);
    })
    ->where('id_intro','=',$intro)
    ->first();
    if(!$thereIsRoom)
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.not_room'))]];

    Messages::create(
    [
      'id_room'=>$room,
      'id_user'=>$data_user['id'],
      'message'=>trim($inputs['message'])
    ]);
    //consultamos los últimos mensajes
    $return = $this->getMessages($lang,$intro,0,$last_message,'>',true);
    $return['data']['message'] = htmlentities(\Lang::get('validation.messages.success_register'));
    return $return;
  }
  /**
  * Función para la autenticación de los usuarios, devuelve error si hay algún problema
  * @author Junior Milano <junior@sappitotech.com>
  * @param  Request  $request contiene todos los campos del formulario enviado y las variables get
  * @return array
  * @memberof MessagesController
  */
  public function leaveRoom($lang ='en',$intro){
    if(trim($lang)!=='' )
      \App::setLocale($lang);

    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    Rooms::where('id_intro','=',$intro)->where('id_user_1','=',$data_user['id'])->update(['id_user_1_leave'=>1]);
    Rooms::where('id_intro','=',$intro)->where('id_user_2','=',$data_user['id'])->update(['id_user_2_leave'=>1]);
    Rooms::where('id_intro','=',$intro)->where('id_user_3','=',$data_user['id'])->update(['id_user_3_leave'=>1]);

    return ['status'=>'success','data'=>['dd'=>$inputs['message'],'message'=>htmlentities(\Lang::get('validation.messages.intro_abandoned'))]];
  }

  /**
  * Función que retorna las intros hechas por el usuario de forma paginada
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function getMadeIntrosPaginate($lang,$page,$quantity){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $made = \App\Models\Intros::where('intros.id_user',$data_user['id'])
    ->join('rooms', function ($join) use($data_user) {
      $join->on('rooms.id_intro','=','intros.id')
      ->where(function($query) use($data_user){
        $query->where(function($query2) use ($data_user){
          $query2->where('rooms.id_user_1', '=', $data_user['id'])
          ->where('rooms.id_user_1_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_2', '=', $data_user['id'])
          ->where('rooms.id_user_2_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_3', '=', $data_user['id'])
          ->where('rooms.id_user_3_leave', '=', 0);
        });
      });
    })
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
    ->limit($quantity)
    ->offset(($page-1)*$quantity)
    ->get([
      'intros.id',
      'intros.id_friend_1',
      'friend_1.first_name as friend_1_first_name',
      'friend_1.last_name as friend_1_last_name',
      'friend_1.image_profile as friend_1_image_profile',
      'friend_1.user_name as friend_1_user_name',
      'intros.id_friend_2',
      'friend_2.first_name as friend_2_first_name',
      'friend_2.last_name as friend_2_last_name',
      'friend_2.image_profile as friend_2_image_profile',
      'friend_2.user_name as friend_2_user_name',
      'intros.reason',
      'intros.created_at'
    ]);

    return ['status'=>'success','data'=>['id_user'=>$data_user['id'],'intros' => $made]];
  }

  /**
  * Función que retorna la cantidad de mensajes recibidos
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function getCountMadeIntros($lang){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];
    $count_made = \App\Models\Intros::where('intros.id_user',$data_user['id'])
    ->join('rooms', function ($join) use($data_user) {
      $join->on('rooms.id_intro','=','intros.id')
      ->where(function($query) use($data_user){
        $query->where(function($query2) use ($data_user){
          $query2->where('rooms.id_user_1', '=', $data_user['id'])
          ->where('rooms.id_user_1_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_2', '=', $data_user['id'])
          ->where('rooms.id_user_2_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_3', '=', $data_user['id'])
          ->where('rooms.id_user_3_leave', '=', 0);
        });
      });
    })
    ->count();

    return ['status'=>'success','data'=>['intros_count' => $count_made]];
  }

  /**
  * Función que retorna las intros recibidas por el usuario de forma paginada
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */

  public function getReceivedIntrosPaginate($lang,$page,$quantity){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $received = \App\Models\Intros::join('rooms', function ($join) use($data_user) {
      $join->on('rooms.id_intro','=','intros.id')
      ->where(function($query) use($data_user){
        $query->where(function($query2) use ($data_user){
          $query2->where('rooms.id_user_1', '=', $data_user['id'])
          ->where('rooms.id_user_1_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_2', '=', $data_user['id'])
          ->where('rooms.id_user_2_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_3', '=', $data_user['id'])
          ->where('rooms.id_user_3_leave', '=', 0);
        });
      });
    })
    ->join('users_friends as owner_friend',function($join) use ($data_user){
      $join->on(function($join2){
          $join2->on('owner_friend.id','=','intros.id_friend_1')
          ->orOn('owner_friend.id','=','intros.id_friend_2');
      })
      ->where('owner_friend.id_user_friend', '=', $data_user['id']);
    })
    ->join('users as user','user.id','=','intros.id_user')
    ->join('users_friends as user_friend_1','user_friend_1.id','=','intros.id_friend_1')
    ->join('users as user_1','user_1.id','=','user_friend_1.id_user_friend')
    ->join('users_friends as user_friend_2','user_friend_2.id','=','intros.id_friend_2')
    ->join('users as user_2','user_2.id','=','user_friend_2.id_user_friend')
    ->orderBy('intros.created_at', 'desc')
    ->orderBy('intros.updated_at', 'desc')
    ->limit($quantity)
    ->offset(($page-1)*$quantity)
    ->get([
      'intros.id',
      'user_1.id as id_user_1',
      'user_2.id as id_user_2',
      'intros.id_user',
      'intros.reason',
      'intros.created_at',
      'user_1.first_name as user_1_first_name',
      'user_1.last_name as user_1_last_name',
      'user_1.image_profile as user_1_image_profile',
      'user_1.user_name as user_1_user_name',
      'user_2.first_name as user_2_first_name',
      'user_2.last_name as user_2_last_name',
      'user_2.image_profile as user_2_image_profile',
      'user_2.user_name as user_2_user_name',
      'user.first_name as user_first_name',
      'user.last_name as user_last_name',
      'user.image_profile as user_image_profile',
      'user.user_name as user_user_name'
    ]);

    return ['status'=>'success','data'=>['id_user'=>$data_user['id'],'intros' => $received]];
  }

  /**
  * Función que retorna la cantidad de mensajes recibidos
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof MessagesController
  */
  public function getCountReceivedIntros($lang){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $count_received = \App\Models\Intros::join('rooms', function ($join) use($data_user) {
      $join->on('rooms.id_intro','=','intros.id')
      ->where(function($query) use($data_user){
        $query->where(function($query2) use ($data_user){
          $query2->where('rooms.id_user_1', '=', $data_user['id'])
          ->where('rooms.id_user_1_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_2', '=', $data_user['id'])
          ->where('rooms.id_user_2_leave', '=', 0);
        })
        ->orWhere(function($query2) use ($data_user){
          $query2->where('rooms.id_user_3', '=', $data_user['id'])
          ->where('rooms.id_user_3_leave', '=', 0);
        });
      });
    })
    ->join('users_friends',function($join) use ($data_user){
      $join->on(function($join2){
          $join2->on('users_friends.id','=','intros.id_friend_1')
          ->orOn('users_friends.id','=','intros.id_friend_2');
      })
      ->where('users_friends.id_user_friend', '=', $data_user['id']);
    })->count();

    return ['status'=>'success','data'=>['intros_count' => $count_received]];
  }
}
