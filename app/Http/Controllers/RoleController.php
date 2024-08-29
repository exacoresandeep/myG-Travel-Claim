<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.role_management.list');
    } 
    public function assign()
    {
        return view('admin.role_management.assign');
    }
}
