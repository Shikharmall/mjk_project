<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class distance_status extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='distance_status';
    public $timestamps=false;
    protected $fillable=['status','created'];
}