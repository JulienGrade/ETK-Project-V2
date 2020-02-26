<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * $fileName = $this->uploader->upload($file);
     */
    public function upload(UploadedFile $image)
    {
        //generer le nom de l'image
        $fileName = uniqid().'.'.$image->guessExtension();
        $image->move($this->uploadDir, $fileName);

        return $fileName;
    }

    public function remove($fileName)
    {
        $fs = new Filesystem();
        //reccuperer le chemin du fichier
        $file = $this->uploadDir.'/'.$fileName;
        if ($fs->exists($file))
        {
            //supprimer le fichier
            $fs->remove($file);
        }
    }
}