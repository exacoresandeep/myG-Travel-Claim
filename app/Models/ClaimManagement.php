<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ClaimManagement extends Model
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
        return $this->belongsTo(Tripclaimdetails::class, 'TripClaimID','TripClaimID');
    }
    public function tripclaimdetailsforclaim(){
        return $this->hasMany(Tripclaimdetails::class, 'TripClaimID','TripClaimID');
    }
    public function sumTripClaimDetailsValue()
    {
        return $this->tripclaimdetails()
            ->select(DB::raw('SUM(Qty * UnitAmount) as total_value'))
            ->pluck('total_value')
            ->first(); // Get the first item since we expect a single value
    }
    public function visitbranchdetails()
    {
        return $this->belongsTo(Branch::class, 'VisitBranchID','BranchID');
    }

    public function userdetails()
    {
        return $this->belongsTo(User::class,'ApproverID', 'id');
    }

    public function usercodedetails()
    {
        return $this->belongsTo(User::class,'ApproverID', 'emp_id');
    }
    
    public function userdata()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
    
    public function triptypedetails()
    {
        return $this->belongsTo(Triptype::class, 'TripTypeID','TripTypeID');
    }

    

}
