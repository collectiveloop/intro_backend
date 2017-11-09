<?php
namespace App\Http\Controllers;
use App\Models\Gainings;
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
* @overview Clase que gestiona las ganancias popsibles de los usuarios
*
**/
class GainingsController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof GainingsController
    */
    public function __construct(){
      //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
      $this->middleware('validate.administrator_user', ['only' => []]);
    }

    /**
    * Función que retorna la información de las ganancias existentes en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof GainingsController
    */
    public function getGainings($lang){
      $gains = Gainings::orderBy('created_at', 'desc')
      ->orderBy('updated_at', 'desc')
      ->get([
        'id',
        'gain_'.$lang.' as option'
      ]);

      return ['status'=>'success','data'=>['gainings' => $gains->toArray()]];
    }

    /**
    * Función que retorna la información de la ganancia pasada como parámetro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof GainingsController
    */
    public function getGain($lang,$id){
      $gain = Gainings::where('id',$id)
      ->first([
        'id',
        'gain_es',
        'gain_en',
        'created_at',
        'updated_at'
      ]);

      return ['status'=>'success','data'=>['gaining' => $gain->toArray()]];
    }
}
