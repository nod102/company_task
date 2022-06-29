<?php

namespace App\Traits;

trait FileUploadTrait
{
    protected function uploadFile ($file, $location) {
        $file_original_name = $file -> getClientOriginalName();
        $file_original_extension = $file -> getClientOriginalExtension();
        $file_unique_name = time().rand(100,999).'.'.$file_original_extension;
        $new_path = 'uploads/'.$location;
        $file -> storeAs('public/uploads/'.$location, $file_unique_name);
        return $file_new_name = '/public/storage/uploads/'.$location.'/'.$file_unique_name;
    }

}
