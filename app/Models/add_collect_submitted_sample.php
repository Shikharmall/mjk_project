<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_collect_submitted_sample extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_collect_submitted_sample';
    public $timestamps=false;
    protected $fillable=['sample_selected_id','created','staff_id','collected_id','report_id','lab_id'];
}
