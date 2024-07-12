<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tripclaim;
use App\Models\Tripclaimdetails;
use App\Models\Policy;
use Validator;
use App\Http\Controllers\Controller; // Import the base Controller class
use DB;
use Hash;
use JWTAuth;
use App\Models\UserManagement;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public $successStatus = 200;
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->middleware('jwt.verify', ['except' => ['login','refresh','logout']]);
    }
/****************************************
   Date        :23/05/2024
   Description :  login
****************************************/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required',
            'password' => 'required',

        ]);
        if ($validator->fails())
        {
            $errors  = json_decode($validator->errors());
            $emp_id=isset($errors->emp_id[0])? $errors->emp_id[0] : '';
            $password=isset($errors->password[0])? $errors->password[0] : '';
             if($emp_id)
            {
              $msg = $emp_id;
            }
            else if($password)
            {
              $msg = $password;
            }
            return response()->json(['message' =>$validator->errors(),'statusCode'=>422,'data'=>[],'success'=>'error'],200);
        }
        $user=User::where('emp_id',$request->emp_id)->first();
        $checkexist   = DB::table('users')->where('emp_id',$request->emp_id)->exists();
        if($checkexist==true)
        {
            if (!Hash::check($request->password, $user->password)) 
            {
                return response()->json(['message' => 'Please check the password','statusCode'=>422,'data'=>[],'success'=>'error'],200);
            }
            $userData     = User::where('emp_id', '=', $request->emp_id)->first();


            $userToken=JWTAuth::fromUser($userData);
            $token   = $this->createNewToken($userToken,$userData);
            $message="verified successfully!";
            return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$userData,'token'=>$token,'success' => 'success'], $this-> successStatus);
        }
        else
        {
            return response()->json(['message' => 'Invalid Username','statusCode'=>422,'data'=>[],'success'=>'error'],200);
            // User::Create([
            //     'EmployeeID'=>$request->emp_id,
            //     // 'password'=>Hash::make($request->password)
            // ]);
            // $userData     = User::where('emp_id', '=', $request->emp_id)->first();
            // $userToken=JWTAuth::fromUser($userData);
            // $token   = $this->createNewToken($userToken,$userData);
            // $message="user verified successfully!";
            // return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$userData,'token'=>$token,'success' => 'success'], $this-> successStatus);    
        }
    }

   
    

/****************************************
   Date        :23/05/2024
   Description :  logout
****************************************/
    public function logout() 
    {
        auth()->logout();
        return response()->json(['message'=>'User successfully signed out', 'statusCode' => 200,'data'=>[],'success' => 'success'], $this-> successStatus);
    }
/****************************************
   Date        :23/05/2024
   Description :  refresh token
****************************************/
    public function refresh() 
    {
        return $this->createNewToken(auth()->refresh());
    }
/****************************************
   Date        :23/05/2024
   Description :  get user details
****************************************/   
    public function userProfile()
    {
        try
        {
            if(auth()->user())
            {
                $user   = DB::table('users')->where('id', auth()->user()->id)->first();
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$user,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }
/****************************************
   Date        :23/05/2024
   Description :  create a new token
****************************************/
    protected function createNewToken($token,$user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
/****************************************
   Date        :29/06/2024
   Description :  list of branches
****************************************/
    public function list_branch()
    {
        try
        {
            if(auth()->user())
            {
                $branch   = DB::table('myg_11_branch')
                                ->where('Status', '1')
                                ->select('BranchID', 'BranchName', 'BranchCode')
                                ->get();
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$branch,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }
/****************************************
   Date        :29/06/2024
   Description :  list of trip types
****************************************/
     public function list_triptype()
    {
        try
        {
            if(auth()->user())
            {
                $triptype   = DB::table('myg_01_triptypes')
                                ->where('Status', '1')
                                ->select('TripTypeID', 'TripTypeName')
                                ->get();
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$triptype,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }
/****************************************
   Date        :29/06/2024
   Description :  list of category 
****************************************/
    public function list_category()
    {
        try
        {
            if(auth()->user())
            {
                $catgeory   = DB::table('myg_03_categories')->where('Status', '1')
                            ->select("CategoryID","CategoryName","TripFrom","TripTo","FromDate","ToDate","DocumentDate","StartMeter","EndMeter")
                            ->get();
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$catgeory,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }
/****************************************
   Date        :29/06/2024
   Description :  list of claims 
   edited by sandeep on 11-07-2024
****************************************/

    public function claimList()
    {
        try
        {
            if(auth()->user())
            {
                //$tripdata   = Tripclaim::with('tripclaimdetails.personsDetails')->where('user_id', auth()->id())->get();
                $tripdata   = Tripclaim::with('tripclaimdetails.personsDetails.userDetails','tripclaimdetails.policyDetails.subCategoryDetails.categoryDetails')->where('user_id', auth()->id())->get()
                ->map(function ($trip) {
                    return [
                        'tripclaim' => [
                            "TripClaimID"=>$trip->TripClaimID,
                            "TripTypeId"=>$trip->TripTypeID,
                            "ApproverId"=>$trip->ApproverID,
                            "TripPurpose"=>$trip->TripPurpose,
                            "VisitBranchId"=>$trip->VisitBranchID,
                            'ClaimDetails' => $trip->tripclaimdetails->map(function ($detail) {
                                return [
                                    "TripClaimDetailID" => $detail->TripClaimDetailID,
                                    "PolicyID"=>$detail->PolicyID,
                                    "PolicyDetails"=>$detail->policyDetails->map(function ($policyDetails) {
                                        return [
                                            "SubCategoryID" => $policyDetails->SubCategoryID,
                                            "GradeID" => $policyDetails->GradeID,
                                            "GradeType" => $policyDetails->GradeType,
                                            "GradeClass" => $policyDetails->GradeClass,
                                            "GradeAmount" => $policyDetails->GradeAmount,
                                            "Approver" => $policyDetails->Approver,
                                            "SubCategoryDetails"=>$policyDetails->subCategoryDetails->map(function ($subCategoryDetails) {
                                                return [
                                                    "SubCategoryID" => $subCategoryDetails->SubCategoryID,
                                                    "CategoryID" => $subCategoryDetails->CategoryID,
                                                    "SubCategoryName" => $subCategoryDetails->SubCategoryName,
                                                    "CategoryDetails"=>$subCategoryDetails->categoryDetails->map(function ($categoryDetails) {
                                                        return [
                                                            "CategoryID" => $categoryDetails->CategoryID,
                                                            "CategoryName" => $categoryDetails->CategoryName,
                                                            "TripTo" => $categoryDetails->TripTo,
                                                            "FromDate" => $categoryDetails->FromDate,
                                                            "ToDate" => $categoryDetails->ToDate,
                                                            "DocumentDate" => $categoryDetails->DocumentDate,
                                                            "StartMeter" => $categoryDetails->StartMeter,
                                                            "EndMeter" => $categoryDetails->EndMeter
                                                        ];
                                                    }),
                                                   
                                                ];
                                            }),
                                        ];
                                    }),
                                    "FromDate"=> $detail->FromDate,
                                    "ToDate"=> $detail->ToDate,
                                    "TripFrom"=> $detail->TripFrom,
                                    "TripTo"=> $detail->TripTo,
                                    "DocumentDate"=>$detail->DocumentDate,
                                    "StartMeter"=> $detail->StartMeter,
                                    "EndMeter"=> $detail->EndMeter,
                                    "Qty"=> $detail->Qty,
                                    "UnitAmount"=> $detail->UnitAmount,
                                    "NoOfPersons"=>$detail->NoOfPersons,
                                    "FileUrl"=>$detail->FileUrl,
                                    "Remarks"=>$detail->Remarks,
                                    "NotificationFlg"=>$detail->NotificationFlg,
                                    "RejectionCount"=>$detail->RejectionCount,
                                    "ApproverID"=>$detail->ApproverID,
                                    'personsDetails' => $detail->personsDetails->map(function ($person) {
                                        return $person->userDetails->map(function ($user) {
                                            return [
                                                'id' => $user->id,
                                                'emp_id' => $user->emp_id,
                                                'emp_name' => $user->emp_name,
                                            ];
                                        });
                                    })->flatten(1)
                                ];
                            })
                        ]
                    ];
                });
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$tripdata,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }

    /****************************************
    Date        :06/07/2024
    Description :  Employee Names
    ****************************************/
    public function employeeNames(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);  // Change the status code here to 422
        }
        try {
            // Fetch employee details
            $employeeDetails = DB::table('users')
                ->where('emp_id', $request->emp_id)
                ->get();
            $message = "Result fetched successfully!";
            return response()->json([
                'message' => $message,
                'statusCode' => 200,
                'data' => $employeeDetails,
                'success' => 'success'
            ], 200);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching employee details:', ['exception' => $e]);
            return response()->json([
                'success' => 'error',
                'statusCode' => 500,
                'data' => [],
                'message' => $e->getMessage(),
            ]);
        }
    }
    
    public function approvalStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TripClaimDetailID' => 'required',
            'Status' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);  // Change the status code here to 422
        }
        try {
            // Fetch employee details
            DB::table('myg_09_trip_claim_details')
                ->where('TripClaimDetailID', $request->TripClaimDetailID)
                ->update(['Status' => 'Completed']);
            $message = "Claim Updated successfully!";
            return response()->json([
                'message' => $message,
                'statusCode' => 200,
                'data' => [],
                'success' => 'success'
            ], 200);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error in Claim Updation:', ['exception' => $e]);
            return response()->json([
                'success' => 'error',
                'statusCode' => 500,
                'data' => [],
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function policies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'GradeID' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);  // Change the status code here to 422
        }
       
        try
        {
            if(auth()->user())
            {
                $policies   = Policy::with([
                    'subCategoryDetails' => function($query) {
                        $query->select('SubCategoryID', 'CategoryID', 'SubCategoryName');
                    },
                    'subCategoryDetails.categoryDetails' => function($query) {
                        $query->select('CategoryID', 'CategoryName');
                    },
                    'gradeDetails' => function($query) {
                        $query->select('GradeID', 'GradeName');
                    }
                ])
                ->where('user_id', auth()->id())
                ->where('GradeID','=',$request->GradeID)
                ->select("PolicyID","SubCategoryID","GradeID","GradeType","GradeClass","GradeAmount","Approver","Status")
                ->get();
                if ($policies->isEmpty()) {
                    Log::info('No policies found', ['user_id' => auth()->id(), 'GradeID' => $gradeId]);
                    return response()->json([
                        'message' => 'No policies found.',
                        'statusCode' => 404,
                        'data' => [],
                        'success' => 'error',
                    ], 404);
                }
                
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$policies,'success' => 'success'], $this->successStatus);
            }
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching employee details:', ['exception' => $e]);
            return response()->json([
                'success' => 'error',
                'statusCode' => 500,
                'data' => [],
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function generateId (){
        $micro = gettimeofday()['usec'];
        $todate =  date("YmdHis");
        $alpha = substr(md5(rand()), 0, 2);
        return($todate.$micro.$alpha);
    }

    public function tripClaim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TripTypeId' => 'required',
            'ApproverId' => 'required',
            'VisitBranchId' => 'required',
            "ClaimDetails"=>'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);
        }
    
        try {
            if (auth()->user()) {
                $claim_id = $this->generateId();
                $data = $request->all(); // Get all request data as an array
                
                $tripClaim = Tripclaim::create([
                    'TripClaimID' => $claim_id,
                    'TripTypeID' => $request->TripTypeId,
                    'ApproverID' => $request->ApproverId,
                    'TripPurpose' => $request->TripPurpose ?? null,
                    'VisitBranchID' => $request->VisitBranchId,
                    'AdvanceAmount' => $request->AdvanceAmount ?: null,
                    'RejectionCount' => 0,
                    'ApprovalDate' => $request->ApprovalDate ?: null,
                    'NotificationFlg' => "0",
                    'Status' => "Pending",
                    'user_id' => auth()->id(),
                ]);
    
                foreach ($data["ClaimDetails"] as $details) {
                    $Tripclaimdetails = Tripclaimdetails::create([
                        'TripClaimDetailID' => $this->generateId(),
                        'TripClaimID' => $claim_id,
                        'PolicyID' => $details['PolicyID'],
                        'FromDate' => $details['FromDate'] ?? null,
                        'ToDate' => $details['ToDate'] ?? null,
                        'TripFrom' => $details['TripFrom'] ?? null,
                        'TripTo' => $details['TripTo'] ?? null,
                        'DocumentDate' => $details['DocumentDate'] ?? null,
                        'StartMeter' => $details['StartMeter'] ?? null,
                        'EndMeter' => $details['EndMeter'] ?? null,
                        'Qty' => $details['Qty'] ?? null,
                        'UnitAmount' => $details['UnitAmount'] ?? null,
                        'NoOfPersons' => $details['NoOfPersons'],
                        'FileUrl' => $details['FileUrl'] ?? null,
                        'Remarks' => $details['Remarks'] ?? null,
                        'NotificationFlg' => "1",
                        'RejectionCount' => 1,
                        'ApproverID' => $request->ApproverID,
                        'Status' => "Pending",
                        'user_id' => auth()->id(),
                    ]);
                }
    
                $message = "Claim submitted successfully!";
                return response()->json(['message' => $message, 'statusCode' => 200, 'success' => 'success'], 200);
            }
        } catch (\Exception $e) {
            // Log the exception with more details
            //Log::error('Claim submission failed:', ['exception' => $e, 'request_data' => $request->all()]);
            return response()->json([
                'success' => 'error',
                'statusCode' => 500,
                'data' => [],
                'message' => 'An error occurred while submitting the claim. Please try again later.',
            ], 500);
        }
    }
    public function claimResubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TripTypeId' => 'required',
            'ApproverId' => 'required',
            'VisitBranchId' => 'required',
            "ClaimDetails"=>'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);
        }
    
        try {
            if (auth()->user()) {
                $claim_id = $this->generateId();
                $data = $request->all(); // Get all request data as an array
                
                $tripClaim = Tripclaim::where('TripClaimID', $request->TripClaimID)->update([
                    'TripTypeID' => $request->TripTypeId,
                    'ApproverID' => $request->ApproverId,
                    'TripPurpose' => $request->TripPurpose ?? null,
                    'VisitBranchID' => $request->VisitBranchId,
                    'AdvanceAmount' => $request->AdvanceAmount ?: null,
                    'RejectionCount' => 1,
                    // 'ApprovalDate' => $request->ApprovalDate ?: null,
                    'NotificationFlg' => "0",
                    'Status' => "Pending",
                    'user_id' => auth()->id(),
                ]);
    
                foreach ($data["ClaimDetails"] as $details) {
                    $Tripclaimdetails = Tripclaimdetails::where('TripClaimDetailID', $request->TripClaimDetailID)->update([
                        'TripClaimID' => $details['TripClaimID'],
                        'PolicyID' => $details['PolicyID'],
                        'FromDate' => $details['FromDate'] ?? null,
                        'ToDate' => $details['ToDate'] ?? null,
                        'TripFrom' => $details['TripFrom'] ?? null,
                        'TripTo' => $details['TripTo'] ?? null,
                        'DocumentDate' => $details['DocumentDate'] ?? null,
                        'StartMeter' => $details['StartMeter'] ?? null,
                        'EndMeter' => $details['EndMeter'] ?? null,
                        'Qty' => $details['Qty'] ?? null,
                        'UnitAmount' => $details['UnitAmount'] ?? null,
                        'NoOfPersons' => $details['NoOfPersons'],
                        'FileUrl' => $details['FileUrl'] ?? null,
                        'Remarks' => $details['Remarks'] ?? null,
                        'NotificationFlg' => "0",
                        'RejectionCount' => 1,
                        'ApproverID' => $request->ApproverID,
                        'Status' => "Pending",
                        'user_id' => auth()->id(),
                    ]);
                }
    
                $message = "Claim Resubmitted successfully!";
                return response()->json(['message' => $message, 'statusCode' => 200, 'success' => 'success'], 200);
            }
        } catch (\Exception $e) {
            // Log the exception with more details
            //Log::error('Claim submission failed:', ['exception' => $e, 'request_data' => $request->all()]);
            return response()->json([
                'success' => 'error',
                'statusCode' => 500,
                'data' => [],
                'message' => 'An error occurred while Resubmitting the claim. Please try again later.',
            ], 500);
        }
    }

    public function claimsForApproval()
    {
        try
        {
            if(auth()->user())
            {
                $tripdata   = Tripclaim::with('tripclaimdetails.personsDetails.userDetails','tripclaimdetails.policyDetails.subCategoryDetails.categoryDetails')
                ->where('user_id', auth()->id())
                ->where('ApproverID', $request->ApproverID)
                ->get()
                ->map(function ($trip) {
                    return [
                        'tripclaim' => [
                            "TripTypeId"=>$trip->TripTypeID,
                            "ApproverId"=>$trip->ApproverID,
                            "TripPurpose"=>$trip->TripPurpose,
                            "VisitBranchId"=>$trip->VisitBranchID,
                            'ClaimDetails' => $trip->tripclaimdetails->map(function ($detail) {
                                return [
                                    "TripClaimDetailID" => $detail->TripClaimDetailID,
                                    "PolicyID"=>$detail->PolicyID,
                                    "PolicyDetails"=>$detail->policyDetails->map(function ($policyDetails) {
                                        return [
                                            "SubCategoryID" => $policyDetails->SubCategoryID,
                                            "GradeID" => $policyDetails->GradeID,
                                            "GradeType" => $policyDetails->GradeType,
                                            "GradeClass" => $policyDetails->GradeClass,
                                            "GradeAmount" => $policyDetails->GradeAmount,
                                            "Approver" => $policyDetails->Approver,
                                            "SubCategoryDetails"=>$policyDetails->subCategoryDetails->map(function ($subCategoryDetails) {
                                                return [
                                                    "SubCategoryID" => $subCategoryDetails->SubCategoryID,
                                                    "CategoryID" => $subCategoryDetails->CategoryID,
                                                    "SubCategoryName" => $subCategoryDetails->SubCategoryName,
                                                    "CategoryDetails"=>$subCategoryDetails->categoryDetails->map(function ($categoryDetails) {
                                                        return [
                                                            "CategoryID" => $categoryDetails->CategoryID,
                                                            "CategoryName" => $categoryDetails->CategoryName,
                                                            "TripTo" => $categoryDetails->TripTo,
                                                            "FromDate" => $categoryDetails->FromDate,
                                                            "ToDate" => $categoryDetails->ToDate,
                                                            "DocumentDate" => $categoryDetails->DocumentDate,
                                                            "StartMeter" => $categoryDetails->StartMeter,
                                                            "EndMeter" => $categoryDetails->EndMeter
                                                        ];
                                                    }),
                                                   
                                                ];
                                            }),
                                        ];
                                    }),
                                    "FromDate"=> $detail->FromDate,
                                    "ToDate"=> $detail->ToDate,
                                    "TripFrom"=> $detail->TripFrom,
                                    "TripTo"=> $detail->TripTo,
                                    "DocumentDate"=>$detail->DocumentDate,
                                    "StartMeter"=> $detail->StartMeter,
                                    "EndMeter"=> $detail->EndMeter,
                                    "Qty"=> $detail->Qty,
                                    "UnitAmount"=> $detail->UnitAmount,
                                    "NoOfPersons"=>$detail->NoOfPersons,
                                    "FileUrl"=>$detail->FileUrl,
                                    "Remarks"=>$detail->Remarks,
                                    "NotificationFlg"=>$detail->NotificationFlg,
                                    "RejectionCount"=>$detail->RejectionCount,
                                    "ApproverID"=>$detail->ApproverID,
                                    'personsDetails' => $detail->personsDetails->map(function ($person) {
                                        return $person->userDetails->map(function ($user) {
                                            return [
                                                'id' => $user->id,
                                                'emp_id' => $user->emp_id,
                                                'emp_name' => $user->emp_name,
                                            ];
                                        });
                                    })->flatten(1)
                                ];
                            })
                        ]
                    ];
                });
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$tripdata,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }

    public function notificationList()
    {
        try
        {
            if(auth()->user())
            {
                $tripdata   = Tripclaim::with('tripclaimdetails.personsDetails.userDetails','tripclaimdetails.policyDetails.subCategoryDetails.categoryDetails')
                ->where('user_id', auth()->id())
                ->whereHas('tripclaimdetails', function ($query) {
                    $query->where('NotificationFlg', 0)->where('Status', 'Pending');
                })
                ->get()

                // $tripdata   = Tripclaim::with('tripclaimdetails.personsDetails.userDetails','tripclaimdetails.policyDetails.subCategoryDetails.categoryDetails')
                // ->where('user_id', auth()->id())
                // ->whereHas('tripclaimdetails', function ($query) {
                //     $query->where('NotificationFlg', 0)->where('Status', 'Pending');
                // })
                // ->get();
                
                ->map(function ($trip) {
                    return [
                        'tripclaim' => [
                            "TripClaimID"=>$trip->TripClaimID,
                            "TripTypeId"=>$trip->TripTypeID,
                            "ApproverId"=>$trip->ApproverID,
                            "TripPurpose"=>$trip->TripPurpose,
                            "VisitBranchId"=>$trip->VisitBranchID,
                            'ClaimDetails' => $trip->tripclaimdetails->map(function ($detail) {
                                return [
                                    "TripClaimDetailID" => $detail->TripClaimDetailID,
                                    "PolicyID"=>$detail->PolicyID,
                                    "PolicyDetails"=>$detail->policyDetails->map(function ($policyDetails) {
                                        return [
                                            "SubCategoryID" => $policyDetails->SubCategoryID,
                                            "GradeID" => $policyDetails->GradeID,
                                            "GradeType" => $policyDetails->GradeType,
                                            "GradeClass" => $policyDetails->GradeClass,
                                            "GradeAmount" => $policyDetails->GradeAmount,
                                            "Approver" => $policyDetails->Approver,
                                            "SubCategoryDetails"=>$policyDetails->subCategoryDetails->map(function ($subCategoryDetails) {
                                                return [
                                                    "SubCategoryID" => $subCategoryDetails->SubCategoryID,
                                                    "CategoryID" => $subCategoryDetails->CategoryID,
                                                    "SubCategoryName" => $subCategoryDetails->SubCategoryName,
                                                    "CategoryDetails"=>$subCategoryDetails->categoryDetails->map(function ($categoryDetails) {
                                                        return [
                                                            "CategoryID" => $categoryDetails->CategoryID,
                                                            "CategoryName" => $categoryDetails->CategoryName,
                                                            "TripTo" => $categoryDetails->TripTo,
                                                            "FromDate" => $categoryDetails->FromDate,
                                                            "ToDate" => $categoryDetails->ToDate,
                                                            "DocumentDate" => $categoryDetails->DocumentDate,
                                                            "StartMeter" => $categoryDetails->StartMeter,
                                                            "EndMeter" => $categoryDetails->EndMeter
                                                        ];
                                                    }),
                                                   
                                                ];
                                            }),
                                        ];
                                    }),
                                    "FromDate"=> $detail->FromDate,
                                    "ToDate"=> $detail->ToDate,
                                    "TripFrom"=> $detail->TripFrom,
                                    "TripTo"=> $detail->TripTo,
                                    "DocumentDate"=>$detail->DocumentDate,
                                    "StartMeter"=> $detail->StartMeter,
                                    "EndMeter"=> $detail->EndMeter,
                                    "Qty"=> $detail->Qty,
                                    "UnitAmount"=> $detail->UnitAmount,
                                    "NoOfPersons"=>$detail->NoOfPersons,
                                    "FileUrl"=>$detail->FileUrl,
                                    "Remarks"=>$detail->Remarks,
                                    "NotificationFlg"=>$detail->NotificationFlg,
                                    "RejectionCount"=>$detail->RejectionCount,
                                    "ApproverID"=>$detail->ApproverID,
                                    'personsDetails' => $detail->personsDetails->map(function ($person) {
                                        return $person->userDetails->map(function ($user) {
                                            return [
                                                'id' => $user->id,
                                                'emp_id' => $user->emp_id,
                                                'emp_name' => $user->emp_name,
                                            ];
                                        });
                                    })->flatten(1)
                                ];
                            })
                        ]
                    ];
                });
                $message="Result fetched successfully!";
                return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$tripdata,'success' => 'success'], $this->successStatus);
            }
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success'    => 'error',
                'statusCode' => 500,
                'data'       => [],
                'message'    => $e->getMessage(),
            ]);
        }
    }
}