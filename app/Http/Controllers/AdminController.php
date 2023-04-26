<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Returns the members of the conference.
//     * @param Request $request
//     * @return Collection/
     */
    public function index()
    {
        return ['isAuthenticated' => Auth::check()];
    }
}
