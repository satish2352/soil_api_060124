<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $table='farmer';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}