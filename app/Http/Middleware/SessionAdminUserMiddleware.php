<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;

/**
*
* @version 1.0
* @license Copyright Kentron 2016. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Middleware que evalua si la sesión est'a iniciada o no, valida el token, versión administrador
*
**/
class SessionAdminUserMiddleware extends JsonResponse {
    /**
    * Función envía el request al controlador y prepara la respuesta una vez obtenido el arreglo del controlador
    * @returns {json}
    * @memberof FilterXAPIMiddleware
    **/
    public function handle($request, Closure $next, $guard = null){
        if (!Auth::guard($guard)->guest())
            $response = redirect()->guest('administration/login');
        else
            $response = $next($request);

        return $response;
    }

    public function terminate($request, $response){}
}
