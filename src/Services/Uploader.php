<?php

namespace App\Services;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{

    public function __construct(private Filesystem $fs, private $profileFolder, private $profileFolderPublic)
    {
    }

    public function uploadProfileImage(UploadedFile $picture, string $oldPicturePath = null): string
    {
        $folder = $this->profileFolder;
        $ext = $picture->guessExtension() ?? 'bin';
        $filename = bin2hex(random_bytes(10)) . '.' . $ext;
        $picture->move($folder, $filename);
        if ($oldPicturePath) {
            $this->fs->remove($folder . '/' . pathInfo($oldPicturePath, PATHINFO_BASENAME));
        }
        return $this->profileFolderPublic . '/' . $filename;
    }
}
