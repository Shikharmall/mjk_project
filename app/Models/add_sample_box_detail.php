<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_sample_box_detail extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_sample_box_detail';
    public $timestamps=false;
    protected $fillable=['sample_id','nikshay_id','patient','created','invoice_photo','date','staff_id','sample_meter_name','sample_meter_photo','sample_date_time','kilometer','degree','sample_box_photo','sample_box_name','to_sample_date_time','to_kilometer','to_degree','type_test_for','type_patient','no_of_sample','specimen_id','test_id','lab_id','status','collected_from','scan_code','district_id','to_sample_meter_photo','to_sample_box_photo','map_area_name','from_latitude','from_longitude','to_map_area_name','to_latitude','to_longitude','collect_map_area_name','collect_latitude','collect_longitude','submit_map_area_name','submit_latitude','submit_longitude','sample_auto_id','sample_invoice_status','submit_actual_kilometer','collect_actual_kilometer','to_actual_kilometer'];
}
