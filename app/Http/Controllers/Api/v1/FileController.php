<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * @param $path
     * @return BinaryFileResponse
     */
    public function getFile($path)
    {
        $file = Resource::query()
            ->where('content', $path)
            ->first();
        if (!$file){
            abort(404);
        }
        return response()->download(public_path($path), $file->name);
    }
}
