<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table='language';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}