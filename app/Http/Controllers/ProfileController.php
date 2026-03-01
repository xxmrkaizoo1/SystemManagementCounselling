<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }



    public function showEditProfile(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');

        return view('edit-profile', [
            'user' => $user,
            'role' => $role,
            'yearOptions' => $this->yearOptions(),
            'programmeOptions' => $this->programmeOptions(),
        ]);
    }

    public function updateProfileInfo(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $role = $user->roles()->value('name');
        $isStudent = $role === 'student';

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'years' => [Rule::requiredIf($isStudent), 'nullable', Rule::in($this->yearOptions())],
            'programme' => [Rule::requiredIf($isStudent), 'nullable', Rule::in($this->programmeOptions())],
        ]);

        $user->full_name = $validated['full_name'];
        $user->name = $validated['full_name'];
        $user->phone = $validated['phone'];
        $user->years = $isStudent ? ($validated['years'] ?? null) : null;
        $user->programme = $isStudent ? ($validated['programme'] ?? null) : null;

        $user->save();

        return back()->with('status', 'Profile updated successfully.');
    }


    private function yearOptions(): array
    {
        return [
            '1SVM SEM1',
            '1SVM SEM2',
            '2SVM SEM3',
            '2SVM SEM4',
            '1DVM SEM1',
            '1DVM SEM2',
            '2DVM SEM3',
            '2DVM SEM4',
        ];
    }

    private function programmeOptions(): array
    {
        return [
            'IPD',
            'ISK',
            'MTK 1',
            'MTK 2',
            'MPI 1',
            'MPI 2',
        ];
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
