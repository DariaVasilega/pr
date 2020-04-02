<?php

namespace App\Service;

class MediaService
{
    public function upload($file, $userId)
    {
        move_uploaded_file($file->getPathName(),
            'upload/'.$userId);

        return 'upload/'.$userId;
    }
}
