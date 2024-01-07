<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table='tbl_subscriber';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}