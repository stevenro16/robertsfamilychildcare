<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderBy('sortOrder')->get();
        return view('portal.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'   => 'required|image|max:10240',
            'caption' => 'nullable|string|max:500',
        ]);

        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $maxSort = GalleryImage::max('sortOrder') ?? 0;

        // Create record first so HasUuidKey generates the ID
        $image = GalleryImage::create([
            'filename'  => '',
            'caption'   => $request->input('caption'),
            'sortOrder' => $maxSort + 1,
        ]);

        $path = $file->storeAs('uploads/gallery', $image->id . '.' . $ext, 'public');
        $image->update(['filename' => '/storage/' . $path]);

        return redirect()->route('portal.gallery.index')->with('success', 'Image uploaded.');
    }

    public function update(Request $request, string $id)
    {
        $image = GalleryImage::findOrFail($id);
        $data = $request->validate([
            'caption'   => 'nullable|string|max:500',
            'sortOrder' => 'sometimes|integer',
        ]);
        $image->update($data);
        return back()->with('success', 'Updated.');
    }

    public function destroy(string $id)
    {
        $image = GalleryImage::findOrFail($id);
        $image->delete();
        return back()->with('success', 'Image deleted.');
    }

    public function instagramImport(Request $request)
    {
        $request->validate(['url' => 'required|url']);

        return back()->with('error', 'Instagram import is not available in this version.');
    }
}