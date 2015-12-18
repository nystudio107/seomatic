<?php
/**
 * User: avasilenko
 * Date: 29.5.13
 * Time: 23:25
 */
namespace LanguageDetector\Format;

use LanguageDetector\AbstractFormat;

abstract class AbstractFileFormat extends AbstractFormat 
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }
}
