<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(Request $request)
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
