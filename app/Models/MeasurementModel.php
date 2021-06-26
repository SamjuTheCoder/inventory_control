<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementModel extends Model
{
    use HasFactory;

    protected $table        = 'measurements';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'description',
    ];

    protected $casts = [];

    protected $hidden = [];

    public $timestamps = true;
}
