<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table='address';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}