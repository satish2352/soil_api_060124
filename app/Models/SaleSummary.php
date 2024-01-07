<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SaleSummary extends Model
{
    protected $table='tbl_sale_summary';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}