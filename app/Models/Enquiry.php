<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table='tbl_enquiry';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}