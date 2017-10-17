<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseHelper;
use App\Models\Users;

/**
*
* @version 1.0
* @license Copyright Kentron 2016. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Middleware que evalua si la sesi'on est'a iniciada o no, valida el token, se puede usar tanto en sistemas como apis
*
**/
class SessionUserMiddleware extends JsonResponse {
    /**
    * Función envía el request al controlador y prepara la respuesta una vez obtenido el arreglo del controlador
    * @returns {json}
    * @memberof FilterXAPIMiddleware
    **/
    public function handle($request, Closure $next){
        $validation = Users::verifyAuthentication();
        $response;
        if(!isset($validation['status']) || $validation['status']==='error' )
            $response = response()->json($validation, ResponseHelper::getTypeResponse('error'), ['content-type'=>ResponseHelper::getCodification()]);
        else
            $response = $next($request);

        return $response;
    }

    public function terminate($request, $response){}
}
