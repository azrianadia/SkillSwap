<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::with(['offeredSkills', 'soughtSkills'])->paginate(10);
        
        return view('dashboard.index', compact('users'));
    }
}
