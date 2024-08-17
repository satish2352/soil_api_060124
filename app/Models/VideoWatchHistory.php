<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VideoWatchHistory extends Model
{
    protected $table='video_watch_history';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}