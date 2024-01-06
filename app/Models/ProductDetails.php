<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    protected $table='tbl_product_details';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}