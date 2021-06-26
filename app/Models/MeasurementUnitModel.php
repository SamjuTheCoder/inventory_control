<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnitModel extends Model
{
    use HasFactory;

    protected $table        = 'measurement_units';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'productID',
        'measurementID',
        'quantity',
    ];

    protected $casts = [];

    protected $hidden = [];

    public $timestamps = true;
}
