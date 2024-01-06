<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='tbl_area';
    protected $primeryKey='location_id';
    public $timestamps=false;
    protected $fillable=[];
}