<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class UsersProfile extends Model
{
    protected $table='tbl_user_profile';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}