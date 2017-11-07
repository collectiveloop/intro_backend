<?php
namespace App\Http\Controllers;
use App\Models\Products;
use App\Models\Users;
use App\Helpers\Responses;
use Illuminate\Support\Facades\Input;
/**
*
* @version 1.0
* @license Copyright  Sappitotech 2017. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que gestiona los Products
*
**/
class ProductsBackupController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof ProductsController
    */
    public function __construct(){
        //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
        $this->middleware('validate.administrator_user', ['only' => [
            'createProduct',
            'updateProduct',
            'deleteProduct'
        ]]);
    }

    /**
    * Función para obtener todos los Productos
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getProducts($page,$quantity){
        $product = Products::orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->orderBy('id', 'desc')
        ->limit($quantity)
        ->offset(($page-1)*$quantity)
        ->get();
        return response()->json(['status'=>'success','data'=>['product' => $product->toArray()]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());
    }

    /**
    * Función para obtener la cantidad de productos en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getCountProducts(){
        $count = Products::count();

        return response()->json(['status'=>'success','data'=>['product_count' => $count]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());
    }

    /**
    * Función para obtener los productos que tiene relación con la cadena pasada como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getFoundedProducts($pattern){
        $products = Products::where('number_product','like','%'.$pattern.'%')
        ->orWhere('title','like','%'.$pattern.'%')
        ->orWhere('synopsis','like','%'.$pattern.'%')
        ->orderBy('updated_at', 'desc')
        ->orderBy('tax', 'desc')
        ->orderBy('value', 'desc')
        ->limit(10)
        ->get([
            'id',
            'title',
            'number_product',
            'synopsis',
            'description',
            'quantity',
            'original_quantity',
            'tax',
            'value',
            'photo'
        ]);

        return response()->json(['status'=>'success','data'=>['founded_products' => $products->toArray()]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());
    }

    /**
    * Función para obtener los datos del grupo de productos pasados como parámetros
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getGroupProducts($IdProducts){
        $products = Products::whereIn('id',explode('-',$IdProducts))
        ->orderBy('updated_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->orderBy('tax', 'desc')
        ->orderBy('value', 'desc')
        ->get([
            'id',
            'title',
            'number_product',
            'synopsis',
            'description',
            'quantity',
            'original_quantity',
            'tax',
            'value',
            'photo'
        ]);

        return response()->json(['status'=>'success','data'=>['products' => $products->toArray()]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());
    }

    /**
    * Función para obtener el producto pasado como parámetro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function getProduct($id){
        $product = Products::find($id);
        if(!$product)
            return response->json(['status'=>'error','data'=>['message'=>htmlentities('Producto no encontrado')]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());;

        return response()->json(['status'=>'success','data'=>['product' => $product->toArray()]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());;
    }

    /**
    * Función para la validación de campos y otros temas de seguridad
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function validateProduct(){
        $user = Users::verifyAuthenticateApp();
        if ($user['status']==='error' || $user['status']==='fails')
            return $user;
        $credentials = Input::all();
        $validator = \Validator::make($credentials, [
            'title' => 'required|min:3',
            'number_product' => 'required|min:1',
            'synopsis' => 'required|min:10',
            'tax' => 'required|numeric',
            'value' => 'required|numeric',
            'weight' => 'required|numeric',
            'quantity' => 'required|numeric',
            'photo' => 'sometimes|dimensions:max_width=480,max_height=360'
        ]);
        if ($validator->fails()){
            $errors = $validator->errors();
            return ['message'=>$errors->all()];
        }

        return ['credentials'=>$credentials,'user'=>$user['data']['user']];
    }

    /**
    * Función para insertar un nuevo producto
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function createProduct(){
        $result=$this->validateProduct();
        if(is_array($result) && isset($result['status']))
            return response()->json(['status'=>'error','data'=>$return], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());
        $credentials=$result['credentials'];
        $user=$result['user'];
        $insertPhoto=$this->insertPhotoProduct($credentials);
        if(is_array($insertPhoto))
            return $insertPhoto;
        else
            $credentials['photo']=$insertPhoto;
        $credentials['id_user']=$user->id;
        $credentials['original_quantity']=$credentials['quantity'];
        $product = Products::create($credentials);

        //insertamos los items
        $time = new \DateTime();
        $now=$time->format('Y-m-d H:m:s');
        $field_insert=[];
        if(isset($credentials['products_fields']) && count($credentials['products_fields'])>0){
            foreach ($credentials['products_fields'] as $index =>$value) {
                if(!isset($value['value']) || !isset($value['id_product_field']) || !is_numeric($value['id_product_field']))
                    return response()->json(['status'=>'error','data'=>['message'=>htmlentities('Hay un problema con el campo '.$value['id_product_field'])]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());
                    $field_insert[]=[
                        'id_product'=>$product->id,
                        'value'=>$value['value'],
                        'id_product_field'=>$value['id_product_field'],
                        'created_at'=>$now,
                    ];
            }
            $inserted_items=\App\Models\ProductsFieldsValues::insert($field_insert);
        }

        return response()->json(['status'=>'success','data'=>['message'=>htmlentities('Nuevo producto insertado'),'product'=>$product]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());;
    }

    /**
    * Función para actualizar un producto
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function updateProduct($id){
        $result=$this->validateProduct();
        if(is_array($result) && isset($result['status']))
            return $result;
        $credentials=$result['credentials'];
        $user=$result['user'];

        if(!is_numeric($id))
            return response()->json(['status'=>'error','data'=>['message'=>htmlentities('El id es necesario y debe ser numérico')]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());;;

        $credentials['id']=$id;
        $product = Products::find($credentials['id']);
        if(!$product)
            return response()->json(['status'=>'error','data'=>['message'=>htmlentities('No se ha encontrado el producto para actualizar')]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());;
        $credentials['original_quantity']=$product->original_quantity;
        $insertPhoto=$this->insertPhotoProduct($credentials);
        if(is_array($insertPhoto)){
            return $insertPhoto;
        }else{
            if(trim($insertPhoto)!=='' && trim($insertPhoto)!=='undefined')
                $credentials['photo']=$insertPhoto;
            else
                $credentials['photo']=$credentials['old_photo'];
        }

        //los campos personalizados
        $inserted_fields=\App\Models\ProductsFieldsValues::where('id_product',$id)->get();
        $time = new \DateTime();
        $now=$time->format('Y-m-d H:m:s');
        $fields_insert=[];
        $fields_delete=[];

        $old_fields=[];
        foreach ($inserted_fields as $key => $field) {
            $old_fields[$field['id_product_field']]=['id'=>$field['id'],'id_product_field'=>$field['id_product_field'],'object'=>$field];
        }
        if(isset($credentials['products_fields']) && count($credentials['products_fields'])>0){
            foreach ($credentials['products_fields'] as $index =>$value) {
                if(!isset($value['value']) || !isset($value['id_product_field']) || !is_numeric($value['id_product_field']))
                    return response()->json(['status'=>'error','data'=>['message'=>htmlentities('Hay un problema con el campo '.$value['id_product_field'])]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());;;

                if(isset($old_fields[$value['id_product_field']])){
                    $old_fields[$value['id_product_field']]['object']
                    ->where('id',$old_fields[$value['id_product_field']]['id'])
                    ->update([
                        'value'=>$value['value'],
                    ]);
                }else{
                    $fields_insert[]=[
                        'id_product'=>$id,
                        'value'=>$value['value'],
                        'id_product_field'=>$value['id_product_field'],
                        'created_at'=>$now,
                    ];
                }
                unset($old_fields[$value['id_product_field']]);
            }
        }

        //el remanente en $old_fields son elementos que se deben eliminar
        foreach ($old_fields as $key => $delete) {
            $fields_delete[]=$delete['id'];
        }
        $credentials['id_user']=$user->id;

        //hacemos la operacion
        $substraction=$credentials['old_quantity']-$credentials['quantity'];
        if($substraction<0){
            //valor negativo implica que la nueva cantidad es positiva, es agregar mas a la existencia
            $credentials['original_quantity']=$credentials['original_quantity']+($substraction*-1);//substration es negativo e snecesario el -1
        }else{
            if($substraction>0){
                //si e smayor es decir lo viejo e smayor implica que estan dismuyendo la existencia
                $credentials['original_quantity']=$credentials['original_quantity']-($substraction);
            }
        }
        $updated = $product->update($credentials);
        $inserted_fields=[];
        $deleted_fields=[];
        //insertamos/actualizamos los campos personalizables
        if(count($fields_insert)>0)
            $inserted_fields=\App\Models\ProductsFieldsValues::insert($fields_insert);
        if(count($fields_delete)>0)
            $deleted_fields=\App\Models\ProductsFieldsValues::destroy($fields_delete);

        return response()->json(['status'=>'success','data'=>['message'=>htmlentities('Producto actualizado')]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());;
    }

    /**
    * Función para insertar la imagen de un producto
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function insertPhotoProduct($credentials){
        $name='';
        if(isset($credentials['photo'])){
            $file=$credentials['photo'];
            $photoDate = new \DateTime();
            if($file && $file!=='' && $file!=='undefined'){
                if(strtolower($file->guessClientExtension())!=='jpg' && strtolower($file->guessClientExtension())!=='jpeg' && strtolower($file->guessClientExtension())!=='png' && strtolower($file->guessClientExtension())!=='svg' && strtolower($file->guessClientExtension())!=='gif' && strtolower($file->guessClientExtension())!=='tiff'){
                    return response()->json(['status'=>'error','data'=>['message'=>htmlentities('Imagen con formato inválido')]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());;
                }else {
                    if(isset($credentials['old_photo']) && $credentials['old_photo']!=='')
                        $this->deletePhoto($credentials['old_photo']);
                    $find = array(' ','_','ñ','á','é','í','ó','ú','-');
                    $replace = array('','','','','','','','');
                    //$new_name=str_replace($find,$replace,$file->getClientOriginalName());
                    $new_name=str_replace($find,$replace,$credentials['title'].$photoDate->format('YmdHis').'.'.strtolower($file->guessClientExtension()));
                    $file->move(base_path('/public/img/products'),$new_name);
                    $name='img/products/'.$new_name;
                }
            }
        }

        return $name;
    }

    /**
    * Función para eliminar la imagen de un producto
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function deletePhoto($old_photo){
        //eliminamos foto previa si es indicada
        if(isset($old_photo) && trim($old_photo)!=='' && trim($old_photo)!==NULL){
            $storage=\Illuminate\Support\Facades\Storage::disk('resources');
            $storage->delete($old_photo);
        }
    }

    /**
    * Función para eliminar un producto
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ProductsController
    */
    public function deleteProduct($id){
        $samples = \App\Models\ProductsSamples::where('id_product',$id)->first();
        if(!$samples){
            $product = Products::find($id);
            $this->deletePhoto($product->photo);
            //borramos los valores de campos dinamicos si existen
            $samples = \App\Models\ProductsFieldsValues::where('id_product',$id)->delete();
            $deletedRows = Products::destroy($id);
            return response()->json(['status'=>'success','data'=>['message'=>htmlentities('Producto eliminado')]], ResponseHelper::getTypeResponse('success'), 'content-type'=>ResponseHelper::getCodification());;
        }else{
            return response()->json(['status'=>'error','data'=>['message'=>htmlentities('Existe una muestra asociada al producto, No se puede borrar este producto')]], ResponseHelper::getTypeResponse('error'), 'content-type'=>ResponseHelper::getCodification());;
        }
    }
}
