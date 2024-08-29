<?php
namespace App\Models;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Models\TripClaim;

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
        $totalClaims = TripClaim::count();

        // Get counts for specific statuses
        $pendingCount = TripClaim::where('Status', 'pending')->count();
        $approvedCount = TripClaim::where('Status', 'approved')->count();
        $settledCount = TripClaim::where('Status', 'Paid')->count();
        return view('home', compact('totalClaims', 'pendingCount', 'approvedCount', 'settledCount'));
    }
}
