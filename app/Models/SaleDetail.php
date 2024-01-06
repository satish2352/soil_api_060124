<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $table='tbl_sale_detail';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}