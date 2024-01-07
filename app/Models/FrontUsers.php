<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FrontUsers extends Model
{
    protected $table='front_usersinfo';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}