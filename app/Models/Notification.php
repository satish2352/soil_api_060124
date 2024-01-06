<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table='notification';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}