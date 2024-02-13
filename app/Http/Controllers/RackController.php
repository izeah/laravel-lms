<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RackController extends Controller
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
        'category_id.required' => 'Please select Category.',
        'category_id.exists' => 'Not found.',
        'position.required' => 'Please input the :attribute.',
        'position.unique' => 'This :attribute has already been taken.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Rack::with('category')
                ->orderBy('updated_at', 'DESC')->get())
                ->addColumn('check', 'admin.racks.check')
                ->addColumn('action', 'admin.racks.action')
                ->rawColumns(['action', 'check'])
                ->addIndexColumn()
                ->make(true);
        }

        $categories = Category::orderBy('name')->get();
        return view('admin.racks.index', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'position' => 'required|string|digits:3|unique:racks,position',
        ], $this->customMessages);

        $racks = Rack::create($request->all());

        $racks = Rack::with('category')->findOrFail($racks->id);

        return response()->json($racks);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $racks = Rack::findOrFail($id);
        return response()->json($racks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $racks = Rack::findOrFail($id);

        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'position' => "required|string|digits:3|unique:racks,position,{$racks->position},position",
        ], $this->customMessages);

        $racks->update($request->all());

        $racks = Rack::with('category')->findOrFail($id);

        return response()->json($racks);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $racks = Rack::destroy($id);
        return response()->json($racks);
    }

    public function deleteAllSelected(Request $request)
    {
        $racks = Rack::destroy($request->post('ids'));
        return response()->json($racks);
    }
}
