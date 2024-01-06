<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class SubscriberTarget extends Model
{
    protected $table='tbl_suscriber';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}