<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Redirect to the admin dashboard.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}
