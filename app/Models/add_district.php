<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_district extends Model
{
    use HasFactory;
    protected $primaryKey='district_id';
    protected $table='add_district';
    public $timestamps=false;
    protected $fillable=['name','created','gps_address','latitude','longitude'];
}
