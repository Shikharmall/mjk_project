<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_sample_box_sample extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_sample_box_sample';
    public $timestamps=false;
    protected $fillable=['sample_selected_id','created','staff_id','hospital_id','date'];
}
