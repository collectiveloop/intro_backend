<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use App\Models\Users;

/**
*
* @version 1.0
* @license Copyright Kentron 2016. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Middleware que evalua si los usuarios son administradores
*
**/
class AdministratorUserMiddleware extends JsonResponse {
    /**
    * Función envía el request al controlador y prepara la respuesta una vez obtenido el arreglo del controlador
    * @returns {json}
    * @memberof FilterXAPIMiddleware
    **/
    public function handle($request, Closure $next){
        $data_user = Users::verifyAuthentication();
        if($data_user['status']==='error' || $data_user['data']['user']['id_type_user']!==2)
            return response()->json(['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.not_permission'))]], ResponseHelper::getTypeResponse('error'), ['content-type'=>ResponseHelper::getCodification()]);


        $response = $next($request);

        return $response;
    }

    public function terminate($request, $response){}
}
