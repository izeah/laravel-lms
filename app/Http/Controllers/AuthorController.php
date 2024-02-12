<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AuthorController extends Controller
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
        'name.required' => 'Please input the :attribute.',
        'max' => ':Attribute may not be more than :max characters.',
        'email.required' => 'Please input email address.',
        'email.email' => ':Attribute is invalid format.',
        'email.unique' => 'This :attribute has already been taken.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Author::orderBy('updated_at', 'DESC')->get())
                ->addColumn('check', 'admin.authors.check')
                ->addColumn('action', 'admin.authors.action')
                ->rawColumns(['action', 'check'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.authors.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:50|email:filter|unique:authors,email',
        ], $this->customMessages);

        $authors = Author::create([
            'name' => strip_tags($request->post('name')),
            'email' => $request->post('email'),
        ]);

        return response()->json($authors);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $authors = Author::findOrFail($id);
        return response()->json($authors);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $authors = Author::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => "required|string|max:50|email:filter|unique:authors,email,{$authors->email},email",
        ], $this->customMessages);

        $authors->update([
            'name' => strip_tags($request->post('name')),
            'email' => $request->post('email'),
        ]);

        return response()->json($authors);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $authors = Author::destroy($id);
        return response()->json($authors);
    }

    public function deleteAllSelected(Request $request)
    {
        $authors = Author::destroy($request->post('ids'));
        return response()->json($authors);
    }
}
