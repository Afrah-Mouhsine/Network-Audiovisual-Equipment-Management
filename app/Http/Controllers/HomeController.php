<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Equipement;
use App\Models\Intervention;

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
        $totalClients = Client::count();
        $totalEquipements = Equipement::count();
        $totalInterventions = Intervention::count();
        // Add more stats as needed, e.g., active interventions, equipment by status

        return view('home', compact('totalClients', 'totalEquipements', 'totalInterventions'));
    }
}

