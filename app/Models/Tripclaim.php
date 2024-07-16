<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tripclaim extends Model
{
    use HasFactory;
    public $table="myg_08_trip_claim";
    protected $fillable = [
        'TripClaimID',
        'TripTypeID',
        'ApproverID',
        'TripPurpose',
        'VisitBranchID',
        'AdvanceAmount',
        'ApprovalDate',
        'RejectionCount',
        'NotificationFlg',
        'Status',
        'user_id'
    ];


    public function tripclaimdetails()
    {
        return $this->hasMany(Tripclaimdetails::class, 'TripClaimID','TripClaimID');
    }

    public function visitbranchdetails()
    {
        return $this->hasMany(Branch::class, 'BranchID','VisitBranchID');
    }

    public function approverdetails()
    {
        return $this->hasMany(User::class, 'id','ApproverID');
    }
    
    public function triptypedetails()
    {
        return $this->hasMany(TripType::class, 'TripTypeID','TripTypeID');
    }
}
