<?php
namespace App\Http\Controllers;
use App\Models\ContactsPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

/**
*
* @version 1.0
* @license Copyright Sappitotech 2017. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que gestiona los datos de los contactos de los usuarios
*
**/
class ContactsController extends Controller
{
    /**
    * Constructor de la clase
    * @author Junior Milano <junior@sappitotech.com>
    * @memberof ContactsController
    */
    public function __construct(){
      //control de acceso a determinados metodos de acuerdo a la permisologisa de administrador
      $this->middleware('validate.administrator_user', ['only' => []]);
    }

    /**
    * Función para retornar los datos del usuario en la sesión
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getDataUser(){
      $verification = \App\Models\Users::verifyAuthentication();

      return $verification['data']['user'];
    }

    /**
    * Función que retorna la información de los contactos del usuario de forma paginada
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getContactsPaginate($lang,$page,$quantity){
      $data_user = $this->getDataUser();
      // obtenemos la información del usuario autenticado
      $contacts = \App\Models\UsersFriends::join('users','users.id','=','users_friends.id_user_friend')
      ->orderBy('users.first_name', 'asc')
      ->orderBy('users.last_name', 'asc')
      ->orderBy('users_friends.created_at', 'desc')
      ->orderBy('users_friends.updated_at', 'desc')
      ->limit($quantity)
      ->offset(($page-1)*$quantity)
      ->where('users_friends.id_user','=',$data_user['id'])
      ->get([
        'users.id as id_user',
        'users_friends.id as id_user_friend',
        'users.first_name',
        'users.last_name',
        'users.user_name',
        'users.email',
        'users.image_profile'
      ]);

      return ['status'=>'success','data'=>['contacts' => $contacts->toArray()]];
    }

    /**
    * Función que retorna la cantidad de contactos del usuarios en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getCountContacts($lang){
      $data_user = $this->getDataUser();
      $count = \App\Models\UsersFriends::where('id_user','=',$data_user['id'])->count();

      return ['status'=>'success','data'=>['contacts_count' => $count]];
    }

    /**
    * Función que retorna la información de las invitaciones pendientes del usuario
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getContactsPendingPaginate($lang,$page,$quantity){
      $data_user = $this->getDataUser();
      // obtenemos la información del usuario autenticado
      $contacts_pending = ContactsPending::leftJoin('users','users.id','=','contacts_pending.id_user')
      ->orderBy('users.first_name', 'asc')
      ->orderBy('users.last_name', 'asc')
      ->orderBy('contacts_pending.created_at', 'desc')
      ->orderBy('contacts_pending.updated_at', 'desc')
      ->limit($quantity)
      ->offset(($page-1)*$quantity)
      ->where('contacts_pending.email','=',$data_user['email'])
      ->where('contacts_pending.status','=',0)
      ->get([
        'contacts_pending.id as id',
        'contacts_pending.id_user as id_user',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.image_profile'
      ]);

      return ['status'=>'success','data'=>['contacts_pending' => $contacts_pending->toArray()]];
    }

    /**
    * Función que retorna la cantidad de invitaciones pendientes del usuarios en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getCountContactsPending($lang){
      $data_user = $this->getDataUser();
      $count = ContactsPending::where('email','=',$data_user['email'])->where('status','=',0)->count();

      return ['status'=>'success','data'=>['sdfsdfs'=>$data_user['email'],'contacts_pending_count' => $count]];
    }

    /**
    * Función que retorna de forma combinada las invitaciones y los contactos del usuarios en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getContactsMixedPaginate($lang,$page,$quantity,$find=''){
      $count = [ 'total' =>0,'contacts_pending'=>0,'user_friends'=>0];
      if($page==1){
        $count = $this->getCountMixedContacts($page,$find);
      }
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      $contacts_pending = ContactsPending::select(\DB::raw('"contacts_pending" as type_query'), 'contacts_pending.id as id', 'contacts_pending.id_user as id_user', 'users.first_name','users.last_name','users.email','users.image_profile','contacts_pending.status as status', 'contacts_pending.created_at as created_at', 'contacts_pending.updated_at as updated_at')
      ->leftJoin('users','users.id','=','contacts_pending.id_user')
      ->where('contacts_pending.email','=',$data_user['email'])
      ->where('contacts_pending.status','!=',1);

      if(trim($find)!==''){
        $contacts_pending->where(function($query) use($find){
          $query->where('users.first_name','like','%'.$find.'%')->orWhere('users.last_name','like','%'.$find.'%')->orWhere('users.email','like','%'.$find.'%')->orWhere('users.user_name','like','%'.$find.'%');
        });
      }

      $contacts_invited = ContactsPending::select(\DB::raw('"contacts_invited" as type_query'), 'contacts_pending.id as id', 'users.id as id_user', 'contacts_pending.full_name as first_name', \DB::raw('"" as last_name'),'contacts_pending.email as email','users.image_profile as image_profile', \DB::raw('"0" as status'), 'contacts_pending.created_at as created_at', 'contacts_pending.updated_at as updated_at')
      ->leftJoin('users','users.email','=','contacts_pending.email')
      ->where('contacts_pending.id_user','=',$data_user['id']);

      if(trim($find)!==''){
        $contacts_invited->where(function($query) use($find){
          $query->where('contacts_pending.full_name','like','%'.$find.'%')->orWhere('contacts_pending.email','like','%'.$find.'%');
        });
      }

      // obtenemos la información del usuario autenticado
      $query = \App\Models\UsersFriends::select(\DB::raw('"users_friends" as type_query'), 'users_friends.id as id','users.id as id_user','users.first_name','users.last_name','users.email','users.image_profile',\DB::raw('"false" as status'), 'users.created_at as created_at', 'users.updated_at as updated_at')
      ->join('users','users.id','=','users_friends.id_user_friend')
      ->where('users_friends.id_user','=',$data_user['id']);

      if(trim($find)!==''){
        $query->where(function($query2) use($find){
          $query2->where('users.first_name','like','%'.$find.'%')->orWhere('users.last_name','like','%'.$find.'%')->orWhere('users.email','like','%'.$find.'%')->orWhere('users.user_name','like','%'.$find.'%');
        });
      }

      /*contactos no vinculados al usuario, directa o indirectamente*/
      $count_users;

      if(trim($find)!=='' && $page==1){
        //los que no son contactos

        $count_users = \App\Models\Users::select(\DB::raw('"users_not_contacts" as type_query'), 'users.id as id','users.id as id_user','users.first_name','users.last_name','users.email','users.image_profile',\DB::raw('"false" as status'), 'users.created_at as created_at', 'users.updated_at as updated_at')
        ->leftJoin('users_friends', function($join) use($data_user,$find){
            $join->on('users_friends.id_user_friend', '=', 'users.id')
            ->where('users_friends.id_user','=',$data_user['id']);
        })
        ->leftJoin('contacts_pending as cp1', function($join) use($data_user,$find){
            $join->on('cp1.email', '=', 'users.email')
            ->where('cp1.id_user','=',$data_user['id']);
        })
        ->leftJoin('contacts_pending as cp2', function($join) use($data_user,$find){
            $join->on('cp2.id_user', '=', 'users.id')
            ->where('cp2.email','=',$data_user['email']);
        })
        ->where(function($query_join) use($find){
          $query_join->where('users.first_name','like','%'.$find.'%')->orWhere('users.last_name','like','%'.$find.'%')->orWhere('users.email','like','%'.$find.'%')->orWhere('users.user_name','like','%'.$find.'%');
        })
        ->whereNull('users_friends.id')
        ->whereNull('cp1.id')
        ->whereNull('cp2.id')
        ->limit(10);
      }
      /*Fin de contactos no vinculados al usuario, directa o indirectamente*/

       $query->union($contacts_invited)
      ->union($contacts_pending);

      //los que no conoce el usuario solo los ponemos si fue una busqueda
      if(trim($find)!=='' && $page==1)
        $query->union($count_users);


      //para anhidar los usuarios  de bd aumentamos provisionalmente quantity solo si es necesario
      if(isset($count['users_not_contacts']) && $count['users_not_contacts']>0)
        $quantity = $quantity+$count['users_not_contacts'];

      $contacts_mixed = $query->orderBy('first_name', 'asc')
      ->orderBy('last_name', 'asc')
      ->orderBy('created_at', 'desc')
      ->orderBy('updated_at', 'desc')
      ->distinct('id_user')
      ->groupBy('email')
      ->limit($quantity)
      ->offset(($page-1)*$quantity)
      ->get();
      $return  = ['status'=>'success','data'=>['contacts_mixed' => $contacts_mixed->toArray()]];
      if($page==1){
        $return['data']['contacts_mixed_count'] = $count['total'];
        $return['data']['contacts_pending_count'] = $count['contacts_pending'];
        $return['data']['contacts_invited_count'] = $count['contacts_invited'];
        $return['data']['user_friends_count'] = $count['user_friends'];
        $return['data']['users_not_contacts_count'] = $count['users_not_contacts'];
      }

      return $return;
    }

    /**
    * Función que retorna de forma combinada la cantidad de invitaciones y contactos del usuarios en bd
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getCountMixedContacts($page,$find){
      $data_user = $this->getDataUser();
      $query = \App\Models\UsersFriends::join('users','users.id','=','users_friends.id_user_friend')->where('users_friends.id_user','=',$data_user['id']);

      $query2 = ContactsPending::join('users','users.id','=','contacts_pending.id_user')->where('contacts_pending.email','=',$data_user['email']);

      $query3 = ContactsPending::where('contacts_pending.id_user','=',$data_user['id']);

      $count_users=0;
      if(trim($find)!=='' && $page==1){
        $query->where(function($query_internal) use($find){
          $query_internal->where('users.first_name','like','%'.$find.'%')->orWhere('users.last_name','like','%'.$find.'%')->orWhere('users.email','like','%'.$find.'%')->orWhere('users.user_name','like','%'.$find.'%');
        });
        $query2->where(function($query_internal) use($find){
          $query_internal->where('users.first_name','like','%'.$find.'%')->orWhere('users.last_name','like','%'.$find.'%')->orWhere('users.email','like','%'.$find.'%')->orWhere('users.user_name','like','%'.$find.'%');
        });

        $query3->where(function($query_internal) use($find){
          $query_internal->where('full_name','like','%'.$find.'%')->orWhere('email','like','%'.$find.'%');
        });
        //los que no han sido invitados,  ni contactos y tampoco han invitado al usuario
        $count_users = \App\Models\Users::select('users.id')
        ->leftJoin('users_friends', function($join) use($data_user,$find){
            $join->on('users_friends.id_user_friend', '=', 'users.id')
            ->where('users_friends.id_user','=',$data_user['id']);
        })
        ->leftJoin('contacts_pending as cp1', function($join) use($data_user,$find){
            $join->on('cp1.email', '=', 'users.email')
            ->where('cp1.id_user','=',$data_user['id']);
        })
        ->leftJoin('contacts_pending as cp2', function($join) use($data_user,$find){
            $join->on('cp2.id_user', '=', 'users.id')
            ->where('cp2.email','=',$data_user['email']);
        })
        ->where(function($query_join) use($find){
          $query_join->where('users.first_name','like','%'.$find.'%')->orWhere('users.last_name','like','%'.$find.'%')->orWhere('users.email','like','%'.$find.'%')->orWhere('users.user_name','like','%'.$find.'%');
        })
        ->whereNull('users_friends.id')
        ->whereNull('cp1.id')
        ->whereNull('cp2.id')
        ->count();
      }

      $count_friends = $query->count();
      $count_pending = $query2->count();
      $count_invited = $query3->count();

      return [
        'total'=>($count_friends+$count_pending+$count_invited),
        'contacts_pending'=>$count_pending,
        'contacts_invited'=>$count_invited,
        'user_friends'=>$count_friends,
        'users_not_contacts'=>$count_users
      ];
    }

    /**
    * Función para aceptar la invitación pendiente del usuario
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function acceptInvitation($lang,$id){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='')
        \App::setLocale($lang);

      $contact = ContactsPending::where('id','=',$id)->where('email','=',$data_user['email'])->first();
      if(!$contact)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invitation_not_found'))]];

      //validamos que el correo sea unico
      if($contact->status!=0){
        if($contact->status==1)
          return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invitation_accepted_invalid'))]];
        else
          return ['status'=>'error','data'=>['status'=>$contact->status,'message'=>htmlentities(\Lang::get('validation.messages.invitation_rejected_invalid'))]];
      }
      $contact->status = 1;
      $contact->save();
      //ahora lo guardamos como contacto
      \App\Models\UsersFriends::create(['id_user'=>$data_user['id'], 'id_user_friend'=>$contact->id_user]);
      \App\Models\UsersFriends::create(['id_user'=>$contact->id_user, 'id_user_friend'=>$data_user['id']]);

      return ['status'=>'success','data'=>[ 'message'=>htmlentities(\Lang::get('validation.messages.success_update')) ] ];
    }

    /**
    * Función para rechazar la invitación pendiente del usuario
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function rejectInvitation($lang,$id){
      //datos del usuario
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='')
        \App::setLocale($lang);

      $contact = ContactsPending::where('id','=',$id)->where('email','=',$data_user['email'])->first();
      if(!$contact)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invitation_not_found'))]];

      //validamos que el correo sea unico
      if($contact->status!=0){
        if($contact->status==1)
          return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invitation_accepted_invalid'))]];
        else
          return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.invitation_rejected_invalid'))]];
      }
      $contact->status = 2;
      $contact->save();

      return ['status'=>'success','data'=>[ 'message'=>htmlentities(\Lang::get('validation.messages.success_update')) ] ];
    }

    /**
    * Función que retorna la información del usuario actual
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function getContact($lang,$id){
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='')
        \App::setLocale($lang);
      // obtenemos la información del usuario autenticado
      $user = \App\Models\Users::where('users.id',$id)
      ->first([
        'users.id',
        'users.user_name',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.job_title',
        'users.job_description',
        'users.company_name',
        'users.image_profile'
      ]);

      return ['status'=>'success','data'=>['contact' => $user->toArray()]];
    }

    /**
    * Función que retorna la información del usuario encontrado según el correo suministrado
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function findContact($lang,$email){
      if(trim($email)==='')
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.custom.email.required'))]];

      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='')
        \App::setLocale($lang);
      // obtenemos la información del usuario autenticado
      $contact = \App\Models\Users::where('email',$email)->where('id','!=',$data_user['id'])
      ->first([
        'users.id',
        'users.first_name',
        'users.last_name',
        'users.email',
        'users.image_profile'
      ]);

      if(!$contact)
        return ['status'=>'warning','data'=>['message'=>htmlentities(\Lang::get('validation.messages.email_no_exist'))]];

      return ['status'=>'success','data'=>['contact' => $contact->toArray()]];
    }

    /**
    * Función para insertar los datos de un nuevo contacto
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function addContact($lang){
      $data_user = $this->getDataUser();
      if(!isset($data_user['id']))
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.user_not_found'))]];

      if(trim($lang)!=='')
        \App::setLocale($lang);

      $inputs = Input::all();
      //'number_phones' => 'required|min:7',
      $validator = \Validator::make($inputs, [
        'full_name' => 'required|min:2',
        'email' => 'required|email|min:5'
      ]);

      if ($validator->fails()){
        $errors = $validator->errors();
        return ['status'=>'error','data'=>['message'=>$errors->all()]];
      }

      // validamso que el nuevo contacto no sea el mismo usuario
      $user = \App\Models\Users::where('id','=',$data_user['id'])->where('email','=',trim($inputs['email']))->first();
      if($user)
        return ['status'=>'error','data'=>['dd'=>$user,'message'=>htmlentities(\Lang::get('validation.messages.invalid_user'))]];

      $user_friend = \App\Models\UsersFriends::join('users', function($join) use ($inputs){
        $join->where('users.email','=',trim($inputs['email']))
            ->on('users.id','=','users_friends.id_user_friend');
      })
      ->where('users_friends.id_user','=',$data_user['id'])->first();

      if($user_friend)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.is_contact'))]];

      $contact_pending=ContactsPending::where('id_user',$data_user['id'])->where('email','=',trim($inputs['email']))->first();
      if($contact_pending)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.is_contact_pending'))]];

      $contact_pending = ContactsPending::create(
      [
        'id_user'=>$data_user['id'],
        'full_name'=>trim($inputs['full_name']),
        'email'=>trim($inputs['email']),
        'status'=>0
      ]);

      $email_data = [
        'email'=>$inputs['email'],
        'full_name'=>$inputs['full_name'],
        'first_name'=>$data_user['first_name'],
        'last_name'=>$data_user['last_name'],
        'link'=>htmlentities(\Lang::get('validation.messages.accept_invitation')),
        'dear_email'=>htmlentities(\Lang::get('validation.messages.dear_email')),
        'content_invitation'=>htmlentities(\Lang::get('validation.messages.content_invitation')),
        'regards_email'=>htmlentities(\Lang::get('validation.messages.regards_email')),
        'invitation_send_email'=>htmlentities(\Lang::get('validation.messages.invitation_send_email'))
      ];

      \Mail::send('emails.add_contact', $email_data, function($message) use ($email_data) {
        $message->to($email_data['email']);
        $message->subject($email_data['invitation_send_email']);
      });

      if(count(\Mail::failures()) > 0)
        return ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('validation.messages.fail_send_email'))]];


      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_register'))]];
    }

    /**
    * Función para redirecionar la pagina y abrir la app
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function redirectLink(){
      $split = explode('/',url('/'));
      $domain = $split[0];

      return view('redirect', ['url'=>$domain.'/invitation-contact','token' =>'']);
    }

    /**
    * Función para eliminar el usuario pasado como parametro
    * @author Junior Milano <junior@sappitotech.com>
    * @return array
    * @memberof ContactsController
    */
    public function deleteUser($lang,$id){
      $deletedRows = ContactsPending::destroy($id);

      return ['status'=>'success','data'=>['message'=>htmlentities(\Lang::get('validation.messages.success_delete'))]];
    }

}
