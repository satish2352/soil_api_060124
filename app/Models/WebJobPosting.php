<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WebJobPosting extends Model
{
    protected $table='tbl_web_job_posting';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}