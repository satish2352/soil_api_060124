<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebAboutUs extends Model
{
    protected $table='tbl_web_aboutus';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}