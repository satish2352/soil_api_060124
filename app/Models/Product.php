<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='tbl_product';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}