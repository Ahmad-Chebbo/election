<?php

use App\Models\Candidate;
use App\Models\Team;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Vote;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'teams' => Team::all(),
        'candidates' => Candidate::all(),
    ]);
})->name('home');

Route::post('/', function (Request $request) {
    try {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'candidate_id' => 'nullable|exists:candidates,id',
        ]);

        Vote::create([
            'team_id' => $validated['team_id'],
            'candidate_id' => $validated['candidate_id'],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Vote recorded successfully');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    }
})->name('home');

Route::get('/result', function () {
    $teamsVotes = Vote::selectRaw('team_id, COUNT(*) as vote_count')
        ->with('team:id,name')
        ->groupBy('team_id')
        ->orderByDesc('vote_count')
        ->get()
        ->map(fn($teamVote) => [
            'team_id' => $teamVote->team->id,
            'team_name' => $teamVote->team->name,
            'vote_count' => $teamVote->vote_count
        ]);

    return Inertia::render('Result', [
        'teamsVotes' => $teamsVotes,
        'totalVotes' => Vote::count(),
    ]);
})->name('result');

Route::get('/result/{teamId}', function ($teamId) {
    $team = Team::findOrFail($teamId);

    $candidateVotes = Vote::selectRaw('candidate_id, team_id, COUNT(*) as vote_count')
        ->whereNotNull('candidate_id')
        ->with('candidate:id,name')
        ->where('team_id', $teamId)
        ->groupBy('candidate_id', 'team_id')
        ->orderByDesc('vote_count')
        ->get();

    return Inertia::render('CandidateResult', [
        'candidateVotes' => $candidateVotes,
        'totalVotes' => Vote::where('team_id', $teamId)->count(),
        'teamName' => $team->name,
    ]);
})->name('candidateResult');

// Analytics
Route::get('/analytics', function () {
    $votes = Vote::all();
    return Inertia::render('Analytics', [
        'votes' => $votes,
    ]);
})->name('analytics');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
