<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use AuthorizesRequests;
    
    public function index()
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('roles.index');

        $roles = Role::latest()->when(request()->q, function($roles) {
            $roles = $roles->where('name', 'like', '%'. request()->q . '%');
        })->paginate(10);
        
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $permissions = Permission::latest()->get();
        return view('roles.addEdit', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->input('name')
        ]);

            $role->syncPermissions($request->input('permissions'));

            if($role){
                //redirect dengan pesan sukses
                return redirect()->route('roles.index')->with(['success' => __('index.role_saved_successfully')]);
            }else{
                //redirect dengan pesan error
                return redirect()->route('roles.index')->with(['error' => __('index.role_save_failed')]);
            }
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
    public function edit($id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.addEdit', compact('role', 'permissions'));
    }

    // Function to update the specified role in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('roles.index')->with('success', __('index.role_updated_successfully'));
    }

    // Function to remove the specified role from storage
    public function destroy($id)
    {
        $roles = Role::findOrFail($id);
        $roles->delete();


        if($roles){
            return response()->json(['success' => true, 'message' => __('index.role_deleted_successfully')]);
        }else{
            return response()->json(['success' => false, 'message' => __('index.role_delete_failed')]);
        }
    }
}
