<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tripclaim;
use App\Models\Tripclaimdetails;
use App\Models\Policy;
use App\Models\Category;
use App\Models\Branch;
use App\Models\Attendance;
use App\Models\Personsdetails;
use Validator;
use App\Http\Controllers\Controller; // Import the base Controller class
use DB;
use Hash;
use JWTAuth;
use App\Models\UserManagement;
use App\Models\Sequirityvlunerability;
use Illuminate\Support\Facades\Http;
use DatePeriod;
use DateInterval;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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
        $this->middleware('jwt.verify', ['except' => ['login','logout','hrmstokengeneration','refresh_token']]);
    }

    public function hrmstokengeneration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->username != "MYG_HRMS" || $request->password != "4fn+Q3OZdv45kE)Bqf") {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find the user by username (assuming you have a User model)
        $user = User::where('emp_name', $request->username)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $token = JWTAuth::fromUser($user);

        return $this->createNewTokenForHRMS($token);
    }

    protected function createNewTokenForHRMS($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function storeAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.date' => 'required|date_format:d-m-Y',
            '*.emp_code' => 'required|string',
            '*.punch_in' => 'required|date_format:h:i A',
            '*.location_in' => 'required|string',
            '*.punch_out' => 'required|date_format:h:i A',
            '*.location_out' => 'required|string',
            '*.duration' => 'required|date_format:H:i',
            '*.remarks' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' =>$validator->errors(),'statusCode'=>422,'data'=>[],'success'=>'error'],200);
        }

        foreach ($request->all() as $attendanceData) {
            Attendance::create([
                'id' => $this->generateId(),
                'date' => Carbon::createFromFormat('d-m-Y', $attendanceData['date'])->format('Y-m-d'),
                'emp_code' => $attendanceData['emp_code'],
                'punch_in' => Carbon::createFromFormat('h:i A', $attendanceData['punch_in'])->format('H:i:s'),
                'location_in' => $attendanceData['location_in'],
                'punch_out' => Carbon::createFromFormat('h:i A', $attendanceData['punch_out'])->format('H:i:s'),
                'location_out' => $attendanceData['location_out'],
                'duration' => Carbon::createFromFormat('H:i', $attendanceData['duration'])->format('H:i:s'),
                'remarks' =>  $attendanceData['remarks']
            ]);
        }

        return response()->json(['message' => 'Attendances saved successfully', 'success' => 'success'], 201);
    }

    public function userUpdate(Request $request){
        $data = $request->json()->all();
        $empDetailsList = $data['lst_emp'];
    
        $results = []; // To store the success or failure messages
    
        foreach ($empDetailsList as $empDetails) {
            $emp_code = $empDetails['Emp_code'];
            $emp_name = $empDetails['Emp_name'];
            $emp_department = $empDetails['Department'];
            $emp_branch = $this->getbranchid($empDetails['Branch']);
            $emp_baselocation = $this->getbranchid($empDetails['Base_location']);
            $emp_designation = $empDetails['Designation'];
            $emp_grade = $empDetails['Grade'];
            $email = $empDetails['Email'];
            $emp_phonenumber = $empDetails['mobile'];
            $reporting_person = $empDetails['Reporting_person_name'];
            $reporting_person_empid = $empDetails['Reporting_person_code'];
            $login_status = $empDetails['Login_status'];
    
            // Find the user by Emp_code
            $user = User::where('emp_id', $emp_code)->first();
    
            if ($user) {
                // Update the user details
                try {
                    $user->update([
                        'emp_name' => $emp_name,
                        'email' => $email,
                        'emp_phonenumber' => $emp_phonenumber,
                        'emp_department' => $emp_department,
                        'emp_branch' => $emp_branch,
                        'emp_baselocation' => $emp_baselocation,
                        'emp_designation' => $emp_designation,
                        'emp_grade' => $emp_grade,
                        'reporting_person' => $reporting_person,
                        'reporting_person_empid' => $reporting_person_empid,
                        'Status' => $login_status,
                    ]);
                    $results[] = ['Emp_code' => $emp_code, 'status' => 'success'];
                } catch (\Exception $e) {
                    $results[] = ['Emp_code' => $emp_code, 'status' => 'fail', 'error' => $e->getMessage()];
                }
            } else {
                $results[] = ['Emp_code' => $emp_code, 'status' => 'fail', 'error' => 'User not found'];
            }
        }
    
        return response()->json([
            'message' => 'Update operation completed',
            'results' => $results
        ], 200);
    }


    public function hrms_login_token(){
        $firstResponse = Http::post('http://103.119.254.250:6062/integration/exacore_login_api/', [
            'Username' => 'MYGE-EXACORE',
            'Password' => 'ibGE44QJhDN~<*x86#4U',
        ]);
        if ($firstResponse->failed()) {
            return response()->json(['message' => 'Failed to authenticate with exacore_login_api', 'statusCode' => $firstResponse->status(), 'data' => [], 'success' => 'error'], 200);
        }
        return $firstResponse->json('token');
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
            $register   = User::where('emp_id',$request->emp_id)->first();
            $sequirity_userid=Sequirityvlunerability::where('user_id',$register->id)->update([
                'user_id'=>$register->id,
                'random_string'=>substr(uniqid(), 0,25)
            ]);

            $sequirity_refresh_token=Sequirityvlunerability::select('random_string')->where('user_id',$register->id)->get();
            $userData = User::with([
            'branchDetails' => function ($query) {
            $query->select('BranchID', 'BranchName'); // Replace with the actual columns you need
            },
            'baselocationDetails' => function ($query) {
            $query->select('BranchID as branch_id', 'BranchName as branch_name', 'BranchCode as branch_code', 'BranchID'); // Include the foreign key to the user table
            },
            'gradeDetails' => function ($query) {
            $query->select('GradeID as grade_id', 'GradeName as grade_name', 'GradeID'); // Include the foreign key to the user table
            }
            ])->where('emp_id', '=', $request->emp_id)->first();

            // dd($userData);
            $userToken=JWTAuth::fromUser($userData);
            $token   = $this->createNewToken($userToken,$userData);
            $message="verified successfully!";
            return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$userData,'token'=>$token,'success' => 'success','refresh_token'=>$sequirity_refresh_token], $this-> successStatus);
        }
        else
        {
            // First API call to get the token
            
            $exacoreToken =$this->hrms_login_token();
            
            // Second API call using the token from the first call
            $secondResponse = Http::withHeaders([
                'Content-type' => 'application/json',
                'Authorization' => 'JWT ' . $exacoreToken,
            ])->post('http://103.119.254.250:6062/integration/login_api/', [
                'Username' => $request->emp_id,
                'Password' => $request->password,
                'Key' => 'dv)B45k+Q34fnOZEqf',
            ]);
            
            $data=[];
            if ($secondResponse->failed()) {
                return response()->json([
                    'message' => 'Failed to authenticate with login_api',
                    'statusCode' => $secondResponse->status(),
                    'data' => [],
                    'errorDetails' => $secondResponse->body(),
                    'success' => 'error'
                ], 200);
            }else{
                $data = $secondResponse->json();
                // Extract the values you need
                $empDetails = $data['lst_emp'];
                $emp_id = $empDetails['Emp_code'];
                $emp_name = $empDetails['Emp_name'];
                $emp_department = $empDetails['Department'];
                $emp_branch = $empDetails['Branch'];
                $emp_baselocation = $empDetails['Base_location'];
                $emp_designation = $empDetails['Designation'];
                $emp_grade = $empDetails['Grade'];
                $email = $empDetails['Email'];
                $emp_phonenumber = $empDetails['mobile'];
                $reporting_person = $empDetails['Reporting_person_name'];
                $reporting_person_empid = $empDetails['Reporting_person_code'];
                $login_status = $empDetails['Login_status'];
            }

          
            $now = new \DateTime();  // Create a new DateTime object
            $currentDateTime = $now->format('Y-m-d H:i:s'); 
            $register=User::Create([
                'emp_id'=>$emp_id,
                'password'=>Hash::make($request->password),
                'emp_name'=>$emp_name,
                'emp_department'=>$emp_department,
                'emp_branch'=>$this->getbranchid($emp_branch),
                'emp_baselocation'=>$this->getbranchid($emp_baselocation),
                'emp_designation'=>$emp_designation,
                'emp_grade'=>1,
                'emp_role'=>$emp_grade,
                'email'=>$email,
                'emp_phonenumber'=>$emp_phonenumber,
                'reporting_person'=>$reporting_person,
                'reporting_person_empid'=>$reporting_person_empid,
                'created_at'=>$currentDateTime,
                'updated_at'=>$currentDateTime
            ]);

            $sequirity_id=Sequirityvlunerability::create([
                'user_id'=>$register->id,
                'random_string'=>substr(uniqid(), 0,25)
            ]);
            $sequirity_refresh_token=Sequirityvlunerability::select('random_string')->where('id', $sequirity_id->id)->first();

            $userData = User::with([
            'branchDetails' => function ($query) {
            $query->select('BranchID', 'BranchName'); // Replace with the actual columns you need
            },
            'baselocationDetails' => function ($query) {
            $query->select('BranchID as branch_id', 'BranchName as branch_name', 'BranchCode as branch_code', 'BranchID'); // Include the foreign key to the user table
            },
            'gradeDetails' => function ($query) {
            $query->select('GradeID as grade_id', 'GradeName as grade_name', 'GradeID'); // Include the foreign key to the user table
            }
            ])->where('emp_id', '=', $request->emp_id)->first();

            $userToken=JWTAuth::fromUser($userData);
            $token   = $this->createNewToken($userToken,$userData);
            $message="user verified successfully!";
            return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$userData,'token'=>$token,'success' => 'success','refresh_token'=>$sequirity_refresh_token], $this-> successStatus);    
        }
    }
    public function getbranchid($string){
        $brachdata     = Branch::where('BranchName', '=', $string)->select('BranchID')->first();
        return $brachdata->BranchID;
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
    public function refresh_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'random_string'  => 'required'
        ]);
        if ($validator->fails())
        {
            $errors  = json_decode($validator->errors());
            $random_string=isset($errors->random_string[0])? $errors->random_string[0] : '';
             if($random_string)
            {
              $msg = $random_string;
            }
            return response()->json(['message' =>$validator->errors(),'statusCode'=>422,'data'=>[],'success'=>'error'],200);
        }
        $checkexist = DB::table('sequirityvlunerability')->where('random_string', $request->random_string)->exists();
        // if($checkexist==true)
        // {
        //     $token     = $request->bearerToken();
        //     $vlunerability_id=DB::table('sequirityvlunerability')->where('random_string', $request->random_string)->first();
        //     $new_token = auth()->tokenById($vlunerability_id->user_id);
        //     // dd($new_token);
        //     $data      = $this->createNewToken($new_token);
        //     // $user=DB::table('users')->where('id', $request->user_id)->first();

        //     $user = User::where('emp_id', '=', $request->emp_id)->first();

        //     // dd($userData);
        //     $newToken = JWTAuth::fromUser($user);
        //     return response()->json(['statusCode' => $this-> successStatus,'data'=>$user,'token'=> $newToken,'success' => 'success'], $this-> successStatus);
        // }

        if ($checkexist == true) 
        {
            $token = $request->bearerToken();
            $vulnerability = DB::table('sequirityvlunerability')->where('random_string', $request->random_string)->first();

            if ($vulnerability) 
            {
                // Assuming you want to fetch the user associated with the vulnerability
                $user = User::where('id', $vulnerability->user_id)->first();

                if ($user) 
                {
                    // Generate a new token
                    $newToken = JWTAuth::fromUser($user);

                    return response()->json([
                    'statusCode' => $this->successStatus,
                    'data' => $user,
                    'token' => $newToken,
                    'success' => 'success'
                    ], $this->successStatus);
                } 
                else 
                {
                    return response()->json([
                    'statusCode' => 404,
                    'message' => 'User not found'
                    ], 404);
                }
            } else 
            {
                return response()->json([
                'statusCode' => 404,
                'message' => 'Vulnerability not found'
                ], 404);
            }
        }



        else
        {
            $error="User does not exist.";
            return response()->json(['message'=>$error,'statusCode'=>401,'data'=>[],'success' => 'error'],$this-> successStatus);
        }
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
                                ->select('BranchID as branch_id', 'BranchName as branch_name', 'BranchCode as branch_code')
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
                                ->select('TripTypeID as triptype_id', 'TripTypeName as triptype_name')
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
                            ->select("CategoryID as category_id","CategoryName as category_name","TripFrom as trip_from","TripTo as trip_to","FromDate as from_date","ToDate as to_date","DocumentDate as document_date","StartMeter as start_meter","EndMeter as end_meter","ImageUrl as image_url")
                            ->get();
                $message="Result fetched successfully!";
                $catgeory->map(function($category) {
                    $category->trip_from = (bool)$category->trip_from;
                    $category->trip_to = (bool)$category->trip_to;
                    $category->from_date = (bool)$category->from_date;
                    $category->to_date = (bool)$category->to_date;
                    $category->document_date = (bool)$category->document_date;
                    $category->start_meter = (bool)$category->start_meter;
                    $category->end_meter = (bool)$category->end_meter;
                    $imagePath = 'images/category/' . $category->image_url;
                    $category->image_url = url($imagePath);
                    return $category;
                });
                // $catgeory->map(function($item) {
                //     $item->Expand = false;
                //     $item->Oncheck = false;
                //     return $item;
                // });
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

    public function categorieswithpolicy(Request $request)
    {
        try
        {
            if(auth()->user())
            {
                $user   = DB::table('users')->where('id', auth()->user()->id)->first();
                $gradeID = $user->emp_grade;
                $category = Category::with(['subcategorydetails' => function($query) use ($gradeID) {
                    $query->whereHas('policies', function($q) use ($gradeID) {
                        $q->where('GradeID', $gradeID);
                    })->with(['policies' => function($q) use ($gradeID) {
                        $q->where('GradeID', $gradeID);
                    }, 'uomdetails']);
                }])->where('Status', '1')
                  ->select("CategoryID","CategoryID as category_id","CategoryName as category_name","TripFrom as trip_from_flag","TripTo as trip_to_flag","FromDate as from_date_flag","ToDate as to_date_flag","DocumentDate as document_date_flag","StartMeter as start_meter_flag","EndMeter as end_meter_flag","ImageUrl as image_url")
                  ->get()
                  ->filter(function ($category) {
                    return $category->subcategorydetails->isNotEmpty();
                });
                $message = "Result fetched successfully!";

                // Transform the data
                $category = $category->map(function($category) {
                    $category->trip_from_flag = (bool)$category->trip_from_flag;
                    $category->trip_to_flag = (bool)$category->trip_to_flag;
                    $category->from_date_flag = (bool)$category->from_date_flag;
                    $category->to_date_flag = (bool)$category->to_date_flag;
                    $category->document_date_flag = (bool)$category->document_date_flag;
                    $category->start_meter_flag = (bool)$category->start_meter_flag;
                    $category->end_meter_flag = (bool)$category->end_meter_flag;
                    $imagePath = 'images/category/' . $category->image_url;
                    $category->image_url = url($imagePath);
                    
                    // Safely handle subcategorydetails
                    $category->subcategorydetails = $category->subcategorydetails->map(function($subcategory) {
                        return [
                            // 'SubCategory' => [
                                "subcategory_id" => $subcategory->SubCategoryID,
                                "subcategory_name" => $subcategory->SubCategoryName,
                                "status" => $subcategory->Status,
                                "policies" => $subcategory->policies->map(function($policy) {
                                    return [
                                        "policy_id" => $policy->PolicyID,
                                        // "subcategory_id" => $policy->SubCategoryID,
                                        "grade_id" => $policy->GradeID,
                                        "grade_type" => $policy->GradeType,
                                        "grade_class" => $policy->GradeClass,
                                        "grade_amount" => $policy->GradeAmount,
                                        "approver" => $policy->Approver,
                                        "status" => $policy->Status,
                                    ];
                                })
                        ];
                    });                   

                    return $category->only(["category_id","category_name","trip_from_flag","trip_to_flag","from_date_flag","to_date_flag","document_date_flag","start_meter_flag","end_meter_flag","image_url", 'subcategorydetails']);

                });

                return response()->json([
                    'message' => $message,
                    'statusCode' => $this->successStatus,
                    'data' => $category,
                    'success' => 'success'
                ], $this->successStatus);
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
        // dd($request);
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
                ->where('emp_id', 'like', '%' . $request->emp_id . '%')
                ->select('id','emp_id','emp_name','emp_grade')
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
    public function fileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Example validation rules
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads'); // Store the file in the 'uploads' directory

            return response()->json([
                'message' => 'File uploaded successfully.',
                'statusCode' => 200,
                'data' => [
                    'filePath' => $path
                ],
                'success' => 'success',
            ], 200);
        }

        return response()->json([
            'message' => 'No file uploaded.',
            'statusCode' => 400,
            'data' => [],
            'success' => 'error',
        ], 400);
    }
    private function extractDates($claimDetails)
    {
        $dates = [];

        foreach ($claimDetails as $claimDetail) {
            $this->addDatesBetween($claimDetail['from_date'], $claimDetail['to_date'], $dates);
            $dates[] = substr($claimDetail['document_date'], 0, 10);
        }

        // Remove duplicate dates
        $dates = array_unique($dates);

        // Sort the dates
        sort($dates);

        return $dates;
    }

    private function addDatesBetween($fromDate, $toDate, &$dates)
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

    public function tripClaim(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'triptype_id' => 'required',
            'visit_branch_id' => 'required',
            'trip_purpose' => 'required',
            "claim_details" => 'required|array',
            'claim_details.*.person_details' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'statusCode' => 422,
                'data' => [],
                'success' => 'error',
            ], 422);
        }

        $exacoreToken =$this->hrms_login_token();        
        // dd($exacoreToken);
        $user=User::where('id',auth()->id())->first();
        $data = $request->all();
        $dates = $this->extractDates($data['claim_details']);

        
        // dd($dates);
        // Second API call using the token from the first call
        $secondResponse = Http::withHeaders([
            'Content-type' => 'application/json',
            'Authorization' => 'JWT ' . $exacoreToken,
        ])->post('http://103.119.254.250:6062/integration/status_api/', [
            'Emp_code' => $user->emp_id,
            'Dates' =>  $dates
        ]);
        $data=[];
        if ($secondResponse->failed()) {
            return response()->json([
                'message' => 'Failed to authenticate with status_api',
                'statusCode' => $secondResponse->status(),
                'data' => [],
                'errorDetails' => $secondResponse->body(),
                'success' => 'error',
                'user' => $user->emp_id,
                'Dates' => $dates
            ], 200);
        }else{
            $resdata = $secondResponse->json();
            $falseDates = [];

            foreach ($resdata['data'][$user->emp_id] as $arrdate => $arrvalue) {
                if ($arrvalue == 0) {
                    $falseDates[] = $arrdate;
                }
            }

            if (!empty($falseDates)) {
                return response()->json([
                    'message' => 'Some dates have a status of false.',
                    'statusCode' => 200,
                    'data' => [
                        'FalseDates' => $falseDates
                    ],
                    'success' => 'error',
                ], 200);
            }
        }

        try {
            if (auth()->user()) {
                $claim_id = $this->generateId();
                $data = $request->all(); // Get all request data as an array
                
                $tripClaim = Tripclaim::create([
                    'TripClaimID' => $claim_id,
                    'TripTypeID' => $request->triptype_id,
                    'ApproverID' => $user->reporting_person_empid,
                    'TripPurpose' => $request->trip_purpose ?? null,
                    'VisitBranchID' => $request->visit_branch_id,
                    'AdvanceAmount' =>null,
                    'RejectionCount' => 0,
                    'ApprovalDate' => null,
                    'NotificationFlg' => "0",
                    'Status' => "Pending",
                    'user_id' => auth()->id(),
                ]);
    
                foreach ($data["claim_details"] as $details) {
                    $TripClaimDetailID=$this->generateId();
                    $Tripclaimdetails = Tripclaimdetails::create([
                        'TripClaimDetailID' => $TripClaimDetailID,
                        'TripClaimID' => $claim_id,
                        'PolicyID' => $details['policy_id'],
                        'FromDate' => $details['from_date'] ?? null,
                        'ToDate' => $details['to_date'] ?? null,
                        'TripFrom' => $details['trip_from'] ?? null,
                        'TripTo' => $details['trip_to'] ?? null,
                        'DocumentDate' => $details['document_date'] ?? null,
                        'StartMeter' => $details['start_meter'] ?? null,
                        'EndMeter' => $details['end_meter'] ?? null,
                        'Qty' => $details['qty'] ?? null,
                        'UnitAmount' => $details['unit_amount'] ?? null,
                        'NoOfPersons' => $details['no_of_person'],
                        'FileUrl' => $details['file_url'] ?? null,
                        'Remarks' => $details['remarks'] ?? null,
                        'NotificationFlg' => "0",
                        'RejectionCount' => 0,
                        'ApproverID' => $user->reporting_person_empid,
                        'Status' => "Pending",
                        'user_id' => auth()->id(),
                    ]);
                    // dd($details['person_details']);
                    foreach($details['person_details'] as $perdet){
                        $claimowner = '0';
                        if ($perdet['id'] == auth()->id()) {
                            $claimowner = '1';
                        }

                        // Debugging: Ensure $perdet array structure
                        if (!isset($perdet['id']) || !isset($perdet['grade'])) {
                            return response()->json([
                                'message' => 'Invalid person detail structure',
                                'statusCode' => 400,
                                'data' => $perdet,
                                'success' => 'error',
                            ], 400);
                        }

                        $persondetails = Personsdetails::create([
                            'PersonDetailsID' => $this->generateId(),
                            'TripClaimDetailID' => $TripClaimDetailID,
                            'EmployeeID' => $perdet['id'],
                            'Grade' => $perdet['grade'],
                            'ClaimOwner' => $claimowner,
                            'user_id' => auth()->id()
                        ]);
                    }
                }
    
                $message = "Claim submitted successfully!";
                return response()->json(['message' => $message, 'statusCode' => 200, 'success' => 'success'], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'error',
                'statusCode' => 500,
                'data' => [],
                'message' => 'An error occurred while submitting the claim. Please try again later.',
            ], 500);
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
                $tripdata   = Tripclaim::with('tripclaimdetails.personsDetails.userDetails','tripclaimdetails.policyDetails.subCategoryDetails.categoryDetails','triptypedetails','approverdetails','visitbranchdetails')->where('user_id', auth()->id())->get()
                ->map(function ($trip) {
                    return [
                        "trip_claim_id"=>$trip->TripClaimID,
                        "trip_type_details" => $trip->triptypedetails->map(function ($typedetail) {
                            return [
                                "triptype_id" => $typedetail->TripTypeID,
                                "triptype_name" => $typedetail->TripTypeName
                            ];
                        }),
                        "approver_details"=> $trip->approverdetails->map(function ($appdetail) {
                            return [
                                "id" => $appdetail->id,
                                "emp_id" => $appdetail->emp_id,
                                "emp_name" => $appdetail->emp_name
                            ];
                        }),
                        "trip_purpose"=>$trip->TripPurpose,
                        "visit_branch_detail"=> $trip->visitbranchdetails->map(function ($branchdetail) {
                            return [
                                "visit_branch_id" => $branchdetail->BranchID,
                                "visit_branch_name" => $branchdetail->BranchName
                            ];
                        }),
                        'claim_details' => $trip->tripclaimdetails->map(function ($detail) {
                            return [
                                "trip_claim_details_id" => $detail->TripClaimDetailID,
                                "policy_id"=>$detail->PolicyID,
                                "policy_details"=>$detail->policyDetails->map(function ($policyDetails) {
                                    return [
                                        "sub_category_id" => $policyDetails->SubCategoryID,
                                        "grade_id" => $policyDetails->GradeID,
                                        "grade_type" => $policyDetails->GradeType,
                                        "grade_class" => $policyDetails->GradeClass,
                                        "grade_amount" => $policyDetails->GradeAmount,
                                        "approver" => $policyDetails->Approver,
                                        "sub_category_details"=>$policyDetails->subCategoryDetails->map(function ($subCategoryDetails) {
                                            return [
                                                "sub_category_id" => $subCategoryDetails->SubCategoryID,
                                                "category_id" => $subCategoryDetails->CategoryID,
                                                "sub_category_name" => $subCategoryDetails->SubCategoryName,
                                                "category_details"=>$subCategoryDetails->categoryDetails->map(function ($categoryDetails) {
                                                    return [
                                                        "category_id" => $categoryDetails->CategoryID,
                                                        "category_name" => $categoryDetails->CategoryName,
                                                        "trip_to" => $categoryDetails->TripTo,
                                                        "from_date" => $categoryDetails->FromDate,
                                                        "to_date" => $categoryDetails->ToDate,
                                                        "document_date" => $categoryDetails->DocumentDate,
                                                        "start_meter" => $categoryDetails->StartMeter,
                                                        "end_meter" => $categoryDetails->EndMeter
                                                    ];
                                                }),
                                                
                                            ];
                                        }),
                                    ];
                                }),
                                "from_date_flag"=> $detail->FromDate,
                                "to_date_flag"=> $detail->ToDate,
                                "trip_from_flag"=> $detail->TripFrom,
                                "trip_to_flag"=> $detail->TripTo,
                                "document_date_flag"=>$detail->DocumentDate,
                                "start_meter_flag"=> $detail->StartMeter,
                                "end_meter_flag"=> $detail->EndMeter,
                                "qty"=> $detail->Qty,
                                "unit_amount"=> $detail->UnitAmount,
                                "no_of_persons"=>$detail->NoOfPersons,
                                "file_url"=>$detail->FileUrl,
                                "remarks"=>$detail->Remarks,
                                "notification_flg"=>$detail->NotificationFlg,
                                "rejection_count"=>$detail->RejectionCount,
                                "approver_id"=>$detail->ApproverID,
                                'persons_details' => $detail->personsDetails->map(function ($person) {
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
    //here();
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
                $tripdata   = Tripclaim::with('tripclaimdetails.personsDetails.userDetails','tripclaimdetails.policyDetails.subCategoryDetails.categoryDetails','triptypedetails','approverdetails','visitbranchdetails')
                // ->where('user_id', auth()->id())
                ->where('ApproverID', auth()->id())
                ->get()
                ->map(function ($trip) {
                    return [
                        'tripclaim' => [
                            "TripClaimID"=>$trip->TripClaimID,
                            "TripTypeDetails" => $trip->triptypedetails->map(function ($typedetail) {
                                return [
                                    "TripTypeID" => $typedetail->TripTypeID,
                                    "TripTypeName" => $typedetail->TripTypeName
                                ];
                            }),
                            "ApproverDetails"=> $trip->approverdetails->map(function ($appdetail) {
                                return [
                                    "emp_id" => $appdetail->emp_id,
                                    "emp_name" => $appdetail->emp_name
                                ];
                            }),
                            "TripPurpose"=>$trip->TripPurpose,
                            "VisitBranchId"=> $trip->visitbranchdetails->map(function ($branchdetail) {
                                return [
                                    "BranchID" => $branchdetail->BranchID,
                                    "BranchName" => $branchdetail->BranchName
                                ];
                            }),
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