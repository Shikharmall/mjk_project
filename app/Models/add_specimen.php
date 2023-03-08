<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_specimen extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_specimen';
    public $timestamps=false;
    protected $fillable=['name','created'];
}
