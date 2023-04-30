<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentProductCategory extends Model
{
    use SoftDeletes;
    protected $table = 'parent_product_categories';
    protected $fillable = ['name', 'slug','parent_category_id','description','image','image_url','thumbnail_image','thumbnail_image_url','status', 'created_by', 'updated_by'];

    public function parent()
    {
        return $this->belongsTo(ParentProductCategory::class, 'parent_category_id');
    }

    public function childes()
    {
        return $this->hasMany(ParentProductCategory::class, 'parent_category_id');
    }

    // recursive, loads all descendants
    public function childrenRecursive()
    {
        return $this->childes()->with('childrenRecursive');
    }


}
