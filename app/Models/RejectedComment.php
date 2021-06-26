<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedComment extends Model
{
    use HasFactory;
    protected $table = "rejected_comment";
    protected $fillable = [
        'item_id',
        'orderNo',
        'comment',
        'rejected_by',
        'rejected_date',
        'created_at',
        'updated_at'
    ];
}
