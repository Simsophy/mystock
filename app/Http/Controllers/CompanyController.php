<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        View::share('active', 'company');
    }

    public function index()
    {
        if (!check('company', 'list')) {
            return view('permissions.no');
        }

        $data['companies'] = DB::table('companies')->get();
        return view('companies.index', $data);
    }

    public function edit()
    {
        $company = DB::table('companies')->find(1);
        return view('companies.edit', compact('company'));
    }

    public function update(Request $r)
    {
        $r->validate([
            'name' => 'required|min:3',
        ]);

        $data = [
            'name'        => $r->name,
            'phone'       => $r->phone,
            'email'       => $r->email,
            'address'     => $r->address,
            'website'     => $r->website,
            'vattin'      => $r->vattin,
            'map_url'     => $r->map_url,
            'description' => $r->description,
        ];

        if ($r->hasFile('logo')) {
            $data['logo'] = $r->file('logo')
                ->store('uploads/companies', 'public');
        }

        $updated = DB::table('companies')
            ->where('id', 1)
            ->update($data);

        if ($updated) {
            return redirect()
                ->route('company.index')
                ->with('success', config('app.success', 'Updated successfully'));
        }

        return redirect()
            ->route('company.edit')
            ->with('fail', config('app.fail', 'Update failed'));
    }
}
