<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class SCTResult extends Model
{
    protected $table='tbl_sct_result';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}