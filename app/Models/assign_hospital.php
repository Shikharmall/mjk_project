<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assign_hospital extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='assign_hospital';
    public $timestamps=false;
    protected $fillable=['staff_id','hospital_id','assign_date','created'];
}
