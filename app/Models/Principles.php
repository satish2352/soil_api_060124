<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Principles extends Model
{
    protected $table='principles';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}