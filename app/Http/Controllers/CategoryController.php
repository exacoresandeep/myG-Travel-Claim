<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Auth;
use DB;
use App\Models\SubCategories;


class  CategoryController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('admin.category.list');
    }

/***************************************
   Date        : 28/06/2024
   Description :  list for category
***************************************/    
	public function get_category_list(Request $request)
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
	            'CategoryID',
	            'created_at',
	            'CategoryName',
	            'Status'
	        ];
	        $orderColumn = $columns[$orderColumnIndex] ?? 'CategoryID';
	        
	        $query = Category::where('Status','1')
	                        ->where(function($q) use ($searchValue) {
                           $q->where('CategoryName', 'like', '%'.$searchValue.'%');
                       })
                       ->orderBy($orderColumn, $orderBy);

	        $recordsTotal = $query->count();
	        $data = $query->skip($skip)->take($pageLength)->get();
	        $recordsFiltered = $recordsTotal;

	        $formattedData = $data->map(function($row) 
	        {
	            return [
	                'CategoryID' => $row->CategoryID,
	                'CategoryName' => $row->CategoryName,
	                'action' => '<a href="' . route('view_category', $row->CategoryID) .'"><i class="fa fa-eye button_orange" aria-hidden="true"></i></a><a href="' . route('edit_category', $row->CategoryID) .'"><i class="fa fa-pencil-square-o button_orange" aria-hidden="true"></i></a><a onclick="delete_category_modal(\'' . $row->CategoryID . '\')"><i class="fa fa-trash button_orange" aria-hidden="true"></i></a>',
	                'checkbox' => '<input type="checkbox" name="item_checkbox[]" value="' . $row->CategoryID. '">',
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
   Description :  add for category
***************************************/
    public function add_category()
    {
    	return view('admin.category.add');
    }
/***************************************
   Date        : 28/06/2024
   Description :  submit for category
***************************************/    
    public function submit(Request $req)
    {
    	// dd($req);
		$req->merge([
			'TripFrom' => $req->has('TripFrom') ? $req->TripFrom : 0,
			'TripTo' => $req->has('TripTo') ? $req->TripTo : 0,
			'FromDate' => $req->has('FromDate') ? $req->FromDate : 0,
			'ToDate' => $req->has('ToDate') ? $req->ToDate : 0,
			'DocumentDate' => $req->has('DocumentDate') ? $req->DocumentDate : 0,
			'StartMeter' => $req->has('StartMeter') ? $req->StartMeter : 0,
			'EndMeter' => $req->has('EndMeter') ? $req->EndMeter : 0,
		]);
		$validatedData = $req->validate([
			'CategoryName' => 'required',

		], [
			'CategoryName.required' => 'Please enter the category name.',
		]);
		$CategoryData=Category::create([
			'CategoryName'=>$req->CategoryName,
			'TripFrom'=>$req->TripFrom,
			'TripTo'=>$req->TripTo,
			'FromDate'=>$req->FromDate,
			'ToDate'=>$req->ToDate,
			'DocumentDate'=>$req->DocumentDate,
			'StartMeter'=>$req->StartMeter,
			'EndMeter'=>$req->EndMeter,
			'user_id'=>Auth::user()->id,
			'Status'=>'1',
		]); 
		return redirect()->route('claim_category')->with('message','Category Added Successfully!');
    }
/***************************************
   Date        : 28/06/2024
   Description :  view for category
***************************************/
    public function view_category($id)
    {
    	$data=Category::where('CategoryID', $id)->first();
    	return view('admin.category.view',compact('data'));
    }
/***************************************
   Date        : 28/06/2024
   Description :  edit for category
***************************************/
    public function edit_category($id)
    {
      $Category=Category::where('CategoryID',$id)->first();
      return view('admin.category.edit',compact('Category'));
    }
/***************************************
   Date        : 28/06/2024
   Description :  update forms for category
***************************************/    
    public function update_category_submit(Request $req)
    { 
    	$req->merge([
			'TripFrom' => $req->has('TripFrom') ? $req->TripFrom : 0,
			'TripTo' => $req->has('TripTo') ? $req->TripTo : 0,
			'FromDate' => $req->has('FromDate') ? $req->FromDate : 0,
			'ToDate' => $req->has('ToDate') ? $req->ToDate : 0,
			'DocumentDate' => $req->has('DocumentDate') ? $req->DocumentDate : 0,
			'StartMeter' => $req->has('StartMeter') ? $req->StartMeter : 0,
			'EndMeter' => $req->has('EndMeter') ? $req->EndMeter : 0,
		]);
        Category::where('CategoryID',$req->id)->update([
            'CategoryName'=>$req->CategoryName,
			'TripFrom'=>$req->TripFrom,
			'TripTo'=>$req->TripTo,
			'FromDate'=>$req->FromDate,
			'ToDate'=>$req->ToDate,
			'DocumentDate'=>$req->DocumentDate,
			'StartMeter'=>$req->StartMeter,
			'EndMeter'=>$req->EndMeter,
			'user_id'=>Auth::user()->id,
          'Status'=>$req->Status,
        ]);
        return redirect()->route('claim_category')->with('message','Category updated Successfully!');
    }
/***************************************
   Date        : 28/06/2024
   Description :  delete for category
***************************************/
    public function delete_category($id)
    {
        Category::where('CategoryID', $id)->update(['Status'=>'2']);
        return response()->json(['success' => true]);
    }  
/*****************************************************
   Date        : 28/06/2024
   Description :  multiple deletions for category
*****************************************************/
    public function delete_multi_category(Request $request)
    {
        $ids = $request->input('ids');
        Category::whereIn('CategoryID', $ids)->update(['Status'=>'2']);
        return response()->json(['success' => true]);
    } 

	public function sub_claim_category()
    {
        return view('admin.subcategory.list');
    }


   /***************************************
   Date        : 28/06/2024
   Description :  list for subcategory
***************************************/    
	public function get_subcategory_list(Request $request)
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
	            'SubCategoryID',
	            'created_at',
	            'UomID',
	            'CategoryID',
	            'SubCategoryName',
	            'Status'
	        ];
	        $orderColumn = $columns[$orderColumnIndex] ?? 'CategoryID';
	        
	        $query = SubCategories::with('categorydata')->where('Status','1')
	                        ->where(function($q) use ($searchValue) {
                           $q->where('SubCategoryName', 'like', '%'.$searchValue.'%')
                            ->orWhere('UomID', 'like', '%'.$searchValue.'%')
							->orWhere('CategoryID', 'like', '%'.$searchValue.'%')
							->orWhere('SubCategoryName', 'like', '%'.$searchValue.'%');
                       })
                       ->orderBy($orderColumn, $orderBy);

	        $recordsTotal = $query->count();
	        $data = $query->skip($skip)->take($pageLength)->get();
	        $recordsFiltered = $recordsTotal;

	        $formattedData = $data->map(function($row) 
	        {
	            return [
	                'SubCategoryID' => $row->SubCategoryID,
	                'UomID' => $row->UomID,
	                'CategoryID' => $row->categorydata->CategoryName ?? 'N/A', // Handle null CategoryName
	                'SubCategoryName'=>$row->SubCategoryName,
	                'action' => '<a href="' . route('view_subcategory', $row->SubCategoryID) .'"><i class="fa fa-eye button_orange" aria-hidden="true"></i></a><a href="' . route('edit_subcategory', $row->SubCategoryID) .'"><i class="fa fa-pencil-square-o button_orange" aria-hidden="true"></i></a><a onclick="delete_subcategory_modal(\'' . $row->SubCategoryID . '\')"><i class="fa fa-trash button_orange" aria-hidden="true"></i></a>',
	                'checkbox' => '<input type="checkbox" name="item_checkbox[]" value="' . $row->SubCategoryID. '">',
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
   Description :  add for subcategory
***************************************/
    public function add_subcategory()
    {
    	$category=Category::where('Status','1')->get();
    	return view('admin.subcategory.add',compact('category'));
    }
/***************************************
   Date        : 28/06/2024
   Description :  submit for subcategory
***************************************/    
    public function subcategorysubmit(Request $req)
    {

		$validatedData = $req->validate([
			'UomID' => 'required|integer',
			'SubCategoryName'=>'required',

		], [
			'UomID.required' => 'Please enter the UomID.',
			'SubCategoryName.required' => 'Please enter the sub-categorys.',
		]);
		$SubCategoriesData=SubCategories::create([
			'UomID'=>$req->UomID,
			'CategoryID'=>$req->Category,
			'SubCategoryName'=>$req->SubCategoryName,
			'user_id'=>Auth::user()->id,
			'Status'=>'1',
		]); 
		return redirect()->route('sub_claim_category')->with('message','Sub-Category Added Successfully!');
    }
/***************************************
   Date        : 28/06/2024
   Description :  view for subcategory
***************************************/
    public function view_subcategory($id)
    {
    	$data=SubCategories::with('categorydata')->where('SubCategoryID', $id)->first();
    	return view('admin.subcategory.view',compact('data'));
    }
/***************************************
   Date        : 28/06/2024
   Description :  edit for subcategory
***************************************/
    public function edit_subcategory($id)
    {
      $category=Category::where('Status','1')->get();
      $subcategory=SubCategories::where('SubCategoryID',$id)->first();
      return view('admin.subcategory.edit',compact('subcategory','category'));
    }
/******************************************************
   Date        : 28/06/2024
   Description :  update forms for subcategory
******************************************************/    
    public function update_subcategory_submit(Request $req)
    { 
    	
        SubCategories::where('SubCategoryID',$req->id)->update([
            'UomID'=>$req->UomID,
			'CategoryID'=>$req->Category,
			'SubCategoryName'=>$req->SubCategoryName,
			'user_id'=>Auth::user()->id,
			'Status'=>'1',
        ]);
        return redirect()->route('sub_claim_category')->with('message','Sub-Category updated Successfully!');
    }
/***************************************
   Date        : 28/06/2024
   Description :  delete for subcategory
***************************************/
    public function delete_subcategory($id)
    {
        SubCategories::where('SubCategoryID', $id)->update(['Status'=>'2']);
        return response()->json(['success' => true]);
    }  
/*****************************************************
   Date        : 28/06/2024
   Description :  multiple deletions for subcategory
*****************************************************/
    public function delete_multi_subcategory(Request $request)
    {
        $ids = $request->input('ids');
        SubCategories::whereIn('SubCategoryID', $ids)->update(['Status'=>'2']);
        return response()->json(['success' => true]);
    }    
}
