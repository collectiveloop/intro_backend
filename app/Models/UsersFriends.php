<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UsersFriends extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_friends';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_user','id_user_friend','friend_info','status','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
