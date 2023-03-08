<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_invoice_sample extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_invoice_sample';
    public $timestamps=false;
    protected $fillable=['invoice_id','sample_id','created'];
}
