<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table='tbl_messages';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}