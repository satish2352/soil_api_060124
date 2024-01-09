<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UsersInfoForStructures extends Model
{
    protected $table='users_info_for_structures';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}