<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->isLibrarian()) {
                return $next($request);
            } else {
                abort(401);
            }
        })->except(['index', 'show']);
    }

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'integer' => ':Attribute must be a number.',
        'min' => ':Attribute must be at least :min.',
        'max' => ':Attribute may not be more than :max characters.',
        'profile_url.max' => ':Attribute size may not be more than :max kb.',
        'exists' => 'Not found.',
        'sn.required' => 'Please input Serial Number',
        'gender.required' => 'Please select "Male" or "Female".',
        'disabled.required' => 'Please select "Yes" or "No".',
        'role_id.required' => 'Please select Role.',
        'faculty_id.required_if' => 'Please select Faculty.',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(User::with('role')->whereNotIn('id', [1, 2])->orderBy('updated_at', 'DESC')->get())
                ->addColumn('check', 'admin.users.check')
                ->addColumn('status', 'admin.users.status')
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action', 'check', 'status'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get()->except([1, 2]);
        $faculties = Faculty::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'faculties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sn' => 'required|string|max:25|unique:users,sn',
            'name' => 'required|string|max:45',
            'username' => 'required|string|max:25|alpha_dash|unique:users,username',
            'email' => 'required|string|max:50|email:filter|unique:users,email',
            'phone_number' => 'required|string|max:25|unique:users,phone_number',
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'password' => 'required|string|confirmed',
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'faculty_id' => 'required_if:role_id,4|integer|exists:faculties,id',
            'role_id' => 'required|integer|exists:roles,id',
            'gender' => 'required|in:M,F',
        ], $this->customMessages);

        $user = new User();
        $user->sn = strip_tags($request->post('sn'));
        $user->name = strip_tags($request->post('name'));
        $user->username = $request->post('username');
        $user->email = $request->post('email');
        $user->phone_number = $request->post('phone_number');
        $user->dob = $request->post('dob');
        $user->address = strip_tags($request->post('address'));
        $user->password = bcrypt($request->post('password'));

        if ($request->hasFile('profile_url')) {
            $image = $request->file('profile_url');
            $imageName = $request->post('name') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/users');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(400, 400)->save($imagePath . '/' . $imageName);

            $user->profile_url = $imageName;
        } else {
            $user->profile_url = 'default.png';
        }

        $user->role_id = $request->post('role_id');
        $user->faculty_id = $request->post('faculty_id');
        $user->gender = $request->post('gender');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "User was successfully added! <br> (name : {$user->name})");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['role', 'faculty'])->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name')->get()->except([1, 2]);
        $faculties = Faculty::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'faculties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'sn' => "required|string|max:25|unique:users,sn,{$user->sn},sn",
            'name' => 'required|string|max:45',
            'username' => "required|string|max:25|alpha_dash|unique:users,username,{$user->username},username",
            'email' => "required|string|max:50|email:filter|unique:users,email,{$user->email},email",
            'phone_number' => "required|string|max:25|unique:users,phone_number,{$user->phone_number},phone_number",
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'faculty_id' => 'required_if:role_id,4|integer|exists:faculties,id',
            'role_id' => 'required|integer|exists:roles,id',
            'gender' => 'required|in:M,F',
            'disabled' => 'nullable|in:0,1'
        ], $this->customMessages);

        $user->sn = strip_tags($request->post('sn'));
        $user->name = strip_tags($request->post('name'));
        $user->username = $request->post('username');
        $user->email = $request->post('email');
        $user->phone_number = $request->post('phone_number');
        $user->address = strip_tags($request->post('address'));
        $user->dob = $request->post('dob');

        if ($request->hasFile('profile_url')) {
            if ($user->profile_url <> 'default.png') {
                $fileName = public_path() . '/img/users/' . $user->profile_url;
                File::delete($fileName);
            }

            $image = $request->file('profile_url');
            $imageName = $request->post('name') . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('img/users');

            $imageGenerate = Image::make($image->path());
            $imageGenerate->resize(400, 400)->save($imagePath . '/' . $imageName);

            $user->profile_url = $imageName;
        }

        $user->role_id = $request->post('role_id');
        $user->faculty_id = $request->post('faculty_id');
        $user->gender = $request->post('gender');

        if ($request->has('disabled')) {
            $user->disabled = $request->post('disabled');
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', "User was successfully updated! <br> (name : {$user->name})");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_url <> 'default.png') {
            $fileName = public_path() . '/img/users/' . $user->profile_url;
            File::delete($fileName);
        }

        $userDelete = $user->delete();

        return response()->json($userDelete);
    }

    public function deleteAllSelected(Request $request)
    {
        foreach ($request->post('ids') as $id) {
            $users = User::findOrFail($id);
            if ($users->profile_url <> 'default.png') {
                $fileName = public_path() . '/img/users/' . $users->profile_url;
                File::delete($fileName);
            }

            $usersDelete = $users->delete();
        }

        return response()->json($usersDelete);
    }

    public function changePassword($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function postChangePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ], $this->customMessages);

        if (Hash::check($request->post('current_password'), $user->password)) {
            $user->password = bcrypt($request->post('password'));
            $user->password_changed_at = now();
            $user->save();

            return response()->json($user);
        }
    }
}
