<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_submitted_hospital_report extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_submitted_hospital_report';
    public $timestamps=false;
    protected $fillable=['lt_name','designation','digital_signature','sample_selected_id','created','staff_id','report_id','hospital_id'];
}
