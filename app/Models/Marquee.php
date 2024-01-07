<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Marquee extends Model
{
    protected $table='tbl_website_marquee';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}