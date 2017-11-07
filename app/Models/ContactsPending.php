<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ContactsPending extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts_pending';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_user','full_name','email','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
