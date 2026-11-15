<?php

namespace App\Http\Controllers;

use App\Models\GalleryVideo;
use Illuminate\Http\JsonResponse;

class GalleryVideoViewController extends Controller
{
    public function __invoke(GalleryVideo $galleryVideo): JsonResponse
    {
        abort_unless($galleryVideo->youtube_id, 404);

        $galleryVideo->increment('views');
        $galleryVideo->refresh();

        return response()->json(['views' => $galleryVideo->views]);
    }
}
