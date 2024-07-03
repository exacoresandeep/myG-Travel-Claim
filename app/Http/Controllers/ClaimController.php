<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClaimController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('admin.claim.request_claim');
    }
    public function view()
    {
        return view('admin.claim.view');
    }
}
