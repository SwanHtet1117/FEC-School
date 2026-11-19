<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use Illuminate\Http\JsonResponse;

class GalleryPhotoViewController extends Controller
{
    public function __invoke(GalleryPhoto $galleryPhoto): JsonResponse
    {
        abort_unless($galleryPhoto->is_approved, 404);

        $galleryPhoto->increment('views');
        $galleryPhoto->refresh();

        return response()->json(['views' => $galleryPhoto->views]);
    }
}
