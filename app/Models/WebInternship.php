<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebInternship extends Model
{
    protected $table='tbl_web_internship';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}