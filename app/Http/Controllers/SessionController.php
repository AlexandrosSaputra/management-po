<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    public function create()
    {
        // Redirect to local login form
        return redirect('/login');
    }

    /**
     * Deprecated: token-based login endpoint
     * Returns 410 Gone with migration guidance.
     */
    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Token-based login is deprecated. Please use the local username/email and password login.'
        ], Response::HTTP_GONE);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
