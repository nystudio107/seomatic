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
mb_internal_encoding('UTF-8');

class NGramParser
{
    protected $min;
    protected $max;
    protected $mb;
    protected $regex = '/[ \r\n\t_\.\-0-9]+/';
    protected $regex_mod = 's';

    public function __construct($min=2, $max=4, $mb = true)
    {
        $this->min = $min;
        $this->max = $max;
        $this->mb  = $mb;
        $this->regex    .= $mb ? 'us' : 's';
        $this->regex_mod = $mb ? 'us' : 's';
    }

    public function splitText($text, $len=200)
    {
        $strtolower = $this->mb ? 'mb_strtolower' : 'strtolower';
        $text  = preg_replace($this->regex, '_', $strtolower($text));
        $parts = preg_split("/(.{{$len}})/" . $this->regex_mod , $text,  0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if (count($parts) > 1 && strlen(end($parts)) < 100) {
            array_pop($parts);
        }

        return $parts;
    }

    public function get($raw_text, $limit = -1)
    {
        $strtolower = $this->mb ? 'mb_strtolower' : 'strtolower';
        $strlen     = $this->mb ? 'mb_strlen' : 'strlen';
        $substr     = $this->mb ? 'mb_substr' : 'substr';

        $text = preg_replace($this->regex, '_', $strtolower($raw_text));

        if ($limit > 0) {
            $text = $substr($text, 0, $limit);
        }
        $len    = $strlen($text);
        $min    = $this->min;
        $max    = $this->max;
        $ngrams = array();
        for ($i=$min; $i <= $max; $i++) {
            for ($e=0; $e < $len; $e++) {
                $ngrams[] = $substr($text, $e, $i);
            }
        }

        return $ngrams;
    }
}
