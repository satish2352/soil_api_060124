<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FarmerMeetingDetails extends Model
{
    protected $table='tbl_farmer_meeting_details';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}