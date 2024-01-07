<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table='tbl_messages';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}