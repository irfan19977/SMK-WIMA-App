<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
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

        $this->authorize('permissions.index');

        $permissions = Permission::latest()->when(request()->q, function($permissions) {
            $permissions = $permissions->where('name', 'like', '%'. request()->q . '%');
        })->paginate(request()->per_page ?? 10);

        // Handle AJAX request
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $permissions->items(),
                'pagination' => [
                    'current_page' => $permissions->currentPage(),
                    'last_page' => $permissions->lastPage(),
                    'per_page' => $permissions->perPage(),
                    'total' => $permissions->total(),
                    'from' => $permissions->firstItem(),
                    'to' => $permissions->lastItem()
                ]
            ]);
        }

        return view('permission.index', compact('permissions'));
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

        return view('permission.addEdit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'guard_name' => 'required',
            'description' => 'nullable|string'
        ]);

        $permission = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name'),
            'description' => $request->input('description')
        ]);

        if ($permission) {
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan permission. Coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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

        $permission = Permission::findOrFail($id);
        return view('permission.addEdit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
            'guard_name' => 'required',
            'description' => 'nullable|string'
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->input('name'),
            'guard_name' => $request->input('guard_name'),
            'description' => $request->input('description')
        ]);
        
        if ($permission) {
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui permission. Coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permissions = Permission::findOrFail($id);
        $permissions->delete();


        if($permissions){
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        }else{
            return response()->json(['success' => false, 'message' => 'Data gagal dihapus.']);
        }
    }
    
    // Controller Method

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        Permission::whereIn('id', $ids)->delete();
        return response()->json(["success"=>"Berhasil"]);
    }

    /**
     * Export permissions to Excel
     */
    public function export()
    {
        $this->authorize('permissions.index');
        
        $permissions = Permission::latest()->get();
        
        $filename = 'permissions_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];
        
        $callback = function() use ($permissions) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Nama Permission', 'Guard Name', 'Deskripsi']);
            
            // Data
            foreach ($permissions as $permission) {
                fputcsv($file, [
                    $permission->name,
                    $permission->guard_name,
                    $permission->description ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import permissions from Excel
     */
    public function import(Request $request)
    {
        $this->authorize('permissions.create');
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);
        
        try {
            $file = $request->file('file');
            $imported = 0;
            $duplicates = 0;
            
            if (($handle = fopen($file->getPathname(), 'r')) !== FALSE) {
                // Skip header row
                fgetcsv($handle);
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if (isset($data[0]) && !empty($data[0])) {
                        $permissionName = trim($data[0]);
                        $guardName = isset($data[1]) ? trim($data[1]) : 'web';
                        $description = isset($data[2]) ? trim($data[2]) : null;
                        
                        // Check if permission already exists
                        $existingPermission = Permission::where('name', $permissionName)
                            ->where('guard_name', $guardName)
                            ->first();
                        
                        if (!$existingPermission) {
                            Permission::create([
                                'name' => $permissionName,
                                'guard_name' => $guardName,
                                'description' => $description
                            ]);
                            $imported++;
                        } else {
                            $duplicates++;
                        }
                    }
                }
                fclose($handle);
            }
            
            $message = "Berhasil mengimport {$imported} permission";
            if ($duplicates > 0) {
                $message .= ". {$duplicates} data duplikat dilewati.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

}
