<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the standard validation rules for an image upload.
     *
     * @param bool $isRequired
     * @return string
     */
    public static function getValidationRules(bool $isRequired = false): string
    {
        $rule = 'image|mimes:jpeg,png,jpg,gif,svg|max:1024';
        return $isRequired ? 'required|' . $rule : 'nullable|' . $rule;
    }


    /**
     * Upload an image to the specified directory.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string|false
     */
    public static function upload(UploadedFile $file, string $directory = 'images')
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }



    /**
     * Delete an image from storage.
     *
     * @param string|null $path
     * @return bool
     */
    public static function delete(?string $path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

}
