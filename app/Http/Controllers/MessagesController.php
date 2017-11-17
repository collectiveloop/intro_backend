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
* @overview Clase que consultade los mensajes en el sistema
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
}
