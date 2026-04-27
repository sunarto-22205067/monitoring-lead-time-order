<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::where('owner_id', Auth::id())
            ->orWhereHas('members', function($q) {
                $q->where('user_id', Auth::id());
            })->with(['owner', 'members.user'])->get();

        return Inertia::render('Teams/Index', [
            'teams' => $teams
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(),
        ]);

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'role' => 'manager',
        ]);

        return redirect()->route('teams.index')->with('success', 'Tim berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $team->load(['owner', 'members.user', 'documents.category']);
        return Inertia::render('Teams/Show', [
            'team' => $team,
            'available_users' => \App\Models\User::whereNotIn('id', $team->members->pluck('user_id'))->get(['id', 'name'])
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($request->only(['name', 'description']));

        return redirect()->route('teams.show', $team)->with('success', 'Tim berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Add protection to only allow owner to delete
        if ($team->owner_id !== Auth::id()) {
            abort(403);
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Tim berhasil dibubarkan.');
    }

    public function addMember(Request $request, Team $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,manager',
        ]);

        if ($team->members()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User sudah menjadi anggota tim.');
        }

        TeamMember::create([
            'team_id' => $team->id,
            'user_id' => $request->user_id,
            'role' => $request->role,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function removeMember(Team $team, \App\Models\User $user)
    {
        if ($team->owner_id !== Auth::id()) {
            abort(403);
        }

        if ($team->owner_id === $user->id) {
            return back()->with('error', 'Pemilik tim tidak bisa dihapus dari tim.');
        }

        $team->members()->where('user_id', $user->id)->delete();

        return back()->with('success', 'Anggota berhasil dikeluarkan.');
    }
}
