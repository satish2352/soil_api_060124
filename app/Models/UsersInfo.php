<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UsersInfo extends Model
{
    protected $table='usersinfo';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}