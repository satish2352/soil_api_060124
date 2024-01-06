<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table='tbl_order_detail';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}