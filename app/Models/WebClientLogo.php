<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WebClientLogo extends Model
{
    protected $table='tbl_web_client_logos';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}