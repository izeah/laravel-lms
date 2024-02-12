<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
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
            return DataTables::of(Role::whereNotIn('id', [1, 2])->orderBy('updated_at', 'DESC')->get())
                ->addColumn('check', 'admin.roles.check')
                ->addColumn('action', 'admin.roles.action')
                ->rawColumns(['action', 'check'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.roles.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:25',
        ], $this->customMessages);

        $roles = Role::create([
            'name' => strip_tags($request->post('name')),
        ]);

        return response()->json($roles);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $roles = Role::findOrFail($id);
        return response()->json($roles);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $roles = Role::findOrFail($id);

        $request->validate([
            'name' => "required|string|unique:roles,name,{$roles->name},name|max:25",
        ], $this->customMessages);

        $roles->update([
            'name' => strip_tags($request->post('name')),
        ]);

        return response()->json($roles);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $roles = Role::destroy($id);
        return response()->json($roles);
    }

    public function deleteAllSelected(Request $request)
    {
        $roles = Role::destroy($request->post('ids'));
        return response()->json($roles);
    }
}
