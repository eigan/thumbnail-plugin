<?php

namespace Tagstore\Plugin;

use Tagstore\Domain\Exif;
use Tagstore\Domain\File;
use Tagstore\Domain\FileContent;
use Tagstore\Plugin\SyncPlugin;

use Eventviva\ImageResize;

class ThumbnailCreator extends SyncPlugin
{
    /**
     * Creates a thumbnail and stores in in the thumbnail path
     */
    public function onSync(File $file, FileContent $photo)
    {
        $hash = $photo->getHash();
        $photoPath = $this->filesystem->absolutepath($file->getPath());
        $thumbPath = $this->filesystem->absolutepath($this->getThumbnailPath() . "/$hash");

        $size = getimagesize($photoPath);
        $fp = fopen($photoPath, "rb");

        if ($size && $fp && file_exists($thumbPath) == false) {
            $image = new ImageResize($photoPath);
            $image->resizeToWidth(300);
            $image->save($thumbPath);
        }
    }

    /**
     * @return string
     */
    private function getThumbnailPath() : string
    {
        $path = $this->tagstore->getConfiguration("config.thumbnails.path");

        return $this->filesystem->absolutepath($path);
    }
}
