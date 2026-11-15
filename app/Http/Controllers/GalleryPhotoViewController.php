<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use Illuminate\Http\JsonResponse;

class GalleryPhotoViewController extends Controller
{
    public function __invoke(GalleryPhoto $galleryPhoto): JsonResponse
    {
        $galleryPhoto->increment('views');
        $galleryPhoto->refresh();

        return response()->json(['views' => $galleryPhoto->views]);
    }
}
