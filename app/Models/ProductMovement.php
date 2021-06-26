<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'storeID',
        'storeID_destination',
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
        'adjust_reason',
        'is_adjusted',
        'hasColor',
        'status',
        'move_in_out_type',
        'is_transferred',
        'is_accepted',
        'transfer_refID',
    ];
}
