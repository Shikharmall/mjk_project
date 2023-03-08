<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_hospital extends Model
{
    use HasFactory;
    protected $primaryKey='hospital_id';
    protected $table='add_hospital';
    public $timestamps=false;
    protected $fillable=['name','district_id','mobile','email','gps_address','latitude','longitude','image','status','created'];
}