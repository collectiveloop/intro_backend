<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Intros extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'intros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_user','id_friend_1','id_friend_2','reason','friend_1_info','friend_2_info','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
