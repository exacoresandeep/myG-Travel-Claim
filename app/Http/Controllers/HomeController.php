<?php
namespace App\Models;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Models\Tripclaim;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalClaims = Tripclaim::count();

        // Get counts for specific statuses
        $pendingCount = Tripclaim::where('Status', 'pending')->count();
        $approvedCount = Tripclaim::where('Status', 'approved')->count();
        $settledCount = Tripclaim::where('Status', 'Paid')->count();
        return view('home', compact('totalClaims', 'pendingCount', 'approvedCount', 'settledCount'));
    }
}
