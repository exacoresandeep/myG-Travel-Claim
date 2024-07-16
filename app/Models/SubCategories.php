<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    public $table="myg_04_subcategories";
    protected $fillable = [
        'SubCategoryID',
        'UomID',
        'CategoryID',
        'SubCategoryName',
        'Status',
        'user_id'
    ];


    public function categoryDetails()
    {
        return $this->hasMany(Category::class, 'CategoryID','CategoryID');
    }

    public function categorydata()
    {
        return $this->hasOne(Category::class, 'CategoryID','CategoryID');
    }
}
