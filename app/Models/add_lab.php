<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_lab extends Model
{
    use HasFactory;
    protected $primaryKey='lab_id';
    protected $table='add_lab';
    public $timestamps=false;
    protected $fillable=['name','district_id','gps_address','latitude','longitude','image','status','created'];
}
