<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Crops extends Model
{
    protected $table='crops';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}