<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tripclaim;
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

            User::Create([
                'EmployeeID'=>$request->emp_id,
                // 'password'=>Hash::make($request->password)
            ]);
            $userData     = User::where('emp_id', '=', $request->emp_id)->first();
            $userToken=JWTAuth::fromUser($userData);
            $token   = $this->createNewToken($userToken,$userData);
            $message="user verified successfully!";
            return response()->json(['message'=>$message, 'statusCode' => $this-> successStatus,'data'=>$userData,'token'=>$token,'success' => 'success'], $this-> successStatus);    
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
                $branch   = DB::table('myg_11_branch')->where('Status', '1')->get();
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
                $triptype   = DB::table('myg_01_triptypes')->where('Status', '1')->get();
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
                $catgeory   = DB::table('myg_03_categories')->where('Status', '1')->get();
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
****************************************/

    public function claimList()
    {
        try
        {
            if(auth()->user())
            {
                $catgeory   = Tripclaim::with('tripclaimdetails.personsDetails')->where('user_id', auth()->id())->get();
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
    
}