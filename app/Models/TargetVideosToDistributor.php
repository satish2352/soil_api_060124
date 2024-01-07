<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TargetVideosToDistributor extends Model
{
    protected $table='tbl_target_videos_to_distributor';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}