<?php

namespace App\Helpers\Files\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Response;

class ImageResponse
{
    /**
     * Create response for previewing specified image.
     * Optionally resize image to specified size.
     *
     * @param $disk
     * @param $filePath
     * @return ResponseFactory|Response
     */
    public static function create($disk, $filePath)
    {
        if (!$disk instanceof FilesystemAdapter) {
            abort(404);
        }

        if (!$disk->exists($filePath)) {
            abort(404);
        }

        $mime = $disk->getMimetype($filePath);
        $content = $disk->get($filePath);

        return response($content, 200, ['Content-Type' => $mime]);
    }
}
