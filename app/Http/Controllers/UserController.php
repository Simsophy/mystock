<?php

namespace App\Http\Controllers;
use \App\Http\Middleware\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Set the active navigation item for the view.
     */
    public function __construct()
    {
        // Only set locale if user is logged in
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->lang) {
                app()->setLocale(Auth::user()->lang);
            }
            return $next($request);
        });

        // Share a variable with all views
        View::share('active', 'user');
    }

    /**
     * List all users, joining with the roles table to get role names.
     */
    public function index()
    {
        $users = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.name as role_name')
                    ->get();

        return view('users.index', [
            'users' => $users,
            'active' => 'user',
        ]);
    }

    /**
     * Show the form to create a new user.
     */
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Handle form submission and save a new user.
     */
    public function store(Request $request)
    {
        // Validate fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255', // allow duplicates
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'lang' => 'required|in:en,kh,cn', 
            'photo' => 'nullable|image|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('logos'), $fileName);
            $validated['photo'] = 'logos/' . $fileName;
        }

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Create user
        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        $roles = DB::table('roles')->get();

        if (!$user) {
            abort(404);
        }

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update an existing user's details.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => "required|string|max:255", // allow duplicate usernames
            'email' => "required|email|unique:users,email,$id",
            'role_id' => 'required|exists:roles,id',
            'lang' => 'required|in:en,kh,cn',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload if exists
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('logos'), $fileName);
            $validated['photo'] = 'logos/' . $fileName;
        }

        DB::table('users')->where('id', $id)->update($validated);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function delete($id)
    {
        $deleted = DB::table('users')->where('id', $id)->delete();

        if ($deleted) {
            return redirect()->route('user.index')->with('success', 'User deleted successfully');
        } else {
            return redirect()->route('user.index')->with('fail', 'Delete failed');
        }
    }

    public function showChangePasswordForm()
    {
        return view('users.change-password');
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function change_lang($lang)
    {
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['lang' => $lang]);

        return redirect()->back();
    }
}
