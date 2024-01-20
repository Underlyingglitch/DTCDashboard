<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'feedback' => 'required'
        ]);

        Feedback::create([
            'user_id' => Auth::user()->id,
            'feedback' => $request->feedback
        ]);

        return redirect()->route('dashboard')->with('success', 'Bedankt voor uw feedback!');
    }
}
