<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LoginDetail extends Model
{
    protected $table='login_detail';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}