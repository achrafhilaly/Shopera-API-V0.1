<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'content_type' => 'required|string',
            'filename'     => 'required|string',
        ]);

        // No leading slash
        $extension = pathinfo($data['filename'], PATHINFO_EXTENSION);
        $path = 'meals/' . Str::uuid() . '.' . $extension;

        /** @var Storage $disk */
        $disk = Storage::disk('s3');

        // 1. Presigned upload URL (PUT)
        // Laravel 12 returns ['url' => string, 'headers' => array] [web:3]
        $upload = $disk->temporaryUploadUrl(
            $path,
            now()->addMinutes(5),
            ['ContentType' => $data['content_type']]
        );

        // 2. Final PUBLIC URL for this object
        $publicUrl = $disk->url($path); // uses AWS_URL + /$path [web:3][web:73]

        return response()->json([
            'upload_url' => $upload,
            'public_url' => $publicUrl,
            'path'       => $path, // optional if you want to store path instead
        ]);
    }
}
