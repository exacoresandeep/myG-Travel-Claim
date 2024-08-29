<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClaimManagement;
use Auth;
use DB;
use DatePeriod;
use DateInterval;
use DateTime;
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
	        $query = ClaimManagement::with(['visitbranchdetails', 'userdetails', 'triptypedetails', 'userdata', 'tripclaimdetails'])
                         ->where('Status', 'Approved')
                        ->orderBy($orderColumn, $orderBy);

			$recordsTotal = $query->count();
			$data = $query->skip($skip)->take($pageLength)->get();
			$recordsFiltered = $recordsTotal;

			$formattedData = $data->map(function($row) {
				// Calculate the total amount using the sumTripClaimDetailsValue method
				$TotalAmount = $row->sumTripClaimDetailsValue();
				$TotalAmount = number_format($TotalAmount, 2, '.', '');

				return [
					'TripClaimID' => $row->TripClaimID,
					'created_at' => $row->created_at->format('d/m/Y'),
					'UserData' => ($row->userdata->emp_name ?? '-') . '/' . ($row->userdata->emp_id ?? '-'),
					'VisitBranchID' => ($row->visitbranchdetails->BranchName ?? 'N/A') . '/' . ($row->visitbranchdetails->BranchCode ?? 'N/A'),
					'TripTypeID' => $row->triptypedetails->TripTypeName ?? 'N/A',
					'TotalAmount' => $TotalAmount,
					'action' => '<a href="'. route('approved_claims_view', $row->TripClaimID).'" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> View</a> <a .href="javascript:void(0);" onclick="openCompleteModal(\''.$row->TripClaimID.'\')" class="btn btn-success"><i class="fa fa-check-square" aria-hidden="true"></i> Mark as Complete</a>',
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
    public function requested_claims()
    {
        return view('admin.claim_management.approved_claims');
    }

    public function requested_claims_list(Request $request)
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
	        $query = ClaimManagement::with(['visitbranchdetails', 'userdetails', 'triptypedetails', 'userdata', 'tripclaimdetails'])
                        // ->where('Status', 'Pending')
                        ->orderBy($orderColumn, $orderBy);

			$recordsTotal = $query->count();
			$data = $query->skip($skip)->take($pageLength)->get();
			$recordsFiltered = $recordsTotal;

			$formattedData = $data->map(function($row) {
				// Calculate the total amount using the sumTripClaimDetailsValue method
				$TotalAmount = $row->sumTripClaimDetailsValue();
				$TotalAmount = number_format($TotalAmount, 2, '.', '');

				return [
					'TripClaimID' => $row->TripClaimID,
					'created_at' => $row->created_at->format('d/m/Y'),
					'UserData' => ($row->userdata->emp_name ?? '-') . '/' . ($row->userdata->emp_id ?? '-'),
					'VisitBranchID' => ($row->visitbranchdetails->BranchName ?? 'N/A') . '/' . ($row->visitbranchdetails->BranchCode ?? 'N/A'),
					'TripTypeID' => $row->triptypedetails->TripTypeName ?? 'N/A',
					'TotalAmount' => $TotalAmount,
					'Status' => $row->Status,
					'action' => '<a href="'. route('requested_claims_view', $row->TripClaimID).'" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> View</a>',
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
                               ->update(['Status' => 'Paid']);
        if ($affected) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

	public function approved_claims_view($id)
    {
        $data=ClaimManagement::with(['visitbranchdetails', 'usercodedetails', 'triptypedetails', 'userdata.branchDetails', 'userdata.baselocationDetails', 'tripclaimdetails','tripclaimdetailsforclaim'])
                                ->where('TripClaimID', $id)
                                ->first();

								
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
		
		$totalValue = $data->sumTripClaimDetailsValue();
		$claimDetails = $data->tripclaimdetailsforclaim->toArray();
		$dates = $this->extractDate($claimDetails);

		if (empty($dates)) {
			$interval = 'N/A'; // Or any default value you want to set
		} else {
			// Calculate interval for display purposes
			$fromDate = min($dates);
			$toDate = max($dates);
			$interval = "{$fromDate} to {$toDate}";
		}

		return view('admin.claim_management.approved_claims_view', compact('data', 'totalValue', 'interval', 'dates'));
    }
    public function requested_claims_view($id)
    {
        $data=ClaimManagement::with(['visitbranchdetails', 'usercodedetails', 'triptypedetails', 'userdata.branchDetails', 'userdata.baselocationDetails', 'tripclaimdetails','tripclaimdetailsforclaim'])
                                ->where('TripClaimID', $id)
                                ->first();

								
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
		
		$totalValue = $data->sumTripClaimDetailsValue();
		$claimDetails = $data->tripclaimdetailsforclaim->toArray();
		$dates = $this->extractDate($claimDetails);

		// Check if $dates contains elements
		if (empty($dates)) {
			$interval = 'N/A'; // Or any default value you want to set
		} else {
			// Calculate interval for display purposes
			$fromDate = min($dates);
			$toDate = max($dates);
			$interval = "{$fromDate} to {$toDate}";
		}
		return view('admin.claim_management.requested_claims_view', compact('data', 'totalValue', 'interval', 'dates'));
    }

    private function extractDate($claimDetails)
    {
        $dates = [];
        foreach ($claimDetails as $claimDetail) {
            $this->addDatesBet($claimDetail['FromDate'], $claimDetail['ToDate'], $dates);
            $dates[] = substr($claimDetail['DocumentDate'], 0, 10);
        }

        // Remove blank entries
        $dates = array_filter($dates, function($date) {
            return !empty($date);
        });

        // Remove duplicate dates
        $dates = array_unique($dates);

        // Sort the dates
        sort($dates);

        return $dates;
    }

    private function addDatesBet($fromDate, $toDate, &$dates)
    {
        $period = new DatePeriod(
            new DateTime(substr($fromDate, 0, 10)),
            new DateInterval('P1D'),
            (new DateTime(substr($toDate, 0, 10)))->modify('+1 day')
        );

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
    }

    public function settled_claims()
    {
        return view('admin.claim_management.settled_claims');
    }

	public function settled_claims_list(Request $request)
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
	        $query = ClaimManagement::with(['visitbranchdetails', 'userdetails', 'triptypedetails', 'userdata', 'tripclaimdetails'])
                        ->where('Status', 'Paid')
                        ->orderBy($orderColumn, $orderBy);

			$recordsTotal = $query->count();
			$data = $query->skip($skip)->take($pageLength)->get();
			$recordsFiltered = $recordsTotal;

			$formattedData = $data->map(function($row) {
				// Calculate the total amount using the sumTripClaimDetailsValue method
				$TotalAmount = $row->sumTripClaimDetailsValue();
				$TotalAmount = number_format($TotalAmount, 2, '.', '');

				return [
					'TripClaimID' => $row->TripClaimID,
					'created_at' => $row->created_at->format('d/m/Y'),
					'UserData' => ($row->userdata->emp_name ?? '-') . '/' . ($row->userdata->emp_id ?? '-'),
					'VisitBranchID' => ($row->visitbranchdetails->BranchName ?? 'N/A') . '/' . ($row->visitbranchdetails->BranchCode ?? 'N/A'),
					'TripTypeID' => $row->triptypedetails->TripTypeName ?? 'N/A',
					'TotalAmount' => $TotalAmount,
					'action' => '<a href="'. route('requested_claims_view', $row->TripClaimID).'" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> View</a>',
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

    public function settled_claims_view($id)
    {
		$data=ClaimManagement::with(['visitbranchdetails', 'usercodedetails', 'triptypedetails', 'userdata.branchDetails', 'userdata.baselocationDetails', 'tripclaimdetails','tripclaimdetailsforclaim'])
                                ->where('TripClaimID', $id)
                                ->first();

								
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
		
		$totalValue = $data->sumTripClaimDetailsValue();
		$claimDetails = $data->tripclaimdetailsforclaim->toArray();
		$dates = $this->extractDate($claimDetails);

		if (empty($dates)) {
			$interval = 'N/A'; // Or any default value you want to set
		} else {
			// Calculate interval for display purposes
			$fromDate = min($dates);
			$toDate = max($dates);
			$interval = "{$fromDate} to {$toDate}";
		}
		// admin.claim_management.approved_claims_view
		return view('admin.claim_management.settled_claims_view', compact('data', 'totalValue', 'interval', 'dates'));
    }

    public function rejected_claims()
    {
        return view('admin.claim_management.rejected_claims');
    }

	public function rejected_claims_list(Request $request)
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
	        $query = ClaimManagement::with(['visitbranchdetails', 'userdetails', 'triptypedetails', 'userdata', 'tripclaimdetails'])
                        ->where('Status', 'Rejected')
                        ->orderBy($orderColumn, $orderBy);

			$recordsTotal = $query->count();
			$data = $query->skip($skip)->take($pageLength)->get();
			$recordsFiltered = $recordsTotal;

			$formattedData = $data->map(function($row) {
				// Calculate the total amount using the sumTripClaimDetailsValue method
				$TotalAmount = $row->sumTripClaimDetailsValue();
				$TotalAmount = number_format($TotalAmount, 2, '.', '');

				return [
					'TripClaimID' => $row->TripClaimID,
					'created_at' => $row->created_at->format('d/m/Y'),
					'UserData' => ($row->userdata->emp_name ?? '-') . '/' . ($row->userdata->emp_id ?? '-'),
					'VisitBranchID' => ($row->visitbranchdetails->BranchName ?? 'N/A') . '/' . ($row->visitbranchdetails->BranchCode ?? 'N/A'),
					'TripTypeID' => $row->triptypedetails->TripTypeName ?? 'N/A',
					'TotalAmount' => $TotalAmount,
					'action' => '<a href="'. route('settled_claims_view', $row->TripClaimID).'" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> View</a>',
					
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
    public function rejected_claims_view($id)
    {
        $data=ClaimManagement::with(['visitbranchdetails', 'usercodedetails', 'triptypedetails', 'userdata.branchDetails', 'userdata.baselocationDetails', 'tripclaimdetails','tripclaimdetailsforclaim'])
                                ->where('TripClaimID', $id)
                                ->first();

								
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
		
		$totalValue = $data->sumTripClaimDetailsValue();
		$claimDetails = $data->tripclaimdetailsforclaim->toArray();
		$dates = $this->extractDate($claimDetails);

		if (empty($dates)) {
			$interval = 'N/A'; // Or any default value you want to set
		} else {
			// Calculate interval for display purposes
			$fromDate = min($dates);
			$toDate = max($dates);
			$interval = "{$fromDate} to {$toDate}";
		}

		return view('admin.claim_management.rejected_claims_view', compact('data', 'totalValue', 'interval', 'dates'));
    }
    public function ro_approval_pending()
    {
        return view('admin.claim_management.ro_approval_pending');
    }

	public function ro_approval_pending_list(Request $request)
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
	        $query = ClaimManagement::with(['visitbranchdetails', 'userdetails', 'triptypedetails', 'userdata', 'tripclaimdetails'])
                        ->where('Status', 'Pending')
                        ->orderBy($orderColumn, $orderBy);

			$recordsTotal = $query->count();
			$data = $query->skip($skip)->take($pageLength)->get();
			$recordsFiltered = $recordsTotal;

			$formattedData = $data->map(function($row) {
				// Calculate the total amount using the sumTripClaimDetailsValue method
				$TotalAmount = $row->sumTripClaimDetailsValue();
				$TotalAmount = number_format($TotalAmount, 2, '.', '');

				return [
					'TripClaimID' => $row->TripClaimID,
					'created_at' => $row->created_at->format('d/m/Y'),
					'UserData' => ($row->userdata->emp_name ?? '-') . '/' . ($row->userdata->emp_id ?? '-'),
					'VisitBranchID' => ($row->visitbranchdetails->BranchName ?? 'N/A') . '/' . ($row->visitbranchdetails->BranchCode ?? 'N/A'),
					'TripTypeID' => $row->triptypedetails->TripTypeName ?? 'N/A',
					'TotalAmount' => $TotalAmount,
					'action' => '<a href="'. route('rejected_claims_view', $row->TripClaimID).'" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> View</a>',
					
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

    public function ro_approval_pending_view($id)
    {
        $data=ClaimManagement::with(['visitbranchdetails', 'usercodedetails', 'triptypedetails', 'userdata.branchDetails', 'userdata.baselocationDetails', 'tripclaimdetails','tripclaimdetailsforclaim'])
                                ->where('TripClaimID', $id)
                                ->first();

								
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
		
		$totalValue = $data->sumTripClaimDetailsValue();
		$claimDetails = $data->tripclaimdetailsforclaim->toArray();
		$dates = $this->extractDate($claimDetails);

		if (empty($dates)) {
			$interval = 'N/A'; // Or any default value you want to set
		} else {
			// Calculate interval for display purposes
			$fromDate = min($dates);
			$toDate = max($dates);
			$interval = "{$fromDate} to {$toDate}";
		}

		return view('admin.claim_management.ro_approval_pending_view', compact('data', 'totalValue', 'interval', 'dates'));
    }
	public function report_management()
    {
        return view('admin.claim_management.report_management');
    }

	public function report_management_list(Request $request)
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
	        $query = ClaimManagement::with(['visitbranchdetails', 'userdetails', 'triptypedetails', 'userdata', 'tripclaimdetails'])
                        // ->where('Status', 'Paid')
                        ->orderBy($orderColumn, $orderBy);

			$recordsTotal = $query->count();
			$data = $query->skip($skip)->take($pageLength)->get();
			$recordsFiltered = $recordsTotal;

			$formattedData = $data->map(function($row) {
				// Calculate the total amount using the sumTripClaimDetailsValue method
				$TotalAmount = $row->sumTripClaimDetailsValue();
				$TotalAmount = number_format($TotalAmount, 2, '.', '');

				return [
					'TripClaimID' => $row->TripClaimID,
					'created_at' => $row->created_at->format('d/m/Y'),
					'triptype' =>$row->triptypedetails->TripTypeName ?? 'N/A',
					'UserData' => ($row->userdata->emp_name ?? '-') . '/' . ($row->userdata->emp_id ?? '-'),
					'Branch' => $row->userdata->emp_branch,
					'Grade' => $row->userdata->emp_grade,
					'Department' =>  ($row->userdata->emp_department ?? '-') . '/' . ($row->userdata->emp_department ?? '-'),
					'TotalAmount' => $TotalAmount,
					// 'action' => '',
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

    public function report_management_view($id)
    {
		$data=ClaimManagement::with(['visitbranchdetails', 'usercodedetails', 'triptypedetails', 'userdata.branchDetails', 'userdata.baselocationDetails', 'tripclaimdetails','tripclaimdetailsforclaim'])
                                ->where('TripClaimID', $id)
                                ->first();

								
        if (!$data) {
            return redirect()->back()->withErrors(['error' => 'Claim not found']);
        }
		
		$totalValue = $data->sumTripClaimDetailsValue();
		$claimDetails = $data->tripclaimdetailsforclaim->toArray();
		$dates = $this->extractDate($claimDetails);

		if (empty($dates)) {
			$interval = 'N/A'; // Or any default value you want to set
		} else {
			// Calculate interval for display purposes
			$fromDate = min($dates);
			$toDate = max($dates);
			$interval = "{$fromDate} to {$toDate}";
		}
		// admin.claim_management.approved_claims_view
		return view('admin.claim_management.settled_claims_view', compact('data', 'totalValue', 'interval', 'dates'));
    }

    
    
}
