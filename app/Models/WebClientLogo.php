<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebClientLogo extends Model
{
    protected $table='tbl_web_client_logos';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}