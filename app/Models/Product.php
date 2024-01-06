<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='tbl_product';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}