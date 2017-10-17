<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Clients extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['rut','id_commune','description', 'address','contact_name','phone_number', 'email','notes'];

    public static function initialCondition(){
        //return self::where('clientes.id','>',0);
    }

}
