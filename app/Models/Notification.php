<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table='notification';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}