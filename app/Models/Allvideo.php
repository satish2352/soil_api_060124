<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Allvideo extends Model
{
    protected $table='tbl_all_videos';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}