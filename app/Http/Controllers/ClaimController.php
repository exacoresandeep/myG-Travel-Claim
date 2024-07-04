<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClaimController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
/**********************************************
   Date        : 28/06/2024
   Description :  list for claim management
**********************************************/    
    public function index()
    {
        return view('admin.claim.request_claim');
    }
/**********************************************
   Date        : 28/06/2024
   Description :  view for claim management
**********************************************/  
    public function view()
    {
        return view('admin.claim.view');
    }
}
