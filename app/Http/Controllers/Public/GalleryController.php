<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderBy('sortOrder')->get(['id', 'filename', 'caption', 'sortOrder']);
        return view('public.gallery', compact('images'));
    }

    public function serve(string $id)
    {
        $image = GalleryImage::findOrFail($id);
        if (! $image->filename) {
            abort(404);
        }

        // filename stored as /storage/uploads/gallery/xxx.jpg — map to storage path
        $relative = ltrim(str_replace('/storage/', '', $image->filename), '/');
        $path = storage_path('app/public/' . $relative);

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}