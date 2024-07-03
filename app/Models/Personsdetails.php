<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personsdetails extends Model
{
    use HasFactory;
    public $table="myg_10_persons_details";
    protected $fillable = [
        'PersonDetailsID',
        'TripClaimDetailID',
        'Grade',
        'EmployeeID',
        'ClaimOwner',
        'user_id'
    ];
}
