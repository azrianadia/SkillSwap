<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['offeredSkills', 'soughtSkills', 'reviewsReceived']);

        // Filter by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by prodi
        if ($request->filled('prodi')) {
            $query->where('prodi', 'like', '%' . $request->prodi . '%');
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by skill (offered or sought)
        if ($request->filled('skill_id')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skills.id', $request->skill_id);
            });
        }

        // Filter by skill type (offer/seek)
        if ($request->filled('skill_id') && $request->filled('skill_type')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skills.id', $request->skill_id)
                  ->where('user_skills.type', $request->skill_type);
            });
        }

        $users = $query->paginate(10)->withQueryString();
        $skills = Skill::orderBy('skill_name')->get();
        $unreadNotifications = auth()->user()->unreadNotifications->count();
        $quota = auth()->user()->getQuotaInfo();
        
        return view('dashboard.index', compact('users', 'skills', 'unreadNotifications', 'quota'));
    }
}
