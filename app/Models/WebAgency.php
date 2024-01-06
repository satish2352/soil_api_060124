<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebAgency extends Model
{
    protected $table='tbl_agency_detail';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}