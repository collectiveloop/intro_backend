<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use \Tymon\JWTAuth\Exceptions\TokenInvalidException;
use \Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        /*AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,*/
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
      $errorFounded=false;
      /*
        $errorFounded=false;
        //en vez de 404 habría que investigar otro tipo de errores que se pueden lanzar 402,505, etc. quizás 404 no sea el más idóneo para TODOS
        if ($e instanceof ReflectionException ) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Controlador o Método no encontrado')]];
        } elseif ($e instanceof \Symfony\Component\Routing\Exception\MethodNotAllowedException) {//el método existe pero no está permitido el accesso por ejemplo es private el método y lo estamos llamando
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Método no encontrado')]];
        } elseif  ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Método Inmediato no encontrado')]];
        } elseif ($e instanceof \InvalidArgumentException) {//cuando hay algo malo en el route. puntualmente un controlador invocado que no existe
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Controlador, Método o parámetro no encontrado')]];
        } elseif ($e instanceof HttpResponseException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Hubo un error enviando la respuesta del servidor')]];
        } elseif ($e instanceof ModelNotFoundException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Modelo no encontrado')]];
        } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Hubo un problema durante la autenticación')]];
        } elseif ($e instanceof AuthorizationException) {
            echo
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Hubo un problema con la autorización')]];
        } elseif ($e instanceof ValidationException && $e->getResponse()) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities('Hubo un error con la validación')]];
        } elseif($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            if ($request->ajax())
                $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.not_direction_ajax'))]];
            else
                $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.not_direction'))]];
        }elseif ($e instanceof TokenInvalidException){
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.token_invalid'))]];
        }elseif ($e instanceof TokenExpiredException){
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.token_expired'))]];
        }
    }
        */
        if ($e instanceof TokenInvalidException){
          $errorFounded =  ['status'=>'error','data'=>['type'=>'session','message'=>htmlentities(\Lang::get('validation.messages.token_invalid'))]];
        }elseif ($e instanceof TokenExpiredException){
          $errorFounded =  ['status'=>'error','data'=>['type'=>'session','message'=>htmlentities(\Lang::get('validation.messages.token_expired'))]];
        }
        if($errorFounded)
            return response()->json($errorFounded, 500,['content-type'=>'application/json; charset=utf-8']);

        return parent::render($request, $e);
    }
}
