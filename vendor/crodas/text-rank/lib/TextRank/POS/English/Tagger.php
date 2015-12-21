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
namespace crodas\TextRank\POS\English;


/**
 *  POS Tagger borrowed from 
 *  http://phpir.com/part-of-speech-tagging
 */
class Tagger
{
    public static function get(Array $words)
    {
        return array_map(function($word) {
            return $word['token'];     
        }, array_filter(self::tag($words), function($word) {
            switch ($word['tag']) {
            case 'NN':
            case 'JJ':
            case 'NNP':
            case 'NNS':
                return true;
            }
            return false;
        }));
    }
    public static function tag(Array $words)
    {
        static $dict;
        if (empty($dict)) {
            $dict = require __DIR__ . '/lexicon.php';
        }

        $tmp    = [];
        $return = [];
        $nouns  = ['NN', 'NNS'];
        $i = 0;
        foreach ($words as $id => $token) {
            $tmp[$i]     = ['token' => $token, 'tag' => 'NN'];
            $return[$id] = &$tmp[$i];

            // remove trailing full stops
            if(substr($token, -1) == '.') {
                $token = preg_replace('/\.+$/', '', $token);
            }

            // get from dict if set
            if(!empty($dict[$token])) {
                $tmp[$i]['tag'] = $dict[$token][0];
            }       

            // Converts verbs after 'the' to nouns
            if($i > 0) {
                if($tmp[$i - 1]['tag'] == 'DT' && 
                        in_array($tmp[$i]['tag'], 
                            array('VBD', 'VBP', 'VB'))) {
                    $tmp[$i]['tag'] = 'NN';
                }
            }

            // Convert noun to number if . appears
            if($tmp[$i]['tag'][0] == 'N' && strpos($token, '.') !== false) {
                $tmp[$i]['tag'] = 'CD';
            }

            // Convert noun to past particile if ends with 'ed'
            if($tmp[$i]['tag'][0] == 'N' && substr($token, -2) == 'ed') {
                $tmp[$i]['tag'] = 'VBN';
            }

            // Anything that ends 'ly' is an adverb
            if(substr($token, -2) == 'ly') {
                $tmp[$i]['tag'] = 'RB';
            }

            // Common noun to adjective if it ends with al
            if(in_array($tmp[$i]['tag'], $nouns) 
                    && substr($token, -2) == 'al') {
                $tmp[$i]['tag'] = 'JJ';
            }

            // Noun to verb if the word before is 'would'
            if($i > 0) {
                if($tmp[$i]['tag'] == 'NN' 
                        && $tmp[$i-1]['token'] == 'would') {
                    $tmp[$i]['tag'] = 'VB';
                }
            }

            // Convert noun to plural if it ends with an s
            if($tmp[$i]['tag'] == 'NN' && substr($token, -1) == 's') {
                $tmp[$i]['tag'] = 'NNS';
            }

            // Convert common noun to gerund
            if(in_array($tmp[$i]['tag'], $nouns) 
                    && substr($token, -3) == 'ing') {
                $tmp[$i]['tag'] = 'VBG';
            }

            // If we get noun noun, and the second can be a verb, convert to verb
            if($i > 0) {
                if(in_array($tmp[$i]['tag'], $nouns) 
                        && in_array($tmp[$i-1]['tag'], $nouns) 
                        && isset($dict[$token])) {
                    if(in_array('VBN', $dict[$token])) {
                        $tmp[$i]['tag'] = 'VBN';
                    } else if(in_array('VBZ', 
                                $dict[$token])) {
                        $tmp[$i]['tag'] = 'VBZ';
                    }
                }
            }

            $i++;

        }

        return $return;
    }
}
