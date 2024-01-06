<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table='tbl_productreview';
    protected $primeryKey='id';
    public $timestamps=false;
    protected $fillable=[];
}