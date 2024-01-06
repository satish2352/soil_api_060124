<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $table='farmer';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}