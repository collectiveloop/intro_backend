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

      $received = \App\Models\Users::join('users_friends as owner_user_friend','owner_user_friend.id_user_friend','=','users.id')
      ->join('intros',
      function($join){
        $join->on('owner_user_friend.id', '=','intros.id_friend_1');
        $join->orOn('owner_user_friend.id', '=','intros.id_friend_2');
      })
      ->join('users_friends as user_friend_1',
      function($join){
        $join->on('user_friend_1.id','=','intros.id_friend_1');
        $join->orOn('user_friend_1.id_user', '=','intros.id_user');
      })
      ->join('users as friend_1','friend_1.id','=','user_friend_1.id_user_friend')
      ->join('users_friends as user_friend_2',
      function($join){
        $join->on('user_friend_2.id','=','intros.id_friend_2');
        $join->orOn('user_friend_2.id_user', '=','intros.id_user');
      })
      ->join('users as friend_2','friend_2.id','=','user_friend_2.id_user_friend')

      ->where('users.id', '=', $data_user['id'])
      ->orderBy('intros.created_at', 'desc')
      ->orderBy('intros.updated_at', 'desc')
      ->first([
        'intros.id',
        'intros.id_friend_1',
        'friend_1.id as id_user_1',
        'friend_1.first_name as friend_1_first_name',
        'friend_1.last_name as friend_1_last_name',
        'friend_1.image_profile as friend_1_image_profile',
        'intros.id_friend_2',
        'friend_2.id as id_user_2',
        'friend_2.first_name as friend_2_first_name',
        'friend_2.last_name as friend_2_last_name',
        'friend_2.image_profile as friend_2_image_profile',
        'intros.reason'
      ]);

      $count_received = \App\Models\Users::join('users_friends','users_friends.id_user_friend','=','users.id')
      ->join('intros',
      function($join){
        $join->on('users_friends.id', '=','intros.id_friend_1');
        $join->orOn('users_friends.id', '=','intros.id_friend_2');
      })
      ->where('users.id', '=', $data_user['id'])
      ->count(['intros.id']);

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
        //retorno los datos de la perosna que no es el usuario que se está consultando, porque es con quien lo están presentando
        if($data_user['id']===$received['id_user_1']){
          $result['received']['id_user'] = $received['id_friend_2'];
          $result['received']['friend_image_profile'] = $received['friend_2_image_profile'];
          $result['received']['friend_first_name'] = $received['friend_2_first_name'];
          $result['received']['friend_last_name'] = $received['friend_2_last_name'];
        }else{
          $result['received']['id_user'] = $received['id_friend_1'];
          $result['received']['friend_image_profile'] = $received['friend_1_image_profile'];
          $result['received']['friend_first_name'] = $received['friend_1_first_name'];
          $result['received']['friend_last_name'] = $received['friend_1_last_name'];
        }
      }

      return ['status'=>'success','data'=>['dashboard' => $result]];
    }

    /**
    * Función que retorna la información los intros de forma paginada
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof IntrosController
    */
    public function getIntrosPaginate($lang,$page,$quantity){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $intros = Intros::where('intros.id_user',$data_user['id'])
      ->join('users as friend_1','friend_1.id','=','intros.id_friend_1')
      ->join('users as friend_2','friend_2.id','=','intros.id_friend_2')
      ->orderBy('intros.created_at', 'desc')
      ->orderBy('intros.updated_at', 'desc')
      ->limit($quantity)
      ->offset(($page-1)*$quantity)
      ->get([
        'intros.id',
        'intros.id_friend_1',
        'friend_1.first_name as friend_1_first_name',
        'friend_1.last_name as friend_1_last_name',
        'intros.id_friend_2',
        'friend_2.first_name as friend_2_first_name',
        'friend_2.last_name as friend_2_last_name',
        'intros.reason',
        'intros.friend_1_info',
        'intros.friend_2_info',
        'intros.created_at',
        'intros.updated_at'
      ]);

      return ['status'=>'success','data'=>['intros' => $intros->toArray()]];
    }

    /**
    * Función que retorna la cantidad de intros registradas en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof IntrosController
    */
    public function getCountIntros($lang){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $count = Intros::where('id_user',$data_user['id'])->count();

      return ['status'=>'success','data'=>['intros_count' => $count]];
    }

    /**
    * Función que retorna la información de la intro pasada como parámetro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof IntrosController
    */
    public function getGain($lang,$id){
      $gain = Intros::where('id',$id)
      ->first([
        'id',
        'gain',
        'created_at',
        'updated_at'
      ]);

      return ['status'=>'success','data'=>['intro' => $gain->toArray()]];
    }
}
