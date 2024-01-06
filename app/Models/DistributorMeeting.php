<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class DistributorMeeting extends Model
{
    protected $table='tbl_distributor_meeting';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}