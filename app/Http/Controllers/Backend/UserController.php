<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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
        
        $this->authorize('users.index');

        $query = DB::table('users')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('administrator', 'users.id', '=', 'administrator.user_id')
            ->select('users.*', 'roles.name as role_name', 'administrator.phone as admin_phone')
            ->when(request()->q, function($query) {
                $query->where('users.name', 'like', '%'. request()->q . '%')
                      ->orWhere('users.email', 'like', '%'. request()->q . '%')
                      ->orWhere('roles.name', 'like', '%'. request()->q . '%');
            })
            ->orderBy('users.created_at', 'desc');

        // Handle AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            // Handle partial rendering like students
            if (request()->get('partial') == '1') {
                $perPage = request()->get('per_page', 10);
                $users = $query->paginate($perPage);
                return view('users._table', compact('users'))->render();
            }
            
            $perPage = request()->get('per_page', 10);
            $users = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ]);
        }

        $users = $query->paginate(10);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('users.create');
        
        $roles = Role::pluck('name', 'id');
        return view('users.addEdit', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('users.create');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Create user
            $userId = Str::uuid();
            DB::table('users')->insert([
                'id' => $userId,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 1,
                'join_date' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // For admin roles, create/update administrator record with phone
            if (in_array($request->role, ['admin', 'Super Admin'])) {
                DB::table('administrator')->insert([
                    'id' => Str::uuid(),
                    'user_id' => $userId,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Assign role
            $user = $userId;
            $userModel = \App\Models\User::find($user);
            if ($userModel) {
                $userModel->assignRole($request->role);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', __('index.user_created_successfully'));
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', __('index.user_create_failed') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('users.edit');
        
        $user = DB::table('users')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('administrator', 'users.id', '=', 'administrator.user_id')
            ->select('users.*', 'roles.name as role_name', 'administrator.phone as admin_phone')
            ->where('users.id', $id)
            ->first();
        
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', __('index.user_not_found'));
        }

        $user->phone = $user->admin_phone;
        
        $roles = Role::pluck('name', 'id');
        
        return view('users.addEdit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('users.edit');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Update user data
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'status' => 1,
                'updated_at' => now(),
            ];

            // For admin roles, update administrator record with phone
            if (in_array($request->role, ['admin', 'Super Admin'])) {
                DB::table('administrator')->updateOrInsert(
                    ['user_id' => $id],
                    [
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'updated_at' => now(),
                    ]
                );
            }
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            DB::table('users')->where('id', $id)->update($updateData);

            // Update role
            $userModel = \App\Models\User::find($id);
            $userModel->syncRoles($request->role);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', __('index.user_updated_successfully'));
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', __('index.user_update_failed') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $this->authorize('users.delete');
        
        try {
            $user = DB::table('users')->where('id', $id)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => __('index.user_not_found')
                ]);
            }

            // Prevent deletion of Super Admin
            $userModel = \App\Models\User::find($id);
            if ($userModel->hasRole('Super Admin')) {
                return response()->json([
                    'success' => false, 
                    'message' => __('index.cannot_delete_super_admin')
                ]);
            }

            // Delete user
            DB::table('users')->where('id', $id)->delete();

            return response()->json([
                'success' => true, 
                'message' => __('index.user_deleted_successfully')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => __('index.user_delete_failed') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function toggleActive($id)
    {
        $this->authorize('users.edit');
        
        try {
            $user = DB::table('users')->where('id', $id)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => __('index.user_not_found')
                ]);
            }

            // Prevent deactivation of Super Admin
            $userModel = \App\Models\User::find($id);
            if ($userModel->hasRole('Super Admin')) {
                return response()->json([
                    'success' => false, 
                    'message' => __('index.cannot_deactivate_super_admin')
                ]);
            }

            // Toggle status
            $newStatus = !$user->status;
            DB::table('users')->where('id', $id)->update([
                'status' => $newStatus,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true, 
                'message' => $newStatus ? __('index.user_activated') : __('index.user_deactivated'),
                'status' => $newStatus
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => __('index.user_status_update_failed') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        // Set language based on user preference
        if (Auth::check()) {
            $user = Auth::user();
            $language = $user && $user->language ? $user->language : 'id';
            App::setLocale($language);
            session(['locale' => $language]);
        }
        
        $this->authorize('users.view');
        
        $user = DB::table('users')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->where('users.id', $id)
            ->first();
        
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', __('index.user_not_found'));
        }
        
        return view('users.show', compact('user'));
    }
}
