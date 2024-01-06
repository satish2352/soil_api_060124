<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebBlog extends Model
{
    protected $table='tbl_web_blog';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}