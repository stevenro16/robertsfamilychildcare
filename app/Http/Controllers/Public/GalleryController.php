<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderByRaw('takenAt IS NULL, takenAt ASC, sortOrder ASC')
                              ->get(['id', 'filename', 'caption', 'takenAt', 'sortOrder']);
        return view('public.gallery', compact('images'));
    }

    public function serve(string $id)
    {
        $image = GalleryImage::findOrFail($id);
        if (! $image->filename) {
            abort(404);
        }

        $relative = ltrim(preg_replace('#^/[^/]+/#', '', $image->filename), '/');
        $path = Storage::disk('public')->path($relative);

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}