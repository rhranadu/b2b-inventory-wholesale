<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopCategories extends Model
{
    protected $guarded = [];

    protected $fillable = [
      'category_id',
      'product_quantity',
      'amount',
      'date',
    ];

}
