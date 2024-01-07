<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table='videos';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}