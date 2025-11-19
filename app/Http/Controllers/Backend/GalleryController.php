<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GalleryController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // $this->authorize('galleries.index'); // aktifkan jika sudah ada gate/permission
        $query = Gallery::latest();

        if ($request->has('q')) {
            $searchTerm = trim($request->q);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('jurusan', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $galleries = $query->paginate(10);

        if ($request->ajax()) {
            $formatted = collect($galleries->items())->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jurusan' => $item->jurusan,
                    'title' => $item->title,
                    'description' => str($item->description)->limit(150)->value(),
                    'image' => $item->image,
                    'created_at' => optional($item->created_at)->format('d M Y'),
                    'updated_at' => optional($item->updated_at)->format('d M Y'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'pagination' => [
                    'total' => $galleries->total(),
                    'per_page' => $galleries->perPage(),
                    'current_page' => $galleries->currentPage(),
                    'last_page' => $galleries->lastPage()
                ]
            ]);
        }

        return view('gallery.index', compact('galleries'));
    }

    private function saveImage($base64Image, $currentImage = null)
    {
        // Hapus prefix data URL jika ada
        if (strpos($base64Image, ';base64,') !== false) {
            list($type, $base64Image) = explode(';', $base64Image);
            list(, $base64Image) = explode(',', $base64Image);
        }

        // Decode base64
        $imageData = base64_decode($base64Image);
        
        // Generate nama file unik
        $filename = 'gallery_' . time() . '.jpg';
        $path = 'galleries/' . $filename;
        
        // Simpan file
        Storage::disk('public')->put($path, $imageData);
        
        // Hapus file lama jika ada
        if ($currentImage) {
            Storage::disk('public')->delete($currentImage);
        }
        
        return $path;
    }

    public function store(Request $request)
    {
        $request->validate([
            'jurusan' => 'required|string|in:Teknik Kendaraan Ringan,Teknik Bisnis Sepeda Motor,Teknik Kimia Industri,Teknik Komputer dan Jaringan',
            'title' => 'required|string|max:255',
            'description' => ['required', 'string', function ($attribute, $value, $fail) {
                $text = trim(strip_tags($value));
                $wordCount = str_word_count($text);
                
                if ($wordCount > 10) {
                    $fail('Deskripsi maksimal 10 kata.');
                }
            }],
            'image' => 'required_without:cropped_image|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'cropped_image' => 'required_without:image|string',
        ]);

        try {
            $data = $request->only(['jurusan','title','description']);
            
            // Handle cropped image
            if ($request->filled('cropped_image')) {
                $data['image'] = $this->saveImage($request->cropped_image);
            } 
            // Handle regular image upload
            elseif ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('galleries', 'public');
            }
            
            $gallery = Gallery::create($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Galeri berhasil disimpan',
                    'data' => $gallery,
                ]);
            }

            return redirect()->route('galleries.index')->with('success', 'Galeri berhasil disimpan');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'jurusan' => 'required|string|in:Teknik Kendaraan Ringan,Teknik Bisnis Sepeda Motor,Teknik Kimia Industri,Teknik Komputer dan Jaringan',
            'title' => 'required|string|max:255',
            'description' => ['required', 'string', function ($attribute, $value, $fail) {
                $text = trim(strip_tags($value));
                $wordCount = str_word_count($text);
                
                if ($wordCount > 10) {
                    $fail('Deskripsi maksimal 10 kata.');
                }
            }],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'cropped_image' => 'nullable|string',
        ]);

        try {
            $gallery = Gallery::findOrFail($id);
            $data = $request->only(['jurusan','title','description']);

            // Handle cropped image
            if ($request->filled('cropped_image')) {
                $data['image'] = $this->saveImage($request->cropped_image, $gallery->image);
            } 
            // Handle regular image upload
            elseif ($request->hasFile('image')) {
                if ($gallery->image) {
                    Storage::delete('public/' . $gallery->image);
                }
                $data['image'] = $request->file('image')->store('galleries', 'public');
            }

            $gallery->update($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Galeri berhasil diperbarui',
                    'data' => $gallery,
                ]);
            }

            return redirect()->route('galleries.index')->with('success', 'Galeri berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            if ($gallery->image) {
                Storage::delete('public/' . $gallery->image);
            }
            $gallery->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Galeri berhasil dihapus',
                ]);
            }

            return redirect()->route('galleries.index')->with('success', 'Galeri berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
