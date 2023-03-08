<?php

namespace App\CPU;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ImageManager
{
    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            $path=public_path('image/');
            $image->move($path,$imageName);
        } else {
            $imageName = 'default.jpg';
        }
        return $imageName;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if( File::exists(public_path('image/'.$old_image)) ) {
            File::delete(public_path('image/'.$old_image));
        }
        $imageName = ImageManager::upload($dir, $format, $image);
        return $imageName;
    }

    public static function delete($full_path)
    {
        if( File::exists(public_path('image/'.$full_path)) ) {
            File::delete(public_path('image/'.$full_path));
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];

    }
}
