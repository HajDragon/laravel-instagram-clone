<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Search for users based on name or email.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $users = User::query()
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        return view('users.search', compact('users'));
    }
}