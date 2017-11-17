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
* @overview Clase que consulta las introducciones de los usuarios en el sistema (INTROS), listado generales
*
**/
class ListIntrosController extends Controller
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
  * Función que retorna la intro solicitada
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof IntrosController
  */
  public function getDetailIntro($lang,$id){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $intro = Intros::where('intros.id',$id)
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
    ->first([
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
      'intros.friend_1_info',
      'intros.friend_2_info'
    ]);

    $gaining_intro = \App\Models\GainingsIntros::rightJoin('gainings',function($join) use($id){
      $join->on('gainings.id','=','gainings_intros.id_gain')
      ->where('gainings_intros.id_intro','=',$id);
    })
    ->get([
      'gainings_intros.id',
      'gainings.id as id_gaining',
      'gainings.gain_'.$lang.' as option'
    ]);

    return ['status'=>'success','data'=>['intro' => $intro, 'gaining_intro' => $gaining_intro->toArray()]];
  }

  /**
  * Función que retorna las intros hechas por el usuario de forma paginada
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof IntrosController
  */
  public function getMadeIntrosPaginate($lang,$page,$quantity){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

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
  * @memberof IntrosController
  */
  public function getCountMadeIntros($lang){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];
    $count_made = Intros::where('intros.id_user',$data_user['id'])
    ->count();

    return ['status'=>'success','data'=>['intros_count' => $count_made]];
  }

  /**
  * Función que retorna las intros recibidas por el usuario de forma paginada
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof IntrosController
  */

  public function getReceivedIntrosPaginate($lang,$page,$quantity){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $made = Intros::join('users_friends as owner_friend',function($join) use ($data_user){
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

    return ['status'=>'success','data'=>['id_user'=>$data_user['id'],'intros' => $made]];
  }

  /**
  * Función que retorna la cantidad de mensajes recibidos
  * @author Junior Milano <junior@sappitotech.com>
  * @return array
  * @memberof IntrosController
  */
  public function getCountReceivedIntros($lang){
    //datos del usuario
    $data_user = $this->getDataUser();
    if(!isset($data_user['id']))
      return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

    $count_received = \App\Models\Intros::join('users_friends',function($join) use ($data_user){
      $join->on(function($join2){
          $join2->on('users_friends.id','=','intros.id_friend_1')
          ->orOn('users_friends.id','=','intros.id_friend_2');
      })
      ->where('users_friends.id_user_friend', '=', $data_user['id']);
    })->count();

    return ['status'=>'success','data'=>['intros_count' => $count_received]];
  }
}
