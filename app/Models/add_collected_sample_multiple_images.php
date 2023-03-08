<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_collected_sample_multiple_images extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_collected_sample_multiple_images';
    public $timestamps=false;
    protected $fillable=['sample_id','created','staff_id','image'];
}
