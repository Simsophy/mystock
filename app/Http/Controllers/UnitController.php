<?php

namespace App\Http\Middleware;
namespace App\Http\Controllers;
use \App\Http\Middleware\Translate;
use Illuminate\Support\Facades\Auth;



use Illuminate\Http\Request;
use View;
use DB;

class UnitController extends Controller
{
  public function __construct()
 {
    $this->middleware(function ($request,$next){
            app()->setLocale(Auth::user()->lang);
             return $next($request);
    });
   
        View::share('active', 'unit');
    }

    // Show list of units
    public function index()
    {
        $units = DB::table('units')->where('active', 1)->get(); // Only active units
        return view('units.index', [
            'units' => $units,
            'active' => 'unit',
        ]);
    }

    // Show create form
    public function create()
    {
        return view('units.create');
    }

    // Save new unit
    public function save(Request $r)
    {
        $r->validate([
            'name' => 'required|min:3|unique:units,name'
        ]);

        $data = [
            'name' => $r->name,
            'active' => 1,
        ];

        $i = DB::table('units')->insert($data);

        if ($i) {
            session()->flash('success', config('app.success'));
            return redirect()->route('unit.index');
        } else {
            session()->flash('fail', config('app.fail'));
            return redirect()->route('unit.create')->withInput();
        }
    }

    // Soft delete unit (set active = 0)
    public function delete($id)
    {
        $x = DB::table('units')
            ->where('id', $id)
            ->update(['active' => 0]);

        if ($x) {
            session()->flash('success', config('app.del_success'));
        } else {
            session()->flash('fail', config('app.del_fail'));
        }

        return redirect()->route('unit.index');
    }

    // Show edit form
    public function edit($id)
    {
        $cat = DB::table('units')->where('id', $id)->first();

        if (!$cat) {
            session()->flash('fail', 'Unit not found.');
            return redirect()->route('unit.index');
        }

        return view('units.edit', compact('cat'));
    }

    // Update unit
    public function update(Request $r, $id)
    {
        $r->validate([
            'name' => 'required|min:3|unique:units,name,' . $id,
        ]);

        $updated = DB::table('units')
            ->where('id', $id)
            ->update([
                'name' => $r->name,
            ]);

        if ($updated) {
            session()->flash('success', 'Update successful!');
        } else {
            session()->flash('fail', 'Update failed.');
        }

        return redirect()->route('unit.index');
    }
}
