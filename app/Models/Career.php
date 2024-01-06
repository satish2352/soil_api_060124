<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table='front_career';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}