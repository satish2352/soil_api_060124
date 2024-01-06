<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class FrontUsers extends Model
{
    protected $table='front_usersinfo';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}