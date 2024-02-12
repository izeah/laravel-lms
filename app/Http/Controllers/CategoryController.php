<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->isLibrarian()) {
                return $next($request);
            } else {
                abort(401);
            }
        })->except(['index']);
    }

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Category::orderBy('updated_at', 'DESC')->get())
                ->addColumn('check', 'admin.categories.check')
                ->addColumn('action', 'admin.categories.action')
                ->rawColumns(['action', 'check'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:50',
        ], $this->customMessages);

        $categories = Category::create([
            'name' => strip_tags($request->post('name')),
        ]);

        return response()->json($categories);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::findOrFail($id);
        return response()->json($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categories = Category::findOrFail($id);

        $request->validate([
            'name' => "required|string|unique:categories,name,{$categories->name},name|max:50",
        ], $this->customMessages);

        $categories->update([
            'name' => strip_tags($request->post('name')),
        ]);

        return response()->json($categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = Category::destroy($id);
        return response()->json($categories);
    }

    public function deleteAllSelected(Request $request)
    {
        $categories = Category::destroy($request->post('ids'));
        return response()->json($categories);
    }
}
