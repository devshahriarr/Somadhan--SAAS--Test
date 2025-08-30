<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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

    public function UserProfileEdit()
    {
        $branch = Branch::all();
        $user = Auth::user();

        return view('pos.profile.profile-edit', compact('branch', 'user'));
    }

    public function UserProfile()
    {
        $branch = Branch::all();
        $user = Auth::user();

        return view('pos.profile.profile', compact('branch', 'user'));
    }

    //
    public function UserProfileUpdate(Request $request)
    {
        $userProfile = Auth::user();
        $user = User::findOrFail($userProfile->id); // Retrieve user by ID

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // Handle the profile image upload
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        $previousImagePath = public_path('uploads/profile/').$user->photo;
        if ($user->photo) {
            if (file_exists($previousImagePath)) {
                unlink($previousImagePath);
            }
        }
        if ($request->image) {
            $imageName = rand().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/profile/'), $imageName);
            $user->photo = $imageName;
        }
        $user->update();
        $notification = [
            'message' => 'Profile updated successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('user.profile')->with($notification);

    }

    // Change Passsword
    public function ChangePassword()
    {
        return view('pos.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validateData = $request->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirm_password' => 'required|same:newpassword',

        ]);

        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->oldpassword, $hashedPassword)) {
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->newpassword);
            $users->save();

            session()->flash('message', 'Password Updated Successfully');

            return redirect()->back();
        } else {
            session()->flash('error', 'Old password is not match');

            return redirect()->back();
        }
    }
}
