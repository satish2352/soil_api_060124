<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class OrderSummary extends Model
{
    protected $table='tbl_order_summary';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}