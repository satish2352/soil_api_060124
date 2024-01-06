<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class WebCompanyProfile extends Model
{
    protected $table='tbl_web_company_profile';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}