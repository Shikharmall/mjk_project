<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assign_district extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='assign_district';
    public $timestamps=false;
    protected $fillable=['staff_id','district_id','assign_date','created'];
}
