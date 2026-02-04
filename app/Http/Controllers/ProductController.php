<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

require_once app_path('Helpers/functions.php');

class ProductController extends Controller
{
    public function __construct()
    {
        // Set locale based on user language
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                app()->setLocale(Auth::user()->lang);
            }
            return $next($request);
        });

        // Share active menu with all views
        View::share('active', 'product');
    }

    // List products
    public function index()
    {
        if (!check('product', 'list')) {
            return view('permissions.no');
        }

        $categories = DB::table('categories')->where('active', 1)->get();

        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->where('products.active', 1)
            ->select('products.*', 'categories.name as cname', 'units.name as uname')
            ->paginate(4);

        return view('products.index', compact('categories', 'products'));
    }

    // Search products
    public function search(Request $request)
    {
        $cid = $request->input('cid', 'all');
        $q = $request->input('q');

        $query = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'categories.name as cname', 'units.name as uname')
            ->where('products.active', 1);

        if ($cid !== 'all') {
            $query->where('products.category_id', $cid);
        }

        if (!empty($q)) {
            $query->where('products.name', 'like', '%' . $q . '%');
        }

        $products = $query->paginate(4);
        $categories = DB::table('categories')->where('active', 1)->get();

        return view('products.index', compact('products', 'categories'));
    }

    // Show create form
    public function create()
    {
        $categories = DB::table('categories')->where('active', 1)->get();
        $units = DB::table('units')->where('active', 1)->get();

        return view('products.create', compact('categories', 'units'));
    }

    // Store new product
    public function store(Request $request)
    {
        if (!check('product', 'list')) {
            return view('permissions.no');
        }

        $validated = $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'alert' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        DB::table('products')->insert([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'unit_id' => $validated['unit_id'],
            'alert' => $validated['alert'] ?? 0,
            'active' => 1,
            'description' => $validated['description'] ?? '',
        ]);

        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Product not found.');
        }

        $categories = DB::table('categories')->where('active', 1)->get();
        $units = DB::table('units')->where('active', 1)->get();

        return view('products.edit', compact('product', 'categories', 'units'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => "required|unique:products,code,$id",
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'alert' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $updated = DB::table('products')->where('id', $id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'alert' => $request->alert ?? 0,
            'description' => $request->description ?? '',
        ]);

        if ($updated) {
            return redirect()->route('product.index')->with('success', 'Product updated successfully.');
        } else {
            return redirect()->back()->with('error', 'No changes made or update failed.');
        }
    }

    // Delete product
    public function delete($id)
    {
        $deleted = DB::table('products')->where('id', $id)->delete();

        if ($deleted) {
            return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
        } else {
            return redirect()->route('product.index')->with('error', 'Product not found or could not be deleted.');
        }
    }

    // Show product details
    public function detail($id)
    {
        $product = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.id', $id)
            ->first();

        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Product not found.');
        }

        return view('products.detail', compact('product'));
    }

    // Export products as CSV
    public function export(Request $request)
    {
        $cid = $request->input('cid');

        $query = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->select('products.code', 'products.name', 'products.price', 'categories.name as category_name', 'units.name as unit_name', 'products.alert');

        if ($cid && $cid !== 'all') {
            $query->where('products.category_id', $cid);
        }

        $products = $query->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=products_export.csv",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Code', 'Name', 'Price', 'Category', 'Unit', 'Alert']);

            // Data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->code,
                    $product->name,
                    $product->price,
                    $product->category_name,
                    $product->unit_name,
                    $product->alert,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Import products from Excel/CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        return redirect()->route('product.index')->with('success', 'Products imported successfully.');
    }

    // Show low stock products
    public function low()
    {
        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->whereColumn('products.onhand', '<=', 'products.alert')
            ->where('products.active', 1)
            ->select('products.*', 'categories.name as cname', 'units.name as uname')
            ->paginate(4);

        return view('products.low', compact('products'));
    }
}
