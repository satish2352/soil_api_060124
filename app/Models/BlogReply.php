<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BlogReply extends Model
{
    protected $table='tbl_blog_reply';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}