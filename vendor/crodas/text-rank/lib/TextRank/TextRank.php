<?php
/*
  +---------------------------------------------------------------------------------+
  | Copyright (c) 2013 César Rodas                                                  |
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
namespace crodas\TextRank;

use LanguageDetector\Sort\PageRank;

class TextRank 
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getAllKeywordsSorted($text)
    {
        // split the text into words
        $words = $this->config->trigger('get_words', $text);

        // get the candidates
        $keywords  = $this->config->trigger('filter_keywords', $words);

        // normalize each candidate
        $normalized = $this->config->trigger('normalize_keywords', $keywords);

        if (count($keywords) != count($normalized)) {
            throw new \RuntimeException("{normalize_keywords} event returned invalid data");
        }

        $graph  = new PageRank;
        $sorted = $graph->sort(array_values($normalized), true);

        if ($sorted == $normalized) {
            // PageRank failed, probably because the input was invalid
            return [];
        }

        $top = array_slice($sorted, 0, 10);

        // build an index of words and positions (so we can collapse compount keywords)
        $index  = [];
        $pindex = [];

        // search for coumpounds keywords
        $prev    = [];
        $phrases = [];
        foreach ($normalized as $pos => $word) {
            if (empty($top[$word])) {
                if (count($prev) > 1 && count($prev) < 4) {
                    $phrases[] = $prev;
                }
                $prev = [];
                continue;
            }
            $prev[] = [$pos,  $word];
        }
        
        if (count($prev) > 1 && count($prev) < 4) {
            $phrases[] = $prev;
        }

        foreach ($phrases as $prev) {
            $start  = current($prev)[0];
            $end    = end($prev)[0];
            $zwords = array_slice($words, $start, $end - $start+1, true);
            if (count(array_filter($zwords, 'ctype_punct')) > 0) {
                continue;
            }
            $phrase = implode(' ', $zwords);
            $score  = 0;
            foreach ($prev as $word) {
                $score  += $top[$word[1]];
            }
            $sorted[ trim($phrase) ] = $score/($end - $start);
        }

        // denormalize each single words
        foreach ($normalized as $pos => $word) {
            if (!empty($sorted[$word]) && $word != $words[$pos]) {
                $sorted[$words[$pos]] = $sorted[$word];
                unset($sorted[$word]);
            }
        }
        
        arsort($sorted);

        return $sorted;
    }

    public function getKeywords($text, $limit = 20)
    {
        return array_slice($this->getAllKeywordsSorted($text), 0, $limit);
    }
}

