<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_intro','id_user_1','id_user_2','id_user_3','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
