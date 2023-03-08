<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CPU\ImageManager;

class FileUploadController extends Controller
{
    public function file_upload(Request $request)
    {
      $image=ImageManager::upload('modal/', 'png', $request->file('image'));
      return response()->json(['image' => $image], 200);
    }
}
