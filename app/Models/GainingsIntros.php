<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GainingsIntros extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gainings_intros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_intro','id_gain','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
