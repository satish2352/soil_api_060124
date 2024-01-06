<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table='tbl_complaint';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}