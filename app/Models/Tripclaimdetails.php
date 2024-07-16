<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tripclaimdetails extends Model
{
    use HasFactory;
    public $table="myg_09_trip_claim_details";
    protected $fillable = [
        'TripClaimDetailID',
        'TripClaimID',
        'PolicyID',
        'FromDate',
        'ToDate',
        'TripFrom',
        'TripTo',
        'DocumentDate',
        'Qty',
        'UnitAmount',
        'NoOfPersons',
        'FileUrl',
        'Remarks',
        'NotificationFlg',
        'RejectionCount',
        'ApproverID',
        'Status',
        'user_id'
    ];

    public function personsDetails()
    {
        return $this->hasMany(PersonsDetails::class, 'TripClaimDetailID', 'TripClaimDetailID');
    }
    public function policyDetails()
    {
        return $this->hasMany(Policy::class, 'PolicyID', 'PolicyID');
    }
    
}
