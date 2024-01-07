<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table='tbl_complaint';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}