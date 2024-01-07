<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table='tbl_area';
    protected $primeryKey='location_id';
    public $timestamps=false;
    protected $fillable=[];
}