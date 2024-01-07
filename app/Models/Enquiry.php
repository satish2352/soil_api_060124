<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table='tbl_enquiry';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}