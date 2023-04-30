<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model {
    protected $table    = 'discounts';

    protected $fillable = [
        'title',
        'discount_for',
        'discount_percentage',
        'discount_amount',
        'start_at',
        'end_at',
        'is_ongoing',
        'status',
        'is_approved',
        'discountable_id',
        'discountable_type',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public function createdBy() {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy() {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }

    public function discountable() {
        return $this->morphTo();
    }
    public function getDiscountedBrand() {
        return $this->where('discountable_type','product_brands');
    }
    public function getDiscountedVendor() {
        return $this->where('discountable_type','vendors');
    }
    public function getDiscountedProduct() {
        return $this->where('discountable_type','product_categories');
    }
    public function getDiscountedCategory() {
        return $this->where('discountable_type','products');
    }
}
