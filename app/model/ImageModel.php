<?php
/**
 * Copyright (c) 2014 Pavel Paulík (http://www.pavelpaulik.cz)
 * ImageModel
 *
 * @created 29.6.14
 * @package ${MODULE_NAME}Module
 * @author  Saurian
 */

namespace App\Model;

use Nette\Http\FileUpload;
use Nette\Object;
use Nette\Utils\UnknownImageFileException;

class ImageModel extends Object
{

    private $_wwwDir;

    private $_imageConfig;

    function __construct($wwwDir, $imageConfig)
    {
        $this->_wwwDir      = $wwwDir;
        $this->_imageConfig = $imageConfig;
    }

    /**
     * save Image
     *
     * @param FileUpload $file file
     *
     * @return bool
     * @throws \Nette\Utils\UnknownImageFileException
     */
    public function saveImage(FileUpload $file)
    {
        $full      = '/full/';
        $path      = '/data/images';
        $thumbnail = '/thumbnails/';

        if (!$file->isOK()) {
            throw new UnknownImageFileException(sprintf("Chyba %d při nahrávání obrázku.", $file->getError()));
        }

        if (!$file->isImage()) {
            throw new UnknownImageFileException("Není podporovaný obrázek.");
        }

        $image = $file->toImage();
        if (!$image->save($this->_wwwDir . $path . $full . $file->name, 70)) {
            throw new UnknownImageFileException("Obrázek nebylo možné uložit do filesystému");
        }

        $image2 = $file->toImage();
        $image2->resize($this->_imageConfig['thumbnailX'], $this->_imageConfig['thumbnailY']);
        if (!$image2->save($this->_wwwDir . $path . $thumbnail . $file->name, 70)) {
            throw new UnknownImageFileException("Obrázek nebylo možné uložit do filesystému");
        }

        return true;
    }


} 