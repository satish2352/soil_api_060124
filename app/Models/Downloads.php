<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Downloads extends Model
{
    protected $table='tbl_downloads';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}