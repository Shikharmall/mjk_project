<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_sample_selected extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_sample_selected';
    public $timestamps=false;
    protected $fillable=['sample_id','collection_id','created'];
}
