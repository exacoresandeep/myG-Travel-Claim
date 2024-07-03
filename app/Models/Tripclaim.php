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
}
