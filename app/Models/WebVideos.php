<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebVideos extends Model
{
    protected $table='tbl_web_videos';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}