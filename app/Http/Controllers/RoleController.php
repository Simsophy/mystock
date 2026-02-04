<?php

namespace App\Http\Middleware;
namespace App\Http\Controllers;
use \App\Http\Middleware\Translate;
use Illuminate\Http\Request;
use View;
use Auth;
use DB;
use App\Models\Role; // ✅ Import the Role model

class RoleController extends Controller
{
   public function __construct()
 {
    $this->middleware(function ($request,$next){
            app()->setLocale(Auth::user()->lang);
             return $next($request);
    });

        View::share('active', 'role');
    }

    // Show list of roles
    public function index()
    {
       $roles = DB::table('roles')->get();
        return view('roles.index', compact('roles'));
    }

    // Show form to create a new role
    public function create()
    {
        return view('roles.create');
    }

    // Store a new role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect()->route('role.index')->with('success', 'Role created successfully!');
    }

    // Show form to edit a role
   public function edit($id)
{
    $user = DB::table('users')->where('id', $id)->first(); // <-- returns object
    $roles = DB::table('roles')->get();

    return view('users.edit', compact('user', 'roles'));
}

    // Update an existing role
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('role.index')->with('success', 'Role updated successfully!');
    }

    // Delete a role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role deleted successfully!');
    }
}
