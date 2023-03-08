<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_selected_sample extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_selected_sample';
    public $timestamps=false;
    protected $fillable=['sample_id','collection_id','created','staff_id','status'];
}
