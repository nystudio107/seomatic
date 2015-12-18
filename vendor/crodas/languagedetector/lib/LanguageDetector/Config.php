<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2013 César D. Rodas                                               |
  +---------------------------------------------------------------------------------+
  | Redistribution and use in source and binary forms, with or without              |
  | modification, are permitted provided that the following conditions are met:     |
  | 1. Redistributions of source code must retain the above copyright               |
  |    notice, this list of conditions and the following disclaimer.                |
  |                                                                                 |
  | 2. Redistributions in binary form must reproduce the above copyright            |
  |    notice, this list of conditions and the following disclaimer in the          |
  |    documentation and/or other materials provided with the distribution.         |
  |                                                                                 |
  | 3. All advertising materials mentioning features or use of this software        |
  |    must display the following acknowledgement:                                  |
  |    This product includes software developed by César D. Rodas.                  |
  |                                                                                 |
  | 4. Neither the name of the César D. Rodas nor the                               |
  |    names of its contributors may be used to endorse or promote products         |
  |    derived from this software without specific prior written permission.        |
  |                                                                                 |
  | THIS SOFTWARE IS PROVIDED BY CÉSAR D. RODAS ''AS IS'' AND ANY                   |
  | EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED       |
  | WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE          |
  | DISCLAIMED. IN NO EVENT SHALL CÉSAR D. RODAS BE LIABLE FOR ANY                  |
  | DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES      |
  | (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;    |
  | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND     |
  | ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT      |
  | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS   |
  | SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE                     |
  +---------------------------------------------------------------------------------+
  | Authors: César Rodas <crodas@php.net>                                           |
  +---------------------------------------------------------------------------------+
*/
namespace LanguageDetector;

use LanguageDetector\Sort\SortInterface;

class Config
{
    protected $minLenNGram = 2;
    protected $maxLenNGram = 4;
    protected $maxNGram    = 300;
    protected $sort        = 'LanguageDetector\\Sort\\PageRank';
    protected $distance    = 'LanguageDetector\\Distance\\OutOfPlace';
    protected $mb          = false;
    
    public function __call($name, $args)
    {
        return $this->$name;
    }

    /**
     * @return SortInterface
     */
    public function getSortObject()
    {
        return new $this->sort;
    }

    /**
     * @return NGramParser
     */
    public function getParser()
    {
        return new NGramParser($this->minLenNGram, $this->maxLenNGram, $this->mb);
    }

    public function getDistanceObject()
    {
        if (is_object($this->distance)) {
            return $this->distance;
        }
        return new $this->distance;
    }

    public function useMb($use)
    {
        $this->mb = (bool)$use;
    }

    public function setDistanceObject(DistanceInterface $obj)
    {
        $this->distance = $obj;
        return $this;
    }

    public function export()
    {
        return get_object_vars($this);
    }

    public static function __set_state(Array $state)
    {
        $obj = new self;
        foreach ($state as $k => $v) {
            $obj->$k = $v;
        }
        return $obj;
    }
}
