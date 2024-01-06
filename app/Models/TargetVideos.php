<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class TargetVideos extends Model
{
    protected $table='tbl_target_videos';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}