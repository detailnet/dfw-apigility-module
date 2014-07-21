<?php

namespace Application\Core\File\Derivative;

use Imagine\Imagick\Imagine;
use Imagine\Image\Box;

use Detail\File\Item\ItemInterface;

class PreviewImageProvider
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $quality = 80;

    /**
     * @var string
     */
    protected $format = 'jpg';

    /**
     * @var string
     */
    protected $inputFilter;

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @return string
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * @param string $inputFilter
     */
    public function setInputFilter($inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    public function __construct($width, $height, $format = null, $quality = null, $inputFilter = null)
    {
        $this->setWidth($width);
        $this->setHeight($height);

        if ($format !== null) {
            $this->setFormat($format);
        }

        if ($quality !== null) {
            $this->setQuality($quality);
        }

        if ($inputFilter !== null) {
            $this->setInputFilter($inputFilter);
        }
    }

    public function createDerivative(ItemInterface $file)
    {
        $inputFilter = $this->getInputFilter();

        $imagine = new Imagine();
        $image = $imagine->open(
            $file->getFile()->getPathname() . ($inputFilter !== null ? '[' . $inputFilter . ']' : '')
        );

        /** @todo Force/convert sRGB */
        /** @todo Set resolution */
        /** @todo Flatten */

        $image->strip();

        $size = new Box($this->getWidth(), $this->getHeight());

        /** @var \Imagine\Image\ImageInterface $thumb */
        $thumb = $image->thumbnail($size, $image::THUMBNAIL_INSET);

        return $thumb->get($this->getFormat(), array('quality' => $this->getQuality()));
    }
}
