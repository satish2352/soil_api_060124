<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table='audio';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}