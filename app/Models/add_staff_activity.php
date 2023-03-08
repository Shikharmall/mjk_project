<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_staff_activity extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_staff_activity';
    public $timestamps=false;
    protected $fillable=['staff_id','address','latitude','longitude','created','created_time','status','status_id','kilometer','actual_kilometer','sample_id'];
}
