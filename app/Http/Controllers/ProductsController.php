<?php
namespace App\Http\Controllers;
use App\Models\Products;
use App\Models\Users;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Input;
/**
*
* @version 1.0
* @license Copyright  IF&L Chile 2017. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que gestiona los Products
*
**/
class ProductsController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof ProductsController
    */
    public function __construct(){
        //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
        /*$this->middleware('validate.administrator_user', ['only' => [
            'createProduct',
            'updateProduct',
            'deleteProduct'
        ]]);
        */
    }

    /**
    * Función para obtener los Productos de forma paginada
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getProductsPaginate($lang,$page,$quantity){
      if(trim($lang!=='') )
        \App::setLocale($lang);
        $product = Products::orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->orderBy('id', 'desc')
        ->limit($quantity)
        ->offset(($page-1)*$quantity)
        ->get();
        return response()->json(['status'=>'success','data'=>['products' => $product->toArray()]], ResponseHelper::getTypeResponse('success'), ['content-type'=>ResponseHelper::getCodification()]);
    }

    /**
    * Función para obtener la cantidad de productos en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getCountProducts($lang){
      if(trim($lang!=='') )
        \App::setLocale($lang);

        $count = Products::count();

        return response()->json(['status'=>'success','data'=>['products_count' => $count]], ResponseHelper::getTypeResponse('success'), ['content-type'=>ResponseHelper::getCodification()]);
    }

    /**
    * Función para obtener el producto pasado como parámetro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getProduct($lang,$id){
      if(trim($lang!=='') )
        \App::setLocale($lang);

      $product = Products::find($id)->first();

      return response()->json(['status'=>'success','data'=>['product' => $product]], ResponseHelper::getTypeResponse('success'), ['content-type'=>ResponseHelper::getCodification()]);
    }

}
