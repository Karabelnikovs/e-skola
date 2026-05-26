<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PublicFileController extends Controller
{
    public function show(Request $request, string $filenameOrPath): Response
    {
        $path = str_contains($filenameOrPath, '/')
            ? ltrim(str_replace('\\', '/', $filenameOrPath), '/')
            : 'uploads/' . basename($filenameOrPath);

        if (str_contains($path, '..') || !str_starts_with($path, 'uploads/')) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path);
    }
}
