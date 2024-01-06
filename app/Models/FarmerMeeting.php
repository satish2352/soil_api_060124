<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class FarmerMeeting extends Model
{
    protected $table='tbl_farmer_meeting';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}