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
namespace LanguageDetector\Sort;

use Exception;
use LanguageDetector\Sort\SortInterface;

class PageRank implements SortInterface
{
    protected $damping = 0.85;
    protected $convergence = 0.01;

    // subs(array $a, array $b) {{{
    /**
     *  Array subtraction
     *
     * @param array $a
     * @param array $b
     *
     * @throws \Exception on array size mismatch
     * @return array
     */
    final protected function subs($a, $b)
    {
        $array = array();
        if (count($a) != count($a)) {
            throw new \Exception("Array shape mismatch");
        }
        foreach ($a as $index => $value) {
            if (!isset($b[$index])) {
                throw new \Exception("Array shape mismatch");
            }
            $array[$index] = $value - $b[$index];
        }
        return $array;
    }
    // }}}

    // mult(array $a, array $b) {{{
    /**
     *  Array multiplication
     *
     * @param array $a
     * @param array $b
     *
     * @throws Exception on array size mismatch
     * @return array
     */
    final protected function mult($a, $b)
    {
        $val = 0;
        if (count($a) != count($a)) {
            throw new Exception("Array shape  mismatch");
        }
        foreach ($a as $index => $value) {
            if (!isset($b[$index])) {
                throw new Exception("Array shape  mismatch");
            }
            $val += $b[$index] * $value;
        }
        return $val;
    }
    // }}}

    // hasConverge {{{
    protected function hasConverge(Array $old, Array $newValues)
    {
        $total = count($newValues);
        $diff  = $this->subs($newValues, $old);
        $done  = (sqrt($this->mult($diff, $diff))/$total) < $this->convergence;

        return $done;
    }
    // }}}

    protected function getGraph(Array $ngrams)
    {
        $outlinks = array();
        $graph    = array();
        $values   = array(); 
        $total = count($ngrams);
        for ($i=0; $i < $total; $i++) {
            if (ctype_punct($ngrams[$i])) {
                continue;
            }
            for ($e=$i; $e < $total && $e <= $i+5; $e++) {
                if ($i > $total || $e > $total) continue;
                if ($ngrams[$e] == $ngrams[$i]) continue;
                if (ctype_punct($ngrams[$e])) {
                    break;
                }

                foreach (array($i, $e) as $id) {
                    if (empty($outlinks[ $ngrams[$id] ])) {
                        $outlinks[ $ngrams[$id] ] = 0;
                    }
                    if (empty($graph[ $ngrams[$id] ])) {
                        $graph[ $ngrams[$id] ] = array();
                    }

                    $outlinks[ $ngrams[$id] ]++; /* increment outlink counter */
                    $values[ $ngrams[$id] ] = 0.15; /* initial value */
                }

                $graph[ $ngrams[$e] ][] = $ngrams[$i];
                $graph[ $ngrams[$i] ][] = $ngrams[$e];
            }
        }

        return compact('graph', 'values', 'outlinks');
    }

    public function sort(Array $ngrams)
    {
        $_graph   = $this->getGraph($ngrams);
        foreach (array('outlinks', 'graph', 'values') as $prop) {
            if (empty($_graph[$prop]) || !is_array($_graph[$prop])) {
                throw new \RuntimeException("Invalid or missing {$prop}");
            }
            $$prop = $_graph[$prop];
        }


        //graph would be empty if all ngrams are the same 
        if (count($graph) === 0) {
            return $ngrams;
        }
        $damping = $this->damping;
        $newvals = array();
        do {
            foreach ($graph as $id => $inlinks) {
                $pr = 0;
                foreach ($inlinks as $zid) {
                    $pr += $values[$zid] / $outlinks[$zid];
                }
                $pr = (1-$damping) * $damping * $pr;
                $newvals[$id] = $pr;
            }
            if ($this->hasConverge($values, $newvals)) {
                break;
            }
            /* update values array */
            $values = $newvals;
        } while (true);

        arsort($newvals);

        return $newvals;
    }
}
