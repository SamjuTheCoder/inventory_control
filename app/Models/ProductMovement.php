<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'storeID',
        'userID',
        'productID',
        'measurementID',
        'projectID',
        'transactionDate',
        'move_in',
        'move_out',
        'description',
        'orderNo',
        'quantity',
        'shelveID',
        'isConfirmed',
        'isConfirmedBy',
        'hasColor',
        'status',
    ];
}
