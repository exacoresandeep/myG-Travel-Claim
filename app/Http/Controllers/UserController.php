<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use DB;
use App\Models\Branch;

class  UserController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function list_users()
    {
        return view('admin.user.list');
    }

/***************************************
   Date        : 28/06/2024
   Description :  list for user
***************************************/    
	public function get_user_list(Request $request)
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
	            'GradeID',
	            'created_at',
	            'GradeName',
	            'Status'
	        ];
	        $orderColumn = $columns[$orderColumnIndex] ?? 'BranchID';
	        
	        $query = User::where(function($q) use ($searchValue) {
                           $q->where('emp_id', 'like', '%'.$searchValue.'%')
                           ->orWhere('emp_name', 'like', '%'.$searchValue.'%')
                           ->orWhere('user_name', 'like', '%'.$searchValue.'%')
                           ->orWhere('email', 'like', '%'.$searchValue.'%');
                       })
                       ->orderBy($orderColumn, $orderBy);

	        $recordsTotal = $query->count();
	        $data = $query->skip($skip)->take($pageLength)->get();
	        $recordsFiltered = $recordsTotal;

	        $formattedData = $data->map(function($row) 
	        {
	            return [
	                'emp_id' => $row->emp_id,
	                'emp_name' => $row->emp_name,
                  'user_name'=>$row->user_name,
                  'email'=>$row->email,

	                'action' => '<a href="' . route('view_user', $row->id) .'"><i class="fa fa-eye button_orange" aria-hidden="true"></i></a><a href="' . route('edit_user', $row->id) .'"><i class="fa fa-pencil-square-o button_orange" aria-hidden="true"></i></a><a onclick="delete_user_modal(\'' . $row->id . '\')"><i class="fa fa-trash button_orange" aria-hidden="true"></i></a>',
	                'checkbox' => '<input type="checkbox" name="item_checkbox[]" value="' . $row->id. '">',
	                'Status' => $row->Status, 
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

/***************************************
   Date        : 28/06/2024
   Description :  view for user
***************************************/
    public function view_user($id)
    {
    	$data=User::with('branchData')->where('id', $id)->first();
    	return view('admin.user.view',compact('data'));
    }
/***************************************
   Date        : 28/06/2024
   Description :  edit for user
***************************************/
    public function edit_user($id)
    {
      $branch=Branch::where('Status','1')->get();
      $userData=User::get();
      $User=User::where('id',$id)->first();
      return view('admin.user.edit',compact('User','branch','userData'));
    }
/***************************************
   Date        : 28/06/2024
   Description :  update forms for user
***************************************/    
    public function update_user_submit(Request $req)
    { 
        User::where('id',$req->id)->update([
          'emp_id'=>$req->emp_id,
          'emp_name'=>$req->emp_name,
          'user_name'=>$req->user_name,
          'email'=>$req->email,
          'emp_phonenumber'=>$req->emp_phonenumber,
          'emp_department'=>$req->emp_department,
          'emp_branch'=>$req->emp_branch,
          'emp_baselocation'=>$req->emp_baselocation,
          'emp_designation'=>$req->emp_designation,
          'emp_grade'=>$req->emp_grade,
          'reporting_person'=>$req->reporting_person,
          'emp_role'=>$req->emp_role,
        ]);
        return redirect()->route('list_users')->with('message','User updated Successfully!');
    }
/***************************************
   Date        : 28/06/2024
   Description :  delete for user
***************************************/
    public function delete_user($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }  
/*****************************************************
   Date        : 28/06/2024
   Description :  multiple deletions for user
*****************************************************/
    public function delete_multi_user(Request $request)
    {
        $ids = $request->input('ids');
        User::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }  
}
