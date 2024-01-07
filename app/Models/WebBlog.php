<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WebBlog extends Model
{
    protected $table='tbl_web_blog';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}