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

class Detect
{
    /**
     * @var Config
     */
    protected $config;
    protected $data;
    protected $parser;
    protected $sort;
    protected $threshold = .02;

    public function __construct(Config $config, Array $data)
    {
        $this->config = $config;
        $this->data = $data;
        $this->parser   = $this->config->getParser();
        $this->sort     = $this->config->getSortObject();
        $this->distance = $this->config->GetDistanceObject();
    }
    
    public static function initByPath($datafile)
    {
        $format = AbstractFormat::initFormatByPath($datafile);
        $data   = $format->load($datafile);
        foreach (array('config', 'data') as $type) {
            if (empty($data[$type])) {
                throw new \Exception("Invalid data file, missing {$type}");
            }
        }
        return new self($data['config'], $data['data']);   
    }

    public function getKnowledge()
    {
        return $this->data;
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function detectChunk($text)
    {
        $ngrams = $this->sort->sort($this->parser->get($text));
        $total  = min($this->config->maxNGram(), count($ngrams));
        foreach ($this->data as $lang => $data) {
            $distance[] = array(
                'lang'  => $lang,
                'score' => $this->distance->distance($data, $ngrams, $total),
            );
        }

        usort($distance, function($a, $b) {
            return $a['score'] > $b['score'] ? -1 : 1;
        });

        if ($distance[0]['score'] - $distance[1]['score'] <= $this->threshold) {
            /** First and second language candidates are similar, we return the
                whole structure */
            return $distance;
        }

        /* we found a candidate which is at least 2% better than the second
           candidate */
        return $distance[0]['lang'];
    }

    public function getLanguages()
    {
        return array_keys($this->data);
    }

    public function detect($text, $limit = 300)
    {
        $chunks = $this->parser->splitText($text, $limit);
        $results = array();

        if (empty($chunks)) {
            throw new \Exception("Invalid input");
        }

        foreach ($chunks as $i => $chunk) {
            $result = $this->detectChunk($chunk);
            if (is_string($result)) {
                /* we successfully detected the chunk's language */
                return $result;
            }
            $results[] = $result;
        }

        $candidates = array();
        $distance   = array();
        foreach ($results as $result) {
            if (is_string($result)) {
                $candidates[] = $result;
                continue;
            }
            foreach ($result as $data) {
                if (empty($distance[ $data['lang'] ])) {
                    $distance[ $data['lang'] ] = array('lang' => $data['lang'], 'score' => 0);
                }
                $distance[ $data['lang'] ]['score'] += $data['score'];
            }
        }

        if (count($candidates) > 0) {
            $candidates = array_count_values($candidates);
            arsort($candidates);
            if (current($candidates) != array_sum(array_splice($candidates, 0, 2))/2) {
                /* the first *is* more than the second */
                return key($candidates);
            }
        }


        $distance = array_map(function($v) use ($results) {
            $v['score'] /= count($results);
            return $v;
        }, $distance);

        $distance = array_values($distance);

        usort($distance, function($a, $b) {
            return $a['score'] > $b['score'] ? -1 : 1;
        });

        if ($distance[0]['score'] - $distance[1]['score'] <= $this->threshold) {
            /** We're not sure at all, we return the whole array then */
            return $distance;
        }
        
        return $distance[0]['lang'];
    }
}
