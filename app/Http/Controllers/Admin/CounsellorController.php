<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CounsellorController extends Controller
{
    public function create(Request $request): View
    {
        $role = $request->user()?->roles()->value('name');

        abort_unless($role === 'admin', 403);

        return view('admin.create-counsellor', [
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $role = $request->user()?->roles()->value('name');

        abort_unless($role === 'admin', 403);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $counsellor = User::create([
            'name' => $validated['full_name'],
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $counsellorRole = Role::firstOrCreate(
            ['name' => 'counsellor'],
            ['description' => 'Counsellor user']
        );

        $counsellor->roles()->syncWithoutDetaching([
            $counsellorRole->id => ['assigned_at' => now()],
        ]);

        return redirect()->route('admin.counsellor.create')->with('status', 'Counsellor account created successfully.');
    }
}
