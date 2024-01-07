<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WebCoverPhoto extends Model
{
    protected $table='tbl_web_cover_photo';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}