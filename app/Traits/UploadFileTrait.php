<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFileTrait
{
    private function uploadFile($path, $file)
    {
        $image_name = time() . '-' . $file->getClientOriginalName();
        $file->move(public_path("images/" . $path), $image_name);
        return "images/" . $path . "/" . $image_name;
    }

    private function generalUploadFile($path, $file)
    {
        $file_name = time() . '-' . $file->getClientOriginalName();
        $file->move(public_path($path), $file_name);
        return $file_name;
    }


    function uploadImage($folder, $image)
    {
        $path = $image->store('images/' . $folder, 'public');
        return $path;
    }

    function  uploadImages($image, $folder, $imagename)
    {
        $image->move(public_path('images/' . $folder), $imagename);
        $path = 'images/' . $folder . '/' . $imagename;
        return $path;
    }
}
