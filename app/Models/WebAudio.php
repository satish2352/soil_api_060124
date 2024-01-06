<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebAudio extends Model
{
    protected $table='tbl_web_audio';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}