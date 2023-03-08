<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_sample_invoice extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_sample_invoice';
    public $timestamps=false;
    protected $fillable=['from_date','to_date','rate','total_sample','amount','created','created_time','district_id'];
}
