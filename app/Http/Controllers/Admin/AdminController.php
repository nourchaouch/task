<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function showUsers()
    {
        $users = User::where('role','!=','admin')->get();
        return view('admin.management-users',compact('users'));
    }


    public function handleAddUser(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'role'      => 'required|in:farmer,transporter,distributor,individual',
            'password'  => 'required|min:8',
        ]);

        User::create([
            'name' => $request->firstname,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'User added successfully!');
    }


    public function handleUpdateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'      => 'required|in:farmer,transporter,distributor,individual',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }


    public function handleDeleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return back()->with('success', 'User deleted successfully.');
        }
        return back()->with('error', 'User not found.');
    }

    public function showPageChangePassword()
    {
        return view('admin.password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Get the current user
        $user = auth()->user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

}
