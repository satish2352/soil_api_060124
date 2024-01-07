<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WebGallaryPhoto extends Model
{
    protected $table='tbl_web_gallary_photo';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}