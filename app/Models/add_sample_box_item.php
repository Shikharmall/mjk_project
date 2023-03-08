<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_sample_box_item extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_sample_box_item';
    public $timestamps=false;
    protected $fillable=['staff_id','invoice_photo','scan_code','patient','created','status','type_test_for','type_patient','no_of_sample','specimen_id','test_id','lab_id','nikshay_id'];
}
