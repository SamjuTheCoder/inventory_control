<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoryID',
        'subcategoryID',
        'productName',
        'min_measurementID',
        'barcode',
    ];

}
