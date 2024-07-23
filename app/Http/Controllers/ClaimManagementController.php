<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClaimManagement;
use Auth;
use DB;
use Carbon\Carbon; // Import Carbon

class ClaimManagementController extends Controller
{
    public function approved_claims()
    {
        return view('admin.claim_management.approved_claims');
    }
    public function approved_claims_list(Request $request)
	{
	    if ($request->ajax()) 
	    { 
	        $pageNumber = ($request->start / $request->length) + 1;
	        $pageLength = $request->length;
	        $skip = ($pageNumber - 1) * $pageLength;
	        $orderColumnIndex = $request->order[0]['column'] ?? 0;
	        $orderBy = $request->order[0]['dir'] ?? 'desc';
	        $searchValue = $request->search['value'] ?? '';
	        $columns = [
	            'TripClaimID',
	            'created_at'
	        ];
	        $orderColumn = $columns[$orderColumnIndex] ?? 'TripClaimID';
	        $query = ClaimManagement::with('visitbranchdetails')->with('userdetails')->with('triptypedetails')
                        ->where('Status','Approved')
                        ->orderBy($orderColumn, $orderBy);

	        $recordsTotal = $query->count();
	        $data = $query->skip($skip)->take($pageLength)->get();
	        $recordsFiltered = $recordsTotal;

	        $formattedData = $data->map(function($row) 
	        {
	            return [
	                'TripClaimID' => $row->TripClaimID,
	                'created_at' => Carbon::parse($row->created_at)->format('d/m/Y'),
	                'ApproverID' => ($row->userdetails->emp_name ?? '-' ).'/'.($row->userdetails->emp_id ?? '-'),
	                'VisitBranchID' => $row->visitbranchdetails->BranchName ?? 'N/A',
	                'TripTypeID' => $row->triptypedetails->TripTypeName ?? 'N/A',
	                'AdvanceAmount' => $row->AdvanceAmount ?? 'N/A',
	                'action' => '',	                
	            ];
	        });
	        return response()->json([
	            "draw" => $request->draw,
	            "recordsTotal" => $recordsTotal,
	            "recordsFiltered" => $recordsFiltered,
	            'data' => $formattedData
	        ], 200);
	    }
    }

    public function complete_approved_claim(Request $request)
    {
        $TripClaimID = $request->TripClaimID;
        // Perform the status change operation here
        
        $affected = ClaimManagement::where('TripClaimID', $TripClaimID)
                               ->update(['Status' => 'Settled']);
        if ($affected) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function approved_claims_view($id)
    {
        $data=ClaimManagement::with('visitbranchdetails','userdata','triptypedetails','userdata.branchDetails','userdata.baselocationDetails','tripclaimdetails')
                                ->where('TripClaimID', $id)
                                ->first();
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
        $totalValue = $data->sumTripClaimDetailsValue();
        return view('admin.claim_management.approved_claims_view', compact('data', 'totalValue'));
    }
    
    public function settled_claims()
    {
        return view('admin.claim_management.settled_claims');
    }

    public function settled_claims_view()
    {
        return view('admin.claim_management.settled_claims_view');
    }

    public function rejected_claims()
    {
        return view('admin.claim_management.rejected_claims');
    }
    public function rejected_claims_view()
    {
        return view('admin.claim_management.rejected_claims_view');
    }
    public function ro_approval_pending()
    {
        return view('admin.claim_management.ro_approval_pending');
    }
    public function ro_approval_pending_view()
    {
        return view('admin.claim_management.ro_approval_pending_view');
    }

    
    
}
