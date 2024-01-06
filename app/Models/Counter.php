<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table='front_counters';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}