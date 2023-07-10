<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NoteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notes = Note::where("user_id", $user->id)->orderBy("created_at", "desc")->pluck("text");

        return Inertia::render("Notes/Index", [
            "notes" => $notes,
        ]);
    }    

    public function store(Request $request)
    {
        $user = Auth::user();
        $notes = $request->input("noteText", []);
    
        foreach ($notes as $note) {
            if ($note !== null) {
                $user->notes()->create(["text" => $note]);
            }
        }
    
        return redirect()->back();
    }
    
}
