<?php

namespace Jsdecena\Baserepo\Traits;

use Illuminate\Http\UploadedFile;

trait UploadableTrait
{
    /**
     * Upload a single file in the server
     * and return the random (string) filename if successful and (boolean) false if not
     *
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @return false|string
     */
    public function uploadOne(UploadedFile $file, $folder = null, $disk = 'public')
    {
        return $file->store($folder, ['disk' => $disk]);
    }
}