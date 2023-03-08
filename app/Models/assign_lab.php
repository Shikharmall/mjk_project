<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assign_lab extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='assign_lab';
    public $timestamps=false;
    protected $fillable=['staff_id','lab_id','assign_date','created'];
}
