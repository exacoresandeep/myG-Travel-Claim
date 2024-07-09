<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    public $table="myg_06_policies";
    protected $fillable = [
        'PolicyID',
        'SubCategoryID',
        'GradeID',
        'GradeClass',
        'GradeType',
        'GradeAmount',
        'Approver',
        'Status',
        'user_id'
    ];


    public function subCategoryDetails()
    {
        return $this->hasMany(SubCategories::class, 'SubCategoryID','SubCategoryID');
    }
    public function gradeDetails()
    {
        return $this->hasMany(Grades::class, 'GradeID','GradeID');
    }
}
