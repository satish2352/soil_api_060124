<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class FarmerVistByDistributor extends Model
{
    protected $table='tbl_farmer_vist_by_distributor';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}