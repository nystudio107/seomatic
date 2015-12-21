TextRank [![Build Status](https://travis-ci.org/crodas/TextRank.png?branch=master)](https://travis-ci.org/crodas/TextRank) <a href="https://flattr.com/submit/auto?user_id=crodas&url=https%3A%2F%2Fgithub.com%2Fcrodas%2Ftextrank" target="_blank"><img src="https://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0"></a>
========

extract relevant keywords from a given text


How to use it
-------------

In order to use the class, you must instance a `Config` object.


```php
<?php

require __DIR__ . "/vendor/autoload.php";

use \crodas\TextRank\Config;
use \crodas\TextRank\TextRank;

$config   = new Config;
$textrank = new TextRank($config);

$keywords = $textrank->getKeywords($some_long_text);

var_dump($keywords);

```

It is possible to get better results by adding few information about the language (`stopword` list, `stemmer` with `pecl install stem`).


```php
<?php

require __DIR__ . "/vendor/autoload.php";

use \crodas\TextRank\Config;
use \crodas\TextRank\TextRank;
use \crodas\TextRank\Stopword;

$config = new Config;
$config->addListener(new Stopword);

$textrank = new TextRank($config);
$keywords = $textrank->getKeywords($some_long_text);

var_dump($keywords);

```
By doing this it will detect the language of the text and will remove common words (from the stopword list). If `ext-stem` is available the results will be even better.


Summarize large texts
---------------------

This class is also capable of summarizing long texts

```php
$config = new \crodas\TextRank\Config;
$config->addListener(new \crodas\TextRank\Stopword);
$analizer = new \crodas\TextRank\Summary($config);
$summary = $analizer->getSummary($text);         
```

`$summary` is at most 5% of the sentences of the text.
