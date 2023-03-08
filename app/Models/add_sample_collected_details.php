<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_sample_collected_details extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_sample_collected_details';
    public $timestamps=false;
    protected $fillable=['sample_id','staff_id','sample_meter_name','sample_meter_photo','sample_date_time','collected_from','map_area_name','kilometer','degree','sample_box_photo','sample_box_name','from_latitude','from_longitude','to_sample_date_time','to_kilometer','to_degree','created','updated','to_map_area_name','to_latitude','to_longitude','status','to_sample_meter_photo','to_sample_box_photo','collect_map_area_name','collect_latitude','collect_longitude','submit_map_area_name','submit_latitude','submit_longitude','kilometer_invoice_status','submit_actual_kilometer','collect_actual_kilometer','to_actual_kilometer'];

    public function c_detail()
    {
        return $this->hasMany(add_sample_box_detail::class,'sample_id','sample_id');
    }
}
