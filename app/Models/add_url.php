<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class add_url extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='add_url';
    public $timestamps=false;
    protected $fillable=['url'];
}
