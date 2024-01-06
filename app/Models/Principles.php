<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Principles extends Model
{
    protected $table='principles';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}